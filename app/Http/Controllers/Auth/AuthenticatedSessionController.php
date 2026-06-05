<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
        'unit' => 'required|string', // Pastikan dropdown unit ada di form login
    ]);

    // Data Password per Unit sesuai permintaan Anda
    $unitPasswords = [
        'Tanjung Karang' => 'tanjungkarang123',
        'Metro'          => 'metro123',
        'Kota Bumi'      => 'kotabumi123',
        'Pringsewu'      => 'pringsewu123',
    ];

    $unit = $request->unit;

    // Cek apakah username adalah UP3 dan password cocok dengan unitnya
    if ($request->username === 'UP3' && isset($unitPasswords[$unit])) {
        if ($request->password === $unitPasswords[$unit]) {
            
            // Login sukses: Buat session manual
            $request->session()->put('logged_in_unit', $unit);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    // Jika gagal
    return back()->withErrors([
        'username' => 'Kredensial unit atau password tidak sesuai.',
    ]);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
