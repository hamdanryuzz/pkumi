@extends('layouts.app')

@section('title', 'Tambah Enrollment - Sistem Penilaian PKUMI')

@section('content')
    <main class="py-6 px-4 md:px-8">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('enrollments.index') }}" class="text-gray-600 hover:text-gray-800 transition duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah Enrollment Baru</h2>
                    <p class="text-base text-gray-500 mt-1">Daftarkan mahasiswa pada mata kuliah di periode tertentu</p>
                </div>
            </div>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">Terjadi kesalahan!</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h5 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Form Tambah Enrollment
                </h5>
                <p class="text-sm text-gray-600 mt-1">Lengkapi informasi pendaftaran mahasiswa</p>
            </div>

            <form action="{{ route('enrollments.store') }}" method="POST" class="p-6" id="enrollmentForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Period -->
                    <div>
                        <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1"></i>Period
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="period_id" id="period_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('period_id') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Period</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}"
                                    {{ old('period_id', $selectedPeriod) == $period->id ? 'selected' : '' }}
                                    data-enrollment-open="{{ $period->isEnrollmentOpen() ? 'true' : 'false' }}">
                                    {{ $period->name }}
                                    @if ($period->status === 'active')
                                        <span class="text-green-600">(Aktif)</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('period_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1" id="periodStatus"></p>
                    </div>

                    <!-- Course -->
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book mr-1"></i>Mata Kuliah
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="course_id" id="course_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('course_id') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Mata Kuliah</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ old('course_id', $selectedCourse) == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student -->
                    <div class="md:col-span-2">
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1"></i>Mahasiswa
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="student_id" id="student_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('student_id') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Mahasiswa</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} - {{ $student->nim }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Enrollment Date -->
                    <div>
                        <label for="enrollment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus mr-1"></i>Tanggal Pendaftaran
                        </label>
                        <input type="date" name="enrollment_date" id="enrollment_date"
                            value="{{ old('enrollment_date', date('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('enrollment_date') border-red-500 @enderror">
                        @error('enrollment_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan tanggal hari ini</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>Status
                        </label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="enrolled" {{ old('status', 'enrolled') === 'enrolled' ? 'selected' : '' }}>
                                Terdaftar</option>
                            <option value="dropped" {{ old('status') === 'dropped' ? 'selected' : '' }}>Dropped</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Card -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-2"></i>
                        <div class="text-sm text-blue-800">
                            <strong>Catatan Penting:</strong>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Pastikan period sedang dalam masa pendaftaran</li>
                                <li>Mahasiswa harus dalam status aktif untuk dapat didaftarkan</li>
                                <li>Satu mahasiswa tidak dapat didaftarkan dua kali pada mata kuliah yang sama di period
                                    yang sama</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Duplicate Check Warning -->
                <div id="duplicateWarning" class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200 hidden">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-2"></i>
                        <div class="text-sm text-yellow-800">
                            <strong>Peringatan:</strong>
                            <p id="duplicateMessage"></p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('enrollments.index') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Enrollment
                    </button>
                </div>
            </form>
        </div>

        <!-- Bulk Enrollment Section -->
        <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <h5 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-users mr-2"></i>
                    Bulk Enrollment
                </h5>
                <p class="text-sm text-gray-600 mt-1">Daftarkan multiple mahasiswa sekaligus</p>
            </div>
            <div class="p-6">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('enrollments.create') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Filter Angkatan -->
                        <div>
                            <label for="year_id" class="block text-sm font-medium text-gray-700 mb-2">Filter
                                Angkatan</label>
                            <select name="year_id" id="year_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Semua Angkatan</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}"
                                        {{ $selectedYear == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Kelas -->
                        <div>
                            <label for="student_class_id" class="block text-sm font-medium text-gray-700 mb-2">Filter
                                Kelas</label>
                            <select name="student_class_id" id="student_class_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Semua Kelas</option>
                                @foreach ($studentClasses as $class)
                                    <option value="{{ $class->id }}"
                                        {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <a href="{{ route('enrollments.create') }}"
                                class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 text-center">
                                <i class="fas fa-refresh mr-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Bulk Enrollment Form -->
                <form action="{{ route('enrollments.bulk') }}" method="POST" id="bulkForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="bulk_period_id" class="block text-sm font-medium text-gray-700 mb-2">Period <span
                                    class="text-red-500">*</span></label>
                            <select name="period_id" id="bulk_period_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                <option value="">Pilih Period</option>
                                @foreach ($periods as $period)
                                    <option value="{{ $period->id }}"
                                        {{ $selectedPeriod == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bulk_course_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah
                                <span class="text-red-500">*</span></label>
                            <select name="course_id" id="bulk_course_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $selectedCourse == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                                <i class="fas fa-user-plus mr-2"></i>Bulk Enroll
                            </button>
                        </div>
                    </div>

                    <!-- Info Filter Aktif -->
                    @if ($selectedYear || $selectedClass)
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                <span class="text-sm text-blue-800">
                                    Filter aktif:
                                    @if ($selectedYear)
                                        Angkatan: {{ $years->where('id', $selectedYear)->first()->name }}
                                    @endif
                                    @if ($selectedClass)
                                        {{ $selectedYear ? ', ' : '' }}Kelas:
                                        {{ $studentClasses->where('id', $selectedClass)->first()->name }}
                                    @endif
                                    ({{ $students->count() }} mahasiswa)
                                </span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Pilih Mahasiswa</label>
                            <div class="text-sm text-gray-500">
                                <span id="selectedCount">0</span> terpilih dari {{ $students->count() }} mahasiswa
                            </div>
                        </div>
                        <div class="border border-gray-300 rounded-md p-3 max-h-48 overflow-y-auto">
                            @if ($students->count() > 0)
                                @foreach ($students as $student)
                                    <label class="flex items-center mb-2 student-checkbox">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                            class="mr-2 student-checkbox-input">
                                        <span class="text-sm">
                                            {{ $student->name }} - {{ $student->nim }}
                                            <small class="text-gray-500 ml-2">
                                                ({{ $student->year->name ?? '-' }}/{{ $student->studentClass->name ?? '-' }})
                                            </small>
                                        </span>
                                    </label>
                                @endforeach
                            @else
                                <p class="text-center text-gray-500 py-4">Tidak ada mahasiswa yang sesuai dengan filter</p>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodSelect = document.getElementById('period_id');
            const periodStatus = document.getElementById('periodStatus');
            const courseSelect = document.getElementById('course_id');
            const studentSelect = document.getElementById('student_id');
            const duplicateWarning = document.getElementById('duplicateWarning');
            const duplicateMessage = document.getElementById('duplicateMessage');

            // Period change handler
            periodSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const isEnrollmentOpen = selectedOption.dataset.enrollmentOpen === 'true';

                if (this.value) {
                    if (isEnrollmentOpen) {
                        periodStatus.innerHTML =
                            '<i class="fas fa-check-circle text-green-600 mr-1"></i>Masa pendaftaran masih terbuka';
                        periodStatus.className = 'text-xs text-green-600 mt-1';
                    } else {
                        periodStatus.innerHTML =
                            '<i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>Masa pendaftaran sudah tutup';
                        periodStatus.className = 'text-xs text-yellow-600 mt-1';
                    }
                } else {
                    periodStatus.innerHTML = '';
                }

                checkDuplicateEnrollment();
            });

            // Course and Student change handlers
            courseSelect.addEventListener('change', checkDuplicateEnrollment);
            studentSelect.addEventListener('change', checkDuplicateEnrollment);

            function checkDuplicateEnrollment() {
                const periodId = periodSelect.value;
                const courseId = courseSelect.value;
                const studentId = studentSelect.value;

                if (periodId && courseId && studentId) {
                    // Here you would typically make an AJAX call to check for duplicates
                    // For now, we'll just show the warning structure
                    duplicateWarning.classList.add('hidden');
                } else {
                    duplicateWarning.classList.add('hidden');
                }
            }

            // Bulk enrollment - select all functionality
            const selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.className = 'text-sm text-blue-600 hover:text-blue-800 mb-2';
            selectAllBtn.innerHTML = '<i class="fas fa-check-square mr-1"></i>Pilih Semua';
            selectAllBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);

                checkboxes.forEach(cb => cb.checked = !allChecked);
                this.innerHTML = allChecked ? '<i class="fas fa-check-square mr-1"></i>Pilih Semua' :
                    '<i class="fas fa-square mr-1"></i>Batal Pilih Semua';
            });

            const bulkStudentContainer = document.querySelector('.border.border-gray-300.rounded-md.p-3');
            bulkStudentContainer.parentNode.insertBefore(selectAllBtn, bulkStudentContainer);
        });
    </script>
@endsection
