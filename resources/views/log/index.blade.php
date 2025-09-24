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
            <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Export PDF</a>
            <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Export Excel</a>
        </div>

        <!-- Log Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Modul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data Lama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data Baru</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $log->user }}</td>
                            <td class="px-6 py-4">
                                @if($log->action === 'CREATE')
                                    <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">CREATE</span>
                                @elseif($log->action === 'UPDATE')
                                    <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">UPDATE</span>
                                @elseif($log->action === 'DELETE')
                                    <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">DELETE</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $log->module }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->old_data }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->new_data }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->ip_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</main>
@endsection
