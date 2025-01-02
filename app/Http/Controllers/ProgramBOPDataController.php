<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Program_BOP_Data;
use App\Models\Transaction_data;
use Illuminate\Http\Request;

class ProgramBOPDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $program_datas = Program_BOP_Data::with(['member', 'admin'])->get();
        $members = Member::all();
        return view('Program.bop.index', compact('program_datas', 'members'));
    }

    public function memberProgramBop($id)
    {
        $member = Member::with('Program_bop_datas')->findOrFail($id);

        return view('Program.bop.index', compact('member'));
    }

    public function stored(Request $request, $id)
    {
        // Buat instance baru dari Program_BOp_Data tanpa langsung menyimpan
        $program_bop = new Program_BOP_Data();
        $program_bop->fill($request->all()); // Isi atribut dengan data dari request

        $debt = $program_bop->member->Debt;

        // Validasi transaksi
        if ($request->transaction_type == "setor") {
            // Cek apakah jumlah yang disetor lebih besar dari hutang
            if ($program_bop->transaction_value + $debt->program_bop > 0) {
                // Jika jumlah yang disetor lebih besar dari hutang, beri pesan error
                session()->flash('status', 'danger');
                session()->flash('message', 'Jumlah setor melebihi hutang.');

                // Redirect kembali tanpa menyimpan
                return redirect()->route('member.program_bop', ['id' => $id]);
            } else {
                // Tambahkan nilai program_bop jika valid
                $debt->program_bop += $program_bop->transaction_value;
                $program_bop->status = 'acc';
            }
        }
        // elseif ($request->transaction_type == "pinjam") {
        //     // Kurangi nilai program_bop jika transaksi adalah "pinjam"
        //     $debt->program_bop -= $program_bop->transaction_value;
        // }

        // Simpan perubahan pada program_bop dan debt
        $program_bop->save(); // Data baru sekarang disimpan di database
        $debt->save();

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'program_bop',
            'program_id' => $program_bop->id,
            'transaction_type' => $program_bop->transaction_type
        ]);

        // Cek jika transaksi berhasil dibuat
        if ($transaction) {
            session()->flash('status', 'success');
            session()->flash('message', 'Transaction Program BOS successfully added.');
        } else {
            // Pesan error jika transaksi gagal
            session()->flash('status', 'danger');
            session()->flash('message', 'Failed to create transaction.');
        }

        // Redirect ke halaman index
        return redirect()->route('member.program_bop', ['id' => $id]);
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
        // Mulai dengan membuat program_bop dari request
        $program_bop = Program_BOP_Data::create($request->all());
        $debt = $program_bop->member->Debt;

        // Tambahkan nilai program_bop
        if ($request->transaction_type == "setor") {
            // Cek apakah jumlah yang disetor lebih besar dari hutang
            if ($program_bop->transaction_value + $debt->program_bop > 0) {
                // Jika jumlah yang disetor lebih besar dari hutang, beri pesan error dan stop proses
                session()->flash('status', 'danger');
                session()->flash('message', 'Jumlah setor melebihi hutang.');

                // Hapus program_bop yang baru dibuat
                $program_bop->delete();

                // Redirect kembali tanpa melanjutkan eksekusi
                return redirect()->route('program_bop.index');
            } else {
                // Jika ada pinjaman, lakukan penambahan program_bop
                $debt->program_bop += $program_bop->transaction_value;
                $program_bop->status = 'acc';
            }
        } elseif ($request->transaction_type == "pinjam") {
            // Jika transaksi adalah "pinjam", kurangi program_bop
            $debt->program_bop -= $program_bop->transaction_value;
        }

        // Simpan perubahan pada program_bop dan debt
        $program_bop->save();
        $debt->save();

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'program_bop',
            'program_id' => $program_bop->id,
            'transaction_type' => $program_bop->transaction_type
        ]);

        // Cek jika transaksi berhasil dibuat
        if ($transaction) {
            session()->flash('status', 'success');
            session()->flash('message', 'Transaction Program Bop Successfully added');
        } else {
            // Pesan error jika transaksi gagal
            session()->flash('status', 'danger');
            session()->flash('message', 'Failed to create transaction.');
        }

        // Redirect ke halaman index
        return redirect()->route('member.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $program_bop = Program_BOP_Data::findOrFail($id);
        $member_id = $program_bop->member->id;

        // Ambil data Debt yang terkait dengan member
        $debt = $program_bop->member->Debt;

        // Sesuaikan nilai program_bop dalam Debt
        if ($program_bop->transaction_type == 'pinjam') {
            if ($program_bop->status == "acc") {
                $debt->program_bop += $program_bop->transaction_value;
            }
        } elseif ($program_bop->transaction_type == 'setor') {
            $debt->program_bop -= $program_bop->transaction_value;
        }

        // Simpan perubahan ke database
        $debt->save();

        // Hapus program_bop dan transaksi terkait
        $program_bop->delete();
        // Menghapus transaksi terkait berdasarkan ID program_bop
        Transaction_data::where('program_id', $id)
            ->where('program_name', 'program_bop') // Pastikan hanya transaksi program_bos yang dihapus
            ->delete();

        // Flash message
        session()->flash('status', 'success');
        session()->flash('message', $program_bop->member->name . " Program Bop Successfully deleted");

        // Redirect
        return redirect()->route('member.program_bop', ['id' => $member_id]);
    }
}
