@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f9fafb;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h2 {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    p.subtext {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }

    .filter-box {
        background: #f1f5f9;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-row input,
    .filter-row select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        flex: 1;
    }

    .export-buttons {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-danger {
        background: #ef4444;
        color: #fff;
    }

    .btn-success {
        background: #22c55e;
        color: #fff;
    }

    .btn:hover {
        opacity: 0.9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead {
        background: #f1f5f9;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .badge {
        font-size: 12px;
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }

    .badge-update {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-create {
        background: #d1fae5;
        color: #059669;
    }

    .badge-delete {
        background: #fee2e2;
        color: #dc2626;
    }
</style>

<div class="container">
    <h2>Log History</h2>
    <p class="subtext">Audit trail dan riwayat perubahan data</p>

    <div class="export-buttons">
        <a href="#" class="btn btn-danger">Export PDF</a>
        <a href="#" class="btn btn-success">Export Excel</a>
    </div>

    <div class="filter-box">
        <div class="filter-row">
            <input type="text" placeholder="Cari aksi, modul, atau user...">
            <select>
                <option>Semua User</option>
                <option>Admin</option>
                <option>Dosen</option>
                <option>Mahasiswa</option>
            </select>
            <input type="date">
            <input type="text" placeholder="8 dari 8 record" readonly>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Aksi</th>
                <th>Modul</th>
                <th>Data Lama</th>
                <th>Data Baru</th>
                <th>Waktu</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Admin</td>
                <td><span class="badge badge-update">UPDATE</span></td>
                <td>Nilai Mahasiswa</td>
                <td>UTS: 75</td>
                <td>UTS: 80</td>
                <td>2024-01-15 14:30:25</td>
                <td>192.168.1.100</td>
            </tr>
            <tr>
                <td>Admin</td>
                <td><span class="badge badge-create">CREATE</span></td>
                <td>Data Mahasiswa</td>
                <td>-</td>
                <td>NIM: 2024006, Nama: Lisa Andriani</td>
                <td>2024-01-15 13:20:15</td>
                <td>192.168.1.100</td>
            </tr>
            <tr>
                <td>Dosen</td>
                <td><span class="badge badge-update">UPDATE</span></td>
                <td>Bobot Nilai</td>
                <td>UAS: 25%</td>
                <td>UAS: 30%</td>
                <td>2024-01-15 12:15:45</td>
                <td>192.168.1.101</td>
            </tr>
            <!-- Tambahkan baris lainnya sesuai data -->
        </tbody>
    </table>
</div>
@endsection
