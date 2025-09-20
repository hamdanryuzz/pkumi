@extends('layouts.app')

@section('title', 'Detail Tahun - Sistem Penilaian PKUMI')

@section('page-title', 'Detail Tahun')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Detail Tahun Akademik</h2>
                <p class="text-base text-gray-500 mt-1">Informasi lengkap tahun akademik "{{ $year->name }}"</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('years.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('years.edit', $year->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200 inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-800">{{ $year->name }}</h5>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Tahun Akademik
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-file-alt mr-2 text-gray-500"></i>Informasi Dasar
                                </h6>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                        <span class="text-sm text-gray-600">ID Tahun:</span>
                                        <span class="text-sm font-medium text-gray-900">#{{ $year->id }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                                        <span class="text-sm text-gray-600">Nama Tahun:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $year->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-sm text-gray-600">Status:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Information -->
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>Timeline
                                </h6>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-blue-200 last:border-b-0">
                                        <span class="text-sm text-gray-600">Dibuat pada:</span>
                                        <div class="text-right">
                                            <span class="text-sm font-medium text-gray-900">{{ $year->created_at->format('d M Y') }}</span>
                                            <div class="text-xs text-gray-500">{{ $year->created_at->format('H:i') }} WIB</div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-blue-200 last:border-b-0">
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

                    <!-- Additional Stats (Mock data for demonstration) -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm">Mahasiswa Terdaftar</p>
                                    <p class="text-2xl font-bold">0</p>
                                </div>
                                <div class="h-10 w-10 bg-blue-400 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm">Kelas Aktif</p>
                                    <p class="text-2xl font-bold">0</p>
                                </div>
                                <div class="h-10 w-10 bg-green-400 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm">Nilai Tersimpan</p>
                                    <p class="text-2xl font-bold">0</p>
                                </div>
                                <div class="h-10 w-10 bg-purple-400 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h6 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>Quick Actions
                    </h6>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('years.edit', $year->id) }}" class="w-full bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg hover:bg-yellow-100 transition duration-200 inline-flex items-center">
                        <i class="fas fa-edit mr-3"></i>Edit Tahun Akademik
                    </a>
                    
                    <button onclick="copyYearInfo()" class="w-full bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg hover:bg-blue-100 transition duration-200 inline-flex items-center">
                        <i class="fas fa-copy mr-3"></i>Salin Informasi
                    </button>
                    
                    <form action="{{ route('years.destroy', $year->id) }}" method="POST" id="deleteForm" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()" class="w-full bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg hover:bg-red-100 transition duration-200 inline-flex items-center">
                            <i class="fas fa-trash mr-3"></i>Hapus Tahun
                        </button>
                    </form>
                </div>
            </div>

            <!-- Year Preview -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h6 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-eye mr-2 text-green-500"></i>Preview
                    </h6>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                            <span class="text-blue-600 font-bold text-xl">{{ substr($year->name, -2) }}</span>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $year->name }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Tahun Akademik</p>
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600">Tampilan tahun ini di sistem</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity (Mock) -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h6 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-history mr-2 text-purple-500"></i>Aktivitas Terkait
                    </h6>
                </div>
                <div class="p-6">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-calendar-times text-3xl mb-3"></i>
                        <p class="text-sm">Belum ada aktivitas</p>
                        <p class="text-xs mt-1">Data mahasiswa dan nilai akan muncul di sini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // E for edit
        if (e.key === 'e' || e.key === 'E') {
            if (!e.ctrlKey && !e.altKey && !e.shiftKey) {
                const activeElement = document.activeElement;
                if (activeElement.tagName !== 'INPUT' && activeElement.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    window.location.href = "{{ route('years.edit', $year->id) }}";
                }
            }
        }
        
        // Backspace or Escape to go back
        if (e.key === 'Escape' || (e.key === 'Backspace' && !e.ctrlKey)) {
            const activeElement = document.activeElement;
            if (activeElement.tagName !== 'INPUT' && activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                window.location.href = "{{ route('years.index') }}";
            }
        }
    });

    // Show keyboard shortcuts on load
    setTimeout(() => {
        showNotification('Keyboard shortcuts: E = Edit, Esc = Kembali', 'info', 3000);
    }, 1000);
});

function copyYearInfo() {
    const yearInfo = `Tahun Akademik: {{ $year->name }}\nID: #{{ $year->id }}\nDibuat: {{ $year->created_at->format('d M Y, H:i') }}\nTerakhir diubah: {{ $year->updated_at->format('d M Y, H:i') }}`;
    
    navigator.clipboard.writeText(yearInfo).then(() => {
        showNotification('Informasi tahun berhasil disalin ke clipboard', 'success');
    }).catch(() => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = yearInfo;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Informasi tahun berhasil disalin', 'success');
    });
}

function confirmDelete() {
    const yearName = "{{ $year->name }}";
    
    if (confirm(`Apakah Anda yakin ingin menghapus tahun akademik "${yearName}"?\n\nPerhatian:\n• Tindakan ini tidak dapat dibatalkan\n• Semua data yang terkait akan terpengaruh\n• Pastikan tidak ada mahasiswa atau nilai yang masih menggunakan tahun ini`)) {
        // Additional confirmation for safety
        const confirmText = prompt(`Untuk mengkonfirmasi, ketik nama tahun "${yearName}" di bawah ini:`);
        
        if (confirmText === yearName) {
            document.getElementById('deleteForm').submit();
        } else if (confirmText !== null) {
            showNotification('Nama tahun tidak cocok. Penghapusan dibatalkan.', 'error');
        }
    }
}

function showNotification(message, type = 'info', duration = 5000) {
    const alertClasses = {
        'success': 'bg-green-100 border-green-400 text-green-700',
        'error': 'bg-red-100 border-red-400 text-red-700',
        'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info': 'bg-blue-100 border-blue-400 text-blue-700'
    };
    
    const iconClasses = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${alertClasses[type]} px-4 py-3 rounded-lg border shadow-lg z-50 max-w-sm`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${iconClasses[type]} mr-2"></i>
            <span class="text-sm">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-lg leading-none">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transition = 'opacity 0.3s ease';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }
    }, duration);
    
    // Slide in animation
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
        notification.style.transition = 'transform 0.3s ease';
        notification.style.transform = 'translateX(0)';
    }, 10);
}

// Auto-refresh timestamps every minute
setInterval(() => {
    // This would typically update relative timestamps
    // For now, just a placeholder for future enhancement
}, 60000);
</script>
@endsection
