<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showStudentLoginForm()
    {
        return view('mahasiswa.login');
    }
    
    /**
     * Tampilkan halaman registrasi.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * Tangani proses login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        // if (Auth::attempt($credentials, $request->remember)) {
        //     $request->session()->regenerate();
            
        //     // Arahkan ke dashboard jika login berhasil.
        //     return redirect()->intended(route('dashboard'));
        // }
        
        // Kembali dengan error jika kredensial tidak cocok.
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function StudentLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        // if (Auth::attempt($credentials, $request->remember)) {
        //     $request->session()->regenerate();
            
        //     // Arahkan ke dashboard jika login berhasil.
        //     return redirect()->intended(route('dashboard'));
        // }
        
        // Kembali dengan error jika kredensial tidak cocok.
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Tangani proses logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Deteksi guard yang sedang aktif
        $guard = null;
        if (Auth::guard('student')->check()) {
            $guard = 'student';
        } elseif (Auth::guard('web')->check()) {
            $guard = 'web';
        }

        // Logout sesuai guard
        if ($guard) {
            Auth::guard($guard)->logout();
        } else {
            // fallback jika tidak ada guard terautentikasi
            Auth::logout();
        }

        // Amankan sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect sesuai guard
        if ($guard === 'student') {
            return redirect()->route('login'); // halaman login mahasiswa
        }

        // guard web -> ke admin login; jika tidak ada, fallback ke login umum
        if (Route::has('admin')) {
            return redirect()->route('admin');
        }
        return redirect()->route('login');
    }
}
