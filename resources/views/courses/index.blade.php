@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-book-open mr-2 text-blue-600"></i>Manage Mata Kuliah
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Kelola data mata kuliah sistem
        </p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('courses.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-1"></i>Cari
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kode atau nama..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>

                <!-- Filter Angkatan (Year) with Select2 -->
                <div>
                    <label for="year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>Angkatan
                    </label>
                    <select name="year_id" 
                            id="year_id"
                            class="select2-filter w-full">
                        <option value="">Semua Angkatan</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kelas (Student Class) with Select2 -->
                <div>
                    <label for="student_class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-users mr-1"></i>Kelas
                    </label>
                    <select name="student_class_id" 
                            id="student_class_id"
                            class="select2-filter w-full">
                        <option value="">Semua Kelas</option>
                        @foreach($studentClasses as $class)
                            <option value="{{ $class->id }}" 
                                    data-year-id="{{ $class->year_id }}"
                                    {{ request('student_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('courses.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Action Button -->
    <div class="mb-6">
        <a href="{{ route('courses.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 shadow-md hover:shadow-lg">
            <i class="fas fa-plus-circle mr-2"></i>
            Tambah Mata Kuliah
        </a>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        @if($courses->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Mata Kuliah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Dibuat
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($courses as $index => $course)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                {{ $courses->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $course->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $course->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $course->studentClass->name ?? 'Tidak ada kelas' }}
                                @if($course->studentClass && $course->studentClass->year)
                                    <span class="block text-xs text-gray-500 dark:text-gray-500">
                                        {{ $course->studentClass->year->name }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $course->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('courses.show', $course->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('courses.edit', $course->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('courses.destroy', $course->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Hapus">
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

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700">
                {{ $courses->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Tidak ada data
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    @if(request()->hasAny(['search', 'year_id', 'student_class_id']))
                        Tidak ada mata kuliah yang sesuai dengan filter yang dipilih.
                    @else
                        Mulai dengan menambahkan mata kuliah baru.
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'year_id', 'student_class_id']))
                    <a href="{{ route('courses.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Tambah Mata Kuliah
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- JavaScript untuk Select2 dan Cascade Dropdown -->
<script>
$(document).ready(function() {
    // Simpan semua data kelas
    const allClassOptions = [];
    $('#student_class_id option').each(function() {
        if ($(this).val() !== '') {
            allClassOptions.push({
                id: $(this).val(),
                text: $(this).text(),
                yearId: $(this).data('year-id')
            });
        }
    });
    
    // Initialize Select2 untuk Angkatan
    $('#year_id').select2({
        placeholder: 'Pilih Angkatan...',
        allowClear: true,
        width: '100%'
    });
    
    // Initialize Select2 untuk Kelas
    let classSelect2 = $('#student_class_id').select2({
        placeholder: 'Pilih Kelas...',
        allowClear: true,
        width: '100%'
    });
    
    // Fungsi untuk update options kelas berdasarkan angkatan
    function updateClassOptions(yearId) {
        const currentValue = $('#student_class_id').val();
        
        // Clear dan rebuild options
        $('#student_class_id').empty().append(
            $('<option>', { value: '', text: 'Semua Kelas' })
        );
        
        let filteredOptions = allClassOptions;
        
        // Filter berdasarkan angkatan jika dipilih
        if (yearId && yearId !== '') {
            filteredOptions = allClassOptions.filter(option => option.yearId == yearId);
        }
        
        // Tambahkan options yang sudah difilter
        filteredOptions.forEach(option => {
            const newOption = $('<option>', {
                value: option.id,
                text: option.text,
                'data-year-id': option.yearId
            });
            $('#student_class_id').append(newOption);
        });
        
        // Set kembali nilai yang dipilih jika masih ada
        const availableIds = filteredOptions.map(o => o.id);
        if (currentValue && availableIds.includes(currentValue)) {
            $('#student_class_id').val(currentValue);
        } else {
            $('#student_class_id').val('');
        }
        
        // Trigger change untuk update Select2 display
        $('#student_class_id').trigger('change');
    }
    
    // Event listener untuk perubahan dropdown angkatan
    $('#year_id').on('change', function() {
        const selectedYearId = $(this).val();
        updateClassOptions(selectedYearId);
    });
    
    // Initialize dengan filter yang ada saat page load
    const initialYearId = $('#year_id').val();
    if (initialYearId) {
        updateClassOptions(initialYearId);
    }
});
</script>
@endsection
