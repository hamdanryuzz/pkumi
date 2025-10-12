@extends('layouts.app')

@section('title', 'Detail Angkatan - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Detail Angkatan</h2>
                    <p class="mt-1 text-sm text-gray-500">Informasi lengkap angkatan "{{ $year->name }}"</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('years.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('years.edit', $year->id) }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <!-- Main Information Card -->
        <div class="bg-white rounded-lg border border-gray-200">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $year->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Angkatan</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Informasi Dasar</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                    <span class="text-sm text-gray-600">ID Angkatan:</span>
                                    <span class="text-sm font-medium text-gray-900">#{{ $year->id }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                    <span class="text-sm text-gray-600">Nama Angkatan:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $year->name }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Aktif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Information -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Timeline</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                    <span class="text-sm text-gray-600">Dibuat pada:</span>
                                    <div class="text-right">
                                        <span class="text-sm font-medium text-gray-900">{{ $year->created_at->format('d M Y') }}</span>
                                        <div class="text-xs text-gray-500">{{ $year->created_at->format('H:i') }} WIB</div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                    <span class="text-sm text-gray-600">Terakhir diubah:</span>
                                    <div class="text-right">
                                        <span class="text-sm font-medium text-gray-900">{{ $year->updated_at->format('d M Y') }}</span>
                                        <div class="text-xs text-gray-500">{{ $year->updated_at->format('H:i') }} WIB</div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-600">Umur data:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $year->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Year Preview -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Preview</h4>
                    <div class="text-center">
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                            <span class="text-blue-600 font-bold text-xl">{{ substr($year->name, -2) }}</span>
                        </div>
                        <h5 class="text-lg font-semibold text-gray-900">{{ $year->name }}</h5>
                        <p class="text-sm text-gray-500 mt-1">Angkatan</p>
                        <div class="mt-4 p-3 bg-gray-100 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600">Tampilan angkatan ini di sistem</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyYearInfo() {
    const text = `Angkatan: {{ $year->name }}\nID: {{ $year->id }}\nDibuat: {{ $year->created_at->format('d M Y H:i') }}`;
    navigator.clipboard.writeText(text).then(() => {
        alert('Informasi angkatan berhasil disalin!');
    });
}
</script>
@endsection