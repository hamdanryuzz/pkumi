<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\Registration;
use App\Helpers\ResponseFormatter;

class RegistrationPasswordResetController extends Controller
{
    // Kirim link reset password
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:registrations,email']);

        $token = Str::random(64);

        DB::table('registration_password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email'      => $request->email,
                'token'      => $token,
                'created_at' => now()
            ]
        );

        // kirim email reset password
        Mail::raw(
            "Klik link berikut untuk reset password: " . url("/reset-password?token=$token&email=" . $request->email),
            function ($message) use ($request) {
                $message->to($request->email)->subject('Reset Password PMB');
            }
        );

        return ResponseFormatter::success(null, 'Link reset password sudah dikirim ke email Anda.');
    }

    // Proses reset password
    public function reset(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:registrations,email',
            'token'    => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('registration_password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return ResponseFormatter::error(null, 'Token tidak valid atau sudah kadaluarsa', 400);
        }

        Registration::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('registration_password_resets')->where('email', $request->email)->delete();

        return ResponseFormatter::success(null, 'Password berhasil direset. Silakan login kembali.');
    }

    // Kalau memang mau ada form reset (opsional, untuk blade)
    public function showResetForm(Request $request)
    {
        return view('pmb.auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }
}
