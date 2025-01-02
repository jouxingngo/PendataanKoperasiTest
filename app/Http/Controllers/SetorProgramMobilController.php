<?php

namespace App\Http\Controllers;

use App\Models\ProgramMobil;
use App\Models\SetorProgramMobil;
use App\Models\Transaction_data;
use Illuminate\Http\Request;

class SetorProgramMobilController extends Controller
{
    public function store(Request $request, $id)
    {
        // Buat instance baru dari setor_Program_mobil_Data tanpa langsung menyimpan
        $setor_program_mobil = new SetorProgramMobil();
        $program_mobil = ProgramMobil::findOrFail($id);
        $setor_program_mobil->fill($request->all()); // Isi atribut dengan data dari request
        $setor_program_mobil->program_mobil_id = $program_mobil->id;
        $member_id = $setor_program_mobil->member_id;
        $sisa_bayar = $program_mobil->sisa_bayar;
        $transaction_value = $setor_program_mobil->transaction_value;

        $debt = $setor_program_mobil->member->Debt;

        // Validasi transaksi
        // Cek apakah jumlah yang disetor lebih besar dari hutang
        if ($program_mobil->total_installment - $transaction_value < 0) {
            // Jika jumlah yang disetor lebih besar dari hutang, beri pesan error
            session()->flash('status', 'danger');
            session()->flash('message', 'Jumlah setor melebihi hutang.');

            // Redirect kembali tanpa menyimpan
            return redirect()->route('member.program_mobil', $member_id);

        } else {
            // Cek apakah sisa bayar menjadi 0
            if ($sisa_bayar - $transaction_value == 0) {
                $program_mobil->paid_off = true; // Update status menjadi lunas
                $program_mobil->save(); // Simpan perubahan status ke database
            } else if ($sisa_bayar - $transaction_value < 0) {
                // Jika jumlah yang disetor lebih besar dari sisa bayar, beri pesan error
                session()->flash('status', 'danger');
                session()->flash('message', 'Jumlah setor melebihi sisa bayar.');

                // Redirect kembali tanpa menyimpan perubahan
                return redirect()->route('member.program_mobil', $member_id);

            }
            $sisa_bayar -= $transaction_value;
            $program_mobil->sisa_bayar = $sisa_bayar;
            $program_mobil->save(); // Simpan perubahan sisa bayar ke database

            $debt->program_mobil += $setor_program_mobil->transaction_value;
            $setor_program_mobil->status = 'acc';
        }

        // Jika transaksi berhasil, simpan perubahan pada program_mobil
        $program_mobil->save();

        // Simpan perubahan pada setor_program_mobil dan debt
        $setor_program_mobil->save(); // Data baru sekarang disimpan di database
        $debt->save();

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'setor_program_mobil',
            'program_id' => $setor_program_mobil->id,
            'transaction_type' => "setor"
        ]);

        // Cek jika transaksi berhasil dibuat
        if ($transaction) {
            session()->flash('status', 'success');
            session()->flash('message', 'Transaction Program Motor successfully added.');
        } else {
            // Pesan error jika transaksi gagal
            session()->flash('status', 'danger');
            session()->flash('message', 'Failed to create transaction.');
        }

        // Redirect ke halaman index
        return redirect()->route('member.program_mobil', $member_id);

    }


    public function destroy($id)
    {
        $setor_program_mobil = SetorProgramMobil::with('programMobil')->findOrFail($id);
        $programMobil = $setor_program_mobil->programMobil;
        // dd($program_type);
       
        $member_id = $setor_program_mobil->member->id;

        // Ambil data Debt yang terkait dengan member
        $debt = $setor_program_mobil->member->Debt;

        // Sesuaikan nilai setor_program_mobil dalam Debt
        if ($setor_program_mobil->status == "acc") {
            // Mengurangi sisa_bayar pada programMobil sesuai dengan transaction_value yang dibayar
            $programMobil->sisa_bayar += $setor_program_mobil->transaction_value;
        
            // Update debt dengan mengurangi program_mobil sesuai dengan jumlah yang dibayar
            $debt->program_mobil -= $setor_program_mobil->transaction_value;
        
            // Simpan perubahan ke database
            $programMobil->save();
            $debt->save();
        }



        // Hapus setor_program_mobil dan transaksi terkait
        $setor_program_mobil->delete();
        // Menghapus transaksi terkait berdasarkan ID setor_program_mobil
        Transaction_data::where('program_id', $id)
            ->where('program_name', 'setor_program_mobil') // Pastikan hanya transaksi program_bos yang dihapus
            ->delete();

        // Flash message
        session()->flash('status', 'success');
        session()->flash('message', $setor_program_mobil->member->name . " setoran Successfully deleted");

        return redirect()->route('member.program_mobil', $member_id);
    }
}
