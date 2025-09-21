@extends('layouts.app')

@section('title', 'Detail Mahasiswa - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Detail Mahasiswa</h2>
            <p class="text-base text-gray-500 mt-1">Informasi lengkap mahasiswa dan nilai akademik.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md transition duration-200">
                <i class="fas fa-edit mr-2"></i>
                Edit Data
            </a>
            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-center text-white">
                    <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        {{ substr($student->name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-bold">{{ $student->name }}</h3>
                    <p class="text-blue-100 mt-1">{{ $student->nim }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-3 {{ $student->status == 'active' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
                
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Dasar</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">NIM:</span>
                            <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $student->nim }}</code>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Username:</span>
                            @if($student->username)
                                <code class="bg-blue-50 px-2 py-1 rounded text-sm text-blue-700">{{ $student->username }}</code>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Email:</span>
                            <span class="text-gray-900">{{ $student->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Telepon:</span>
                            <span class="text-gray-900">{{ $student->phone ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Tahun:</span>
                            <span class="text-gray-900">{{ $student->year->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Kelas:</span>
                            <span class="text-gray-900">{{ $student->studentClass->name ?? '-' }}</span>
                        </div>
                    </div>
                    
                    @if($student->address)
                    <div class="mt-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-2">Alamat</h5>
                        <p class="text-gray-600 text-sm bg-gray-50 p-3 rounded">{{ $student->address }}</p>
                    </div>
                    @endif
                    
                    <div class="mt-6 pt-4 border-t border-gray-200 text-xs text-gray-500">
                        <p>Dibuat: {{ $student->created_at->format('d M Y H:i') }}</p>
                        @if($student->updated_at != $student->created_at)
                            <p>Diubah: {{ $student->updated_at->format('d M Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Info & Grades -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Grade Overview Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                        Nilai Akademik
                    </h4>
                </div>
                
                <div class="p-6">
                    @if($student->grade)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ $student->grade->attendance_score ?? '0' }}
                                </div>
                                <div class="text-sm text-gray-600">Kehadiran</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $student->grade->assignment_score ?? '0' }}
                                </div>
                                <div class="text-sm text-gray-600">Tugas</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ $student->grade->exam_score ?? '0' }}
                                </div>
                                <div class="text-sm text-gray-600">Ujian</div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $student->grade->final_grade ? number_format($student->grade->final_grade, 2) : '0' }}
                                </div>
                                <div class="text-sm text-gray-600">Nilai Akhir</div>
                            </div>
                        </div>

                        @if($student->grade->final_grade)
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-lg text-center">
                                <div class="text-4xl font-bold text-indigo-600 mb-2">
                                    {{ $student->grade->letter_grade ?? 'N/A' }}
                                </div>
                                <div class="text-lg text-gray-700">Grade Huruf</div>
                                <div class="text-sm text-gray-500 mt-2">
                                    Nilai Akhir: {{ number_format($student->grade->final_grade, 2) }}
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <i class="fas fa-exclamation-triangle text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-600">Belum ada nilai yang diinput</p>
                                <p class="text-sm text-gray-500">Hubungi admin untuk input nilai</p>
                            </div>
                        @endif

                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h5 class="text-sm font-semibold text-gray-700 mb-3">Detail Penilaian</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kehadiran:</span>
                                    <span class="font-medium">{{ $student->grade->attendance_score ?? '0' }}/100</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tugas:</span>
                                    <span class="font-medium">{{ $student->grade->assignment_score ?? '0' }}/100</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ujian:</span>
                                    <span class="font-medium">{{ $student->grade->exam_score ?? '0' }}/100</span>
                                </div>
                            </div>
                            @if($student->grade->updated_at)
                                <p class="text-xs text-gray-500 mt-3">
                                    Terakhir diubah: {{ $student->grade->updated_at->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-600">Belum ada data nilai untuk mahasiswa ini</p>
                            <p class="text-sm text-gray-500">Data nilai akan muncul setelah diinput oleh admin</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="text-lg font-bold text-gray-800">Aksi Cepat</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('students.edit', $student) }}" class="flex items-center justify-center px-4 py-3 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 rounded-lg transition duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Data
                        </a>
                        <a href="{{ route('grades.index') }}?student={{ $student->id }}" class="flex items-center justify-center px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg transition duration-200">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Kelola Nilai
                        </a>
                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-red-100 hover:bg-red-200 text-red-800 rounded-lg transition duration-200">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection