@extends('layouts.app')

@section('title', 'Detail Angkatan - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('years.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white shadow-sm hover:shadow-md transition-all duration-200 group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Angkatan</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap angkatan "{{ $year->name }}"</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Angkatan Information Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Informasi Angkatan
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Nama Angkatan -->
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-600 mb-1">Nama Angkatan</p>
                                <p class="text-lg font-bold text-gray-900">{{ $year->name }}</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Tahun Ajaran -->
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-600 mb-1">Tahun Ajaran</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $year->period->name ?? '-' }}
                                </p>
                                @if($year->period)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                                    @if($year->period->status == 'active') bg-green-100 text-green-800
                                    @elseif($year->period->status == 'completed') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($year->period->status == 'active')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Aktif
                                    @elseif($year->period->status == 'completed')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                                        </svg>
                                        Selesai
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Draft
                                    @endif
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Tanggal Dibuat -->
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-600 mb-1">Tanggal Dibuat</p>
                                <p class="text-lg font-bold text-gray-900">{{ $year->created_at->format('d F Y, H:i') }} WIB</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $year->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Terakhir Diperbarui -->
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-600 mb-1">Terakhir Diperbarui</p>
                                <p class="text-lg font-bold text-gray-900">{{ $year->updated_at->format('d F Y, H:i') }} WIB</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $year->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Statistik
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Total Mahasiswa -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-semibold text-blue-700">Total Mahasiswa</p>
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <p class="text-3xl font-bold text-blue-900">{{ $year->students()->count() }}</p>
                                <p class="text-xs text-blue-600 mt-1">mahasiswa terdaftar</p>
                            </div>

                            <!-- Total Kelas -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-semibold text-purple-700">Total Kelas</p>
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <p class="text-3xl font-bold text-purple-900">{{ $year->studentClasses()->count() }}</p>
                                <p class="text-xs text-purple-600 mt-1">kelas tersedia</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Kelas Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Daftar Kelas Terelasi
                        </h2>
                    </div>

                    @if($year->studentClasses->count() > 0)
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($year->studentClasses as $class)
                            <div class="group bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border border-gray-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-700 transition-colors duration-200">
                                            {{ $class->name }}
                                        </h3>
                                    </div>
                                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors duration-200">
                                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 mb-3">
                                    <!-- Total Mahasiswa -->
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Mahasiswa</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $class->students_count ?? 0 }}</p>
                                        </div>
                                    </div>

                                    <!-- Total Enrollments -->
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Enrollments</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $class->enrollments_count ?? 0 }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- View Button -->
                                <a href="{{ route('student_classes.show', $class->id) }}" 
                                   class="block w-full px-4 py-2 bg-indigo-600 text-white text-sm font-semibold text-center rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                                    Lihat Detail Kelas
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <!-- Empty State -->
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Tidak ada kelas</h3>
                        <p class="mt-2 text-sm text-gray-600">Belum ada kelas yang terdaftar untuk angkatan ini.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Aksi Cepat
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Edit Button -->
                        <a href="{{ route('years.edit', $year->id) }}" 
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Angkatan
                        </a>

                        <!-- Back to List Button -->
                        <a href="{{ route('years.index') }}" 
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            Kembali ke Daftar
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus angkatan ini? Tindakan ini tidak dapat dibatalkan!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Angkatan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-100">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Informasi</h3>
                            <p class="text-sm text-gray-700">
                                Tampilan detail ini menampilkan informasi lengkap tentang angkatan "{{ $year->name }}" termasuk semua kelas yang terdaftar dalam angkatan ini.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Summary Stats Card -->
                @if($year->studentClasses->count() > 0)
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Ringkasan Kelas
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Kelas:</span>
                            <span class="text-sm font-bold text-gray-900">{{ $year->studentClasses->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Mahasiswa:</span>
                            <span class="text-sm font-bold text-gray-900">{{ $year->studentClasses->sum('students_count') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Enrollments:</span>
                            <span class="text-sm font-bold text-gray-900">{{ $year->studentClasses->sum('enrollments_count') }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
