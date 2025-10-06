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
use Illuminate\Support\Facades\Hash; // Tambah Hash

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $yearFilter = $request->input('year_id');
        $studentClassFilter = $request->input('student_class_id');
        $courseFilter = $request->input('course_id');

        $query = Student::with(['enrollments', 'year', 'studentClass']);

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
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
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
                    'status' => 'active',
                ];

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
        $semester = Semester::all();
        
        // Base query untuk enrollments mahasiswa dengan relasi course dan semester
        $enrollmentQuery = Enrollment::where('student_id', $student->id)
            ->with(['course', 'semester'])
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
        
        return view('students.show', compact('student', 'enrollments', 'semester', 'search', 'semesterFilter','totalCredits','ipk')); // totalCredits diganti totalSKS
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
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'username' => 'required|unique:students,username,' . $student->id,
            'student_class_id' => 'required|exists:student_classes,id',
            'year_id' => 'required|exists:years,id',
            'status' => 'required|in:active,inactive',
        ]);

        $updateData = $request->except(['password']);
        
        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password); // Menggunakan Hash::make()
        }

        $student->update($updateData);

        return redirect()->route('students.index')
            ->with('success', 'Data student berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
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

        Excel::import(new StudentsImport, $request->file('file'));

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
            $year = Year::find($yearId);
            $studentClass = StudentClass::find($studentClassId);
            
            if (!$year || !$studentClass) {
                throw new \Exception('Year or StudentClass not found');
            }
            
            $yearCode = substr($year->name, -2);
            $classCode = str_pad($studentClass->id, 2, '0', STR_PAD_LEFT);
            
            $lastStudent = Student::where('year_id', $yearId)
                                 ->where('student_class_id', $studentClassId)
                                 ->orderBy('nim', 'desc')
                                 ->first();
            
            $sequential = 1;
            if ($lastStudent && strlen($lastStudent->nim) >= 3) {
                $lastSequential = (int)substr($lastStudent->nim, -3);
                $sequential = $lastSequential + 1;
            }
            
            $sequentialCode = str_pad($sequential, 3, '0', STR_PAD_LEFT);
            
            return $yearCode . $classCode . $sequentialCode;
            
        } catch (\Exception $e) {
            \Log::error('Error generating NIM: ' . $e->getMessage());
            return date('y') . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
    }
}