@extends('layouts.app')

@section('title', 'Kelola Enrollment - Sistem Penilaian PKUMI')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Enrollment</h1>
        <p class="text-gray-600">Kelola pendaftaran kelas pada mata kuliah per semester akademik</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="mb-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-sm" role="alert">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
            <div>
                <p class="font-bold">Peringatan!</p>
                <p>{{ session('warning') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle mr-3 text-xl mt-1"></i>
            <div>
                <p class="font-bold mb-2">Error!</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h5 class="text-lg font-semibold text-gray-800 mb-1">Filter & Pencarian</h5>
                <p class="text-sm text-gray-600">Filter enrollment berdasarkan semester, mata kuliah, atau status</p>
            </div>
            <a href="{{ route('enrollments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200 inline-flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Tambah Enrollment
            </a>
        </div>

        <form action="{{ route('enrollments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Semester Filter -->
            <div>
                <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester_id" id="semester_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                        @if($semester->status === 'active')
                        (Aktif)
                        @endif
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Course Filter -->
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                <select name="course_id" id="course_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} ({{ $course->code }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Semua Status</option>
                    <option value="enrolled" {{ request('status') === 'enrolled' ? 'selected' : '' }}>Terdaftar</option>
                    <option value="dropped" {{ request('status') === 'dropped' ? 'selected' : '' }}>Dropped</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Kelas atau Mata Kuliah</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama kelas atau mata kuliah..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('enrollments.index') }}" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition duration-200 inline-flex items-center">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </a>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('enrollments.export', request()->all()) }}" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-file-export mr-2"></i>
                    Export
                </a>
            </div>
        </form>
    </div>

    <!-- Enrollments Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-800 mb-1">Daftar Enrollment ({{ $enrollments->total() }})</h5>
            <p class="text-sm text-gray-600">Kelola semua pendaftaran kelas</p>
        </div>

        @if($enrollments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tgl Daftar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <!-- Student Class -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    {{ strtoupper(substr($enrollment->studentClass->name, 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $enrollment->studentClass->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $enrollment->studentClass->year->name ?? '-' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Course -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->course->name }}</div>
                            <div class="text-xs text-gray-500">{{ $enrollment->course->code }}</div>
                        </td>

                        <!-- Semester -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->semester->name }}</div>
                            <div class="text-xs text-gray-500">{{ $enrollment->semester->code }}</div>
                        </td>

                        <!-- Enrollment Date -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M Y') }}</div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusConfig = [
                                'enrolled' => ['bg-green-100 text-green-800', 'Terdaftar', 'fas fa-check-circle'],
                                'dropped' => ['bg-red-100 text-red-800', 'Dropped', 'fas fa-times-circle'],
                                'completed' => ['bg-blue-100 text-blue-800', 'Selesai', 'fas fa-flag-checkered']
                            ];
                            $config = $statusConfig[$enrollment->status] ?? $statusConfig['enrolled'];
                            @endphp
                            <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full {{ $config[0] }}">
                                <i class="{{ $config[2] }} mr-2"></i>
                                {{ $config[1] }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('enrollments.show', $enrollment->id) }}" class="text-blue-600 hover:text-blue-800 transition duration-150" title="Detail">
                                    <i class="fas fa-eye text-lg"></i>
                                </a>
                                <a href="{{ route('enrollments.edit', $enrollment->id) }}" class="text-yellow-600 hover:text-yellow-800 transition duration-150" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </a>
                                <form action="{{ route('enrollments.destroy', $enrollment->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus enrollment ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition duration-150" title="Hapus">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $enrollments->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada enrollment yang ditemukan</h3>
            <p class="text-gray-500 mb-6">
                @if(request()->hasAny(['semester_id', 'course_id', 'status', 'search']))
                Reset filter untuk melihat semua enrollment
                @else
                Klik tombol di bawah untuk membuat enrollment pertama
                @endif
            </p>
            @if(request()->hasAny(['semester_id', 'course_id', 'status', 'search']))
            <a href="{{ route('enrollments.index') }}" class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200">
                <i class="fas fa-redo mr-2"></i>
                Reset Filter
            </a>
            @else
            <a href="{{ route('enrollments.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200">
                <i class="fas fa-plus-circle mr-2"></i>
                Tambah Enrollment
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
// Initialize Select2 on page load
$(document).ready(function() {
    console.log('Initializing Select2...');
    
    // Initialize Select2 for Single Enrollment
    $('#semester_id').select2({
        placeholder: '-- Pilih Semester --',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
    
    $('#course_id').select2({
        placeholder: 'Pilih kelas terlebih dahulu...',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
    
    $('#status').select2({
        placeholder: '-- Pilih Status --',
        allowClear: false,
        width: '100%',
        minimumResultsForSearch: -1, // Hide search for status
        theme: 'default'
    });
    
    // Initialize Select2 for Bulk Enrollment
    $('#bulk_semester_id').select2({
        placeholder: '-- Pilih Semester --',
        allowClear: true,
        width: '100%',
        containerCssClass: 'select2-green',
        theme: 'default'
    });
    
    $('#bulk_student_class_id').select2({
        placeholder: '-- Pilih Kelas --',
        allowClear: true,
        width: '100%',
        containerCssClass: 'select2-green',
        theme: 'default'
    });
    
    // Trigger change event when Select2 changes
    $('#student_class_id').on('select2:select', function(e) {
        const studentClassId = $(this).val();
        handleStudentClassChange(studentClassId);
    });
    
    $('#student_class_id').on('select2:clear', function(e) {
        handleStudentClassChange('');
    });
    
    $('#bulk_student_class_id').on('select2:select', function(e) {
        const studentClassId = $(this).val();
        handleBulkClassChange(studentClassId);
    });
    
    $('#bulk_student_class_id').on('select2:clear', function(e) {
        handleBulkClassChange('');
    });
    
    console.log('Select2 initialized successfully');
});
</script>
@endsection
