<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PKUMI System')</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Masukkan CSS Kustom di sini jika ada --}}
    @yield('styles')

    <style>
        body {
            background-image: url('{{ asset('images/istiqlal.jpg') }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            /* Tambahkan overlay gelap untuk visibilitas teks */
            background-color: rgba(0, 0, 0, 0.5);
            background-blend-mode: darken;
        }
    </style>
</head>
<body>

    <div class="min-h-screen flex items-center justify-center">
        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>