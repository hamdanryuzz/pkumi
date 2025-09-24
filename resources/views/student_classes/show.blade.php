@extends('layouts.app')

@section('title', 'Detail Kelas: ' . $studentClass->name)
@section('page-title', 'Detail Kelas: ' . $studentClass->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Kelas</h3>
                        <p class="text-sm text-gray-500 mt-1">Kelas {{ $studentClass->name }} - Tahun {{ $studentClass->year->name ?? 'N/A' }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('student_classes.edit', $studentClass) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button onclick="confirmDelete({{ $studentClass->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Informasi Kelas</h4>
                        <p><strong>Nama:</strong> {{ $studentClass->name }}</p>
                        <p><strong>Angkatan:</strong> {{ $studentClass->year->name ?? 'N/A' }}</p>
                        <p><strong>Dibuat:</strong> {{ $studentClass->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Statistik</h4>
                        <p><strong>Jumlah Siswa:</strong> {{ $studentClass->students->count() }}</p>
                        <p><strong>Status:</strong> {{ $studentClass->deleted_at ? 'Nonaktif' : 'Aktif' }}</p>
                    </div>
                </div>

                <div class="overflow-hidden shadow rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($studentClass->students as $index => $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->nim }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $student->name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada siswa di kelas ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus kelas ini? Data siswa tidak akan terhapus, tapi kelas akan nonaktif.')) {
        window.location.href = `/student_classes/${id}`;
    }
}
</script>
@endsection