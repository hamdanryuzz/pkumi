@extends('layouts.app')

@section('title', 'Detail Enrollment - Sistem Penilaian PKUMI')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Enrollment</h1>
        <p class="text-gray-600">Informasi lengkap enrollment {{ $enrollment->studentClass->name }}</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Enrollment Information Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h5 class="text-lg font-semibold text-white">Informasi Enrollment</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Class -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Kelas</label>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    {{ strtoupper(substr($enrollment->studentClass->name, 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold text-gray-900">{{ $enrollment->studentClass->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $enrollment->studentClass->year->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Course -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Mata Kuliah</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $enrollment->course->name }}</p>
                            <p class="text-sm text-gray-500">{{ $enrollment->course->code }}</p>
                        </div>

                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Semester</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $enrollment->semester->name }}</p>
                            <p class="text-sm text-gray-500">{{ $enrollment->semester->code }}</p>
                        </div>

                        <!-- Enrollment Date -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Tanggal Pendaftaran</label>
                            <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M Y') }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                            @php
                            $statusConfig = [
                                'enrolled' => ['bg-green-100 text-green-800', 'Terdaftar', 'fas fa-check-circle'],
                                'dropped' => ['bg-red-100 text-red-800', 'Dropped', 'fas fa-times-circle'],
                                'completed' => ['bg-blue-100 text-blue-800', 'Selesai', 'fas fa-flag-checkered']
                            ];
                            $config = $statusConfig[$enrollment->status] ?? $statusConfig['enrolled'];
                            @endphp
                            <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-full {{ $config[0] }}">
                                <i class="{{ $config[2] }} mr-2"></i>
                                {{ $config[1] }}
                            </span>
                        </div>

                        <!-- Created At -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Dibuat Pada</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $enrollment->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students in Class -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h5 class="text-lg font-semibold text-white">Mahasiswa dalam Kelas</h5>
                </div>
                <div class="p-6">
                    @if($enrollment->studentClass->students->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NIM</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($enrollment->studentClass->students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $student->nim }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $student->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                                            {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-4 text-sm text-gray-600">Total: <span class="font-semibold">{{ $enrollment->studentClass->students->count() }}</span> mahasiswa</p>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada mahasiswa dalam kelas ini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                    <h5 class="text-lg font-semibold text-white">Aksi Cepat</h5>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('enrollments.edit', $enrollment->id) }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200 inline-flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Enrollment
                    </a>

                    @if($enrollment->status === 'enrolled')
                    <form action="{{ route('enrollments.drop', $enrollment->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" onclick="return confirm('Yakin ingin mengubah status menjadi dropped?')" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200 inline-flex items-center justify-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            Drop Enrollment
                        </button>
                    </form>
                    @elseif($enrollment->status === 'dropped')
                    <form action="{{ route('enrollments.reactivate', $enrollment->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" onclick="return confirm('Yakin ingin mengaktifkan kembali enrollment ini?')" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200 inline-flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Aktifkan Kembali
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('enrollments.destroy', $enrollment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus enrollment ini? Tindakan ini tidak dapat dibatalkan.')" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200 inline-flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Enrollment
                        </button>
                    </form>

                    <a href="{{ route('enrollments.index') }}" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200 inline-flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Semester Info -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <h5 class="text-lg font-semibold text-white">Info Semester</h5>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Periode</label>
                        <p class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($enrollment->semester->start_date)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($enrollment->semester->end_date)->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status Semester</label>
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                            {{ $enrollment->semester->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($enrollment->semester->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($enrollment->semester->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Periode Pendaftaran</label>
                        <p class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($enrollment->semester->enrollment_start_date)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($enrollment->semester->enrollment_end_date)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
