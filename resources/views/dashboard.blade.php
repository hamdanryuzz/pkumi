@extends('layouts.app')

@section('title', 'Dashboard - Sistem Penilaian PKUMI')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg border-l-4 border-blue-500 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-blue-600 uppercase mb-1">
                    Total Mahasiswa
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</div>
            </div>
            <div class="text-gray-300">
                <i class="fas fa-users fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg border-l-4 border-green-500 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-green-600 uppercase mb-1">
                    Mahasiswa Aktif
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $activeStudents }}</div>
            </div>
            <div class="text-gray-300">
                <i class="fas fa-user-check fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg border-l-4 border-cyan-500 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-cyan-600 uppercase mb-1">
                    Nilai Sudah Diinput
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $gradesEntered }}</div>
            </div>
            <div class="text-gray-300">
                <i class="fas fa-clipboard-list fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg border-l-4 border-yellow-500 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-yellow-600 uppercase mb-1">
                    Rata-rata Kelas
                </div>
                <div class="text-2xl font-bold text-gray-800">
                    {{ $averageGrade ? number_format($averageGrade, 2) : '-' }}
                </div>
            </div>
            <div class="text-gray-300">
                <i class="fas fa-chart-line fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-lg font-bold text-blue-600">Distribusi Nilai Huruf</h6>
        </div>
        <div class="p-6">
            @if($gradeDistribution->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Nilai Huruf</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Jumlah Mahasiswa</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradeDistribution as $grade)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $grade->letter_grade }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-900">{{ $grade->count }}</td>
                                <td class="py-3 px-4 text-gray-900">{{ number_format(($grade->count / $gradesEntered) * 100, 1) }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Belum ada data nilai yang diinput.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-lg font-bold text-blue-600">Aksi Cepat</h6>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <a href="{{ route('students.create') }}" 
                   class="flex items-center justify-center w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Mahasiswa Baru
                </a>
                <a href="{{ route('grades.index') }}" 
                   class="flex items-center justify-center w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i>Input Nilai Mahasiswa
                </a>
                <a href="{{ route('reports.exportPdf') }}" 
                   class="flex items-center justify-center w-full px-4 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                    <i class="fas fa-file-pdf mr-2"></i>Cetak Laporan PDF
                </a>
                <a href="{{ route('reports.exportExcel') }}" 
                   class="flex items-center justify-center w-full px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                    <i class="fas fa-file-excel mr-2"></i>Export ke Excel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection