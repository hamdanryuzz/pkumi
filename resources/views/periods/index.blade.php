@extends('layouts.app')

@section('title', 'Kelola Tahun Ajaran - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Tahun Ajaran</h2>
        <p class="text-base text-gray-500 mt-1">Manajemen periode akademik</p>
    </div>

    <!-- Search and Action Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search Form -->
            <form method="GET" action="{{ route('periods.index') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}" 
                        placeholder="Cari nama, kode, atau status..." 
                        class="w-full px-4 py-3 pl-10 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    
                    @if($search)
                    <a 
                        href="{{ route('periods.index') }}" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        title="Clear search"
                    >
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>

            <!-- Add Button -->
            <a 
                href="{{ route('periods.create') }}" 
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md"
            >
                <i class="fas fa-plus mr-2"></i>
                Tambah Tahun Ajaran
            </a>
        </div>

        <!-- Search Results Info -->
        @if($search)
        <div class="mt-4 flex items-center text-sm text-gray-600">
            <i class="fas fa-info-circle mr-2"></i>
            <span>Menampilkan hasil pencarian untuk "<strong>{{ $search }}</strong>" ({{ $periods->count() }} data ditemukan)</span>
        </div>
        @endif
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($periods->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Tahun Ajaran
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($periods as $period)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $period->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $period->code }}
                        </td>
                        <td class="px-6 py-4">
                            @if($period->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            @elseif($period->status === 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a 
                                    href="{{ route('periods.show', $period) }}" 
                                    class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                    title="Lihat Detail"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a 
                                    href="{{ route('periods.edit', $period) }}" 
                                    class="inline-flex items-center px-3 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form 
                                    action="{{ route('periods.destroy', $period) }}" 
                                    method="POST" 
                                    class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-graduation-cap text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">
                @if($search)
                    Tidak Ada Hasil Pencarian
                @else
                    Tidak Ada Tahun Ajaran
                @endif
            </h3>
            <p class="text-gray-500 mb-6">
                @if($search)
                    Tidak ditemukan tahun ajaran yang sesuai dengan pencarian "{{ $search }}"
                @else
                    Mulai dengan membuat periode akademik baru.
                @endif
            </p>
            @if($search)
            <a 
                href="{{ route('periods.index') }}" 
                class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors"
            >
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Semua Data
            </a>
            @else
            <a 
                href="{{ route('periods.create') }}" 
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
            >
                <i class="fas fa-plus mr-2"></i>
                Tambah Tahun Ajaran
            </a>
            @endif
        </div>
        @endif
    </div>
</main>
@endsection
