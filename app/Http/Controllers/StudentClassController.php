<?php

namespace App\Http\Controllers;

use App\Models\StudentClass;
use App\Models\Year;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StudentClassController extends Controller
{
    /**
     * Menampilkan semua data kelas siswa.
     */
    public function index(Request $request)
    {
        // Ambil parameter dari request
        $search = $request->input('search');
        $yearFilter = $request->input('year_id');
        
        // Query dasar dengan relasi year dan students
        $query = StudentClass::with(['year', 'students' => function ($query) {
            $query->select('id', 'student_class_id', 'name', 'nim'); // Limit kolom untuk optimasi
        }]);
        
        // Terapkan search jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('year', function ($yearQuery) use ($search) {
                      $yearQuery->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }
        
        // Terapkan filter tahun jika ada
        if ($yearFilter) {
            $query->where('year_id', $yearFilter);
        }
        
        // Eksekusi query dengan pagination
        $studentClasses = $query->withCount('students') // Hitung jumlah siswa tanpa load data lengkap
            ->latest()
            ->paginate(10); // Tambah pagination (10 item per halaman)
        
        // Ambil semua tahun untuk dropdown filter
        $years = Year::orderBy('name', 'desc')->get();
        
        return view('student_classes.index', compact('studentClasses', 'years', 'yearFilter', 'search'));
    }

    /**
     * Menampilkan form tambah kelas siswa.
     */
    public function create()
    {
        $years = Year::all(); // untuk dropdown pilihan tahun
        return view('student_classes.create', compact('years'));
    }

    /**
     * Simpan kelas siswa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('student_classes', 'name')->where(function ($query) use ($request) {
                    return $query->where('year_id', $request->year_id);
                }), // Unik per tahun
            ],
        ]);

        try {
            StudentClass::create([
                'year_id' => $request->year_id,
                'name' => $request->name,
            ]);

            Log::info('Kelas siswa baru dibuat', ['name' => $request->name, 'year_id' => $request->year_id]);

            return redirect()->route('student_classes.index')->with('success', 'Kelas berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal membuat kelas siswa', ['error' => $e->getMessage()]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat kelas. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan detail kelas siswa.
     */
    public function show(Request $request, $id)
    {
        $studentClass = StudentClass::with(['year', 'students' => function ($query) {
            $query->select('id', 'student_class_id', 'name', 'nim');
        }])->findOrFail($id);

        // Ambil parameter search dan filter semester
        $search = $request->input('search');
        $semesterFilter = $request->input('semester_id');

        // Query untuk mata kuliah dengan relasi
        $coursesQuery = Course::where('student_class_id', $id)
            ->with(['semesters']);

        // Terapkan search jika ada
        if ($search) {
            $coursesQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('code', 'LIKE', '%' . $search . '%');
            });
        }

        // Terapkan filter semester jika ada
        if ($semesterFilter) {
            $coursesQuery->whereHas('semesters', function ($q) use ($semesterFilter) {
                $q->where('semesters.id', $semesterFilter);
            });
        }

        // Eksekusi query dengan pagination
        $courses = $coursesQuery->paginate(10);

        // Ambil semua semester untuk dropdown filter
        $semesters = Semester::orderBy('name')->get();

        return view('student_classes.show', compact('studentClass', 'courses', 'semesters', 'semesterFilter', 'search'));
    }

    /**
     * Menampilkan form edit kelas siswa.
     */
    public function edit($id)
    {
        $studentClass = StudentClass::findOrFail($id);
        $years = Year::all();
        return view('student_classes.edit', compact('studentClass', 'years'));
    }

    /**
     * Update data kelas siswa.
     */
    public function update(Request $request, $id)
    {
        $studentClass = StudentClass::findOrFail($id);

        $request->validate([
            'year_id' => 'required|exists:years,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('student_classes', 'name')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('year_id', $request->year_id);
                }), // Unik per tahun, ignore ID saat edit
            ],
        ]);

        try {
            $studentClass->update([
                'year_id' => $request->year_id,
                'name' => $request->name,
            ]);

            Log::info('Kelas siswa diupdate', ['id' => $id, 'name' => $request->name]);

            return redirect()->route('student_classes.index')->with('success', 'Kelas berhasil diupdate.');
        } catch (\Exception $e) {
            Log::error('Gagal update kelas siswa', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate kelas. Silakan coba lagi.']);
        }
    }

    /**
     * Hapus data kelas siswa.
     */
    public function destroy($id)
    {
        $studentClass = StudentClass::findOrFail($id);

        try {
            $studentClass->delete(); // Sekarang soft delete dengan trait

            Log::info('Kelas siswa dihapus', ['id' => $id, 'name' => $studentClass->name]);

            return redirect()->route('student_classes.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus kelas siswa', ['id' => $id, 'error' => $e->getMessage()]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus kelas. Pastikan tidak ada siswa yang bergantung.']);
        }
    }
}