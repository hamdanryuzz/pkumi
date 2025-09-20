@extends('layouts.app')

@section('title', 'Manage Years - Sistem Penilaian PKUMI')

@section('page-title', 'Manage Years')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Manage Years</h2>
        <p class="text-base text-gray-500 mt-1">Kelola data tahun akademik sistem</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Add New Year Button -->
    <div class="mb-6">
        <a href="{{ route('years.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Tahun
        </a>
    </div>

    <!-- Years Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="text-lg font-bold text-gray-800">Daftar Tahun Akademik</h5>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>Data tahun akademik yang tersedia
                    </p>
                </div>
                <div class="text-sm text-gray-600">
                    Total: <span class="font-medium">{{ $years->count() }}</span> tahun
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-1"></i>No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-1"></i>Nama Tahun
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-clock mr-1"></i>Dibuat
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($years as $index => $year)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $year->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>{{ $year->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-2">
                                <!-- View Button -->
                                <a href="{{ route('years.show', $year->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition duration-150">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('years.edit', $year->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded-full hover:bg-yellow-200 transition duration-150">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('years.destroy', $year->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun {{ $year->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full hover:bg-red-200 transition duration-150">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p class="text-lg">Tidak ada data tahun</p>
                            <p class="text-sm mt-2">
                                <a href="{{ route('years.create') }}" class="text-blue-600 hover:text-blue-800">Tambah tahun pertama</a>
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($years->count() > 0)
        <!-- Footer with Statistics -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Total <span class="font-medium">{{ $years->count() }}</span> tahun akademik terdaftar
                </div>
                <div class="flex gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-calendar-check mr-1"></i>Aktif
                    </span>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success message
    const successAlert = document.querySelector('[role="alert"]');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.transition = 'opacity 0.5s';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }, 5000);
    }

    // Confirm delete with more details
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const yearName = this.querySelector('button').closest('tr').querySelector('.text-gray-900').textContent.trim();
            if (!confirm(`Apakah Anda yakin ingin menghapus tahun "${yearName}"?\n\nPerhatian: Tindakan ini tidak dapat dibatalkan.`)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Add hover effect to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>
@endsection
