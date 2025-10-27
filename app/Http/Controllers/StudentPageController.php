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
// Hapus komentar pada use statement untuk model submission
use App\Models\RubrikSubmission;
use App\Models\KhazanahSubmission;
use App\Models\Student; // Pastikan model Student di-import

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

        // Query untuk mendapatkan enrollment berdasarkan student_class_id dari student
        $enrollments = Enrollment::where('student_class_id', $student->student_class_id)
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

        // Ambil nilai dari tabel grades dan tampilkan semua komponen nilai
        $courses = $enrollments->map(function ($enrollment) use ($student) {
            $grade = Grade::where('student_id', $student->id)
                ->where('course_id', $enrollment->course_id)
                ->where('semester_id', $enrollment->semester_id)
                ->first();

            return [
                'course_name' => $enrollment->course->name,
                'course_code' => $enrollment->course->code,
                'sks' => $enrollment->course->sks,
                'attendance_score' => $grade ? $grade->attendance_score : '-',
                'assignment_score' => $grade ? $grade->assignment_score : '-',
                'midterm_score' => $grade ? $grade->midterm_score : '-',
                'final_score' => $grade ? $grade->final_score : '-',
                'final_grade' => $grade ? $grade->final_grade : '-',
                'letter_grade' => $grade ? $grade->letter_grade : '-',
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
    public function showRubrikForm()
    {
        // Pastikan mahasiswa login
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login.');
        }
        // Kamu bisa mengirim data 'tags' atau kategori ke view ini jika perlu
        return view('mahasiswa.rubrik-opini.create');
    }

    /**
     * Menyimpan data submission Rubrik Opini dari mahasiswa.
     */
    public function storeRubrik(Request $request)
    {
         // Pastikan mahasiswa login
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('login')->with('error', 'Silakan login.');
        }

        // Validasi data input
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'ringkasan' => 'required|string|max:500', // Sesuaikan max length jika perlu
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
            'tags' => 'nullable|string|max:255', // Asumsi tags dipisah koma
            'konten' => 'required|string',
        ]);

        // --- Logika Penyimpanan Data ---
        // 1. Proses upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            // Simpan gambar dan dapatkan path-nya
            $imagePath = $request->file('cover_image')->store('rubrik_covers', 'public'); 
             // Pastikan storage:link sudah dijalankan
        }

        // 2. Buat record baru di database menggunakan Model
        RubrikSubmission::create([
            'student_id' => $student->id,
            'title' => $validated['judul'],
            'summary' => $validated['ringkasan'],
            'content' => $validated['konten'],
            'tags' => $validated['tags'],
            'cover_image_path' => $imagePath,
            'status' => 'pending', // Status awal
        ]);

        // Redirect kembali ke dashboard dengan pesan sukses
        return redirect()->route('mahasiswa.dashboard')->with('success', 'Rubrik Opini berhasil dikirim dan sedang menunggu review.');
    }

    /**
     * Menampilkan form untuk submit Khazanah.
     */
    public function showKhazanahForm()
    {
        // Pastikan mahasiswa login
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login.');
        }
        return view('mahasiswa.khazanah.create');
    }

    /**
     * Menyimpan data submission Khazanah dari mahasiswa.
     */
    public function storeKhazanah(Request $request)
    {
        // Pastikan mahasiswa login
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('login')->with('error', 'Silakan login.');
        }

        // Validasi data input
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'ringkasan' => 'required|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tags' => 'nullable|string|max:255',
            'konten' => 'required|string',
        ]);

        // --- Logika Penyimpanan Data ---
        $imagePath = null;
        if ($request->hasFile('cover_image')) {
             // Simpan gambar dan dapatkan path-nya
            $imagePath = $request->file('cover_image')->store('khazanah_covers', 'public');
            // Pastikan storage:link sudah dijalankan
        }

        // Buat record baru di database menggunakan Model
        KhazanahSubmission::create([
            'student_id' => $student->id,
            'title' => $validated['judul'],
            'summary' => $validated['ringkasan'],
            'content' => $validated['konten'],
            'tags' => $validated['tags'],
            'cover_image_path' => $imagePath,
            'status' => 'pending', // Status awal
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Khazanah berhasil dikirim dan sedang menunggu review.');
    }
}

