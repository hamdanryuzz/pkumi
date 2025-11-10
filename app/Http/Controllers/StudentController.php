<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Year;
use App\Models\StudentClass;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $yearFilter = $request->input('year_id');
        $studentClassFilter = $request->input('student_class_id');
        $courseFilter = $request->input('course_id');

        $query = Student::with(['year', 'studentClass']);

        // Search berdasarkan nama, NIM, atau username
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan year/angkatan
        if ($yearFilter) {
            $query->where('year_id', $yearFilter);
        }

        // Filter berdasarkan student_class/kelas
        if ($studentClassFilter) {
            $query->where('student_class_id', $studentClassFilter);
        }

        // Filter berdasarkan course/mata kuliah
        if ($courseFilter) {
            $query->whereHas('enrollments', function($q) use ($courseFilter) {
                $q->where('course_id', $courseFilter);
            });
        }

        $students = $query->paginate(15)->appends(request()->query());

        // Data untuk dropdown filter
        $years = Year::all();
        $studentClasses = StudentClass::with('year')->get();
        $courses = Course::all();

        return view('students.index', compact('students', 'years', 'studentClasses', 'courses'));
    }

    public function create()
    {
        $years = Year::all();
        $classes = StudentClass::all();
        return view('students.create', compact('years', 'classes'));
    }

    public function store(Request $request)
        {
            // Validasi dasar
            $rules = [
                'name' => 'required|string|max:255',
                'year_id' => 'required|exists:years,id',
                'student_class_id' => 'required|exists:student_classes,id',
                'email' => 'nullable|email|unique:students,email',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'status' => 'required|in:active,inactive', // Tambahkan validasi status
                'generation_mode' => 'required|in:auto,manual',
            ];

            // Conditional validation berdasarkan mode
            if ($request->generation_mode === 'manual') {
                $rules['nim'] = 'required|string|unique:students,nim|max:20';
                $rules['username'] = 'required|string|unique:students,username|max:50';
                $rules['password'] = 'required|string|min:6|max:255';
            }

            $request->validate($rules);

            try {
                $studentData = [
                    'name' => $request->name,
                    'year_id' => $request->year_id,
                    'student_class_id' => $request->student_class_id,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    // 'status' => 'active',
                    'status' => $request->status,
                ];

                // Handle Image Upload
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // Gunakan Storage facade dengan disk 'public'
                    \Storage::disk('public')->putFileAs('students', $image, $imageName);
                    
                    $studentData['image'] = $imageName;
                }

                // Auto generation atau manual input
                if ($request->generation_mode === 'auto') {
                    $studentData['nim'] = $this->generateNIM($request->year_id, $request->student_class_id);
                    $studentData['username'] = $this->generateUsername($request->name);
                    $generatedPassword = $this->generatePassword();
                    $studentData['password'] = Hash::make($generatedPassword); // Menggunakan Hash::make()
                    
                    $student = Student::create($studentData);
                    // Dihapus: Grade::create(['student_id' => $student->id]);
                    
                    // Redirect ke success page dengan generated credentials
                    session()->flash('student_generated', [
                        'id' => $student->id,
                        'name' => $student->name,
                        'nim' => $student->nim,
                        'username' => $student->username,
                        'generated_password' => $generatedPassword,
                        'email' => $student->email,
                    ]);

                    return redirect()->route('students.success')->with('success', 'Student account generated successfully!');
                    
                } else {
                    // Manual mode
                    $studentData['nim'] = $request->nim;
                    $studentData['username'] = $request->username;
                    $studentData['password'] = Hash::make($request->password); // Menggunakan Hash::make()
                    
                    $student = Student::create($studentData);
                    // Dihapus: Grade::create(['student_id' => $student->id]);

                    return redirect()->route('students.index')
                        ->with('success', 'Student berhasil ditambahkan dengan kredensial manual.');
                }

            } catch (\Exception $e) {
                \Log::error('Error creating student: ' . $e->getMessage());
                \Log::error('Request data: ' . json_encode($request->all()));
                return back()->withInput()->withErrors(['error' => 'Failed to create student: ' . $e->getMessage()]);
            }
        }

    public function success()
    {
        if (!session()->has('student_generated')) {
            return redirect()->route('students.create')->with('error', 'No student data found.');
        }

        $studentData = session('student_generated');
        return view('students.success', compact('studentData'));
    }

    public function show(Request $request, Student $student)
    {
        // Load relasi yang dibutuhkan
        $student->load(['studentClass', 'year']);
        
        $search = $request->get('search');
        $semesterFilter = $request->get('semester_id');
        
        // Ambil semua semester untuk dropdown filter
        $semesters = Semester::orderBy('name', 'desc')->get();
        
        // Base query untuk enrollments mahasiswa dengan relasi course dan semester
        $enrollmentQuery = Enrollment::where('student_class_id', $student->student_class_id)
            ->with(['course','semester'])
            ->where('status', 'enrolled');

        
        // Filter berdasarkan pencarian mata kuliah
        if ($search) {
            $enrollmentQuery->whereHas('course', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan semester/Semester
        if ($semesterFilter) {
            $enrollmentQuery->where('semester_id', $semesterFilter);
        }
        
        // Ambil data enrollments dengan paginasi
        $enrollments = $enrollmentQuery->paginate(10);
        
        $totalSKS = 0;
        $totalBobot = 0;

        // FIXED: Hitung IPK pada SEMUA data nilai, bukan hanya yang di paginate.
        // Eager load grades secara terpisah untuk efisiensi.
        $allGrades = Grade::where('student_id', $student->id)
                          ->with('course')
                          ->get()
                          ->keyBy(function($grade) {
                              return $grade->course_id . '_' . $grade->semester_id;
                          });

        // Loop melalui collection yang di-paginate untuk menambahkan grade dan menghitung total
        $enrollmentWithGrades = $enrollments->getCollection()->map(function ($enrollment) use ($allGrades) {
            $key = $enrollment->course_id . '_' . $enrollment->semester_id;
            $grade = $allGrades->get($key);
            $enrollment->grade = $grade;
            return $enrollment;
        });

        $enrollments->setCollection($enrollmentWithGrades);

        // Perhitungan IPK (dilakukan pada SEMUA riwayat nilai yang ada di $allGrades)
        $totalSKS = $allGrades->sum(function($grade) {
            // Menggunakan (float) untuk konversi bobot dan memastikan perkalian yang benar
            return ($grade->course->sks ?? 0) * (float)($grade->bobot ?? 0);
        });

        $totalCredits = $allGrades->sum(function($grade) {
            return $grade->course->sks ?? 0;
        });

        // Hitung IPK
        $ipk = $totalCredits > 0 ? round($totalSKS / $totalCredits, 2) : 0;
        
        return view('students.show', compact('student', 'enrollments', 'semesters', 'search', 'semesterFilter','totalCredits','ipk', 'totalSKS')); // totalCredits diganti totalSKS
    }

    public function edit(Student $student)
    {
        $years = Year::all();
        $classes = StudentClass::all();
        return view('students.edit', compact('student', 'years', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nim' => 'required|unique:students,nim,' . $student->id,
            'name' => 'required|string|max:255',
            'username' => 'required|unique:students,username,' . $student->id,
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'student_class_id' => 'required|exists:student_classes,id',
            'year_id' => 'required|exists:years,id',
            'status' => 'required|in:active,inactive,lulus',
            'gender' => 'nullable|in:Laki-Laki,Perempuan',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'student_job' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:Belum Kawin,Kawin',
            'program' => 'nullable|string|max:255',
            'admission_year' => 'nullable|string|max:4',
            'first_semester' => 'nullable|string|max:255',
            'origin_of_university' => 'nullable|string|max:255',
            'initial_study_program' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|string|max:4',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'father_name' => 'nullable|string|max:255',
            'father_last_education' => 'nullable|string|max:255',
            'father_job' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_last_education' => 'nullable|string|max:255',
            'mother_job' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'street' => 'nullable|string|max:255',
            'rt_rw' => 'nullable|string|max:20',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $updateData = $request->except(['password', '_token', '_method']);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Hapus image lama jika ada
            if ($student->image && \Storage::disk('public')->exists('students/' . $student->image)) {
                \Storage::disk('public')->delete('students/' . $student->image);
            }
            
            // Upload image baru
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            \Storage::disk('public')->putFileAs('students', $image, $imageName);
            $updateData['image'] = $imageName;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $student->update($updateData);

        return redirect()->route('students.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        // Hapus image jika ada
        if ($student->image && \Storage::disk('public')->exists('students/' . $student->image)) {
            \Storage::disk('public')->delete('students/' . $student->image);
        }

        // Perlu dipertimbangkan untuk menghapus Enrollments dan Grades terkait
        // karena student memiliki relasi hasMany ke keduanya.
        $student->delete(); 
        return redirect()->route('students.index')
            ->with('success', 'Student berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new StudentsImport();
        Excel::import($import, $request->file('file'));

        $errors = $import->getErrors();

        if (count($errors) > 0) {
            return redirect()->route('students.index')
                ->with('warning', 'Beberapa baris tidak diimpor karena data tidak valid:')
                ->with('import_errors', $errors);
        }

        return redirect()->route('students.index')
            ->with('success', 'Data students berhasil diimpor.');
    }

    // API untuk AJAX calls
    public function getStudentClasses($yearId)
    {
        $studentClasses = StudentClass::where('year_id', $yearId)->get();
        return response()->json($studentClasses);
    }

    // Private helper methods
    private function generateUsername($name)
    {
        $baseUsername = strtolower(str_replace(' ', '.', trim($name)));
        $username = $baseUsername;
        $counter = 1;

        while (Student::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    private function generatePassword()
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $symbols[rand(0, strlen($symbols) - 1)];

        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < 8; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }

    private function generateNIM($yearId, $studentClassId)
    {
        try {
            $year = Year::with('period')->find($yearId);
            $studentClass = StudentClass::find($studentClassId);
            
            if (!$year || !$studentClass) {
                throw new \Exception('Year or StudentClass not found');
            }
            
            // 1. Ambil 2 digit terakhir dari period->name (Tahun Ajaran)
            // Mendukung format: "Tahun Ajaran 2025", "Tahun Ajaran 2025/2026", "Tahun Ajaran 2025-2026"
            $periodName = $year->period ? $year->period->name : $year->name;
            preg_match('/(\d{4})/', $periodName, $matches);
            $yearCode = isset($matches[1]) ? substr($matches[1], -2) : date('y');
            
            // 2. Tentukan kode angkatan dari year->name (ganjil=1, genap=2)
            // Ekstrak angka dari nama angkatan (misal: "Angkatan 3" -> 3)
            preg_match('/(\d+)/', $year->name, $angkatanMatches);
            $angkatanNumber = isset($angkatanMatches[1]) ? (int)$angkatanMatches[1] : 0;
            $angkatanCode = ($angkatanNumber % 2 === 0) ? '2' : '1'; // Genap=2, Ganjil=1
            
            // 3. Tentukan kode program studi berdasarkan nama kelas
            $className = strtoupper($studentClass->name);
            $prodiCode = '00'; // default
            
            if (preg_match('/S2\s*PKU\s*[ABC]?$/i', $className)) {
                // S2 PKU A, S2 PKU B, S2 PKU C, atau hanya S2 PKU
                $prodiCode = '01';
            } elseif (preg_match('/S2\s*PKUP/i', $className)) {
                // S2 PKUP
                $prodiCode = '02';
            } elseif (preg_match('/S3\s*PKU/i', $className)) {
                // S3 PKU
                $prodiCode = '03';
            }
            
            // 4. Generate nomor urut 3 digit (001, 002, ...)
            $lastStudent = Student::where('year_id', $yearId)
                                ->where('student_class_id', $studentClassId)
                                ->orderBy('nim', 'desc')
                                ->first();
            
            $sequential = 1;
            if ($lastStudent && strlen($lastStudent->nim) >= 9) {
                // Ambil 4 digit terakhir dari NIM sebelumnya
                $lastSequential = (int)substr($lastStudent->nim, -4);
                $sequential = $lastSequential + 1;
            }

            $sequentialCode = str_pad($sequential, 4, '0', STR_PAD_LEFT);
            
            // Format: Period YY + Year Angkatan + Prodi + Urut
            // Contoh: 25 (dari Period) + 1 (dari Year ganjil) + 1 (S2 PKU) + 001 = 25101001
            return $yearCode . $angkatanCode . $prodiCode . $sequentialCode;
            
        } catch (\Exception $e) {
            \Log::error('Error generating NIM: ' . $e->getMessage());
            return date('y') . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        }
    }

    // API untuk mendapatkan periode dari year yang dipilih
    public function getYearPeriod($yearId)
    {
        $year = Year::with('period')->find($yearId);
        
        if (!$year) {
            return response()->json(['error' => 'Year not found'], 404);
        }
        
        return response()->json([
            'year_name' => $year->name,
            'period_name' => $year->period ? $year->period->name : null
        ]);
    }
}