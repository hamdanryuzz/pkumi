@extends('layouts.app')

@section('title', 'Kelola Enrollment - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Enrollment</h2>
        <p class="text-base text-gray-500 mt-1">Kelola pendaftaran mahasiswa pada mata kuliah per periode akademik</p>
    </div>

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Peringatan!</strong>
            <span class="block sm:inline">{{ session('warning') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h5 class="text-lg font-bold text-gray-800">
                <i class="fas fa-filter mr-2"></i>Filter & Pencarian
            </h5>
            <p class="text-sm text-gray-600 mt-1">Filter enrollment berdasarkan periode, mata kuliah, atau status</p>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('enrollments.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <!-- Period Filter -->
                <div>
                    <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>Period
                    </label>
                    <select name="period_id" id="period_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Period</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Course Filter -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-1"></i>Mata Kuliah
                    </label>
                    <select name="course_id" id="course_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Mata Kuliah</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Status
                    </label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="enrolled" {{ request('status') === 'enrolled' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="dropped" {{ request('status') === 'dropped' ? 'selected' : '' }}>Dropped</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>Cari Mahasiswa
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Nama atau NIM..." 
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request()->hasAny(['period_id', 'course_id', 'status', 'search']))
                            <a href="{{ route('enrollments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex flex-wrap justify-between items-center">
                <div>
                    <h5 class="text-lg font-bold text-gray-800">
                        Daftar Enrollment 
                        <span class="text-sm font-normal text-gray-500">({{ $enrollments->total() }})</span>
                    </h5>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-user-plus mr-1"></i>
                        Kelola semua pendaftaran mahasiswa
                    </p>
                </div>
                <div class="flex gap-3 mt-2 sm:mt-0">
                    <a href="{{ route('enrollments.export', request()->query()) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </a>
                    <a href="{{ route('enrollments.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah Enrollment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i>Mahasiswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-book mr-1"></i>Mata Kuliah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-1"></i>Period
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar-plus mr-1"></i>Tgl Daftar
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-1"></i>Status
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($enrollments as $enrollment)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $enrollment->period->name }}</div>
                                <div class="text-sm text-gray-500">{{ $enrollment->period->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                {{ $enrollment->enrollment_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusConfig = [
                                        'enrolled' => ['bg-green-100 text-green-800', 'Terdaftar', 'fas fa-check-circle'],
                                        'dropped' => ['bg-red-100 text-red-800', 'Dropped', 'fas fa-times-circle'],
                                        'completed' => ['bg-blue-100 text-blue-800', 'Selesai', 'fas fa-flag-checkered']
                                    ];
                                    $config = $statusConfig[$enrollment->status] ?? $statusConfig['enrolled'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                                    <i class="{{ $config[2] }} mr-1"></i>
                                    {{ $config[1] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('enrollments.show', $enrollment) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('enrollments.edit', $enrollment) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 transition duration-200" 
                                       title="Edit Enrollment">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($enrollment->status === 'enrolled')
                                        <form action="{{ route('enrollments.drop', $enrollment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition duration-200" 
                                                    title="Drop Enrollment"
                                                    onclick="return confirm('Yakin ingin drop enrollment ini?')">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        </form>
                                    @elseif($enrollment->status === 'dropped')
                                        <form action="{{ route('enrollments.reactivate', $enrollment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900 transition duration-200" 
                                                    title="Reactivate Enrollment">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(!$enrollment->student->grade()->where('course_id', $enrollment->course_id)->where('period_id', $enrollment->period_id)->exists())
                                        <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition duration-200" 
                                                    title="Hapus Enrollment"
                                                    onclick="return confirm('Yakin ingin menghapus enrollment ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-user-slash text-4xl mb-4"></i>
                                <p class="text-lg">Tidak ada enrollment yang ditemukan</p>
                                <p class="text-sm mt-2">
                                    @if(request()->hasAny(['period_id', 'course_id', 'status', 'search']))
                                        <a href="{{ route('enrollments.index') }}" class="text-blue-600 hover:text-blue-800">
                                            Reset filter untuk melihat semua enrollment
                                        </a>
                                    @else
                                        <a href="{{ route('enrollments.create') }}" class="text-blue-600 hover:text-blue-800">
                                            Klik di sini untuk membuat enrollment pertama
                                        </a>
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($enrollments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $enrollments->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
