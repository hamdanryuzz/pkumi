@extends('layouts.app')

@section('title', 'Laporan - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Laporan Rekap Nilai</h2>
        <p class="text-base text-gray-500 mt-1">Kelola dan pantau nilai mahasiswa secara keseluruhan</p>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="flex items-center px-6 py-4 border-b border-gray-200">
            <i class="fas fa-filter text-gray-400 mr-2"></i>
            <h3 class="text-lg font-bold text-gray-800">Filter Data</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex flex-col flex-1 min-w-[160px] gap-1">
                    <label for="angkatan" class="block text-sm font-medium text-gray-700">Angkatan</label>
                    <select name="angkatan" id="angkatan" class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Pilih Angkatan...</option>
                        {{-- Contoh data angkatan, sesuaikan dengan data lo --}}
                        <option value="2024" {{ request('angkatan') == '2024' ? 'selected' : '' }}>2024</option>
                        <option value="2023" {{ request('angkatan') == '2023' ? 'selected' : '' }}>2023</option>
                    </select>
                </div>

                <div class="flex flex-col flex-1 min-w-[160px] gap-1">
                    <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                    <select name="kelas" id="kelas" class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Pilih Kelas...</option>
                        {{-- Contoh data kelas, sesuaikan dengan data lo --}}
                        <option value="A" {{ request('kelas') == 'A' ? 'selected' : '' }}>Kelas A</option>
                        <option value="B" {{ request('kelas') == 'B' ? 'selected' : '' }}>Kelas B</option>
                    </select>
                </div>

                <div class="flex flex-col flex-1 min-w-[160px] gap-1">
                    <label for="matakuliah" class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                    <select name="matakuliah" id="matakuliah" class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Pilih Mata Kuliah...</option>
                        {{-- Contoh data matakuliah, sesuaikan dengan data lo --}}
                        <option value="Pemrograman Web" {{ request('matakuliah') == 'Pemrograman Web' ? 'selected' : '' }}>Pemrograman Web</option>
                    </select>
                </div>
                
                <button type="submit" class="inline-flex items-center px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                    <i class="fas fa-undo-alt mr-2"></i>
                    Reset
                </a>
            </form>
            
            <hr class="my-6 border-gray-200">
            
            <h4 class="text-base font-bold text-gray-800 mb-4">Cetak Laporan</h4>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('reports.exportPdf') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-md shadow-sm hover:bg-red-700 transition duration-200">
                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                </a>
                <a href="{{ route('reports.exportExcel') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-md shadow-sm hover:bg-green-700 transition duration-200">
                    <i class="fas fa-file-excel mr-2"></i>Download Excel
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Data Nilai Mahasiswa</h3>
            <p class="text-sm text-gray-500 mt-1">Pemrograman Web - Kelas A - Angkatan 2024</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Presensi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Huruf</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->nim }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $student->grade->attendance_score ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $student->grade->assignment_score ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $student->grade->midterm_score ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $student->grade->final_score ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                @if($student->grade && $student->grade->final_grade)
                                    <strong class="font-bold">{{ number_format($student->grade->final_grade, 2) }}</strong>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                @if($student->grade && $student->grade->letter_grade)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $student->grade->letter_grade }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('reports.studentCard', $student->id) }}" 
                                   class="inline-flex items-center p-2 rounded-md hover:bg-gray-100 transition-colors duration-200 text-blue-500" title="Cetak Kartu Nilai">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium">Tidak ada data mahasiswa</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 mb-3">
                <i class="fas fa-users text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $students->count() }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">Total Mahasiswa</p>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center text-center">
            @php
                $completedGrades = $students->filter(fn($s) => $s->grade && $s->grade->final_grade);
            @endphp
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center text-green-600 mb-3">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $completedGrades->count() }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">Sudah Dinilai</p>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h6 class="text-lg font-bold text-gray-800 mb-3">Bobot Penilaian</h6>
            <table class="w-full text-sm">
                <tbody>
                    <tr>
                        <td class="py-1 text-gray-600">Presensi</td>
                        <td class="py-1 text-right font-medium text-gray-800">{{ $weights->attendance_weight }}%</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">Tugas</td>
                        <td class="py-1 text-right font-medium text-gray-800">{{ $weights->assignment_weight }}%</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">UTS</td>
                        <td class="py-1 text-right font-medium text-gray-800">{{ $weights->midterm_weight }}%</td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600">UAS</td>
                        <td class="py-1 text-right font-medium text-gray-800">{{ $weights->final_weight }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h6 class="text-lg font-bold text-gray-800 mb-3">Distribusi Nilai</h6>
            <table class="w-full text-sm">
                <tbody>
                    @php
                        $gradeDistribution = $completedGrades->groupBy(fn($s) => $s->grade->letter_grade);
                    @endphp
                    @foreach(['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C', 'D', 'E'] as $grade)
                        @if($gradeDistribution->has($grade) || $grade === 'A+' || $grade === 'A' || $grade === 'A-' || $grade === 'B+' || $grade === 'B' || $grade === 'B-' || $grade === 'C' || $grade === 'D' || $grade === 'E')
                            <tr>
                                <td class="py-1 text-sm text-gray-600">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                        $grade == 'A+' || $grade == 'A' || $grade == 'A-' ? 'bg-green-100 text-green-800' : (
                                        $grade == 'B+' || $grade == 'B' || $grade == 'B-' ? 'bg-blue-100 text-blue-800' : (
                                        $grade == 'C' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                                        ))
                                    }}">
                                        {{ $grade }}
                                    </span>
                                </td>
                                <td class="py-1 text-right text-gray-800 font-medium">
                                    {{ $gradeDistribution->get($grade, collect())->count() }} mahasiswa
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</main>
@endsection