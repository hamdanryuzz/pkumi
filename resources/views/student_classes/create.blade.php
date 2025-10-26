@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Tambah Kelas</h1>
    <p class="text-gray-600 mb-6">Buat kelas baru untuk siswa</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('student_classes.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="year_id" class="block text-gray-700 font-medium mb-2">
                    Tahun Ajaran<span class="text-red-500">*</span>
                </label>
                <select name="year_id" id="year_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($years as $year)
                        <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
                @error('year_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="class_program" class="block text-gray-700 font-medium mb-2">
                    Program Kelas<span class="text-red-500">*</span>
                </label>
                <select name="class_program" id="class_program" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Program</option>
                    <option value="S2 PKU" {{ old('class_program') == 'S2 PKU' ? 'selected' : '' }}>S2 PKU</option>
                    <option value="S2 PKUP" {{ old('class_program') == 'S2 PKUP' ? 'selected' : '' }}>S2 PKUP</option>
                    <option value="S3 PKU" {{ old('class_program') == 'S3 PKU' ? 'selected' : '' }}>S3 PKU</option>
                </select>
                @error('class_program')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="class_suffix" class="block text-gray-700 font-medium mb-2">Kelas</label>
                <select name="class_suffix" id="class_suffix" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Kelas</option>
                    <option value="A" {{ old('class_suffix') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('class_suffix') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('class_suffix') == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ old('class_suffix') == 'D' ? 'selected' : '' }}>D</option>
                </select>
                <p class="text-gray-500 text-sm mt-1">Boleh dikosongkan jika kelas tidak memiliki suffix.</p>
                @error('class_suffix')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('student_classes.index') }}" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
