@extends('layouts.app')

@section('title', 'Detail Semester - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('semester.index') }}" 
               class="text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">{{ $semester->name }}</h2>
                        <p class="text-base text-gray-500 mt-1">Detail informasi Semester dan statistik enrollment</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('semester.edit', $semester) }}" 
                           class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit Semester
                        </a>
                        @if($semester->status !== 'active')
                            <form action="{{ route('semester.activate', $semester) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200"
                                        onclick="return confirm('Yakin ingin mengaktifkan semester ini?')">
                                    <i class="fas fa-play mr-2"></i>Aktifkan
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
        <!-- Semester Information Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h5 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Semester
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Semester Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Semester</label>
                                <p class="text-gray-900 font-medium">{{ $semester->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Kode Semester</label>
                                <p class="text-gray-900 font-mono">{{ $semester->code }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                @php
                                    $statusConfig = [
                                        'active' => ['bg-green-100 text-green-800', 'Aktif', 'fas fa-check-circle'],
                                        'draft' => ['bg-yellow-100 text-yellow-800', 'Draft', 'fas fa-edit'],
                                        'completed' => ['bg-gray-100 text-gray-800', 'Selesai', 'fas fa-flag-checkered']
                                    ];
                                    $config = $statusConfig[$semester->status] ?? $statusConfig['draft'];
                                @endphp
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config[0] }}">
                                        <i class="{{ $config[2] }} mr-2"></i>
                                        {{ $config[1] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>semestere Akademik
                                </label>
                                <div class="mt-1 space-y-1">
                                    <p class="text-sm text-green-600">
                                        <i class="fas fa-play mr-1"></i>
                                        Mulai: {{ $semester->start_date->format('d M Y') }}
                                    </p>
                                    <p class="text-sm text-red-600">
                                        <i class="fas fa-stop mr-1"></i>
                                        Berakhir: {{ $semester->end_date->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">
                                    <i class="fas fa-user-plus mr-1"></i>Masa Pendaftaran
                                </label>
                                <div class="mt-1 space-y-1">
                                    <p class="text-sm text-blue-600">
                                        <i class="fas fa-door-open mr-1"></i>
                                        Mulai: {{ $semester->enrollment_start_date->format('d M Y') }}
                                    </p>
                                    <p class="text-sm text-orange-600">
                                        <i class="fas fa-door-closed mr-1"></i>
                                        Berakhir: {{ $semester->enrollment_end_date->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                @php
                                    $now = now();
                                    $enrollmentOpen = $now->between($semester->enrollment_start_date, $semester->enrollment_end_date);
                                @endphp
                                <label class="text-sm font-medium text-gray-500">Status Pendaftaran</label>
                                <div class="mt-1">
                                    @if($enrollmentOpen)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Terbuka
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Tertutup
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h5 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Statistik Enrollment
                    </h5>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Total Enrollments -->
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Total Enrollment</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $statistics['total_enrollments'] }}</span>
                        </div>

                        <!-- Active Enrollments -->
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-check text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Aktif</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ $statistics['active_enrollments'] }}</span>
                        </div>

                        <!-- Total Courses -->
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-book text-yellow-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Mata Kuliah</span>
                            </div>
                            <span class="text-lg font-bold text-yellow-600">{{ $statistics['total_courses'] }}</span>
                        </div>

                        <!-- Total Students -->
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-graduation-cap text-purple-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Mahasiswa</span>
                            </div>
                            <span class="text-lg font-bold text-purple-600">{{ $statistics['total_students'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollments Table -->
    @if($semester->enrollments->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
                <div class="flex justify-between items-center">
                    <h5 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Enrollment ({{ $semester->enrollments->count() }})
                    </h5>
                    <a href="{{ route('enrollments.index', ['semester_id' => $semester->id]) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($semester->enrollments->take(10) as $enrollment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <span class="text-blue-600 font-semibold text-sm">
                                                {{ strtoupper(substr($enrollment->student->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $enrollment->student->nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $enrollment->course->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $enrollment->course->code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $enrollment->enrollment_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusConfig = [
                                            'enrolled' => ['bg-green-100 text-green-800', 'Terdaftar'],
                                            'dropped' => ['bg-red-100 text-red-800', 'Dropped'],
                                            'completed' => ['bg-blue-100 text-blue-800', 'Selesai']
                                        ];
                                        $config = $statusConfig[$enrollment->status] ?? $statusConfig['enrolled'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                                        {{ $config[1] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($semester->enrollments->count() > 10)
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-center">
                    <a href="{{ route('enrollments.index', ['semester_id' => $semester->id]) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat {{ $semester->enrollments->count() - 10 }} enrollment lainnya
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="mt-6 bg-white rounded-lg shadow-lg p-8 text-center">
            <i class="fas fa-user-slash text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Enrollment</h3>
            <p class="text-gray-600">Semester ini belum memiliki data enrollment mahasiswa.</p>
            <a href="{{ route('enrollments.create', ['semester_id' => $semester->id]) }}" 
               class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                <i class="fas fa-plus mr-2"></i>Tambah Enrollment
            </a>
        </div>
    @endif
</main>
@endsection
