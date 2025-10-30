{{-- Menggunakan layout slicing dengan sidebar --}}
@extends('layouts.slicing')

{{-- Mengatur judul untuk halaman ini --}}
@section('title', 'Rincian Nilai Akademik')

{{-- Mendefinisikan blok konten --}}
@section('content')

{{-- Header "Welcome" (Hanya untuk halaman ini) --}}
<section id="section-welcome" class="mt-2 mb-8">
    <div class="bg-blue-primary rounded-xl flex items-center justify-between text-white p-6 md:p-10 relative overflow-hidden min-h-[140px] md:h-[175px]">
        <div class="z-10">
            <h1 class="font-sans font-bold text-2xl md:text-[35px] leading-tight -tracking-[1.05px] mb-2">Welcome back, {{ $student ? explode(' ', $student->name)[0] : 'Mahasiswa' }}</h1>
            <p class="font-sans text-sm md:text-[15px] leading-snug md:leading-[26px] tracking-[0.3px] max-w-lg">Lihat rincian nilai mata kuliah Anda di sini.</p>
        </div>
        <div class="absolute right-0 bottom-0 h-full opacity-50 md:opacity-100">
            <img src="{{ asset('images/bg-guru.png') }}" alt="Teacher and students illustration" class="h-full w-auto object-cover">
        </div>
    </div>
</section>

<style>
    /* Custom Colors */
    .border-blue-primary { border-color: #007BFF; }
    .bg-blue-primary { background-color: #007BFF; }
    .text-blue-primary { color: #007BFF; }
    .text-blue-light { color: #FFFFFF; }
    .text-gray-subtext { color: #718096; }
    .shadow-card { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    /* Kelas Nilai (background + color) */
    .bg-green-grade { background-color: #86EFAC; color: #15803d; } /* Hijau */
    .bg-blue-grade  { background-color: #90CDF4; color: #1d4ed8; } /* Biru */
    .bg-yellow-grade { background-color: #FDE047; color: #a16207; } /* Kuning */
    .bg-red-grade   { background-color: #FEB2B2; color: #b91c1c; } /* Merah */
    
    /* Detail section styling (Accordion) */
    .detail-section {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    
    .detail-section.active {
        max-height: 500px; /* Cukup besar untuk konten */
        transition: max-height 0.4s ease-in;
    }
    
    .chevron-icon {
        transition: transform 0.3s ease;
    }
    
    .chevron-icon.rotate {
        transform: rotate(180deg);
    }
</style>

@php
    $selectedPeriodId = request('period_id');
    $selectedSemesterId = request('semester_id');
@endphp

<section id="section-grades" class="py-4">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <h2 class="font-poppins font-semibold text-2xl sm:text-4xl tracking-wider text-center sm:text-left">
            Rincian Nilai Akademik
        </h2>
    </div>

    {{-- FILTERS DINAMIS --}}
    <form method="GET" action="{{ route('mahasiswa.dashboard') }}" id="filter-form" class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative w-full md:w-1/3">
            <label for="period_filter" class="block text-sm font-poppins font-medium text-gray-700 mb-1">Filter Tahun Ajaran</label>
            <select name="period_id" id="period_filter" onchange="this.form.submit()"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 font-poppins text-sm focus:outline-none focus:ring-2 focus:ring-blue-primary appearance-none">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none mt-6">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div class="relative w-full md:w-1/3">
            <label for="semester_filter" class="block text-sm font-poppins font-medium text-gray-700 mb-1">Filter Semester</label>
            <select name="semester_id" id="semester_filter" onchange="this.form.submit()"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 font-poppins text-sm focus:outline-none focus:ring-2 focus:ring-blue-primary appearance-none">
                <option value="">Semua Semester</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none mt-6">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>
    {{-- END FILTERS --}}

    {{-- DAFTAR MATA KULIAH DENGAN ACCORDION --}}
    <div class="space-y-4">
        @forelse($courses as $index => $item)
            @php
                $gradeLetter = $item['letter_grade'];
                $bgColor = match($gradeLetter) {
                    'A+', 'A', 'A-' => 'bg-green-grade',
                    'B+', 'B', 'B-' => 'bg-blue-grade',
                    'C+', 'C', 'C-' => 'bg-yellow-grade',
                    'D', 'E'        => 'bg-red-grade',
                    default         => 'bg-gray-400 text-white',
                };
            @endphp
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-card overflow-hidden">
                <div class="p-3 sm:p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition duration-200" 
                     onclick="toggleDetail({{ $index }})">
                    
                    <div class="flex-1">
                        <h3 class="font-poppins font-semibold text-lg sm:text-xl">{{ $item['course_name'] }} ({{ $item['course_code'] }})</h3>
                        <p class="font-poppins text-xs sm:text-sm text-gray-subtext mt-1">Semester: {{ $item['semester'] }} | {{ $item['period_name'] }}</p>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="flex flex-col items-center space-y-1">
                            <div class="{{ $bgColor }} w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center shadow-sm">
                                <span class="font-poppins font-bold text-lg sm:text-base">{{ $gradeLetter }}</span>
                            </div>
                            <p class="font-poppins text-xs text-gray-subtext">{{ $item['sks'] }} SKS</p>
                        </div>
                        
                        <svg id="chevron-{{ $index }}" class="w-6 h-6 text-gray-400 chevron-icon flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div id="detail-{{ $index }}" class="detail-section">
                    <div class="px-3 pb-3 sm:px-4 sm:pb-4 pt-2 border-t border-gray-100">
                        <h4 class="font-poppins font-semibold text-base sm:text-lg mb-3 text-gray-700">Rincian Nilai</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4">
                            
                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai Kehadiran</span>
                                <span class="font-poppins text-sm sm:text-base font-bold text-gray-800">{{ $item['attendance_score'] ?? '-' }}</span>
                            </div>

                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai Tugas</span>
                                <span class="font-poppins text-sm sm:text-base font-bold text-gray-800">{{ $item['assignment_score'] ?? '-' }}</span>
                            </div>

                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai UTS</span>
                                <span class="font-poppins text-sm sm:text-base font-bold text-gray-800">{{ $item['midterm_score'] ?? '-' }}</span>
                            </div>

                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai UAS</span>
                                <span class="font-poppins text-sm sm:text-base font-bold text-gray-800">{{ $item['final_score'] ?? '-' }}</span>
                            </div>

                            <div class="flex justify-between items-center bg-blue-50 rounded-lg p-3 border border-blue-200">
                                <span class="font-poppins text-sm font-semibold text-blue-700">Nilai Akhir</span>
                                <span class="font-poppins text-base sm:text-lg font-bold text-blue-800">{{ $item['final_grade'] }}</span>
                            </div>

                            <div class="flex justify-between items-center {{ $bgColor }} bg-opacity-10 rounded-lg p-3 border border-current border-opacity-30">
                                <span class="font-poppins text-sm font-semibold">Nilai Huruf</span>
                                <span class="font-poppins text-xl sm:text-2xl font-bold">
                                    {{ $item['letter_grade'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed">
                <p class="text-lg font-medium text-gray-500">Tidak ada mata kuliah terdaftar atau nilai yang ditemukan untuk filter ini.</p>
                <p class="text-sm text-gray-400 mt-2">Coba ganti Tahun Ajaran atau Semester.</p>
            </div>
        @endforelse
    </div>
</section>

{{-- JavaScript untuk toggle accordion --}}
<script>
    function toggleDetail(index) {
        const detailSection = document.getElementById(`detail-${index}`);
        const chevronIcon = document.getElementById(`chevron-${index}`);
        
        if (!detailSection || !chevronIcon) return;

        const isActive = detailSection.classList.toggle('active');
        chevronIcon.classList.toggle('rotate', isActive);
    }
</script>

@endsection