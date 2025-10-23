{{-- Menggunakan layout slicing dengan sidebar --}}
@extends('layouts.slicing')

{{-- Mengatur judul untuk halaman ini --}}
@section('title', 'Rincian Nilai Akademik')

{{-- Mendefinisikan blok konten --}}
@section('content')

{{-- Menambahkan warna custom yang dibutuhkan untuk elemen konten ini --}}
<style>
    /* Custom Colors */
    .border-blue-primary { border-color: #007BFF; }
    .bg-blue-primary { background-color: #007BFF; }
    .text-blue-primary { color: #007BFF; }
    .text-blue-light { color: #FFFFFF; }
    .text-gray-subtext { color: #718096; }
    .bg-green-grade { background-color: #86EFAC; }
    .bg-blue-grade { background-color: #90CDF4; }
    .bg-yellow-grade { background-color: #FDE047; }
    .bg-red-grade { background-color: #FEB2B2; }
    .shadow-card { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    
    /* Detail section styling */
    .detail-section {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    
    .detail-section.active {
        max-height: 500px;
        transition: max-height 0.4s ease-in;
    }
    
    .chevron-icon {
        transition: transform 0.3s ease;
    }
    
    .chevron-icon.rotate {
        transform: rotate(180deg);
    }

    /* Responsive Styles Khusus Konten */
    @media (max-width: 1024px) {
        #section-grades h2 {
            font-size: 1.5rem;
        }
        #section-grades .flex-col.md\:flex-row {
            flex-direction: column;
        }
        #section-grades select {
            width: 100%;
        }
        .bg-blue-primary.text-blue-light {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 640px) {
        #section-grades h2 {
            font-size: 1.25rem;
            text-align: center;
        }
        #section-grades .flex.justify-between.items-center {
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }
        #section-grades .bg-blue-primary.text-blue-light {
            width: 100%;
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        .shadow-card {
            padding: 0.75rem;
        }
        .shadow-card h3 {
            font-size: 1rem;
        }
        .shadow-card p {
            font-size: 0.75rem;
        }
        .shadow-card .w-12 {
            width: 2.5rem;
            height: 2.5rem;
        }
        .shadow-card .font-bold {
            font-size: 1rem;
        }

        #filter-form {
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }
        #filter-form select {
            width: 100% !important;
            padding: 0.75rem 2.5rem 0.75rem 1rem !important;
            font-size: 0.875rem !important;
        }
        #filter-form .relative {
            position: relative;
            width: 100%;
        }
        #filter-form .absolute.inset-y-0.right-0 {
            right: 0.75rem;
        }
    }
</style>

@php
    $selectedPeriodId = request('period_id');
    $selectedSemesterId = request('semester_id');
@endphp

<section id="section-grades" class="py-4">
    <div class="flex justify-between items-center mb-8">
        <h2 class="font-poppins font-semibold text-4xl tracking-wider">Rincian Nilai Akademik</h2>
        {{-- Link unduh transkrip - arahkan ke ReportController --}}
        <!-- <a href="#" class="bg-blue-primary text-blue-light font-poppins font-medium px-5 py-2 rounded-lg hover:bg-blue-600 transition duration-200 shadow-md">
            Unduh transkrip
        </a> -->
    </div>

    {{-- FILTERS DINAMIS --}}
    <form method="GET" action="{{ route('mahasiswa.dashboard') }}" id="filter-form" class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative w-full md:w-1/3">
            <label for="period_filter" class="block text-sm font-poppins font-medium text-gray-700 mb-1">Filter Tahun Ajaran</label>
            <select name="period_id" id="period_filter" onchange="this.form.submit()"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 font-poppins text-sm focus:outline-none focus:ring-2 focus:ring-blue-primary appearance-none">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}
                    </option>
                @endforeach
            </select>
            {{-- Dropdown Icon --}}
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none mt-6">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div class="relative w-full md:w-1/3">
            <label for="semester_filter" class="block text-sm font-poppins font-medium text-gray-700 mb-1">Filter Semester</label>
            <select name="semester_id" id="semester_filter" onchange="this.form.submit()"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 font-poppins text-sm focus:outline-none focus:ring-2 focus:ring-blue-primary appearance-none">
                <option value="">Semua Semester</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                    </option>
                @endforeach>
            </select>
            {{-- Dropdown Icon --}}
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none mt-6">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Input tersembunyi untuk mempertahankan nilai search saat filter diubah --}}
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>
    {{-- END FILTERS --}}

    {{-- DAFTAR MATA KULIAH DENGAN ACCORDION --}}
    <div class="space-y-4">
        @forelse($courses as $index => $item)
            @php
                $gradeLetter = $item['letter_grade'];
                $bgColor = 'bg-gray-500'; // Default
                if ($gradeLetter == 'A+' || $gradeLetter == 'A' || $gradeLetter == 'A-') {
                    $bgColor = 'bg-green-grade';
                } elseif (str_contains($gradeLetter, 'B+') || $gradeLetter == 'B' || $gradeLetter == 'B-') {
                    $bgColor = 'bg-blue-grade';
                } elseif (str_contains($gradeLetter, 'C+') || $gradeLetter == 'C' || $gradeLetter == 'C-') {
                    $bgColor = 'bg-yellow-grade';
                } elseif ($gradeLetter == 'D' || $gradeLetter == 'E') {
                    $bgColor = 'bg-red-grade';
                }
            @endphp
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-card overflow-hidden">
                {{-- Header yang bisa diklik --}}
                <div class="p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition duration-200" 
                     onclick="toggleDetail({{ $index }})">
                    <div class="flex-1">
                        <h3 class="font-poppins font-semibold text-xl">{{ $item['course_name'] }} ({{ $item['course_code'] }})</h3>
                        <p class="font-poppins text-sm text-gray-subtext mt-1">Semester: {{ $item['semester'] }} | {{ $item['period_name'] }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col items-center space-y-1">
                            <div class="{{ $bgColor }} w-12 h-12 rounded-full flex items-center justify-center shadow-sm">
                                <span class="font-poppins font-bold {{ $bgColor === 'bg-green-grade' ? 'text-green-700' : ($bgColor === 'bg-blue-grade' ? 'text-blue-700' : ($bgColor === 'bg-yellow-grade' ? 'text-yellow-700' : ($bgColor === 'bg-red-grade' ? 'text-red-700' : 'text-gray-300'))) }}">{{ $gradeLetter }}</span>
                            </div>
                            <p class="font-poppins text-xs text-gray-subtext">{{ $item['sks'] }} SKS</p>
                        </div>
                        {{-- Chevron Icon --}}
                        <svg id="chevron-{{ $index }}" class="w-6 h-6 text-gray-400 chevron-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Detail Section (Hidden by default) --}}
                <div id="detail-{{ $index }}" class="detail-section">
                    <div class="px-4 pb-4 pt-2 border-t border-gray-100">
                        <h4 class="font-poppins font-semibold text-lg mb-3 text-gray-700">Rincian Nilai</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Attendance Score --}}
                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai Kehadiran</span>
                                <span class="font-poppins text-base font-bold text-gray-800">{{ $item['attendance_score'] }}</span>
                            </div>

                            {{-- Assignment Score --}}
                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai Tugas</span>
                                <span class="font-poppins text-base font-bold text-gray-800">{{ $item['assignment_score'] }}</span>
                            </div>

                            {{-- Midterm Score --}}
                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai UTS</span>
                                <span class="font-poppins text-base font-bold text-gray-800">{{ $item['midterm_score'] }}</span>
                            </div>

                            {{-- Final Score --}}
                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <span class="font-poppins text-sm font-medium text-gray-600">Nilai UAS</span>
                                <span class="font-poppins text-base font-bold text-gray-800">{{ $item['final_score'] }}</span>
                            </div>

                            {{-- Final Grade --}}
                            <div class="flex justify-between items-center bg-blue-50 rounded-lg p-3 border border-blue-200">
                                <span class="font-poppins text-sm font-semibold text-blue-700">Nilai Akhir</span>
                                <span class="font-poppins text-lg font-bold text-blue-800">{{ $item['final_grade'] }}</span>
                            </div>

                            {{-- Letter Grade --}}
                            <div class="flex justify-between items-center {{ $bgColor }} bg-opacity-10 rounded-lg p-3 border border-opacity-30" style="border-color: currentColor;">
                                <span class="font-poppins text-sm font-semibold {{ $bgColor === 'bg-green-grade' ? 'text-green-700' : ($bgColor === 'bg-blue-grade' ? 'text-blue-700' : ($bgColor === 'bg-yellow-grade' ? 'text-yellow-700' : ($bgColor === 'bg-red-grade' ? 'text-red-700' : 'text-gray-700'))) }}">Nilai Huruf</span>
                                <span class="font-poppins text-2xl font-bold {{ $bgColor === 'bg-green-grade' ? 'text-green-700' : ($bgColor === 'bg-blue-grade' ? 'text-blue-700' : ($bgColor === 'bg-yellow-grade' ? 'text-yellow-700' : ($bgColor === 'bg-red-grade' ? 'text-red-700' : 'text-gray-700'))) }}">
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
        
        // Toggle active class
        detailSection.classList.toggle('active');
        chevronIcon.classList.toggle('rotate');
    }
</script>

@endsection
