<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\GradeWeight;
use App\Models\Period;
use App\Models\Semester;
use App\Models\Year;
use App\Models\StudentClass;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Registration;
use App\Models\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Context: Current date is October 6, 2025
     * Active semester: Semester Ganjil 2025/2026
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        $this->clearTables();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed in logical order
        $this->seedGradeWeights();
        $this->seedUsers();
        $this->seedPeriods();
        $this->seedSemesters();
        $this->seedYears();
        $this->seedStudentClasses();
        $this->seedCourses();
        $this->seedStudents();
        // $this->seedEnrollments();
        $this->seedGrades();
        $this->seedRegistrations();
        $this->seedLogs();

        $this->command->info('✅ Database seeded successfully!');
    }

    private function clearTables(): void
    {
        Log::truncate();
        Registration::truncate();
        Grade::truncate();
        Enrollment::truncate();
        Student::truncate();
        Course::truncate();
        StudentClass::truncate();
        Year::truncate();
        Semester::truncate();
        Period::truncate();
        User::truncate();
        GradeWeight::truncate();
    }

    /**
     * Seed Grade Weights (Global Configuration)
     */
    private function seedGradeWeights(): void
    {
        GradeWeight::create([
            'attendance_weight' => 10.00,
            'assignment_weight' => 20.00,
            'midterm_weight' => 30.00,
            'final_weight' => 40.00,
        ]);

        $this->command->info('✓ Grade weights seeded');
    }

    /**
     * Seed Users (Admin & Lecturers)
     */
    private function seedUsers(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@university.ac.id',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dr. Budi Santoso, M.Kom',
                'email' => 'budi.santoso@university.ac.id',
                'password' => Hash::make('dosen123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Prof. Siti Aminah, Ph.D',
                'email' => 'siti.aminah@university.ac.id',
                'password' => Hash::make('dosen123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('✓ Users seeded (3 users)');
    }

    /**
     * Seed Academic Periods
     */
    private function seedPeriods(): void
    {
        $periods = [
            [
                'name' => 'Tahun Ajaran 2023',
                'code' => '2023',
                'status' => 'completed',
            ],
            [
                'name' => 'Tahun Ajaran 2024',
                'code' => '2024',
                'status' => 'completed',
            ],
            [
                'name' => 'Tahun Ajaran 2025',
                'code' => '2025',
                'status' => 'active',
            ],
        ];

        foreach ($periods as $period) {
            Period::create($period);
        }

        $this->command->info('✓ Periods seeded (3 periods)');
    }

    /**
     * Seed Semesters
     */
    private function seedSemesters(): void
    {
        $semesters = [
            // 2023/2024 - Completed
            [
                'period_id' => 1,
                'name' => '2023 Ganjil',
                'code' => '2023-1',
                'start_date' => '2023-08-01',
                'end_date' => '2023-12-31',
                'enrollment_start_date' => '2023-07-01',
                'enrollment_end_date' => '2023-07-31',
                'status' => 'completed',
            ],
            [
                'period_id' => 1,
                'name' => '2023 Genap',
                'code' => '2023-2',
                'start_date' => '2024-02-01',
                'end_date' => '2024-06-30',
                'enrollment_start_date' => '2024-01-01',
                'enrollment_end_date' => '2024-01-31',
                'status' => 'completed',
            ],
            
            // 2024/2025 - Completed
            [
                'period_id' => 2,
                'name' => '2024 Ganjil',
                'code' => '2024-1',
                'start_date' => '2024-08-01',
                'end_date' => '2024-12-31',
                'enrollment_start_date' => '2024-07-01',
                'enrollment_end_date' => '2024-07-31',
                'status' => 'completed',
            ],
            [
                'period_id' => 2,
                'name' => '2024 Genap',
                'code' => '2024-2',
                'start_date' => '2025-02-01',
                'end_date' => '2025-06-30',
                'enrollment_start_date' => '2025-01-01',
                'enrollment_end_date' => '2025-01-31',
                'status' => 'completed',
            ],
            
            // 2025/2026 - Current (October 2025 = Semester Ganjil is ACTIVE)
            [
                'period_id' => 3,
                'name' => '2025 Ganjil',
                'code' => '2025-1',
                'start_date' => '2025-08-01',
                'end_date' => '2025-12-31',
                'enrollment_start_date' => '2025-07-01',
                'enrollment_end_date' => '2025-07-31',
                'status' => 'completed', // Currently running
            ],
            [
                'period_id' => 3,
                'name' => '2025 Genap',
                'code' => '2025-2',
                'start_date' => '2026-02-01',
                'end_date' => '2026-06-30',
                'enrollment_start_date' => '2026-01-01',
                'enrollment_end_date' => '2026-01-31',
                'status' => 'active', // Future semester
            ],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }

        $this->command->info('✓ Semesters seeded (6 semesters)');
    }

    /**
     * Seed Years (Student Levels: Tahun 1, 2, 3, 4)
     */
    private function seedYears(): void
    {
        $years = [
            ['name' => 'Angkatan 1'],
            ['name' => 'Angkatan 2'],
            ['name' => 'Angkatan 3'],
            ['name' => 'Angkatan 4'],
        ];

        foreach ($years as $year) {
            Year::create($year);
        }

        $this->command->info('✓ Years seeded (4 levels)');
    }

    /**
     * Seed Student Classes
     */
    private function seedStudentClasses(): void
    {
        $classes = [
            // Tahun 1
            ['year_id' => 1, 'name' => 'S2 PKUP A'],
            ['year_id' => 1, 'name' => 'S2 PKUP B'],
            ['year_id' => 1, 'name' => 'S2 PKU A'],
            ['year_id' => 1, 'name' => 'S3 PKU A'],

            // Tahun 2
            ['year_id' => 2, 'name' => 'S2 PKUP A'],
            ['year_id' => 2, 'name' => 'S2 PKUP B'],
            ['year_id' => 2, 'name' => 'S2 PKU A'],
            ['year_id' => 2, 'name' => 'S3 PKU A'],

            // Tahun 3
            ['year_id' => 3, 'name' => 'S2 PKUP A'],
            ['year_id' => 3, 'name' => 'S2 PKU A'],
            ['year_id' => 3, 'name' => 'S3 PKU A'],

            // Tahun 4
            ['year_id' => 4, 'name' => 'S2 PKUP A'],
            ['year_id' => 4, 'name' => 'S2 PKU A'],
        ];

        foreach ($classes as $class) {
            StudentClass::create($class);
        }

        $this->command->info('✓ Student classes seeded (13 classes)');
    }

    /**
     * Seed Courses
     */
    private function seedCourses(): void
    {
        $courses = [
            // Tahun 1 Courses
            ['name' => 'Pemrograman Dasar', 'code' => 'CS101', 'student_class_id' => 1, 'sks' => 3],
            ['name' => 'Matematika Diskrit', 'code' => 'MTK101', 'student_class_id' => 1, 'sks' => 3],
            ['name' => 'Bahasa Inggris Teknis', 'code' => 'ENG101', 'student_class_id' => 1, 'sks' => 2],
            ['name' => 'Algoritma & Pemrograman', 'code' => 'CS102', 'student_class_id' => 1, 'sks' => 4],
            
            ['name' => 'Pemrograman Dasar', 'code' => 'CS101B', 'student_class_id' => 2, 'sks' => 3],
            ['name' => 'Matematika Diskrit', 'code' => 'MTK101B', 'student_class_id' => 2, 'sks' => 3],
            
            ['name' => 'Pengantar Teknologi Informasi', 'code' => 'TI101', 'student_class_id' => 3, 'sks' => 3],
            ['name' => 'Logika Informatika', 'code' => 'CS103', 'student_class_id' => 3, 'sks' => 2],
            
            ['name' => 'Sistem Digital', 'code' => 'CS104', 'student_class_id' => 4, 'sks' => 3],
            ['name' => 'Arsitektur Komputer', 'code' => 'CS105', 'student_class_id' => 4, 'sks' => 3],

            // Tahun 2 Courses
            ['name' => 'Struktur Data', 'code' => 'CS201', 'student_class_id' => 5, 'sks' => 4],
            ['name' => 'Basis Data', 'code' => 'CS202', 'student_class_id' => 5, 'sks' => 3],
            ['name' => 'Pemrograman Web', 'code' => 'CS203', 'student_class_id' => 5, 'sks' => 3],
            ['name' => 'Jaringan Komputer', 'code' => 'CS204', 'student_class_id' => 5, 'sks' => 3],
            
            ['name' => 'Struktur Data', 'code' => 'CS201B', 'student_class_id' => 6, 'sks' => 4],
            ['name' => 'Basis Data', 'code' => 'CS202B', 'student_class_id' => 6, 'sks' => 3],
            
            ['name' => 'Pemrograman Berorientasi Objek', 'code' => 'CS205', 'student_class_id' => 7, 'sks' => 4],
            ['name' => 'Sistem Operasi', 'code' => 'CS206', 'student_class_id' => 7, 'sks' => 3],

            // Tahun 3 Courses
            ['name' => 'Kecerdasan Buatan', 'code' => 'CS301', 'student_class_id' => 9, 'sks' => 3],
            ['name' => 'Machine Learning', 'code' => 'CS302', 'student_class_id' => 9, 'sks' => 3],
            ['name' => 'Rekayasa Perangkat Lunak', 'code' => 'CS303', 'student_class_id' => 9, 'sks' => 4],
            
            ['name' => 'Data Mining', 'code' => 'CS304', 'student_class_id' => 10, 'sks' => 3],
            ['name' => 'Keamanan Informasi', 'code' => 'CS305', 'student_class_id' => 10, 'sks' => 3],

            // Tahun 4 Courses
            ['name' => 'Metodologi Penelitian', 'code' => 'CS401', 'student_class_id' => 12, 'sks' => 2],
            ['name' => 'Etika Profesi', 'code' => 'CS402', 'student_class_id' => 12, 'sks' => 2],
            ['name' => 'Skripsi', 'code' => 'CS499', 'student_class_id' => 12, 'sks' => 6],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        $this->command->info('✓ Courses seeded (26 courses)');
    }

    /**
     * Seed Students
     */
    private function seedStudents(): void
    {
        $students = [
            // Tahun 1 - Class 1 (S2 PKUP A)
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025010101', 'name' => 'Ahmad Fauzi', 'username' => 'ahmad.fauzi', 'email' => 'ahmad.fauzi@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567801', 'address' => 'Jakarta', 'status' => 'active'],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025010102', 'name' => 'Siti Nurhaliza', 'username' => 'siti.nurhaliza', 'email' => 'siti.nurhaliza@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567802', 'address' => 'Bandung', 'status' => 'active'],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025010103', 'name' => 'Muhammad Rizki', 'username' => 'muhammad.rizki', 'email' => 'muhammad.rizki@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567803', 'address' => 'Surabaya', 'status' => 'active'],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025010104', 'name' => 'Fatimah Azzahra', 'username' => 'fatimah.azzahra', 'email' => 'fatimah.azzahra@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567804', 'address' => 'Yogyakarta', 'status' => 'active'],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025010105', 'name' => 'Abdullah Rahman', 'username' => 'abdullah.rahman', 'email' => 'abdullah.rahman@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567805', 'address' => 'Semarang', 'status' => 'active'],
            
            // Tahun 1 - Class 2 (S2 PKUP B)
            ['year_id' => 1, 'student_class_id' => 2, 'nim' => '2025010201', 'name' => 'Dewi Anggraini', 'username' => 'dewi.anggraini', 'email' => 'dewi.anggraini@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567806', 'address' => 'Malang', 'status' => 'active'],
            ['year_id' => 1, 'student_class_id' => 2, 'nim' => '2025010202', 'name' => 'Rudi Hartono', 'username' => 'rudi.hartono', 'email' => 'rudi.hartono@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567807', 'address' => 'Medan', 'status' => 'active'],
            ['year_id' => 1, 'student_class_id' => 2, 'nim' => '2025010203', 'name' => 'Aisyah Putri', 'username' => 'aisyah.putri', 'email' => 'aisyah.putri@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567808', 'address' => 'Palembang', 'status' => 'active'],
            
            // Tahun 1 - Class 3 (S2 PKU A)
            ['year_id' => 1, 'student_class_id' => 3, 'nim' => '2025010301', 'name' => 'Budi Setiawan', 'username' => 'budi.setiawan', 'email' => 'budi.setiawan@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567809', 'address' => 'Jakarta', 'status' => 'active'],
            ['year_id' => 1, 'student_class_id' => 3, 'nim' => '2025010302', 'name' => 'Nina Kartika', 'username' => 'nina.kartika', 'email' => 'nina.kartika@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567810', 'address' => 'Bogor', 'status' => 'active'],

            // Tahun 2 - Class 5 (S2 PKUP A)
            ['year_id' => 2, 'student_class_id' => 5, 'nim' => '2024020101', 'name' => 'Arief Budiman', 'username' => 'arief.budiman', 'email' => 'arief.budiman@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567811', 'address' => 'Jakarta', 'status' => 'active'],
            ['year_id' => 2, 'student_class_id' => 5, 'nim' => '2024020102', 'name' => 'Linda Wijaya', 'username' => 'linda.wijaya', 'email' => 'linda.wijaya@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567812', 'address' => 'Bandung', 'status' => 'active'],
            ['year_id' => 2, 'student_class_id' => 5, 'nim' => '2024020103', 'name' => 'Hendra Gunawan', 'username' => 'hendra.gunawan', 'email' => 'hendra.gunawan@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567813', 'address' => 'Surabaya', 'status' => 'active'],
            ['year_id' => 2, 'student_class_id' => 5, 'nim' => '2024020104', 'name' => 'Maya Sari', 'username' => 'maya.sari', 'email' => 'maya.sari@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567814', 'address' => 'Semarang', 'status' => 'active'],

            // Tahun 2 - Class 6 (S2 PKUP B)
            ['year_id' => 2, 'student_class_id' => 6, 'nim' => '2024020201', 'name' => 'Eko Prasetyo', 'username' => 'eko.prasetyo', 'email' => 'eko.prasetyo@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567815', 'address' => 'Yogyakarta', 'status' => 'active'],
            ['year_id' => 2, 'student_class_id' => 6, 'nim' => '2024020202', 'name' => 'Rini Susanti', 'username' => 'rini.susanti', 'email' => 'rini.susanti@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567816', 'address' => 'Solo', 'status' => 'active'],

            // Tahun 3 - Class 9 (S2 PKUP A)
            ['year_id' => 3, 'student_class_id' => 9, 'nim' => '2023030101', 'name' => 'Doni Kurniawan', 'username' => 'doni.kurniawan', 'email' => 'doni.kurniawan@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567817', 'address' => 'Jakarta', 'status' => 'active'],
            ['year_id' => 3, 'student_class_id' => 9, 'nim' => '2023030102', 'name' => 'Sri Mulyani', 'username' => 'sri.mulyani', 'email' => 'sri.mulyani@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567818', 'address' => 'Bandung', 'status' => 'active'],
            ['year_id' => 3, 'student_class_id' => 9, 'nim' => '2023030103', 'name' => 'Agus Salim', 'username' => 'agus.salim', 'email' => 'agus.salim@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567819', 'address' => 'Surabaya', 'status' => 'active'],

            // Tahun 4 - Class 12 (S2 PKUP A)
            ['year_id' => 4, 'student_class_id' => 12, 'nim' => '2022040101', 'name' => 'Irfan Hakim', 'username' => 'irfan.hakim', 'email' => 'irfan.hakim@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567820', 'address' => 'Jakarta', 'status' => 'active'],
            ['year_id' => 4, 'student_class_id' => 12, 'nim' => '2022040102', 'name' => 'Nur Azizah', 'username' => 'nur.azizah', 'email' => 'nur.azizah@student.ac.id', 'password' => Hash::make('student123'), 'phone' => '081234567821', 'address' => 'Tangerang', 'status' => 'active'],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        $this->command->info('✓ Students seeded (21 students)');
    }

    /**
     * Seed Enrollments (Current Active Semester)
     */
    // private function seedEnrollments(): void
    // {
    //     $activeSemesterId = 5; // Semester Ganjil 2025/2026 (currently active)
        
    //     $enrollments = [
    //         // Tahun 1 students enrolled in their courses (semester 5)
    //         ['student_id' => 1, 'course_id' => 1, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-15', 'status' => 'enrolled'],
    //         ['student_id' => 1, 'course_id' => 2, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-15', 'status' => 'enrolled'],
    //         ['student_id' => 1, 'course_id' => 3, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-15', 'status' => 'enrolled'],
    //         ['student_id' => 1, 'course_id' => 4, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-15', 'status' => 'enrolled'],
            
    //         ['student_id' => 2, 'course_id' => 1, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-16', 'status' => 'enrolled'],
    //         ['student_id' => 2, 'course_id' => 2, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-16', 'status' => 'enrolled'],
    //         ['student_id' => 2, 'course_id' => 3, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-16', 'status' => 'enrolled'],
            
    //         ['student_id' => 3, 'course_id' => 1, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-17', 'status' => 'enrolled'],
    //         ['student_id' => 3, 'course_id' => 2, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-17', 'status' => 'enrolled'],
    //         ['student_id' => 3, 'course_id' => 4, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-17', 'status' => 'enrolled'],
            
    //         ['student_id' => 4, 'course_id' => 1, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-18', 'status' => 'enrolled'],
    //         ['student_id' => 4, 'course_id' => 2, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-18', 'status' => 'enrolled'],
            
    //         ['student_id' => 5, 'course_id' => 1, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-19', 'status' => 'enrolled'],
    //         ['student_id' => 5, 'course_id' => 3, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-19', 'status' => 'enrolled'],
    //         ['student_id' => 5, 'course_id' => 4, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-19', 'status' => 'enrolled'],
            
    //         // Class 2 students
    //         ['student_id' => 6, 'course_id' => 5, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-20', 'status' => 'enrolled'],
    //         ['student_id' => 6, 'course_id' => 6, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-20', 'status' => 'enrolled'],
            
    //         ['student_id' => 7, 'course_id' => 5, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-21', 'status' => 'enrolled'],
    //         ['student_id' => 7, 'course_id' => 6, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-21', 'status' => 'enrolled'],
            
    //         ['student_id' => 8, 'course_id' => 5, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-22', 'status' => 'enrolled'],
    //         ['student_id' => 8, 'course_id' => 6, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-22', 'status' => 'enrolled'],
            
    //         // Class 3 students
    //         ['student_id' => 9, 'course_id' => 7, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-23', 'status' => 'enrolled'],
    //         ['student_id' => 9, 'course_id' => 8, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-23', 'status' => 'enrolled'],
            
    //         ['student_id' => 10, 'course_id' => 7, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-24', 'status' => 'enrolled'],
    //         ['student_id' => 10, 'course_id' => 8, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-24', 'status' => 'enrolled'],
            
    //         // Tahun 2 students
    //         ['student_id' => 11, 'course_id' => 11, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-10', 'status' => 'enrolled'],
    //         ['student_id' => 11, 'course_id' => 12, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-10', 'status' => 'enrolled'],
    //         ['student_id' => 11, 'course_id' => 13, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-10', 'status' => 'enrolled'],
            
    //         ['student_id' => 12, 'course_id' => 11, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-11', 'status' => 'enrolled'],
    //         ['student_id' => 12, 'course_id' => 12, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-11', 'status' => 'enrolled'],
    //         ['student_id' => 12, 'course_id' => 14, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-11', 'status' => 'enrolled'],
            
    //         ['student_id' => 13, 'course_id' => 11, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-12', 'status' => 'enrolled'],
    //         ['student_id' => 13, 'course_id' => 13, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-12', 'status' => 'enrolled'],
            
    //         ['student_id' => 14, 'course_id' => 12, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-13', 'status' => 'enrolled'],
    //         ['student_id' => 14, 'course_id' => 14, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-13', 'status' => 'enrolled'],
            
    //         // Tahun 3 students
    //         ['student_id' => 17, 'course_id' => 19, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-05', 'status' => 'enrolled'],
    //         ['student_id' => 17, 'course_id' => 20, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-05', 'status' => 'enrolled'],
            
    //         ['student_id' => 18, 'course_id' => 19, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-06', 'status' => 'enrolled'],
    //         ['student_id' => 18, 'course_id' => 21, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-06', 'status' => 'enrolled'],
            
    //         // Tahun 4 students
    //         ['student_id' => 20, 'course_id' => 24, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-01', 'status' => 'enrolled'],
    //         ['student_id' => 20, 'course_id' => 25, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-01', 'status' => 'enrolled'],
    //         ['student_id' => 20, 'course_id' => 26, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-01', 'status' => 'enrolled'],
            
    //         ['student_id' => 21, 'course_id' => 24, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-02', 'status' => 'enrolled'],
    //         ['student_id' => 21, 'course_id' => 26, 'semester_id' => $activeSemesterId, 'enrollment_date' => '2025-07-02', 'status' => 'enrolled'],
    //     ];

    //     foreach ($enrollments as $enrollment) {
    //         Enrollment::create($enrollment);
    //     }

    //     $this->command->info('✓ Enrollments seeded (48 enrollments)');
    // }

    /**
     * Seed Grades (Mix of complete, partial, and empty grades)
     */
    private function seedGrades(): void
    {
        $weights = GradeWeight::first();
        $activeSemesterId = 5;

        $gradesData = [
            // Student 1 - Has all scores
            [
                'student_id' => 1, 
                'course_id' => 1, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 95.00,
                'assignment_score' => 88.00,
                'midterm_score' => 85.00,
                'final_score' => 90.00,
            ],
            [
                'student_id' => 1, 
                'course_id' => 2, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 90.00,
                'assignment_score' => 85.00,
                'midterm_score' => 80.00,
                'final_score' => 87.00,
            ],
            [
                'student_id' => 1, 
                'course_id' => 3, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 100.00,
                'assignment_score' => 92.00,
                'midterm_score' => 88.00,
                'final_score' => null, // Final exam not yet taken
            ],
            [
                'student_id' => 1, 
                'course_id' => 4, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 85.00,
                'assignment_score' => 78.00,
                'midterm_score' => null,
                'final_score' => null,
            ],
            
            // Student 2 - Mix of scores
            [
                'student_id' => 2, 
                'course_id' => 1, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 88.00,
                'assignment_score' => 90.00,
                'midterm_score' => 82.00,
                'final_score' => 85.00,
            ],
            [
                'student_id' => 2, 
                'course_id' => 2, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 92.00,
                'assignment_score' => 87.00,
                'midterm_score' => 89.00,
                'final_score' => null,
            ],
            [
                'student_id' => 2, 
                'course_id' => 3, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 95.00,
                'assignment_score' => 88.00,
                'midterm_score' => null,
                'final_score' => null,
            ],
            
            // Student 3 - Some scores
            [
                'student_id' => 3, 
                'course_id' => 1, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 78.00,
                'assignment_score' => 82.00,
                'midterm_score' => 75.00,
                'final_score' => 80.00,
            ],
            [
                'student_id' => 3, 
                'course_id' => 2, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 85.00,
                'assignment_score' => 80.00,
                'midterm_score' => 78.00,
                'final_score' => null,
            ],
            
            // Student 4 - Early stage (only attendance)
            [
                'student_id' => 4, 
                'course_id' => 1, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 90.00,
                'assignment_score' => null,
                'midterm_score' => null,
                'final_score' => null,
            ],
            [
                'student_id' => 4, 
                'course_id' => 2, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 88.00,
                'assignment_score' => null,
                'midterm_score' => null,
                'final_score' => null,
            ],
            
            // Student 5
            [
                'student_id' => 5, 
                'course_id' => 1, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 92.00,
                'assignment_score' => 95.00,
                'midterm_score' => 90.00,
                'final_score' => 93.00,
            ],
            
            // Student 11 (Year 2) - Complete grades
            [
                'student_id' => 11, 
                'course_id' => 11, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 95.00,
                'assignment_score' => 90.00,
                'midterm_score' => 92.00,
                'final_score' => 94.00,
            ],
            [
                'student_id' => 11, 
                'course_id' => 12, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 88.00,
                'assignment_score' => 85.00,
                'midterm_score' => 87.00,
                'final_score' => 89.00,
            ],
            
            // Student 12 (Year 2)
            [
                'student_id' => 12, 
                'course_id' => 11, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 82.00,
                'assignment_score' => 85.00,
                'midterm_score' => 80.00,
                'final_score' => 83.00,
            ],
            
            // Student 17 (Year 3)
            [
                'student_id' => 17, 
                'course_id' => 19, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 90.00,
                'assignment_score' => 88.00,
                'midterm_score' => 85.00,
                'final_score' => 87.00,
            ],
            
            // Student 20 (Year 4) - Thesis student
            [
                'student_id' => 20, 
                'course_id' => 26, 
                'semester_id' => $activeSemesterId,
                'attendance_score' => 100.00,
                'assignment_score' => 90.00,
                'midterm_score' => 85.00,
                'final_score' => null, // Thesis not yet defended
            ],
        ];

        foreach ($gradesData as $data) {
            // Calculate final grade only if all scores are present
            if ($data['attendance_score'] && $data['assignment_score'] && 
                $data['midterm_score'] && $data['final_score']) {
                
                $finalGrade = Grade::calculateFinalGrade(
                    $data['attendance_score'],
                    $data['assignment_score'],
                    $data['midterm_score'],
                    $data['final_score'],
                    $weights
                );
                
                $data['final_grade'] = $finalGrade;
                $data['letter_grade'] = Grade::getLetterGrade($finalGrade);
                $data['bobot'] = Grade::getBobot($finalGrade);
            } else {
                $data['final_grade'] = null;
                $data['letter_grade'] = null;
                $data['bobot'] = null;
            }

            Grade::create($data);
        }

        $this->command->info('✓ Grades seeded (18 grade records)');
    }

    /**
     * Seed Registrations (Prospective Students)
     */
    private function seedRegistrations(): void
    {
        $registrations = [
            [
                'username' => 'anisa.rahmawati',
                'email' => 'anisa.rahmawati@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'pending',
            ],
            [
                'username' => 'farhan.maulana',
                'email' => 'farhan.maulana@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'pending',
            ],
            [
                'username' => 'dimas.prakoso',
                'email' => 'dimas.prakoso@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'lolos',
            ],
            [
                'username' => 'putri.amanda',
                'email' => 'putri.amanda@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'lolos',
            ],
            [
                'username' => 'rizal.hidayat',
                'email' => 'rizal.hidayat@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'gagal',
            ],
            [
                'username' => 'sari.wulandari',
                'email' => 'sari.wulandari@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'gagal',
            ],
            [
                'username' => 'bayu.santoso',
                'email' => 'bayu.santoso@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'pending',
            ],
            [
                'username' => 'mega.susanti',
                'email' => 'mega.susanti@gmail.com',
                'password' => Hash::make('register123'),
                'status' => 'lolos',
            ],
        ];

        foreach ($registrations as $registration) {
            Registration::create($registration);
        }

        $this->command->info('✓ Registrations seeded (8 registrations)');
    }

    /**
     * Seed Logs (Audit Trail)
     */
    private function seedLogs(): void
    {
        $logs = [
            [
                'user' => 'admin@university.ac.id',
                'action' => 'CREATE',
                'module' => 'Student',
                'old_data' => null,
                'new_data' => json_encode([
                    'nim' => '2025010101',
                    'name' => 'Ahmad Fauzi',
                    'student_class_id' => 1,
                ]),
                'ip_address' => '192.168.1.10',
                'created_at' => now()->subDays(10),
            ],
            [
                'user' => 'budi.santoso@university.ac.id',
                'action' => 'UPDATE',
                'module' => 'Grade',
                'old_data' => json_encode([
                    'midterm_score' => null,
                ]),
                'new_data' => json_encode([
                    'midterm_score' => 85.00,
                ]),
                'ip_address' => '192.168.1.15',
                'created_at' => now()->subDays(5),
            ],
            [
                'user' => 'budi.santoso@university.ac.id',
                'action' => 'UPDATE',
                'module' => 'Grade',
                'old_data' => json_encode([
                    'final_score' => null,
                    'final_grade' => null,
                ]),
                'new_data' => json_encode([
                    'final_score' => 90.00,
                    'final_grade' => 88.50,
                    'letter_grade' => 'A',
                ]),
                'ip_address' => '192.168.1.15',
                'created_at' => now()->subDays(2),
            ],
            [
                'user' => 'admin@university.ac.id',
                'action' => 'UPDATE',
                'module' => 'Semester',
                'old_data' => json_encode([
                    'status' => 'draft',
                ]),
                'new_data' => json_encode([
                    'status' => 'active',
                ]),
                'ip_address' => '192.168.1.10',
                'created_at' => now()->subDays(60),
            ],
            [
                'user' => 'admin@university.ac.id',
                'action' => 'CREATE',
                'module' => 'Enrollment',
                'old_data' => null,
                'new_data' => json_encode([
                    'student_id' => 1,
                    'course_id' => 1,
                    'semester_id' => 5,
                    'status' => 'enrolled',
                ]),
                'ip_address' => '192.168.1.10',
                'created_at' => now()->subDays(80),
            ],
            [
                'user' => 'siti.aminah@university.ac.id',
                'action' => 'UPDATE',
                'module' => 'Grade',
                'old_data' => json_encode([
                    'assignment_score' => 85.00,
                ]),
                'new_data' => json_encode([
                    'assignment_score' => 88.00,
                ]),
                'ip_address' => '192.168.1.20',
                'created_at' => now()->subDays(3),
            ],
            [
                'user' => 'admin@university.ac.id',
                'action' => 'DELETE',
                'module' => 'Student',
                'old_data' => json_encode([
                    'nim' => '2024999999',
                    'name' => 'Test Student',
                    'status' => 'inactive',
                ]),
                'new_data' => null,
                'ip_address' => '192.168.1.10',
                'created_at' => now()->subDays(15),
            ],
            [
                'user' => 'admin@university.ac.id',
                'action' => 'UPDATE',
                'module' => 'Registration',
                'old_data' => json_encode([
                    'status' => 'pending',
                ]),
                'new_data' => json_encode([
                    'status' => 'lolos',
                ]),
                'ip_address' => '192.168.1.10',
                'created_at' => now()->subDays(20),
            ],
        ];

        foreach ($logs as $log) {
            Log::create($log);
        }

        $this->command->info('✓ Logs seeded (8 audit logs)');
    }
}