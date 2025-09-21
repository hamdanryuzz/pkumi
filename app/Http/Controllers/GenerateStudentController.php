<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Year;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenerateStudentController extends Controller
{
    public function create()
    {
        $years = Year::all();
        return view('students.create', compact('years'));
    }

    public function getStudentClasses($yearId)
    {
        $studentClasses = StudentClass::where('year_id', $yearId)->get();
        
        \Log::info('Year ID: ' . $yearId);
        \Log::info('Student Classes found: ' . $studentClasses->count());
        \Log::info('Student Classes data: ' . $studentClasses->toJson());
        
        return response()->json($studentClasses);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'year_id' => 'required|exists:years,id',
                'student_class_id' => 'required|exists:student_classes,id',
                'email' => 'nullable|email|unique:students,email',
            ]);

            // Generate username dari name
            $username = $this->generateUsername($request->name);
            
            // Generate password random
            $password = $this->generatePassword();
            
            // Generate NIM
            $nim = $this->generateNIM($request->year_id, $request->student_class_id);

            // FIX: Tambahkan field username ke create data
            $student = Student::create([
                'name' => $request->name,
                'year_id' => $request->year_id,
                'student_class_id' => $request->student_class_id,
                'nim' => $nim,
                'username' => $username, // TAMBAHKAN INI
                'email' => $request->email,
                'password' => bcrypt($password),
                'status' => 'active',
            ]);

            session()->flash('student_generated', [
                'id' => $student->id,
                'name' => $student->name,
                'nim' => $student->nim,
                'username' => $username,
                'generated_password' => $password,
                'email' => $student->email,
            ]);

            return redirect()->route('students.index')->with('success', 'Student account generated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating student: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));
            return back()->withInput()->withErrors(['error' => 'Failed to create student: ' . $e->getMessage()]);
        }
    }

    // public function success()
    // {
    //     if (!session()->has('student_generated')) {
    //         return redirect()->route('students.create')->with('error', 'No student data found.');
    //     }

    //     $studentData = session('student_generated');
    //     return view('students.success', compact('studentData'));
    // }

    // public function index()
    // {
    //     $students = Student::with(['year', 'studentClass'])->get();
    //     return view('students.index', compact('students'));
    // }

    private function generateUsername($name)
    {
        // Convert name to username: lowercase, remove spaces, replace with dots
        $baseUsername = strtolower(str_replace(' ', '.', trim($name)));
        $username = $baseUsername;
        $counter = 1;

        // FIX: Check username field, bukan nim field
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
            // Fallback generation
            return date('y') . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
    }
}
