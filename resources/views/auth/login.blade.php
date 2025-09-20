@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white bg-opacity-10 backdrop-filter backdrop-blur-md rounded-xl shadow-lg border border-white border-opacity-20 text-white dark:bg-gray-800 dark:bg-opacity-50 dark:border-gray-600">
        <h2 class="text-3xl font-bold text-center text-white">Sign In to PKUMI</h2>
        <p class="text-center text-sm text-gray-200">Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-blue-300 hover:text-blue-200">Daftar di sini</a></p>
        
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <form class="space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-200">Alamat Email</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-400 border-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-transparent text-white">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-200">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full px-3 py-2 border border-gray-400 border-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-transparent text-white">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-200">Ingat saya</label>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Sign In</button>
            </div>
        </form>
    </div>
</div>
@endsection