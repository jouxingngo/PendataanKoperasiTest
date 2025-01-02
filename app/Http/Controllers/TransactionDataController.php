<?php

namespace App\Http\Controllers;

use App\Models\Program_BOP_Data;
use App\Models\Program_BOS_Data;
use App\Models\ProgramMobil;
use App\Models\ProgramMotor;
use App\Models\SetorProgramMobil;
use App\Models\SetorProgramMotor;
use App\Models\Transaction_data;
use Illuminate\Http\Request;

class TransactionDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    // $transactions = Transaction_data::with(['member', 'admin'])->get();
    // foreach ($transactions as $transaction) {
    //     if ($transaction->program_name == "program_bos") {
    //         $program = Program_BOS_Data::with(['admin', 'member'])->find($transaction->program_id);
    //     } elseif ($transaction->program_name == "program_bop") {
    //         $program = Program_BOP_Data::find($transaction->program_id);
    //     }
    //     $transaction->program = $program;
    // }
    // return view('transaction.index', compact('transactions'));
    // }
    public function index()
    {
        // Eager load relationships untuk mengurangi N+1 query
        $transactions = Transaction_data::with(['member', 'admin'])->where('transaction_type', 'pinjam')->get();

        // Ambil semua program data sekaligus
        $programBOSIds = $transactions->where('program_name', 'program_bos')->pluck('program_id')->unique();
        $programBOPIds = $transactions->where('program_name', 'program_bop')->pluck('program_id')->unique();
        $programMotorIds = $transactions->where('program_name', 'program_motor')->pluck('program_id')->unique();
        $programMobilIds = $transactions->where('program_name', 'program_mobil')->pluck('program_id')->unique();

        $programBOS = Program_BOS_Data::with(['admin', 'member'])->whereIn('id', $programBOSIds)->get()->keyBy('id');
        $programBOP = Program_BOP_Data::with(['admin', 'member'])->whereIn('id', $programBOPIds)->get()->keyBy('id');
        $programMotors = ProgramMotor::with(['admin', 'member'])->whereIn('id', $programMotorIds)->get()->keyBy('id');
        $programMobils = ProgramMobil::with(['admin', 'member'])->whereIn('id', $programMobilIds)->get()->keyBy('id');

        // Tambahkan program ke masing-masing transaksi
        foreach ($transactions as $transaction) {
            if ($transaction->program_name == "program_bos") {
                $transaction->program = $programBOS->get($transaction->program_id);
            } elseif ($transaction->program_name == "program_bop") {
                $transaction->program = $programBOP->get($transaction->program_id);
            } elseif ($transaction->program_name == "program_motor") {
                $transaction->program = $programMotors->get($transaction->program_id);
            } elseif ($transaction->program_name == "program_mobil") {
                $transaction->program = $programMobils->get($transaction->program_id);
            }
        }

        return view('transaction.index', compact('transactions'));
    }

    public function setor()
    {
        // Eager load relationships untuk mengurangi N+1 query
        $transactions = Transaction_data::with(['member', 'admin'])->where('transaction_type', 'setor')->get();

        // Ambil semua program data sekaligus
        $programBOSIds = $transactions->where('program_name', 'program_bos')->pluck('program_id')->unique();
        $programBOPIds = $transactions->where('program_name', 'program_bop')->pluck('program_id')->unique();
        $setorMotorIds = $transactions->where('program_name', 'setor_program_motor')->pluck('program_id')->unique();
        $setorMobilIds = $transactions->where('program_name', 'setor_program_mobil')->pluck('program_id')->unique();

        $programBOS = Program_BOS_Data::with(['admin', 'member'])->whereIn('id', $programBOSIds)->get()->keyBy('id');
        $programBOP = Program_BOP_Data::with(['admin', 'member'])->whereIn('id', $programBOPIds)->get()->keyBy('id');
        $setorMotorIds = SetorProgramMotor::with(['admin', 'member'])->whereIn('id', $setorMotorIds)->get()->keyBy('id');
        $setorMobilIds = SetorProgramMobil::with(['admin', 'member'])->whereIn('id', $setorMobilIds)->get()->keyBy('id');

        // Tambahkan program ke masing-masing transaksi
        foreach ($transactions as $transaction) {
            if ($transaction->program_name == "program_bos") {
                $transaction->program = $programBOS->get($transaction->program_id);
            } elseif ($transaction->program_name == "program_bop") {
                $transaction->program = $programBOP->get($transaction->program_id);
            }elseif ($transaction->program_name == "setor_program_motor") {
                $transaction->program = $setorMotorIds->get($transaction->program_id);
            }elseif ($transaction->program_name == "setor_program_mobil") {
                $transaction->program = $setorMobilIds->get($transaction->program_id);
            }
        }

        return view('transaction.setor', compact('transactions'));
    }


    public function acc($id)
    {
        $transaction = Transaction_data::findOrFail($id);
        if ($transaction->program_name == "program_bos") {
            $program = Program_BOS_Data::find($transaction->program_id);
            $debt = $program->member->Debt;
            $debt->program_bos -= $program->transaction_value;
        } else if ($transaction->program_name == "program_bop") {
            $program = Program_BOP_Data::find($transaction->program_id);
            $debt = $program->member->Debt;
            $debt->program_bop -= $program->transaction_value;
        } else if ($transaction->program_name == "program_motor") {
            $program = ProgramMotor::find($transaction->program_id);
            $debt = $program->member->Debt;
            $debt->program_motor -= $program->total_installment;
        }else if ($transaction->program_name == "program_mobil") {
            $program = ProgramMobil::find($transaction->program_id);
            $debt = $program->member->Debt;
            $debt->program_mobil -= $program->total_installment;
        }
        if ($program) {
            $program->status = 'acc';
            $program->save();
        }
        $debt->save();
        // Redirect kembali setelah update
        return redirect()->route('transaction.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction_data::findOrFail($id);
        if ($transaction->program_name == "program_bos") {
            $program = Program_BOS_Data::with(['admin', 'member'])->find($transaction->program_id);
        } elseif ($transaction->program_name == "program_bop") {
            $program = Program_BOP_Data::find($transaction->program_id);
        }
        $transaction->program = $program;
        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
