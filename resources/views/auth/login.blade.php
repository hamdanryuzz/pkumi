@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md p-8 space-y-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white">Sign In to PKUMI</h2>
        <p class="text-center text-sm text-gray-600 dark:text-gray-400">Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar di sini</a></p>
        
        <form class="space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" required autocomplete="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Ingat saya</label>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Sign In</button>
            </div>
        </form>
    </div>
</div>
@endsection
