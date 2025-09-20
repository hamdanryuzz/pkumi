@extends('layouts.app')

@section('title', 'Log History')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Log History</h2>
        <p class="text-base text-gray-500 mt-1">
            Riwayat aktivitas dan perubahan yang terjadi di sistem.
        </p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Export Buttons -->
        <div class="flex justify-end gap-2 mb-4">
            <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg transition-colors duration-200 hover:bg-red-700">Export PDF</a>
            <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition-colors duration-200 hover:bg-green-700">Export Excel</a>
        </div>

        <!-- Filter Box -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <input type="text" placeholder="Cari aksi, modul, atau user..." class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option>Semua User</option>
                    <option>Admin</option>
                    <option>Dosen</option>
                    <option>Mahasiswa</option>
                </select>
                <input type="date" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <input type="text" placeholder="8 dari 8 record" readonly class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none bg-gray-100 text-gray-500">
            </div>
        </div>

        <!-- Log Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Modul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data Lama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data Baru</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Admin</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">UPDATE</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Nilai Mahasiswa</td>
                        <td class="px-6 py-4 text-sm text-gray-500">UTS: 75</td>
                        <td class="px-6 py-4 text-sm text-gray-500">UTS: 80</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15 14:30:25</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">192.168.1.100</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Admin</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">CREATE</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Data Mahasiswa</td>
                        <td class="px-6 py-4 text-sm text-gray-500">-</td>
                        <td class="px-6 py-4 text-sm text-gray-500">NIM: 2024006, Nama: Lisa Andriani</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15 13:20:15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">192.168.1.100</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Dosen</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">UPDATE</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Bobot Nilai</td>
                        <td class="px-6 py-4 text-sm text-gray-500">UAS: 25%</td>
                        <td class="px-6 py-4 text-sm text-gray-500">UAS: 30%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15 12:15:45</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">192.168.1.101</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection