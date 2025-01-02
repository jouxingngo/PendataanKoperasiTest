<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class AuthController extends Controller
{
    public function index()
    {

        return view('auth.login');
    }

    public function authenticating(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if(Auth::user()->role == 'sp'){
                return redirect()->route('admin.index');
            }
            return redirect()->route('member.index');

        }
        // Login gagal, beri pesan dan alihkan kembali ke halaman login
        session()->flash('status', 'danger');
        session()->flash('message', 'invalid username or password');
        return redirect()->route('login');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->flash('status', 'success');
        session()->flash('message', 'You have successfully logged out.!');
        return redirect()->route('login');
    }
    
}
