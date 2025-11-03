@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Laporan KHS Mahasiswa</h2>

        <!-- Filter Section -->
        <form id="filterForm" method="POST" action="{{ route('reports.print-filter') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Filter Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester_id" id="semester_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">
                                {{ $semester->name }}
                                @if($semester->status === 'active') (Aktif) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Angkatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                    <select name="year_id" id="year_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Pilih Angkatan</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="student_class_id" id="student_class_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required disabled>
                        <option value="">Pilih Kelas</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa (opsional)</label>
                <input type="text" id="searchName" name="searchName" placeholder="Nama atau NIM..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mb-6">
            
                <button type="button" id="btnFilter" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                    Tampilkan Data
                </button>
                <button type="submit" id="btnPrintFilter" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium" disabled>
                    <i class="fas fa-print mr-2"></i>Print PDF
                </button>
            </div>
        </form>

       <!-- Students List Section -->
        <div id="studentsTable" class="hidden">
            <h3 class="text-xl font-semibold mb-4">Daftar KHS Mahasiswa</h3>
            <div id="studentsContainer" class="space-y-8"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const semesterId = document.getElementById('semester_id');
    const yearId = document.getElementById('year_id');
    const studentClassId = document.getElementById('student_class_id');
    const btnFilter = document.getElementById('btnFilter');
    const btnPrintFilter = document.getElementById('btnPrintFilter');
    const studentsTable = document.getElementById('studentsTable');
    const studentsContainer = document.getElementById('studentsContainer');


    // Load kelas berdasarkan angkatan
    yearId.addEventListener('change', function() {
        const yearValue = this.value;
        studentClassId.disabled = true;
        studentClassId.innerHTML = '<option value="">Pilih Kelas</option>';
        studentsTable.classList.add('hidden');
        btnPrintFilter.disabled = true;

        if (yearValue) {
            const url = '{{ route("reports.student-classes", ":year") }}'.replace(':year', yearValue);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            studentClassId.appendChild(option);
                        });
                        studentClassId.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data kelas');
                });
        }
    });

    // Tombol tampilkan data
    btnFilter.addEventListener('click', function(e) {
        e.preventDefault();

        if (!semesterId.value || !yearId.value || !studentClassId.value) {
            alert('Mohon lengkapi semua filter!');
            return;
        }

        const searchName = document.getElementById('searchName').value;

        const url = '{{ route("reports.filtered-students") }}?' + new URLSearchParams({
            semester_id: semesterId.value,
            year_id: yearId.value,
            student_class_id: studentClassId.value,
            search: searchName
        });

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('HTTP error ' + response.status);
                return response.json();
            })
            .then(students => {
                studentsContainer.innerHTML = '';

                if (students && students.length > 0) {
                    students.forEach((student, index) => {
                        const studentBlock = document.createElement('div');
                        studentBlock.classList.add('border', 'rounded-lg', 'p-4', 'shadow-sm', 'bg-gray-50');

                        // Header Mahasiswa
                       const header = `
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="text-sm text-gray-600">
                                    <strong>NIM:</strong> ${student.nim} <br>
                                    <strong>Nama:</strong> ${student.name}
                                </p>
                            </div>
                            <button 
                                class="printStudent bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded"
                                data-student-id="${student.id}">
                                <i class="fas fa-file-pdf mr-1"></i> Print PDF
                            </button>
                        </div>
                    `;




                        // Tabel Nilai
                        let table = `
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 bg-white rounded-md">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th>No</th>
                                            <th>Mata Kuliah</th>
                                            <th>Nilai Akhir</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200">
                        `;

                        if (student.grades && student.grades.length > 0) {
                             student.grades.forEach((grade, gIndex) => {
                                table += `
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-700">${gIndex + 1}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700">${grade.course?.name ?? '-'}</td>
                                        <td class="px-3 py-2 text-center text-sm font-semibold text-indigo-600">${grade.final_grade ?? '-'}</td>
                                        <td class="px-3 py-2 text-center text-sm text-gray-700">${grade.letter_grade ?? '-'}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            table += `
                                <tr>
                                    <td colspan="4" class="px-3 py-3 text-center text-sm text-gray-500">Belum ada nilai</td>
                                </tr>
                            `;
                        }

                        table += `</tbody></table></div>`;

                        studentBlock.innerHTML = header + table;
                        studentsContainer.appendChild(studentBlock);
                    });

                    studentsTable.classList.remove('hidden');
                    btnPrintFilter.disabled = false;
                } else {
                    studentsContainer.innerHTML = `
                        <p class="text-center text-gray-500 py-4">Tidak ada data mahasiswa</p>`;
                    studentsTable.classList.remove('hidden');
                    btnPrintFilter.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data mahasiswa: ' + error.message);
            });
    });

    studentsContainer.addEventListener('click', function(e) {
        const btn = e.target.closest('.printStudent');
        if (!btn) return;

        const studentId = btn.dataset.studentId;
        if (!studentId) {
            alert('Data mahasiswa tidak valid.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reports.print-student") }}';
        form.target = '_blank';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="student_id" value="${studentId}">
            <input type="hidden" name="semester_id" value="${semesterId.value}">
        `;

        document.body.appendChild(form);
        form.submit();
        form.remove();
    });



});
</script>
@endsection
