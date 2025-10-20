@extends('layouts.app')

@section('title', 'Detail Kelas: ' . $studentClass->name)

@section('content')
<main class="py-6 px-4 md:px-8">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Detail Kelas</h2>
                <p class="text-base text-gray-500 mt-1">Informasi lengkap kelas {{ $studentClass->name }}</p>
            </div>
            <a href="{{ route('student_classes.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    {{-- Informasi Kelas --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Informasi Kelas
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Nama Kelas</span>
                <span class="text-lg font-semibold text-gray-900">{{ $studentClass->name }}</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Tahun Ajaran</span>
                <span class="text-lg font-semibold text-gray-900">{{ $studentClass->year->name ?? '-' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Jumlah Siswa</span>
                <span class="text-lg font-semibold text-gray-900">{{ $studentClass->students->count() }} orang</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-500 mb-1">Status</span>
                <span class="inline-flex items-center">
                    @if($studentClass->deleted_at)
                        <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>Aktif
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Mata Kuliah --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-book text-indigo-600 mr-2"></i>
                Mata Kuliah yang Terdaftar
            </h3>
        </div>

        {{-- Filter dan Search Form --}}
        <form method="GET" action="{{ route('student_classes.show', $studentClass->id) }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search Input --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i>Cari Mata Kuliah
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}" 
                        placeholder="Nama atau kode mata kuliah..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                </div>

                {{-- Filter Semester --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-filter mr-1"></i>Filter Semester
                    </label>
                    <select 
                        name="semester_id" 
                        class="select2-semester w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                        <option value="">Semua Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ $semesterFilter == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-check mr-1"></i>Terapkan
                        </button>
                        <a href="{{ route('student_classes.show', $studentClass->id) }}" 
                           class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-redo mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        {{-- Tabel Mata Kuliah --}}
        @if($courses->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Mata Kuliah
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SKS
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Semester
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($courses as $index => $course)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $courses->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                    {{ $course->code }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $course->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $course->sks }} SKS
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($course->semesters->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($course->semesters as $sem)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $sem->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">Belum terdaftar</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $courses->appends(['search' => $search, 'semester_id' => $semesterFilter])->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">Tidak ada mata kuliah yang ditemukan</p>
                <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
            </div>
        @endif
    </div>

    {{-- Daftar Siswa --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-users text-green-600 mr-2"></i>
                Daftar Siswa
            </h3>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                {{ $studentClass->students->count() }} Siswa
            </span>
        </div>

        @if($studentClass->students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIM
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Siswa
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($studentClass->students as $index => $student)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                    {{ $student->nim }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $student->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">Belum ada siswa di kelas ini</p>
                <p class="text-gray-400 text-sm mt-1">Tambahkan siswa untuk mulai mengelola kelas</p>
            </div>
        @endif
    </div>
</main>
<script>
    // ========== SELECT2 INITIALIZATION ==========
    
    /**
     * Initialize Select2 for Semester dropdown
     */
    $('.select2-semester').select2({
        placeholder: '-- Pilih Semester --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Semester tidak ditemukan"; },
            searching: function() { return "Mencari..."; }
        }
    });
</script>
@endsection
