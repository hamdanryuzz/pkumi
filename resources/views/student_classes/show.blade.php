@extends('layouts.app')

@section('title', 'Detail Kelas: ' . $studentClass->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Detail Kelas</h2>
                    <p class="mt-1 text-sm text-gray-500">Kelas {{ $studentClass->name }} - Tahun {{ $studentClass->year->name ?? 'N/A' }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('student_classes.edit', $studentClass) }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <button onclick="confirmDelete({{ $studentClass->id }})" 
                            class="inline-flex items-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <!-- Informasi Kelas -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kelas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Detail Kelas</h4>
                    <p class="text-sm text-gray-600"><span class="font-medium">Nama:</span> {{ $studentClass->name }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Angkatan:</span> {{ $studentClass->year->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Dibuat:</span> {{ $studentClass->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Statistik</h4>
                    <p class="text-sm text-gray-600"><span class="font-medium">Jumlah Siswa:</span> {{ $studentClass->students->count() }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Status:</span> 
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $studentClass->deleted_at ? 'Nonaktif' : 'Aktif' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                    <div class="text-sm text-gray-500">
                        {{ $studentClass->students->count() }} siswa
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($studentClass->students as $index => $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->nim }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $student->name }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada siswa di kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus kelas ini? Data siswa tidak akan terhapus, tapi kelas akan nonaktif.')) {
        // Hapus kelas dengan method DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/student_classes/${id}`;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection