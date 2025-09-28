<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Registration;
use App\Helpers\ResponseFormatter;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|unique:registrations,username',
            'email'    => 'required|email|unique:registrations,email',
            'password' => 'required|min:6',
        ]);

        // Validasi manual untuk pesan custom
        if (Registration::where('username', $request->username)->exists()) {
            return ResponseFormatter::error(null, 'Username sudah digunakan, silakan pilih yang lain', 422);
        }

        if (Registration::where('email', $request->email)->exists()) {
            return ResponseFormatter::error(null, 'Email sudah digunakan, silakan gunakan email lain', 422);
        }

        $reg = Registration::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => 'pending',
        ]);

        // Generate token Sanctum
        $token = $reg->createToken('registration_token')->plainTextToken;

        return ResponseFormatter::success([
            'user'  => $reg,
            'token' => $token,
        ], 'Pendaftaran berhasil, menunggu verifikasi');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $reg = Registration::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$reg || !Hash::check($request->password, $reg->password)) {
            return ResponseFormatter::error(null, 'Username atau password salah', 401);
        }

        // Hapus token lama biar tidak numpuk
        $reg->tokens()->delete();

        // Generate token baru
        $token = $reg->createToken('registration_token')->plainTextToken;

        return ResponseFormatter::success([
            'user'   => $reg,
            'token'  => $token,
            'status' => $reg->status,
        ], 'Login berhasil');
    }
}
