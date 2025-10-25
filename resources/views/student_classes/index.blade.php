@extends('layouts.app')

@section('title', 'Manage Kelas')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Kelas</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola data kelas mahasiswa dan angkatan</p>
        </div>
        <a href="{{ route('student_classes.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Tambah Kelas
        </a>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
        <form method="GET" action="{{ route('student_classes.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>
                        Cari Kelas
                    </label>
                    <input type="text" 
                        name="search" 
                        id="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama kelas atau angkatan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Year Filter -->
                <div>
                    <label for="year_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>
                        Filter Angkatan
                    </label>
                    <select name="year_id" 
                            id="year_id"
                            class="select2-year w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Semua Angkatan</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ $yearFilter == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Class Name Filter (NEW) -->
                <div>
                    <label for="class_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                        Filter Nama Kelas
                    </label>
                    <select name="class_name" 
                            id="class_name"
                            class="select2-class w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Semua Kelas</option>
                        @foreach($classNames as $className)
                            <option value="{{ $className }}" {{ $classFilter == $className ? 'selected' : '' }}>
                                {{ $className }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('student_classes.index') }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </a>
            </div>
            
            <!-- Active Filters Display (Optional) -->
            @if($search || $yearFilter || $classFilter)
            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Filter Aktif:</p>
                <div class="flex flex-wrap gap-2">
                    @if($search)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-search mr-1"></i>
                            Pencarian: {{ $search }}
                            <a href="{{ route('student_classes.index', array_filter(['year_id' => $yearFilter, 'class_name' => $classFilter])) }}" 
                            class="ml-2 hover:text-blue-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if($yearFilter)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-calendar mr-1"></i>
                            Angkatan: {{ $years->firstWhere('id', $yearFilter)->name ?? 'Unknown' }}
                            <a href="{{ route('student_classes.index', array_filter(['search' => $search, 'class_name' => $classFilter])) }}" 
                            class="ml-2 hover:text-purple-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if($classFilter)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            Kelas: {{ $classFilter }}
                            <a href="{{ route('student_classes.index', array_filter(['search' => $search, 'year_id' => $yearFilter])) }}" 
                            class="ml-2 hover:text-green-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Nama Kelas
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Angkatan
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Total Semester
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Jumlah Siswa
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Tanggal Dibuat
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($studentClasses as $index => $studentClass)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $studentClasses->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        @if($search)
                                            {!! str_ireplace($search, '<span class="bg-yellow-200 px-1 rounded">'.$search.'</span>', $studentClass->name) !!}
                                        @else
                                            {{ $studentClass->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $studentClass->year->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                <i class="fas fa-book-open mr-1"></i>
                                Semester {{ $studentClass->unique_semesters_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-users mr-1"></i>
                                {{ $studentClass->students_count }} siswa
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="far fa-clock mr-1"></i>
                            {{ $studentClass->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('student_classes.show', $studentClass->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-150"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('student_classes.edit', $studentClass->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-150"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('student_classes.destroy', $studentClass->id) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $studentClass->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-150"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="bg-gray-100 rounded-full p-6">
                                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                </div>
                                @if($search || $yearFilter)
                                    <p class="text-gray-600 font-medium">Tidak ada kelas yang sesuai dengan kriteria pencarian.</p>
                                    <a href="{{ route('student_classes.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-redo mr-2"></i>
                                        Reset Filter
                                    </a>
                                @else
                                    <p class="text-gray-600 font-medium">Belum ada data kelas</p>
                                    <p class="text-sm text-gray-500">Mulai dengan menambahkan kelas siswa pertama ke dalam sistem.</p>
                                    <a href="{{ route('student_classes.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors mt-2">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Kelas Baru
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($studentClasses->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Menampilkan 
                    <span class="font-semibold">{{ $studentClasses->firstItem() }}</span>
                    sampai 
                    <span class="font-semibold">{{ $studentClasses->lastItem() }}</span>
                    dari 
                    <span class="font-semibold">{{ $studentClasses->total() }}</span>
                    hasil
                </div>
                <div>
                    {{ $studentClasses->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- Success/Error Toast Messages -->
@if(session('success'))
    <div id="success-toast" class="fixed top-4 right-4 z-50 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-lg max-w-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="document.getElementById('success-toast').remove()" class="ml-3 text-green-500 hover:text-green-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('success-toast');
            if(toast) toast.remove();
        }, 5000);
    </script>
@endif

@if($errors->any())
    <div id="error-toast" class="fixed top-4 right-4 z-50 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-lg max-w-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-medium">{{ $errors->first() }}</span>
            <button onclick="document.getElementById('error-toast').remove()" class="ml-3 text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('error-toast');
            if(toast) toast.remove();
        }, 7000);
    </script>
@endif

<!-- Auto-submit search form on Enter key -->
<script>
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        this.closest('form').submit();
    }
});
</script>
<script>
    // ========== SELECT2 INITIALIZATION ==========
    
    /**
     * Initialize Select2 for Year dropdown
     */
    $('.select2-year').select2({
        placeholder: '-- Pilih Angkatan --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Angkatan tidak ditemukan"; },
            searching: function() { return "Mencari..."; }
        }
    });

    /**
     * Initialize Select2 for Class dropdown
     */
    $('.select2-class').select2({
        placeholder: '-- Pilih Kelas --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Kelas tidak ditemukan"; },
            searching: function() { return "Mencari..."; }
        }
    });
</script>
@endsection
