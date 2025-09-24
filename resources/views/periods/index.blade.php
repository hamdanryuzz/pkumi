@extends('layouts.app')

@section('title', 'Kelola Tahun Ajaran - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Tahun Ajaran</h2>
        <p class="text-base text-gray-500 mt-1">Kelola tahun ajaran dan masa pendaftaran mahasiswa</p>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Alert -->
    @if(session('error') || $errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            @if(session('error'))
                <span class="block sm:inline">{{ session('error') }}</span>
            @endif
            @if($errors->any())
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <!-- Action Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex flex-wrap justify-between items-center">
                <div>
                    <h5 class="text-lg font-bold text-gray-800">Daftar Tahun Ajaran</h5>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Kelola semua tahun ajaran dan pengaturannya
                    </p>
                </div>
                <div class="flex gap-3 mt-2 sm:mt-0">
                    <a href="{{ route('periods.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah Tahun Ajaran
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Periods Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-tag mr-1"></i>Tahun Ajaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-1"></i>Tanggal Periode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-user-plus mr-1"></i>Masa Pendaftaran
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-1"></i>Status
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-users mr-1"></i>Enrollment
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($periods as $period)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $period->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $period->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    <span class="text-green-600">
                                        <i class="fas fa-play mr-1"></i>
                                        {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                    </span>
                                    <span class="text-red-600">
                                        <i class="fas fa-stop mr-1"></i>
                                        {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    <span class="text-blue-600">
                                        <i class="fas fa-door-open mr-1"></i>
                                        {{ \Carbon\Carbon::parse($period->enrollment_start_date)->format('d M Y') }}
                                    </span>
                                    <span class="text-orange-600">
                                        <i class="fas fa-door-closed mr-1"></i>
                                        {{ \Carbon\Carbon::parse($period->enrollment_end_date)->format('d M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusConfig = [
                                        'active' => ['bg-green-100 text-green-800', 'Aktif', 'fas fa-check-circle'],
                                        'draft' => ['bg-yellow-100 text-yellow-800', 'Draft', 'fas fa-edit'],
                                        'completed' => ['bg-gray-100 text-gray-800', 'Selesai', 'fas fa-flag-checkered']
                                    ];
                                    $config = $statusConfig[$period->status] ?? $statusConfig['draft'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                                    <i class="{{ $config[2] }} mr-1"></i>
                                    {{ $config[1] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                @php
                                    $enrollmentCount = $period->enrollments()->count();
                                    $activeCount = $period->enrollments()->where('status', 'enrolled')->count();
                                @endphp
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $activeCount }}</span>
                                    <span class="text-xs text-gray-500">dari {{ $enrollmentCount }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('periods.show', $period) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('periods.edit', $period) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 transition duration-200" 
                                       title="Edit Period">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($period->status !== 'active')
                                        <form action="{{ route('periods.activate', $period) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900 transition duration-200" 
                                                    title="Aktifkan Period"
                                                    onclick="return confirm('Yakin ingin mengaktifkan period ini?')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($period->enrollments()->count() === 0)
                                        <form action="{{ route('periods.destroy', $period) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition duration-200" 
                                                    title="Hapus Period"
                                                    onclick="return confirm('Yakin ingin menghapus period ini?')">
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
                                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                <p class="text-lg">Belum ada Tahun Ajaran yang dibuat</p>
                                <p class="text-sm mt-2">
                                    <a href="{{ route('periods.create') }}" class="text-blue-600 hover:text-blue-800">
                                        Klik di sini untuk membuat tahun ajaran pertama
                                    </a>
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($periods->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $periods->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
