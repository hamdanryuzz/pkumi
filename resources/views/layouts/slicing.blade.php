<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PKUMI Dashboard')</title>

    @vite('resources/css/app.css')

    <style>
        :root {
            /* PERUBAHAN 1: Warna background sidebar diubah di sini */
            --brand-bg: #E6F1FF;
            --brand-gpa-label: #7D7D7D;
            --brand-gpa-value: #1B2559;
            --brand-badge-bg: #E9F7F0;
            --brand-badge-text: #00B69B;
            --brand-active-page: #717171;
            --brand-page-inactive: #C0C0C0;
            --brand-dark-header: #353535;
        }
        .bg-brand-bg { background-color: var(--brand-bg); }
        .text-brand-gpa-label { color: var(--brand-gpa-label); }
        .text-brand-gpa-value { color: var(--brand-gpa-value); }
        .bg-brand-badge-bg { background-color: var(--brand-badge-bg); }
        .text-brand-badge-text { color: var(--brand-badge-text); }
        .bg-brand-active-page { background-color: var(--brand-active-page); }
        .text-brand-page-inactive { color: var(--brand-page-inactive); }
        .bg-brand-dark-header { background-color: var(--brand-dark-header); }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            /* Tablet: Kurangi lebar sidebar dan sesuaikan elemen */
            aside .w-[365px] {
                width: 250px;
            }
            aside .w-[120px] {
                width: 80px;
                height: 80px;
            }
            aside h1 {
                font-size: 1.25rem;
            }
            aside .text-base {
                font-size: 0.875rem;
            }
            aside .text-[32.5px] {
                font-size: 1.5rem;
            }
            aside .gap-[23px] {
                gap: 1rem;
            }
            aside .gap-[19px] {
                gap: 0.75rem;
            }
            aside .gap-7 {
                gap: 1rem;
            }
            aside .w-[45px] {
                width: 35px;
                height: 30px;
            }
            aside .text-[13px] {
                font-size: 0.75rem;
            }
            aside .w-[129px], aside .w-[101px] {
                width: 80px;
            }
            aside .flex-1.text-sm {
                font-size: 0.75rem;
            }
            aside .py-3.5 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }

        @media (max-width: 640px) {
            /* Mobile: Sidebar menjadi full-width dan collapsable */
            .flex {
                flex-direction: column;
            }
            aside {
                width: 100%;
                height: auto;
                position: relative;
                top: auto;
            }
            aside .w-[365px] {
                width: 100%;
                padding: 1rem;
            }
            aside .w-[120px] {
                width: 60px;
                height: 60px;
            }
            aside h1 {
                font-size: 1rem;
            }
            aside .text-base {
                font-size: 0.75rem;
            }
            aside .text-[32.5px] {
                font-size: 1.25rem;
            }
            aside .gap-[23px] {
                gap: 0.5rem;
            }
            aside .gap-5 {
                gap: 0.75rem;
            }
            aside .pl-[29px] {
                padding-left: 1rem;
            }
            aside .pr-[26px] {
                padding-right: 1rem;
            }
            aside .pt-[22px] {
                padding-top: 1rem;
            }
            aside .pb-[6px] {
                padding-bottom: 0.5rem;
            }
            aside .gap-[19px] {
                gap: 0.5rem;
            }
            aside .gap-7 {
                gap: 0.5rem;
            }
            aside .w-[45px] {
                width: 30px;
                height: 25px;
            }
            aside .text-[13px] {
                font-size: 0.625rem;
            }
            aside .w-6 {
                width: 1.25rem;
                height: 1.25rem;
            }
            aside .w-[129px], aside .w-[101px] {
                width: 60px;
            }
            aside .flex-1.text-sm {
                font-size: 0.625rem;
            }
            aside .py-2.5 {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
            aside .px-2.5 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            main {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">

    @php
        // Menggunakan guard 'student' untuk Mahasiswa
        $student = Auth::guard('student')->user();
        
        // Eager load relasi yang dibutuhkan untuk sidebar (studentClass dan grades.course)
        // Jika user adalah Mahasiswa, kita load data
        if ($student) {
            $student->load(['studentClass', 'grades.course']);
        }
        // Menghitung IPK (menggunakan accessor ipk yang kita tambahkan)
        $ipk = $student ? number_format($student->ipk, 2, ',', '.') : '0,00';
    @endphp

    <div class="flex">
        <aside class="h-screen sticky top-0 flex-shrink-0">
            <div class="w-[365px] h-full bg-brand-bg flex flex-col items-center gap-8 pt-[54px] pb-[23px] px-[26px] overflow-y-auto">
                <div class="flex flex-col items-center text-center gap-[23px]">
                    <img src="{{ asset('images/xaviera.jpeg') }}" alt="Profile Picture" class="w-[120px] h-[120px] rounded-full object-cover">
                    <div class="flex flex-col">
                        {{-- DATA DINAMIS --}}
                        <h1 class="font-poppins font-semibold text-[25px] leading-tight text-black">{{ $student->name ?? 'Pengguna' }}</h1>
                        <div class="flex flex-col mt-2">
                            <p class="font-poppins text-base text-black/65">NIM : {{ $student->nim ?? 'N/A' }}</p>
                            <p class="font-poppins text-base text-black/65">Program Studi : {{ $student->studentClass->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-black/40 flex flex-col gap-5 rounded-lg shadow-sm w-full">
                    <div class="pl-[29px] pr-[26px] pt-[22px] pb-[6px]">
                        <div class="flex flex-col gap-[7px]">
                            <p class="font-inter font-medium text-[14px] text-brand-gpa-label">Indeks Prestasi Kumulatif</p>
                            <div class="flex items-center gap-[19px]">
                                {{-- IPK DINAMIS --}}
                                <span class="font-inter font-semibold text-[32.5px] leading-none text-brand-gpa-value tracking-[-1.5px]">{{ $ipk }}</span>
                                {{-- Persentase statis --}}
                                <!-- <div class="bg-brand-badge-bg flex items-center gap-1 rounded-md py-1 px-1.5">
                                    <svg class="w-4 h-4 text-brand-badge-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                    </svg>
                                    <span class="font-inter font-semibold text-[13px] leading-none text-brand-badge-text">9.2%</span>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-7 px-7 pb-6 -mt-3">
                        <a href="#" class="bg-brand-active-page text-white font-inter text-[13px] rounded-md flex items-center justify-center w-[45px] h-[35px]">1</a>
                        <a href="#" class="text-brand-page-inactive font-inter text-[13px]">2</a>
                        <a href="#" class="text-brand-page-inactive font-inter text-[13px]">3</a>
                        <a href="#" class="ml-auto">
                            <svg class="w-6 h-6 text-brand-page-inactive" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden w-full shadow-sm">
                    <div class="flex bg-brand-dark-header text-white font-poppins font-semibold text-base text-center">
                        <div class="py-2.5 px-2.5" style="width: 129px;">Jenis</div>
                        <div class="py-2.5 px-2.5" style="width: 101px;">Terverifikasi</div>
                        <div class="flex-1 py-2.5 px-2.5 text-sm">Belum di<br>Verifikasi</div>
                    </div>
                    <div class="font-poppins font-semibold text-base text-black text-center">
                        <div class="flex border-b border-black/40">
                            <div class="w-[129px] py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">Khazanah</div>
                            <div class="w-[101px] py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">12</div>
                            <div class="flex-1 py-3.5 px-2.5 flex items-center justify-center">7</div>
                        </div>
                        <div class="flex">
                            <div class="w-[129px] py-3.5 px-2.5 border-r border-black/40 rounded-bl-2xl flex items-center justify-center">Rubik</div>
                            <div class="w-[101px] py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">5</div>
                            <div class="flex-1 py-3.5 px-2.5 rounded-br-2xl flex items-center justify-center">3</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-grow p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>