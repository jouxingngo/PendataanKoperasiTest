<?php

namespace App\Http\Controllers;

use App\Models\ProgramMotor;
use App\Models\SetorProgramMotor;
use App\Models\Transaction_data;
use Illuminate\Http\Request;

class SetorProgramMotorController extends Controller
{
    public function store(Request $request, $id)
    {
        // Buat instance baru dari setor_Program_motor_Data tanpa langsung menyimpan
        $setor_program_motor = new SetorProgramMotor();
        $program_motor = ProgramMotor::findOrFail($id);
        $setor_program_motor->fill($request->all()); // Isi atribut dengan data dari request
        $setor_program_motor->program_motor_id = $program_motor->id;
        $program_type = $program_motor->program_type;
        $member_id = $setor_program_motor->member_id;
        $sisa_bayar = $program_motor->sisa_bayar;
        $transaction_value = $setor_program_motor->transaction_value;
        switch ($program_type) {
            case "program_1":
                $program_type = 1;
                break;
            case "program_2":
                $program_type = 2;
                break;
            case "program_3":
                $program_type = 3;
                break;
            default:
                $program_type = null; // Atau lakukan penanganan jika program tidak valid
        }
        $debt = $setor_program_motor->member->Debt;

        // Validasi transaksi
        // Cek apakah jumlah yang disetor lebih besar dari hutang
        if ($program_motor->total_installment - $transaction_value < 0) {
            // Jika jumlah yang disetor lebih besar dari hutang, beri pesan error
            session()->flash('status', 'danger');
            session()->flash('message', 'Jumlah setor melebihi hutang.');

            // Redirect kembali tanpa menyimpan
            return redirect()->route('program_motor.show', ['member' => $member_id, 'program' => $program_type]);
        } else {
            // Cek apakah sisa bayar menjadi 0
            if ($sisa_bayar - $transaction_value == 0) {
                $program_motor->paid_off = true; // Update status menjadi lunas
                $program_motor->save(); // Simpan perubahan status ke database
            } else if ($sisa_bayar - $transaction_value< 0) {
                // Jika jumlah yang disetor lebih besar dari sisa bayar, beri pesan error
                session()->flash('status', 'danger');
                session()->flash('message', 'Jumlah setor melebihi sisa bayar.');

                // Redirect kembali tanpa menyimpan perubahan
                return redirect()->route('program_motor.show', ['member' => $member_id, 'program' => $program_type]);
            }
            $sisa_bayar -= $transaction_value;
            $program_motor->sisa_bayar = $sisa_bayar;
            $program_motor->save(); // Simpan perubahan sisa bayar ke database

            $debt->program_motor += $setor_program_motor->transaction_value;
            $setor_program_motor->status = 'acc';
        }

        // Jika transaksi berhasil, simpan perubahan pada program_motor
        $program_motor->save();

        // Simpan perubahan pada setor_program_motor dan debt
        $setor_program_motor->save(); // Data baru sekarang disimpan di database
        $debt->save();

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'setor_program_motor',
            'program_id' => $setor_program_motor->id,
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
        return redirect()->route('program_motor.show', ['member' => $member_id, 'program' => $program_type]);
    }

    public function destroy($id)
    {
        $setor_program_motor = SetorProgramMotor::with('programMotor')->findOrFail($id);
        $program_type = $setor_program_motor->programMotor->program_type;
        $programMotor = $setor_program_motor->programMotor;
        // dd($program_type);
        switch ($program_type) {
            case "program_1":
                $program_type = 1;
                break;
            case "program_2":
                $program_type = 2;
                break;
            case "program_3":
                $program_type = 3;
                break;
            default:
                $program_type = null; // Atau lakukan penanganan jika program tidak valid
        }
        $member_id = $setor_program_motor->member->id;

        // Ambil data Debt yang terkait dengan member
        $debt = $setor_program_motor->member->Debt;

        // Sesuaikan nilai setor_program_motor dalam Debt
        if ($setor_program_motor->status == "acc") {
            // Mengurangi sisa_bayar pada programMotor sesuai dengan transaction_value yang dibayar
            $programMotor->sisa_bayar += $setor_program_motor->transaction_value;
        
            // Update debt dengan mengurangi program_motor sesuai dengan jumlah yang dibayar
            $debt->program_motor -= $setor_program_motor->transaction_value;
        
            // Simpan perubahan ke database
            $programMotor->save();
            $debt->save();
        }



        // Hapus setor_program_motor dan transaksi terkait
        $setor_program_motor->delete();
        // Menghapus transaksi terkait berdasarkan ID setor_program_motor
        Transaction_data::where('program_id', $id)
            ->where('program_name', 'setor_program_motor') // Pastikan hanya transaksi program_bos yang dihapus
            ->delete();

        // Flash message
        session()->flash('status', 'success');
        session()->flash('message', $setor_program_motor->member->name . " setoran Successfully deleted");

        return redirect()->route('program_motor.show', ['member' => $member_id, 'program' => $program_type]);
    }
}
