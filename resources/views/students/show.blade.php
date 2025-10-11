@extends('layouts.app')

@section('title', 'Detail Mahasiswa - ' . $student->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('students.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Mahasiswa</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Mahasiswa</h1>
        <p class="text-gray-600">Informasi lengkap dan nilai akademik mahasiswa</p>
    </div>

    <!-- Student Profile Header with Photo -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
            <!-- Student Photo -->
            <div class="flex-shrink-0">
                @if($student->image)
                    <img src="{{ asset('storage/students/' . $student->image) }}" 
                         alt="{{ $student->name }}" 
                         class="w-32 h-32 lg:w-40 lg:h-40 object-cover rounded-xl border-4 border-blue-500 shadow-lg ring-4 ring-blue-100">
                @else
                    <div class="w-32 h-32 lg:w-40 lg:h-40 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl border-4 border-blue-500 shadow-lg ring-4 ring-blue-100 flex items-center justify-center">
                        <span class="text-white text-5xl lg:text-6xl font-bold">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Student Info -->
            <div class="flex-1 w-full">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ $student->name }}</h2>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                NIM: {{ $student->nim }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold 
                                {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('students.edit', $student) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('students.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Student Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Username</p>
                            <p class="text-gray-900 font-semibold">{{ $student->username }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Email</p>
                            <p class="text-gray-900 font-semibold">{{ $student->email ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Kelas</p>
                            <p class="text-gray-900 font-semibold">{{ $student->studentClass->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Angkatan</p>
                            <p class="text-gray-900 font-semibold">{{ $student->year->name ?? '-' }}</p>
                        </div>
                    </div>

                    @if($student->phone)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Telepon</p>
                            <p class="text-gray-900 font-semibold">{{ $student->phone }}</p>
                        </div>
                    </div>
                    @endif

                    @if($student->address)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg {{ $student->phone ? '' : 'md:col-span-2' }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Alamat</p>
                            <p class="text-gray-900 font-semibold">{{ $student->address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Records Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Riwayat Nilai Akademik</h3>
            <!-- Filter Section -->
            <form method="GET" action="{{ route('students.show', $student) }}" class="flex gap-2">
                <select name="semester_filter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ request('semester_filter') == $sem->id ? 'selected' : '' }}>
                            {{ $sem->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari mata kuliah..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition duration-200">
                    Filter
                </button>
                @if(request('search') || request('semester_filter'))
                    <a href="{{ route('students.show', $student) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition duration-200">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Academic Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Mata Kuliah</th>
                        <th class="px-6 py-3">Semester</th>
                        <th class="px-6 py-3 text-center">SKS</th>
                        <th class="px-6 py-3 text-center">Kehadiran</th>
                        <th class="px-6 py-3 text-center">Tugas</th>
                        <th class="px-6 py-3 text-center">UTS</th>
                        <th class="px-6 py-3 text-center">UAS</th>
                        <th class="px-6 py-3 text-center">Nilai Akhir</th>
                        <th class="px-6 py-3 text-center">Grade</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    @if(isset($enrollment->course))
                                        {{ $enrollment->course->name ?? '-' }}
                                    @else
                                        {{ $enrollment->course_name ?? '-' }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if(isset($enrollment->course))
                                        {{ $enrollment->course->code ?? '-' }}
                                    @else
                                        {{ $enrollment->course_code ?? '-' }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if(isset($enrollment->semester))
                                    {{ $enrollment->semester->name ?? '-' }}
                                @else
                                    {{ $enrollment->semester_name ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($enrollment->course))
                                    {{ $enrollment->course->sks ?? '-' }}
                                @else
                                    {{ $enrollment->credits ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($enrollment->grade))
                                    {{ $enrollment->grade->attendance_score ?? '-' }}
                                @else
                                    {{ $enrollment->attendance_score ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($enrollment->grade))
                                    {{ $enrollment->grade->assignment_score ?? '-' }}
                                @else
                                    {{ $enrollment->assignment_score ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($enrollment->grade))
                                    {{ $enrollment->grade->midterm_score ?? '-' }}
                                @else
                                    {{ $enrollment->midterm_score ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($enrollment->grade))
                                    {{ $enrollment->grade->final_score ?? '-' }}
                                @else
                                    {{ $enrollment->final_score ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-semibold">
                                @if(isset($enrollment->grade) && $enrollment->grade && $enrollment->grade->final_grade)
                                    {{ $enrollment->grade->final_grade }}
                                @elseif(isset($enrollment->final_grade) && $enrollment->final_grade)
                                    {{ $enrollment->final_grade }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($enrollment->grade) && $enrollment->grade && $enrollment->grade->letter_grade)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($enrollment->grade->letter_grade == 'A+') bg-green-100 text-green-800
                                        @elseif($enrollment->grade->letter_grade == 'A') bg-green-100 text-green-800
                                        @elseif($enrollment->grade->letter_grade == 'A-') bg-green-100 text-green-800
                                        @elseif($enrollment->grade->letter_grade == 'B+') bg-blue-100 text-blue-800
                                        @elseif($enrollment->grade->letter_grade == 'B') bg-blue-100 text-blue-800
                                        @elseif($enrollment->grade->letter_grade == 'B-') bg-blue-100 text-blue-800
                                        @elseif($enrollment->grade->letter_grade == 'C') bg-yellow-100 text-yellow-800
                                        @elseif($enrollment->grade->letter_grade == 'D') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $enrollment->grade->letter_grade }}
                                    </span>
                                @elseif(isset($enrollment->letter_grade) && $enrollment->letter_grade)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($enrollment->letter_grade == 'A+') bg-green-100 text-green-800
                                        @elseif($enrollment->letter_grade == 'A') bg-green-100 text-green-800
                                        @elseif($enrollment->letter_grade == 'A-') bg-green-100 text-green-800
                                        @elseif($enrollment->letter_grade == 'B+') bg-blue-100 text-blue-800
                                        @elseif($enrollment->letter_grade == 'B') bg-blue-100 text-blue-800
                                        @elseif($enrollment->letter_grade == 'B-') bg-blue-100 text-blue-800
                                        @elseif($enrollment->letter_grade == 'C') bg-yellow-100 text-yellow-800
                                        @elseif($enrollment->letter_grade == 'D') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $enrollment->letter_grade }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($enrollment->grade_id) ? $enrollment->grade_id : (isset($enrollment->grade) ? $enrollment->grade : false))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Sudah Dinilai
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-700 mb-1">
                                        @if(request('search') || request('semester_filter'))
                                            Tidak Ada Data Ditemukan
                                        @else
                                            Belum Ada Enrollment
                                        @endif
                                    </h3>
                                    <p class="text-sm">
                                        @if(request('search') || request('semester_filter'))
                                            Tidak ada mata kuliah yang sesuai dengan filter yang dipilih.
                                        @else
                                            Mahasiswa belum melakukan pendaftaran mata kuliah.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Academic Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total SKS</p>
                    <h3 class="text-4xl font-bold">{{ $totalSKS }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">IPK</p>
                    <h3 class="text-4xl font-bold">{{ $ipk }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
