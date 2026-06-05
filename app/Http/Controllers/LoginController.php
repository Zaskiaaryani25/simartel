<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


public function login(Request $request)
{
    // 1. Validasi Input
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // 2. Coba Login
    if (Auth::attempt($credentials)) {
        // 3. Regenerasi session untuk keamanan (SANGAT PENTING)
        $request->session()->regenerate();

        // 4. Redirect ke dashboard
        return redirect()->intended('/dashboard');
    }

    // 5. Jika gagal, kembalikan dengan pesan error
    return back()->withErrors([
        'username' => 'Username atau password salah.',
    ])->onlyInput('username');
}

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}