<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Registration;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|unique:registrations,username',
            'email'    => 'required|email|unique:registrations,email',
            'password' => 'required|min:6',
        ]);

        // Jika username/email sudah ada → otomatis gagal validasi
        // Kita bisa tangkap manual untuk memberi pesan custom
        if (Registration::where('username', $request->username)->exists()) {
            return response()->json([
                'message' => 'Username sudah digunakan, silakan pilih yang lain'
            ], 422);
        }

        if (Registration::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email sudah digunakan, silakan gunakan email lain'
            ], 422);
        }

        $reg = Registration::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => 'pending', // default calon mahasiswa
        ]);

        // Generate token Sanctum
        $token = $reg->createToken('registration_token')->plainTextToken;

        return response()->json([
            'message' => 'Pendaftaran berhasil, menunggu verifikasi',
            'data'    => $reg,
            'token'   => $token,
        ]);
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
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        // Hapus token lama biar tidak numpuk
        $reg->tokens()->delete();

        // Generate token baru
        $token = $reg->createToken('registration_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'status'  => $reg->status,
            'token'   => $token,
        ]);
    }
}
