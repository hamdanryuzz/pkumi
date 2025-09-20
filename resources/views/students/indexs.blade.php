@extends('layouts.app')

@section('title', 'Manage Mahasiswa - Sistem Penilaian PKUMI')
@section('page-title', 'Manage Mahasiswa')

@section('content')
<main class="py-6 px-4 md:px-8">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Manage Mahasiswa</h2>
            <p class="text-base text-gray-500 mt-1">Kelola data mahasiswa, nilai, dan akun login.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition duration-200">
                <i class="fas fa-user-plus mr-2"></i>
                <span>Tambah Mahasiswa</span>
            </a>
            <button onclick="openImportModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-200">
                <i class="fas fa-upload mr-2"></i>
                <span>Import Data</span>
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        
        <div class="flex flex-wrap justify-between items-center px-6 py-4 border-b border-gray-200 gap-4">
            <div class="flex-grow">
                <form action="{{ route('students.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Cari nama atau NIM..." 
                           class="w-full md:w-80 px-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ request('search') }}">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-search mr-2"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-undo-alt mr-2"></i>
                        <span>Reset</span>
                    </a>
                </form>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.export') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                    <i class="fas fa-download mr-2"></i>
                    <span>Export</span>
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->nim }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-xs">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <span>{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $student->email ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $student->phone ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($student->grade && $student->grade->final_grade)
                                    {{ number_format($student->grade->final_grade, 2) }} 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                        {{ $student->grade->letter_grade }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Belum ada nilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('students.show', $student) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition duration-200 p-2 rounded-md hover:bg-gray-100" title="Lihat">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 transition duration-200 p-2 rounded-md hover:bg-gray-100" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-200 p-2 rounded-md hover:bg-gray-100" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium">Tidak ada data mahasiswa</p>
                                    <p class="text-sm">Silakan tambah mahasiswa baru atau import data</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-center mt-6">
                {{ $students->links() }}
            </div>
        </div>
    </div>
    
</main>
@endsection
@section('scripts')
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 transition-opacity duration-300 opacity-0">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-transform duration-300 ease-in-out scale-95">
        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Import Data Mahasiswa</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" id="closeModalBtn">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mt-4">
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel/CSV</label>
                    <input type="file" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                           id="file" 
                           name="file" 
                           accept=".xlsx,.xls,.csv" 
                           required>
                    <p class="mt-1 text-sm text-gray-500">Format: NIM, Nama, Email, Telepon, Status</p>
                </div>
            </div>
            
            <div class="flex items-center justify-end pt-3 border-t border-gray-200 space-x-2">
                <button type="button" 
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-200" 
                        onclick="closeImportModal()">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-200">
                    Import
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.remove('hidden', 'opacity-0');
    modal.classList.add('opacity-100');
    modal.querySelector('.relative').classList.remove('scale-95');
    modal.querySelector('.relative').classList.add('scale-100');
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    const modalContent = modal.querySelector('.relative');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});
</script>
@endsection