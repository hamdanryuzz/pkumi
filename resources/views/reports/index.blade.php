@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Laporan Nilai Mahasiswa</h2>

        <!-- Filter Section -->
        <form id="filterForm" method="POST" action="{{ route('reports.print-filter') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                
                <!-- Filter Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester_id" id="semester_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">
                                {{ $semester->name }}
                                @if($semester->status === 'active')
                                (Aktif)
                                @endif
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

                <!-- Filter Mata Kuliah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                    <select name="course_id" id="course_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required disabled>
                        <option value="">Pilih Mata Kuliah</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mb-6">
                <button type="button" id="btnFilter" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                    Tampilkan Data
                </button>
                <button type="submit" id="btnPrintFilter" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium" disabled>
                    <i class="fas fa-print mr-2"></i>Print PDF (Filter)
                </button>
            </div>
        </form>

        <!-- Students List Table -->
        <div id="studentsTable" class="hidden">
            <h3 class="text-xl font-semibold mb-4">Daftar Mahasiswa</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Letter Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const semesterId = document.getElementById('semester_id');
    const yearId = document.getElementById('year_id');
    const studentClassId = document.getElementById('student_class_id');
    const courseId = document.getElementById('course_id');
    const btnFilter = document.getElementById('btnFilter');
    const btnPrintFilter = document.getElementById('btnPrintFilter');
    const studentsTable = document.getElementById('studentsTable');
    const studentsTableBody = document.getElementById('studentsTableBody');

    // Event listener untuk angkatan - load kelas
    yearId.addEventListener('change', function() {
        const yearValue = this.value;
        
        studentClassId.disabled = true;
        studentClassId.innerHTML = '<option value="">Pilih Kelas</option>';
        courseId.disabled = true;
        courseId.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
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

    // Event listener untuk load courses
    function loadCourses() {
        const classValue = studentClassId.value;
        const semesterValue = semesterId.value;
        
        if (classValue && semesterValue) {
            courseId.disabled = true;
            courseId.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
            studentsTable.classList.add('hidden');
            btnPrintFilter.disabled = true;

            const url = '{{ route("reports.courses") }}?student_class_id=' + classValue + '&semester_id=' + semesterValue;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.code + ' - ' + item.name;
                            courseId.appendChild(option);
                        });
                        courseId.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data mata kuliah');
                });
        }
    }

    studentClassId.addEventListener('change', loadCourses);
    semesterId.addEventListener('change', function() {
        if (studentClassId.value) {
            loadCourses();
        }
    });

    // Event listener untuk button Tampilkan Data
    btnFilter.addEventListener('click', function(e) {
        e.preventDefault();
        
        console.log('Button clicked'); // Debug
        
        if (!semesterId.value || !yearId.value || !studentClassId.value || !courseId.value) {
            alert('Mohon lengkapi semua filter!');
            return;
        }

        console.log('All filters filled'); // Debug

        // Load students data
        const url = '{{ route("reports.filtered-students") }}?' + new URLSearchParams({
            semester_id: semesterId.value,
            year_id: yearId.value,
            student_class_id: studentClassId.value,
            course_id: courseId.value
        });

        console.log('Fetching URL:', url); // Debug

        fetch(url)
            .then(response => {
                console.log('Response status:', response.status); // Debug
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(students => {
                console.log('Students data:', students); // Debug
                
                // Clear table body
                studentsTableBody.innerHTML = '';
                
                if (students && students.length > 0) {
                    students.forEach((student, index) => {
                        const grade = student.grades && student.grades.length > 0 ? student.grades[0] : null;

                        // Helper function untuk handle null/undefined values dan string "null"
                        const displayValue = (value) => {
                            // Cek jika value adalah null, undefined, empty string, atau string "null"
                            if (value === null || value === undefined || value === '' || value === 'null') {
                                return '-';
                            }
                            return value;
                        };
                        
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${student.nim}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${student.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${grade ? grade.attendance_score : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${grade ? grade.assignment_score : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${grade ? grade.midterm_score : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${grade ? grade.final_score : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${grade ? grade.final_grade : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${grade ? grade.letter_grade : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="printStudent(${student.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                                    <i class="fas fa-print mr-1"></i>Print PDF
                                </button>
                            </td>
                        `;
                        studentsTableBody.appendChild(row);
                    });
                    
                    // Show table and enable print button
                    studentsTable.classList.remove('hidden');
                    btnPrintFilter.disabled = false;
                } else {
                    studentsTableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data mahasiswa</td></tr>';
                    studentsTable.classList.remove('hidden');
                    btnPrintFilter.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data mahasiswa: ' + error.message);
            });
    });
});

// Function untuk print per student
function printStudent(studentId) {
    const semesterId = document.getElementById('semester_id').value;
    const courseId = document.getElementById('course_id').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("reports.print-student") }}';
    form.target = '_blank';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add student_id
    const studentInput = document.createElement('input');
    studentInput.type = 'hidden';
    studentInput.name = 'student_id';
    studentInput.value = studentId;
    form.appendChild(studentInput);
    
    // Add semester_id
    const semesterInput = document.createElement('input');
    semesterInput.type = 'hidden';
    semesterInput.name = 'semester_id';
    semesterInput.value = semesterId;
    form.appendChild(semesterInput);
    
    // Add course_id
    const courseInput = document.createElement('input');
    courseInput.type = 'hidden';
    courseInput.name = 'course_id';
    courseInput.value = courseId;
    form.appendChild(courseInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endsection
