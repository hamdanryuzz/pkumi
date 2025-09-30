<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PKUMI</title>

    {{-- Menggunakan Vite untuk memuat aset CSS/JS (standar Laravel 10+) --}}
    @vite('resources/css/app.css') 
    {{-- Jika belum setup Vite, bisa pakai CDN Tailwind untuk testing --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    {{-- Disarankan untuk mendefinisikan custom colors di tailwind.config.js --}}
    <style>
        :root {
            --brand-dark-blue: #0A2540;
            --brand-slate: #425466;
            --brand-light-bg: #F6F9FC;
            --brand-border: #E6E6E6;
            --brand-placeholder: #A9A9A9;
            --brand-link: #635BFF;
            --brand-primary-blue: #0066FF;
        }
        .bg-brand-dark-blue { background-color: var(--brand-dark-blue); }
        .text-brand-dark-blue { color: var(--brand-dark-blue); }
        .text-brand-slate { color: var(--brand-slate); }
        .bg-brand-light-bg { background-color: var(--brand-light-bg); }
        .border-brand-border { border-color: var(--brand-border); }
        .placeholder-brand-placeholder::placeholder { color: var(--brand-placeholder); }
        .text-brand-link { color: var(--brand-link); }
        .focus\:ring-brand-link:focus { --tw-ring-color: var(--brand-link); }
        .bg-brand-primary-blue { background-color: var(--brand-primary-blue); }
    </style>
</head>
<body class="bg-gray-100">

<section id="section-login">
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl flex flex-col lg:flex-row bg-white rounded-3xl shadow-lg overflow-hidden">
      
      <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 sm:p-12 md:p-16">
        <div class="w-full max-w-md mx-auto">
          <img src="{{ asset('images/logo0pkumi.png') }}" alt="PKUMI Logo" class="w-[170px] mb-12">

          <div class="flex flex-col gap-7">
            <h1 class="font-sans text-4xl font-semibold text-brand-dark-blue">Welcome to PKUMI 👋</h1>
            <p class="font-sans text-xl text-brand-slate leading-8">Today is a new day. It's your day. You shape it. Sign in to start managing your projects.</p>
          </div>

          <form class="mt-12 flex flex-col gap-6" action="{{ route('mahasiswa.login') }}" method="POST">
            {{-- Token CSRF untuk keamanan, wajib ada di form Laravel --}}
            @csrf

            {{-- Menampilkan error login jika ada --}}
            @if($errors->any())
              <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md text-sm" role="alert">
                <p>{{ $errors->first() }}</p>
              </div>
            @endif

            <div class="flex flex-col gap-2">
              <label for="email" class="font-roboto text-base text-brand-dark-blue">Email</label>
              {{-- Tambahkan atribut name="email" dan value="{{ old('email') }}" --}}
              <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Example@email.com" required autofocus class="h-12 px-4 font-roboto text-base bg-brand-light-bg border border-brand-border rounded-xl placeholder-brand-placeholder focus:outline-none focus:ring-2 focus:ring-brand-link">
            </div>

            <div class="flex flex-col gap-2">
              <label for="password" class="font-roboto text-base text-brand-dark-blue">Password</label>
              {{-- Tambahkan atribut name="password" --}}
              <input type="password" id="password" name="password" placeholder="At least 8 characters" required class="h-12 px-4 font-roboto text-base bg-brand-light-bg border border-brand-border rounded-xl placeholder-brand-placeholder focus:outline-none focus:ring-2 focus:ring-brand-link">
            </div>

            {{-- Arahkan ke route untuk reset password jika ada --}}
            <a href="#" class="self-end font-roboto text-base text-brand-link hover:underline">Forgot Password?</a>

            <button type="submit" class="h-[52px] w-full bg-brand-primary-blue text-white font-roboto text-xl rounded-xl flex items-center justify-center hover:bg-blue-800 transition-colors">
              Sign in
            </button>
          </form>
        </div>
      </div>

      <div class="hidden lg:block lg:w-1/2">
        {{-- Menggunakan helper asset() Laravel --}}
        <img src="{{ asset('images/promo-art.png') }}" alt="Promotional Art" class="w-full h-full object-cover">
      </div>

    </div>
  </div>
</section>

</body>
</html>