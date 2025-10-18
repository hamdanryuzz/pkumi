@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Manage Mahasiswa</h1>
                    <p class="mt-1 text-sm text-gray-500">Kelola data mahasiswa, nilai, dan akun login.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('students.export') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('students.create') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Mahasiswa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg border border-gray-200 mb-6">
            <form method="GET" action="{{ route('students.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wider">Pencarian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama, NIM, atau username"
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200">
                        </div>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label for="year_id" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wider">Angkatan</label>
                        <select name="year_id" id="year_id" 
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition-all duration-200">
                            <option value="">Semua Angkatan</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Student Class Filter -->
                    <div>
                        <label for="student_class_id" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wider">Kelas</label>
                        <select name="student_class_id" id="student_class_id" 
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition-all duration-200">
                            <option value="">Semua Kelas</option>
                            @foreach($studentClasses as $class)
                                <option value="{{ $class->id }}" {{ request('student_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                    @if($class->year)
                                        ({{ $class->year->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Course Filter -->
                <!-- <div class="mb-6">
                    <label for="course_id" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wider">Mata Kuliah</label>
                    <select name="course_id" id="course_id" 
                            class="block w-full md:w-1/2 lg:w-1/3 px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition-all duration-200">
                        <option value="">Semua Mata Kuliah</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->code }} - {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div> -->

                <!-- Filter Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-100">
                    <div class="flex flex-wrap gap-2">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari & Filter
                        </button>
                        <a href="{{ route('students.index') }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filter
                        </a>
                    </div>

                    <!-- Results Info -->
                    <div class="text-xs text-gray-500 uppercase tracking-wider">
                        {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} mahasiswa
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            @if($students->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIM</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $student)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 text-gray-800 text-xs font-mono font-medium">
                                            {{ $student->nim }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold text-xs">
                                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($student->username)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-mono font-medium">
                                                {{ $student->username }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">{{ $student->studentClass->name ?? '—' }}</div>
                                            @if($student->year)
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $student->year->name }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $student->email ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                            {{ $student->status === 'active' ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <a href="{{ route('students.show', $student) }}" 
                                               class="text-gray-500 hover:text-blue-600 transition-colors duration-200"
                                               title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}" 
                                               class="text-gray-500 hover:text-yellow-600 transition-colors duration-200"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('students.destroy', $student) }}" 
                                                  onsubmit="return confirm('Yakin hapus mahasiswa ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-gray-500 hover:text-red-600 transition-colors duration-200"
                                                        title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
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
                <div class="px-6 py-4 border-t border-gray-200 bg-white">
                    {{ $students->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <h3 class="mt-4 text-base font-medium text-gray-900">Tidak ada data mahasiswa</h3>
                    <p class="mt-2 text-sm text-gray-500">Silakan tambah mahasiswa baru atau import data.</p>
                    <div class="mt-6">
                        <a href="{{ route('students.create') }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Mahasiswa
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript for Enhanced Filtering -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year_id');
    const studentClassSelect = document.getElementById('student_class_id');
    
    yearSelect.addEventListener('change', function() {
        const selectedYearId = this.value;
        const options = studentClassSelect.querySelectorAll('option');
        
        options.forEach(function(option) {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            if (selectedYearId === '') {
                option.style.display = 'block';
            } else {
                const optionText = option.textContent;
                const yearMatch = optionText.includes('{{ $years->where("id", "' + selectedYearId + '")->first()->name ?? "" }}');
                option.style.display = yearMatch ? 'block' : 'none';
            }
        });
        
        if (studentClassSelect.value !== '' && studentClassSelect.selectedOptions[0].style.display === 'none') {
            studentClassSelect.value = '';
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.querySelector('select[name="year_id"]');
    const classSelect = document.querySelector('select[name="student_class_id"]');
    const allClasses = @json($studentClasses);
    
    // Simpan nilai yang sudah dipilih sebelumnya
    const selectedYearId = "{{ request('year_id') }}";
    const selectedClassId = "{{ request('student_class_id') }}";
    
    // Function untuk memfilter kelas berdasarkan year_id
    function filterClasses(yearId) {
        // Clear existing options kecuali option pertama (Semua Kelas)
        classSelect.innerHTML = '<option value="">Semua Kelas</option>';
        
        if (yearId) {
            // Filter kelas yang year_id-nya sesuai dengan yang dipilih
            const filteredClasses = allClasses.filter(cls => cls.year_id == yearId);
            
            filteredClasses.forEach(function(cls) {
                const option = new Option(cls.name, cls.id);
                if (cls.id == selectedClassId) {
                    option.selected = true;
                }
                classSelect.add(option);
            });
        } else {
            // Jika tidak ada year yang dipilih, tampilkan semua kelas
            allClasses.forEach(function(cls) {
                const option = new Option(cls.name, cls.id);
                if (cls.id == selectedClassId) {
                    option.selected = true;
                }
                classSelect.add(option);
            });
        }
    }
    
    // Jalankan filter saat halaman pertama kali dimuat
    if (selectedYearId) {
        filterClasses(selectedYearId);
    }
    
    // Event listener saat dropdown angkatan berubah
    yearSelect.addEventListener('change', function() {
        const selectedYear = this.value;
        filterClasses(selectedYear);
        
        // Reset dropdown kelas jika year berubah
        classSelect.value = '';
    });
});
</script>
@endsection