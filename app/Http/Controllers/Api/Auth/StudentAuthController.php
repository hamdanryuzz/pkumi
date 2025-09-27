<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $student = Student::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        $token = $student->createToken('student_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'student' => $student,
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}
