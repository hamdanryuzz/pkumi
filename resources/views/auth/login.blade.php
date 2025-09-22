@extends('layouts.auth')

@section('title', 'Login - Sistem Penilaian PKUMI')

@section('content')
<div class="rounded-xl bg-gray-800 bg-opacity-50 px-16 py-10 shadow-lg backdrop-blur-md max-sm:px-8">
    <div class="text-white">
        <div class="mb-8 flex flex-col items-center">
            <img src="{{ asset('images/logo0pkumi.png') }}" width="150" alt="Logo PKUMI" class="mb-2" />
            <h1 class="mb-2 text-2xl">Sistem Penilaian PKUMI</h1>
            <span class="text-gray-300">Masukkan Detail Login</span>
        </div>
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div class="text-lg">
                <input class="rounded-3xl border-none bg-blue-400 bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md w-full @error('email') border-red-500 @enderror" type="email" name="email" placeholder="id@email.com" value="{{ old('email') }}" required />
                @error('email')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-lg">
                <input class="rounded-3xl border-none bg-blue-400 bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md w-full @error('password') border-red-500 @enderror" type="password" name="password" placeholder="*********" required />
                @error('password')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-8 flex justify-center text-lg text-black">
                <button type="submit" class="rounded-3xl bg-blue-400 bg-opacity-50 px-10 py-2 text-white shadow-xl backdrop-blur-md transition-colors duration-300 hover:bg-blue-600 w-full">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </div>
        </form>
    </div>
</div>
@endsection