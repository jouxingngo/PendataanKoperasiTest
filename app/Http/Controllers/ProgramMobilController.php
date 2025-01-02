<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ProgramMobil;
use App\Models\Transaction_data;
use Illuminate\Http\Request;

class ProgramMobilController extends Controller
{
    // 
    public function index($id)
    {
        $member = Member::with('ActiveProgramMobils.setors')->findOrFail($id);

        return view('Program.mobil.index', compact('member'));
    }

    public function lunas($id)
    {
        $member = Member::with('LunasProgramMobils')->findOrFail($id);
        return view('Program.mobil.lunas', compact('member'));
    }
    
    public function showLunas($memberId, $programId)
    {
        // Mengambil data member beserta program motor dan setornya menggunakan eager loading
        $member = Member::with(['LunasProgramMobils' => function ($query) use ($programId) {
            $query->where('id', $programId)->with('setors'); // Memfilter program berdasarkan id dan eager load setors
        }])->findOrFail($memberId);

        // Ambil program motor yang sesuai
        $programMobil = $member->LunasProgramMobils->first();

        return view('Program.mobil.showLunas', compact('programMobil'));
    }
 


    public function store(Request $request, $id)
    {


        // Cek apakah ada program motor lain dengan paid_off false untuk program_type yang sama
        $existingProgram = ProgramMobil::where('paid_off', false)->first();

        // Jika ada program dengan paid_off false, batalkan proses penyimpanan dan beri pesan error
        if ($existingProgram) {
            session()->flash('status', 'danger');
            session()->flash('message', 'There is already an active program with unpaid status. Please finish the previous program before adding a new one.');
            return redirect()->route('member.program_mobil', $id);
        }

        // Buat instance baru dari program_mobil$program_mobil_Data tanpa langsung menyimpan
        $program_mobil = new ProgramMobil();
        $program_mobil->fill($request->all()); // Isi atribut dengan data dari request
        $program_mobil->sisa_bayar = $program_mobil->total_installment;
        // Simpan perubahan pada program_mobil$program_mobil
        $program_mobil->save(); // Data baru sekarang disimpan di database

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'program_mobil',
            'program_id' => $program_mobil->id,
            'transaction_type' => "pinjam"
        ]);



        // Cek jika transaksi berhasil dibuat
        if ($transaction) {
            session()->flash('status', 'success');
            session()->flash('message', 'Transaction Program Motor.');
        } else {
            // Pesan error jika transaksi atau setor gagal
            session()->flash('status', 'danger');
            session()->flash('message', 'Failed to create transaction or initial setor.');
        }

        // Redirect ke halaman program motor sesuai dengan program_type
        return redirect()->route('member.program_mobil', $id);
    }
    

    public function destroy($id)
    {
        $program_mobil = ProgramMobil::findOrFail($id);
    
        $member_id = $program_mobil->member->id;

        // Ambil data Debt yang terkait dengan member
        $debt = $program_mobil->member->Debt;

        // Sesuaikan nilai program_mobil dalam Debt
        if ($program_mobil->status == "acc") {
            $debt->program_mobil += $program_mobil->total_installment;
        }


        // Simpan perubahan ke database
        $debt->save();

        // Hapus program_mobil dan transaksi terkait
        $program_mobil->delete();
        // Menghapus transaksi terkait berdasarkan ID program_mobil
        Transaction_data::where('program_id', $id)
            ->where('program_name', 'program_mobil') // Pastikan hanya transaksi program_bos yang dihapus
            ->delete();

        // Flash message
        session()->flash('status', 'success');
        session()->flash('message', $program_mobil->member->name . " Program Mobil 1 Successfully deleted");

        return redirect()->route('member.program_mobil', $member_id);
    }
    
    
}
