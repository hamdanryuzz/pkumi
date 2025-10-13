<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentPageController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Pastikan mahasiswa sudah login
        if (!$student) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mengakses dashboard.');
        }

        // Ambil data untuk filter dropdown
        $periods = Period::orderBy('code', 'desc')->get();
        $semesters = Semester::when($request->period_id, function ($query) use ($request) {
            return $query->where('period_id', $request->period_id);
        })->orderBy('code', 'asc')->get();

        // Query untuk mendapatkan mata kuliah berdasarkan filter
        $enrollments = Enrollment::where('student_id', $student->id)
            ->where('status', 'enrolled')
            ->when($request->period_id, function ($query) use ($request) {
                return $query->whereHas('semester', function ($q) use ($request) {
                    $q->where('period_id', $request->period_id);
                });
            })
            ->when($request->semester_id, function ($query) use ($request) {
                return $query->where('semester_id', $request->semester_id);
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->whereHas('course', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('code', 'like', '%' . $request->search . '%');
                });
            })
            ->with(['course', 'semester.period'])
            ->get();

        // Ambil nilai dari tabel grades dan hitung SKS/Bobot
        $courses = $enrollments->map(function ($enrollment) use ($student) {
            $grade = Grade::where('student_id', $student->id)
                ->where('course_id', $enrollment->course_id)
                ->where('semester_id', $enrollment->semester_id)
                ->first();

            return [
                'course_name' => $enrollment->course->name,
                'course_code' => $enrollment->course->code,
                'sks' => $enrollment->course->sks,
                'letter_grade' => $grade ? $grade->letter_grade : '-',
                'final_grade' => $grade ? $grade->final_grade : '-',
                'semester' => $enrollment->semester->name,
                'period_name' => $enrollment->semester->period->name,
            ];
        });

        // Jika tidak ada data, kirim pesan ke view
        $message = $courses->isEmpty() && ($request->period_id || $request->semester_id || $request->search)
            ? 'Tidak ada mata kuliah yang sesuai dengan filter atau pencarian.'
            : null;

        return view('mahasiswa.grades', compact('student', 'periods', 'semesters', 'courses', 'message'));
    }

    /**
     * Menampilkan halaman profil Mahasiswa yang sedang login.
     */
    public function profile()
    {
        $student = Auth::guard('student')->user();

        // Pastikan mahasiswa sudah login
        if (!$student) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mengakses profil.');
        }

        return view('mahasiswa.profile', compact('student'));
    }

    /**
     * Memproses update data profil Mahasiswa.
     */
    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Pastikan mahasiswa sudah login
        if (!$student) {
            return redirect()->route('login')->with('error', 'Silakan login untuk memperbarui profil.');
        }

        // Validasi data profil, memastikan username/email unik tidak bentrok dengan diri sendiri
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', Rule::unique('students', 'username')->ignore($student->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            // Opsi untuk mengganti password
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $student->update($updateData);

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}