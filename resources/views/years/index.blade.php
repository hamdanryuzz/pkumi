@extends('layouts.app')

@section('title', 'Manage Years - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Manage Angkatan</h2>
        <p class="text-base text-gray-500 mt-1">Kelola data angkatan</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Add New Year Button -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('years.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 inline-flex items-center shadow-md">
            <i class="fas fa-plus mr-2"></i>Tambah Angkatan
        </a>
    </div>

    <!-- Years Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="text-lg font-bold text-gray-800">Daftar Angkatan</h5>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>Data angkatan yang tersedia
                    </p>
                </div>
                <div class="text-sm text-gray-600">
                    Total: <span class="font-medium">{{ $years->count() }}</span> angkatan
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-1"></i>No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-1"></i>Nama Angkatan
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-clock mr-1"></i>Dibuat
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($years as $index => $year)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $year->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>{{ $year->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-2">
                                <!-- View Button -->
                                <a href="{{ route('years.show', $year->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition duration-150">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('years.edit', $year->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded-full hover:bg-yellow-200 transition duration-150">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('years.destroy', $year->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun {{ $year->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full hover:bg-red-200 transition duration-150">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p class="text-lg">Tidak ada data angkatan</p>
                            <p class="text-sm mt-2">
                                <a href="{{ route('years.create') }}" class="text-blue-600 hover:text-blue-800">Tambah angkatan pertama</a>
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
