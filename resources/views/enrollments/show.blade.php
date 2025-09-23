@extends('layouts.app')

@section('title', 'Detail Enrollment - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('enrollments.index') }}" 
               class="text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Detail Enrollment</h2>
                        <p class="text-base text-gray-500 mt-1">
                            Informasi lengkap enrollment {{ $enrollment->student->name }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('enrollments.edit', $enrollment) }}" 
                           class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        @if($enrollment->status === 'enrolled')
                            <form action="{{ route('enrollments.drop', $enrollment) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200"
                                        onclick="return confirm('Yakin ingin drop enrollment ini?')">
                                    <i class="fas fa-user-times mr-2"></i>Drop
                                </button>
                            </form>
                        @elseif($enrollment->status === 'dropped')
                            <form action="{{ route('enrollments.reactivate', $enrollment) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                                    <i class="fas fa-user-check mr-2"></i>Reactivate
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Enrollment Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h5 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Enrollment
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Mahasiswa</label>
                                <div class="mt-1 flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-bold">
                                            {{ strtoupper(substr($enrollment->student->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $enrollment->student->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $enrollment->student->nim }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status Mahasiswa</label>
                                <div class="mt-1">
                                    @if($enrollment->student->status === 'active')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Course and Period Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-book mr-1"></i>Mata Kuliah
                                </label>
                                <div class="mt-1">
                                    <p class="text-gray-900 font-medium">{{ $enrollment->course->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $enrollment->course->code }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>Period
                                </label>
                                <div class="mt-1">
                                    <p class="text-gray-900 font-medium">{{ $enrollment->period->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $enrollment->period->code }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Enrollment Details -->
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Pendaftaran</label>
                                <p class="mt-1 text-gray-900 font-medium">
                                    <i class="fas fa-calendar-plus mr-1 text-blue-600"></i>
                                    {{ $enrollment->enrollment_date->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status Enrollment</label>
                                @php
                                    $statusConfig = [
                                        'enrolled' => ['bg-green-100 text-green-800', 'Terdaftar', 'fas fa-check-circle'],
                                        'dropped' => ['bg-red-100 text-red-800', 'Dropped', 'fas fa-times-circle'],
                                        'completed' => ['bg-blue-100 text-blue-800', 'Selesai', 'fas fa-flag-checkered']
                                    ];
                                    $config = $statusConfig[$enrollment->status] ?? $statusConfig['enrolled'];
                                @endphp
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config[0] }}">
                                        <i class="{{ $config[2] }} mr-2"></i>
                                        {{ $config[1] }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Dibuat</label>
                                <p class="mt-1 text-gray-900 text-sm">
                                    {{ $enrollment->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h5 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-bolt mr-2"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="p-6 space-y-3">
                    @if($enrollment->status === 'enrolled')
                        <a href="{{ route('grades.index', ['course_id' => $enrollment->course_id, 'period_id' => $enrollment->period_id]) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-graduation-cap mr-2"></i>Input Nilai
                        </a>
                    @endif
                    
                    <a href="{{ route('enrollments.index', ['course_id' => $enrollment->course_id, 'period_id' => $enrollment->period_id]) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200">
                        <i class="fas fa-list mr-2"></i>Lihat Enrollment Serupa
                    </a>
                    
                    <a href="{{ route('periods.show', $enrollment->period) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-calendar mr-2"></i>Detail Period
                    </a>
                </div>
            </div>

            <!-- Period Information -->
            <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h5 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Info Period
                    </h5>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Periode:</span>
                            <span class="font-medium">
                                {{ $enrollment->period->start_date->format('d M') }} - 
                                {{ $enrollment->period->end_date->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Pendaftaran:</span>
                            <span class="font-medium">
                                {{ $enrollment->period->enrollment_start_date->format('d M') }} - 
                                {{ $enrollment->period->enrollment_end_date->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Status:</span>
                            @php
                                $periodStatusConfig = [
                                    'active' => ['text-green-600', 'Aktif'],
                                    'draft' => ['text-yellow-600', 'Draft'],
                                    'completed' => ['text-gray-600', 'Selesai']
                                ];
                                $periodConfig = $periodStatusConfig[$enrollment->period->status] ?? $periodStatusConfig['draft'];
                            @endphp
                            <span class="font-medium {{ $periodConfig[0] }}">{{ $periodConfig[1] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Information -->
    <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-amber-50">
            <h5 class="text-lg font-bold text-gray-800">
                <i class="fas fa-chart-line mr-2"></i>
                Informasi Nilai
            </h5>
        </div>
        <div class="p-6">
            @if($grade)
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $grade->attendance_score ?? '-' }}
                        </div>
                        <div class="text-sm text-gray-600">Kehadiran</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $grade->assignment_score ?? '-' }}
                        </div>
                        <div class="text-sm text-gray-600">Tugas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $grade->midterm_score ?? '-' }}
                        </div>
                        <div class="text-sm text-gray-600">UTS</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">
                            {{ $grade->final_score ?? '-' }}
                        </div>
                        <div class="text-sm text-gray-600">UAS</div>
                    </div>
                    <div class="text-center border-l border-gray-200">
                        <div class="text-3xl font-bold text-purple-600">
                            {{ $grade->letter_grade ?? '-' }}
                        </div>
                        <div class="text-sm text-gray-600">Nilai Akhir</div>
                        @if($grade->final_grade)
                            <div class="text-xs text-gray-500 mt-1">
                                ({{ number_format($grade->final_grade, 2) }})
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('grades.index', ['course_id' => $enrollment->course_id, 'period_id' => $enrollment->period_id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Nilai
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Nilai</h3>
                    <p class="text-gray-600 mb-4">Nilai untuk enrollment ini belum diinput.</p>
                    @if($enrollment->status === 'enrolled')
                        <a href="{{ route('grades.index', ['course_id' => $enrollment->course_id, 'period_id' => $enrollment->period_id]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Input Nilai
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
