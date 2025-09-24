@extends('layouts.slicing')

@section('title', 'My Profile Settings')

@section('content')

{{-- Menambahkan warna custom khusus untuk halaman ini (jika ada) --}}
<style>
    .bg-custom-blue { background-color: #4A90E2; }
    .focus\:ring-custom-blue:focus { --tw-ring-color: #4A90E2; }
    .bg-custom-blue\/10 { background-color: rgba(74, 144, 226, 0.1); }
    .hover\:bg-custom-blue\/20:hover { background-color: rgba(74, 144, 226, 0.2); }
    .text-custom-blue { color: #4A90E2; }
</style>

<div class="bg-white rounded-xl shadow-sm w-full max-w-5xl p-8 md:p-10 lg:p-12">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
        <div class="flex items-center gap-6">
            <img src="{{ asset('images/xaviera.jpeg') }}" alt="User Avatar" class="w-24 h-24 rounded-full object-cover flex-shrink-0">
            <div>
                <h2 class="text-xl font-medium text-black leading-tight">Xaviera</h2>
                <p class="text-base font-normal text-black/50 leading-normal">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <button class="bg-custom-blue text-white text-base font-normal px-8 py-2.5 rounded-lg hover:bg-blue-700 transition-colors w-full sm:w-auto flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
            <span>Edit</span>
        </button>
    </div>

    <form class="mt-12" method="POST" action="#">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-6 gap-x-8 xl:gap-x-24">
            <div class="flex flex-col gap-2">
                <label for="full-name" class="text-base font-normal text-black/80">Full Name</label>
                <input type="text" id="full-name" name="name" value="{{ old('name', Auth::user()->name) }}" placeholder="Your Full Name" class="bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
            </div>
            <div class="flex flex-col gap-2">
                <label for="nick-name" class="text-base font-normal text-black/80">Nick Name</label>
                <input type="text" id="nick-name" name="nickname" value="{{ old('nickname', Auth::user()->nickname ?? '') }}" placeholder="Your Nick Name" class="bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
            </div>
            <div class="flex flex-col gap-2">
                <label for="gender" class="text-base font-normal text-black/80">Gender</label>
                <div class="relative">
                    <select id="gender" name="gender" class="w-full appearance-none bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', Auth::user()->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', Auth::user()->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none"><svg class="w-5 h-5 text-black/50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg></div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="country" class="text-base font-normal text-black/80">Country</label>
                <div class="relative">
                    <select id="country" name="country" class="w-full appearance-none bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
                        <option value="">Select Country</option>
                        <option value="Indonesia" {{ old('country', Auth::user()->country ?? '') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                        <option value="United States" {{ old('country', Auth::user()->country ?? '') == 'United States' ? 'selected' : '' }}>United States</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none"><svg class="w-5 h-5 text-black/50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg></div>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-12">
        <h3 class="text-lg font-medium text-black">My email Address</h3>
        <div class="flex items-center gap-4 mt-6">
            <div class="relative flex-shrink-0 w-12 h-12 flex items-center justify-center bg-custom-blue/10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-custom-blue" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
            </div>
            <div>
                <p class="text-base font-normal text-black">{{ Auth::user()->email }}</p>
                <p class="text-base font-normal text-black/50">Joined {{ Auth::user()->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <button class="mt-8 bg-custom-blue/10 text-custom-blue text-base font-normal px-6 py-2.5 rounded-lg hover:bg-custom-blue/20 transition-colors flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            <span>Add Email Address</span>
        </button>
    </div>

</div>

@endsection