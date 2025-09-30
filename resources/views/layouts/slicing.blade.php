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
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex">
        <!-- PERUBAHAN 2: Sidebar dibuat 'sticky' -->
        <!-- Menambahkan class 'h-screen', 'sticky', dan 'top-0' pada <aside> -->
        <aside class="h-screen sticky top-0 flex-shrink-0">
            <!-- Menambahkan class 'h-full' agar div ini mengisi tinggi <aside> -->
            <div class="w-[365px] h-full bg-brand-bg flex flex-col items-center gap-8 pt-[54px] pb-[23px] px-[26px] overflow-y-auto">
                <!-- Profile Info -->
                <div class="flex flex-col items-center text-center gap-[23px]">
                    <img src="{{ asset('images/xaviera.jpeg') }}" alt="Profile Picture" class="w-[120px] h-[120px] rounded-full object-cover">
                    <div class="flex flex-col">
                        <h1 class="font-poppins font-semibold text-[25px] leading-tight text-black">{{ Auth::user()->name }}</h1>
                        <div class="flex flex-col mt-2">
                            <p class="font-poppins text-base text-black/65">NIM : {{ Auth::user()->nim ?? '123456789' }}</p>
                            <p class="font-poppins text-base text-black/65">Program Studi : {{ Auth::user()->studentClass->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- GPA Card -->
                <div class="bg-white border border-black/40 flex flex-col gap-5 rounded-lg shadow-sm w-full">
                    <div class="pl-[29px] pr-[26px] pt-[22px] pb-[6px]">
                        <div class="flex flex-col gap-[7px]">
                            <p class="font-inter font-medium text-[14px] text-brand-gpa-label">Indeks Prestasi Kumulatif</p>
                            <div class="flex items-center gap-[19px]">
                                <span class="font-inter font-semibold text-[32.5px] leading-none text-brand-gpa-value tracking-[-1.5px]">3,94</span>
                                <div class="bg-brand-badge-bg flex items-center gap-1 rounded-md py-1 px-1.5">
                                    <svg class="w-4 h-4 text-brand-badge-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                    </svg>
                                    <span class="font-inter font-semibold text-[13px] leading-none text-brand-badge-text">9.2%</span>
                                </div>
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

                <!-- Verification Table Card -->
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

        <!-- Main Content Area -->
        <main class="flex-grow p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>