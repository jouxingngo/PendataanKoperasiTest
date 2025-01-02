<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Program_BOS_Data;
use App\Models\Transaction_data;
use Illuminate\Http\Request;

class ProgramBOSDataController extends Controller
{

    public function __construct()
    {
        $this->middleware('superadmin')->only(['edit', 'update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $program_datas = Program_BOS_Data::with(['member', 'admin'])->get();
        $members = Member::all();
        // $member = Member::with('Porgram_bos_datas')->findOrFail($id);
        return view('Program.bos.index', compact('program_datas', 'members'));
    }

    public function memberProgramBos($id)
    {
        $member = Member::findOrFail($id);

        // $program_datas = Program_BOS_Data::with(['member', 'admin'])->where->get();


        return view('Program.bos.index', compact('member'));
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

    public function stored(Request $request, $id)
    {
        // Buat instance baru dari Program_BOS_Data tanpa langsung menyimpan
        $program_bos = new Program_BOS_Data();
        $program_bos->fill($request->all()); // Isi atribut dengan data dari request

        $debt = $program_bos->member->Debt;

        // Validasi transaksi
        if ($request->transaction_type == "setor") {
            // Cek apakah jumlah yang disetor lebih besar dari hutang
            if ($program_bos->transaction_value + $debt->program_bos > 0) {
                // Jika jumlah yang disetor lebih besar dari hutang, beri pesan error
                session()->flash('status', 'danger');
                session()->flash('message', 'Jumlah setor melebihi hutang.');

                // Redirect kembali tanpa menyimpan
                return redirect()->route('member.program_bos', ['id' => $id]);
            } else {
                // Tambahkan nilai program_bos jika valid
                $debt->program_bos += $program_bos->transaction_value;
                $program_bos->status = 'acc';
            }
        }

        // Simpan perubahan pada program_bos dan debt
        $program_bos->save(); // Data baru sekarang disimpan di database
        $debt->save();

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'program_bos',
            'program_id' => $program_bos->id,
            'transaction_type' => $program_bos->transaction_type
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
        return redirect()->route('member.program_bos', ['id' => $id]);
    }


    public function store(Request $request)
    {

        // Mulai dengan membuat program_bos dari request
        $program_bos = Program_BOS_Data::create($request->all());
        $debt = $program_bos->member->Debt;

        // Tambahkan nilai program_bos
        if ($request->transaction_type == "setor") {
            // Cek apakah jumlah yang disetor lebih besar dari hutang
            if ($program_bos->transaction_value + $debt->program_bos > 0) {
                // Jika jumlah yang disetor lebih besar dari hutang, beri pesan error dan stop proses
                session()->flash('status', 'danger');
                session()->flash('message', 'Jumlah setor melebihi hutang.');

                // Hapus program_bos yang baru dibuat
                $program_bos->delete();

                // Redirect kembali tanpa melanjutkan eksekusi
                return redirect()->route('program_bos.index');
            } else {
                // Jika ada pinjaman, lakukan penambahan program_bos
                $debt->program_bos += $program_bos->transaction_value;
                $program_bos->status = 'acc';
            }
        } elseif ($request->transaction_type == "pinjam") {
            // Jika transaksi adalah "pinjam", kurangi program_bos
            $debt->program_bos -= $program_bos->transaction_value;
        }

        // Simpan perubahan pada program_bos dan debt
        $program_bos->save();
        $debt->save();

        // Buat data transaksi
        $transaction = Transaction_data::create([
            'program_name' => 'program_bos',
            'program_id' => $program_bos->id,
            'transaction_type' => $program_bos->transaction_type
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
        return redirect()->route('member.index', compact('member'));
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
    public function update(Request $request, string $id) {}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program_bos = Program_BOS_Data::findOrFail($id);
        $member_id = $program_bos->member->id;

        // Ambil data Debt yang terkait dengan member
        $debt = $program_bos->member->Debt;

        // Sesuaikan nilai program_bos dalam Debt
        if ($program_bos->transaction_type == 'pinjam') {
            if ($program_bos->status == "acc") {
                $debt->program_bos += $program_bos->transaction_value;
            }
        } elseif ($program_bos->transaction_type == 'setor') {
            $debt->program_bos -= $program_bos->transaction_value;
        }

        // Simpan perubahan ke database
        $debt->save();

        // Hapus program_bos dan transaksi terkait
        $program_bos->delete();
        // Menghapus transaksi terkait berdasarkan ID program_bos
        Transaction_data::where('program_id', $id)
            ->where('program_name', 'program_bos') // Pastikan hanya transaksi program_bos yang dihapus
            ->delete();

        // Flash message
        session()->flash('status', 'success');
        session()->flash('message', $program_bos->member->name . " Program Bop Successfully deleted");

        // Redirect
        return redirect()->route('member.program_bos', ['id' => $member_id]);
    }
}
