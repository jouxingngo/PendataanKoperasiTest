<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ProgramMotor;
use App\Models\SetorProgramMotor;
use App\Models\Transaction_data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramMotorController extends Controller
{
    public function index($id)
    {
        $member = Member::with('ActiveProgramMotors')->findOrFail($id);

        return view('Program.motor.index', compact('member'));
    }

    public function lunas($id)
    {
        $member = Member::with('LunasProgramMotors')->findOrFail($id);
        return view('Program.motor.lunas', compact('member'));
    }


    public function showLunas($memberId, $programId)
    {
        // Mengambil data member beserta program motor dan setornya menggunakan eager loading
        $member = Member::with(['LunasProgramMotors' => function ($query) use ($programId) {
            $query->where('id', $programId)->with('setors'); // Memfilter program berdasarkan id dan eager load setors
        }])->findOrFail($memberId);

        // Ambil program motor yang sesuai
        $programMotor = $member->LunasProgramMotors->first();

        return view('Program.motor.showLunas', compact('programMotor'));
    }
 

    public function show($memberId, $program)
    {
        $member = Member::with('ActiveProgramMotors.setors')->findOrFail($memberId);

        // Filter program berdasarkan tipe
        $memberPrograms = $member->ActiveProgramMotors()
            ->where('program_type', 'program_' . $program)
            ->with('setors') // Pastikan data setor juga dimuat
            ->get();

        // Tentukan view berdasarkan tipe program
        switch ($program) {
            case 1:
                return view('Program.motor.program1', compact('member', 'memberPrograms'));
            case 2:
                return view('Program.motor.program2', compact('member', 'memberPrograms'));
            case 3:
                return view('Program.motor.program3', compact('member', 'memberPrograms'));
            default:
                abort(404); // Menangani jika program yang diberikan tidak valid
        }
    }


    public function store(Request $request, $id)
    {
        // Tentukan program_type yang diinputkan
        $program_type = $request->program_type;
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


        // Cek apakah ada program motor lain dengan paid_off false untuk program_type yang sama
        $existingProgram = ProgramMotor::where('program_type', $program_type)
            ->where('paid_off', false)
            ->first();

        // Jika ada program dengan paid_off false, batalkan proses penyimpanan dan beri pesan error
        if ($existingProgram) {
            session()->flash('status', 'danger');
            session()->flash('message', 'There is already an active program with unpaid status. Please finish the previous program before adding a new one.');
            return redirect()->route('program_motor.show', ['member' => $id, 'program' => $program_type]);
        }

        // Buat instance baru dari program_motor_Data tanpa langsung menyimpan
        $program_motor = new ProgramMotor();
        $program_motor->fill($request->all()); // Isi atribut dengan data dari request
        $program_motor->sisa_bayar = $program_motor->total_installment;
        // Simpan perubahan pada program_motor
        $program_motor->save(); // Data baru sekarang disimpan di database

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'program_motor',
            'program_id' => $program_motor->id,
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
        return redirect()->route('program_motor.show', ['member' => $id, 'program' => $program_type]);
    }


    public function destroy($id)
    {
        $program_motor = ProgramMotor::findOrFail($id);
        $program_type = $program_motor->program_type;
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
        $member_id = $program_motor->member->id;

        // Ambil data Debt yang terkait dengan member
        $debt = $program_motor->member->Debt;

        // Sesuaikan nilai program_motor dalam Debt
        if ($program_motor->status == "acc") {
            $debt->program_motor += $program_motor->total_installment;
        }


        // Simpan perubahan ke database
        $debt->save();

        // Hapus program_motor dan transaksi terkait
        $program_motor->delete();
        // Menghapus transaksi terkait berdasarkan ID program_motor
        Transaction_data::where('program_id', $id)
            ->where('program_name', 'program_motor') // Pastikan hanya transaksi program_bos yang dihapus
            ->delete();

        // Flash message
        session()->flash('status', 'success');
        session()->flash('message', $program_motor->member->name . " Program Motor 1 Successfully deleted");

        return redirect()->route('program_motor.show', ['member' => $member_id, 'program' => $program_type]);
    }
}
