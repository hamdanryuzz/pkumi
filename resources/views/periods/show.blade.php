@extends('layouts.app')

@section('title', 'Detail Tahun Ajaran - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('periods.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('periods.edit', $period) }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('periods.destroy', $period) }}" method="POST" class="inline"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Detail Tahun Ajaran</h2>
            <p class="mt-1 text-sm text-gray-500">Informasi lengkap periode akademik dan semesternya</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Period Information Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Tahun Ajaran</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Tahun Ajaran</label>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ $period->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 uppercase tracking-wider">Kode</label>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ $period->code }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 uppercase tracking-wider">Status</label>
                            <div class="mt-1">
                                @if($period->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span>
                                        Active
                                    </span>
                                @elseif($period->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 mr-1.5 bg-gray-500 rounded-full"></span>
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="w-2 h-2 mr-1.5 bg-yellow-500 rounded-full"></span>
                                        Draft
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <label class="text-xs font-medium text-gray-700 uppercase tracking-wider">Dibuat</label>
                            <p class="mt-1 text-sm text-gray-700">{{ $period->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 uppercase tracking-wider">Terakhir Diupdate</label>
                            <p class="mt-1 text-sm text-gray-700">{{ $period->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="mt-6 bg-white rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Statistik</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Semester</p>
                                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $period->semesters->count() }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Semesters List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Semester</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            {{ $period->semesters->count() }} Semester
                        </span>
                    </div>
                    <div class="p-6">
                        @forelse($period->semesters as $semester)
                        <div class="mb-4 last:mb-0 bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-150">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $semester->name }}</h4>
                                    <p class="mt-1 text-xs text-gray-600">Kode: {{ $semester->code }}</p>
                                    <div class="mt-2">
                                        @if($semester->status === 'active')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 mr-1 bg-green-500 rounded-full"></span>
                                                Active
                                            </span>
                                        @elseif($semester->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="w-1.5 h-1.5 mr-1 bg-gray-500 rounded-full"></span>
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="w-1.5 h-1.5 mr-1 bg-yellow-500 rounded-full"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    <p class="text-xs text-gray-500">Dibuat</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $semester->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-4 text-base font-medium text-gray-900">Belum Ada Semester</h3>
                            <p class="mt-2 text-sm text-gray-500">Tahun ajaran ini belum memiliki semester yang terdaftar.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection