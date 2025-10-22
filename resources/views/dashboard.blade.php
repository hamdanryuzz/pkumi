@extends('layouts.app')

@section('title', 'Dashboard - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard</h2>
        <p class="text-base text-gray-500 mt-1">Ringkasan cepat data penilaian mahasiswa.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-start space-y-4">
            <div class="flex items-center justify-between w-full">
                <span class="text-xs font-semibold text-gray-500 uppercase">Total Mahasiswa</span>
                <i class="fas fa-users text-2xl text-gray-400"></i>
            </div>
            <div class="flex items-center w-full">
                <span class="text-4xl font-bold text-gray-800">{{ $totalStudents }}</span>
            </div>
            <a href="{{ route('students.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
                Lihat detail
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-start space-y-4">
            <div class="flex items-center justify-between w-full">
                <span class="text-xs font-semibold text-gray-500 uppercase">Mahasiswa Aktif</span>
                <i class="fas fa-user-check text-2xl text-gray-400"></i>
            </div>
            <div class="flex items-center w-full">
                <span class="text-4xl font-bold text-gray-800">{{ $activeStudents }}</span>
            </div>
            <a href="{{ route('students.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
                Lihat detail
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-start space-y-4">
            <div class="flex items-center justify-between w-full">
                <span class="text-xs font-semibold text-gray-500 uppercase">Nilai Sudah Diinput</span>
                <i class="fas fa-clipboard-list text-2xl text-gray-400"></i>
            </div>
            <div class="flex items-center w-full">
                <span class="text-4xl font-bold text-gray-800">{{ $gradesEntered }}</span>
            </div>
            <a href="{{ route('grades.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
                Lihat detail
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-start space-y-4">
            <div class="flex items-center justify-between w-full">
                <span class="text-xs font-semibold text-gray-500 uppercase">Rata-rata Kelas</span>
                <i class="fas fa-chart-line text-2xl text-gray-400"></i>
            </div>
            <div class="flex items-center w-full">
                <span class="text-4xl font-bold text-gray-800">{{ $averageGrade ? number_format($averageGrade, 2) : '-' }}</span>
            </div>
            <a href="{{ route('reports.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
                Lihat detail
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-lg font-bold text-gray-800">Distribusi Nilai Semester Ini</h6>
            </div>
            <div class="p-6">
                @if($gradeDistribution->count() > 0)
                    <div class="space-y-4">
                        @foreach($gradeDistribution as $grade)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ $grade->letter_grade }}</span>
                            <div class="relative w-full ml-4 h-3 bg-gray-200 rounded-full">
                                @php
                                    $percentage = ($grade->count / $gradesEntered) * 100;
                                @endphp
                                <div class="absolute inset-y-0 left-0 rounded-full {{ 
                                    $grade->letter_grade == 'A+' || $grade->letter_grade == 'A' || $grade->letter_grade == 'A-' ? 'bg-green-500' : (
                                    $grade->letter_grade == 'B+' || $grade->letter_grade == 'B' || $grade->letter_grade == 'B-' ? 'bg-blue-500' : (
                                    $grade->letter_grade == 'C' ? 'bg-yellow-500' : 'bg-red-500'
                                    ))
                                }}" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-500 ml-4 w-12 text-right">{{ number_format($percentage, 1) }}%</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Belum ada data nilai yang diinput.</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-lg font-bold text-gray-800">Aksi Cepat</h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('students.create') }}" 
                        class="flex flex-col items-center justify-center p-4 bg-blue-50 text-blue-600 rounded-lg transition-colors duration-200 hover:bg-blue-100">
                        <i class="fas fa-user-plus text-xl mb-2"></i>
                        <span class="text-sm font-medium">Tambah Mahasiswa</span>
                    </a>
                    <a href="{{ route('grades.index') }}" 
                        class="flex flex-col items-center justify-center p-4 bg-green-50 text-green-600 rounded-lg transition-colors duration-200 hover:bg-green-100">
                        <i class="fas fa-edit text-xl mb-2"></i>
                        <span class="text-sm font-medium">Input Nilai</span>
                    </a>
                
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
