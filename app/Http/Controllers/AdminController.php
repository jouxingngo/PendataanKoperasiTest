<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SuperAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('superadmin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::all(); // Menampilkan semua admin
        return view('admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = User::create($request->all());
        if($admin){
            session()->flash('status', 'success');
            session()->flash('message', 'Admin ' . $admin->name . " Successfully added");
        }
        return redirect()->route('admin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = User::findOrFail($id);
        $admin->update($request->all());
        if($admin){
            session()->flash('status', 'success');
            session()->flash('message', 'Admin ' . $admin->name . " Successfully edited");
        }
        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();
        if ($admin) {
            session()->flash('status', 'danger');
            session()->flash('message', 'Admin ' . $admin->name . " Successfully deleted");
        }
        return redirect()->route('admin.index');
    }
}
