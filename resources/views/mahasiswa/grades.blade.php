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
    .bg-green-grade { background-color: #28A745; }
    .bg-yellow-grade { background-color: #FFC107; }
    .bg-red-grade { background-color: #DC3545; }
    .shadow-card { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    /* Responsive Styles Khusus Konten */
    @media (max-width: 1024px) {
        /* Adjust for tablets */
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
        /* Adjust for mobile */
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

        /* == FILTER RESPONSIF MOBILE == */
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
        <a href="{{ route('reports.exportPdf') }}" class="bg-blue-primary text-blue-light font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center">Unduh transkrip</a>
    </div>

    {{-- FILTERS DINAMIS --}}
    <form id="filter-form" action="{{ route('mahasiswa.dashboard') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:space-x-5 space-y-4 md:space-y-0 mb-8">
        <div class="relative">
            <label for="period-filter" class="sr-only">Filter Tahun Ajaran</label>
            <select name="period_id" id="period-filter"
                class="bg-blue-primary appearance-none text-blue-light font-poppins font-medium text-xl w-full md:w-auto px-8 py-3 rounded-2xl text-center flex items-center justify-center gap-2 transition-colors duration-300">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}
                    </option>
                @endforeach
            </select>
            {{-- Dropdown Icon --}}
            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-blue-light">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
        </div>
        
        <div class="relative">
             <label for="semester-filter" class="sr-only">Filter Semester</label>
             <select name="semester_id" id="semester-filter"
                class="bg-blue-primary appearance-none text-blue-light font-poppins font-medium text-xl w-full md:w-auto px-8 py-3 rounded-2xl text-center flex items-center justify-center gap-2 transition-colors duration-300">
                <option value="">Semua Semester</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                    </option>
                @endforeach>
            </select>
            {{-- Dropdown Icon --}}
            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-blue-light">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
        </div>
        
        {{-- Input tersembunyi untuk mempertahankan nilai search saat filter diubah --}}
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>
    {{-- END FILTERS --}}

    <div class="flex flex-col space-y-4">
        @forelse($courses as $item)
            @php
                $gradeLetter = $item['letter_grade'];
                $bgColor = 'bg-gray-500'; // Default
                if ($gradeLetter == 'A+' || $gradeLetter == 'A' || $gradeLetter == 'A-') {
                    $bgColor = 'bg-green-grade';
                } elseif (str_contains($gradeLetter, 'B')) {
                    $bgColor = 'bg-yellow-grade';
                } elseif ($gradeLetter == 'C') {
                    $bgColor = 'bg-red-grade';
                }
            @endphp
            
            <div class="bg-white border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-card transition duration-300 hover:shadow-md">
                <div>
                    <h3 class="font-poppins font-semibold text-xl">{{ $item['course_name'] }} ({{ $item['course_code'] }})</h3>
                    <p class="font-poppins text-sm text-gray-subtext mt-1">Semester: {{ $item['semester'] }} | {{ $item['period_name'] }}</p>
                </div>
                <div class="flex flex-col items-center space-y-1">
                    <div class="{{ $bgColor }} w-12 h-12 rounded-full flex items-center justify-center shadow-sm">
                        <span class="font-poppins font-bold text-white">{{ $gradeLetter }}</span>
                    </div>
                    <p class="font-poppins text-xs text-gray-subtext">{{ $item['sks'] }} SKS</p>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Setup dropdown untuk filter
        setupDropdown('period-filter');
        setupDropdown('semester-filter');

        // Fungsi untuk membuat dropdown mobile-friendly
        function setupDropdown(selectId) {
            const select = document.getElementById(selectId);
            if (!select) return;

            // Saat dropdown dibuka, pastikan tidak keluar layar
            select.addEventListener('change', function() {
                // Submit form otomatis saat pilih
                document.getElementById('filter-form').submit();
            });
        }

        // Fungsi untuk menangani dropdown di mobile agar tidak overlap
        if (window.innerWidth <= 640) {
            const selects = document.querySelectorAll('#filter-form select');
            selects.forEach(select => {
                select.addEventListener('focus', function() {
                    // Scroll ke dropdown saat focus
                    this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            });
        }
    });
</script>

@endsection