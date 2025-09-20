@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Generate Student Account</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('students.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year_id" class="form-label">Year <span class="text-danger">*</span></label>
                            <select class="form-select @error('year_id') is-invalid @enderror" 
                                    id="year_id" name="year_id" required>
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('year_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_class_id" class="form-label">Student Class <span class="text-danger">*</span></label>
                            <select class="form-select @error('student_class_id') is-invalid @enderror" 
                                    id="student_class_id" name="student_class_id" required 
                                    {{ old('year_id') ? '' : 'disabled' }}>
                                <option value="">Select Class</option>
                            </select>
                            @error('student_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email (Optional)</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        
                        <div class="mb-3">
                            <label for="generated_username" class="form-label">Preview Username</label>
                            <input type="text" class="form-control" id="generated_username" readonly>
                            <small class="form-text text-muted">Username will be auto-generated from name</small>
                        </div>

                        <div class="mb-3">
                            <label for="generated_password" class="form-label">Preview Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="generated_password" readonly>
                                <button class="btn btn-outline-secondary" type="button" id="generatePasswordBtn">
                                    Preview Password
                                </button>
                            </div>
                            <small class="form-text text-muted">8 characters: uppercase, lowercase, number, and symbol</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Generate Student Account</button>
                            <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to Students List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const yearSelect = document.getElementById('year_id');
    const classSelect = document.getElementById('student_class_id');
    const usernameInput = document.getElementById('generated_username');
    const passwordInput = document.getElementById('generated_password');
    const generatePasswordBtn = document.getElementById('generatePasswordBtn');

    // Auto generate username preview when name changes
    nameInput.addEventListener('input', function() {
        const username = this.value.toLowerCase().replace(/\s+/g, '.');
        usernameInput.value = username;
    });

    // Load student classes when year changes
    yearSelect.addEventListener('change', function() {
        const yearId = this.value;
        console.log('Year selected:', yearId); // Debug
        
        classSelect.innerHTML = '<option value="">Select Class</option>';
        classSelect.disabled = !yearId;

        if (yearId) {
            // Gunakan URL yang benar
            const url = `/api/student-classes/${yearId}`;
            console.log('Fetching from URL:', url); // Debug
            
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status); // Debug
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Student classes data:', data); // Debug
                    
                    if (data.length === 0) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No classes available for this year';
                        classSelect.appendChild(option);
                    } else {
                        data.forEach(studentClass => {
                            const option = document.createElement('option');
                            option.value = studentClass.id;
                            option.textContent = studentClass.name;
                            classSelect.appendChild(option);
                        });
                    }
                    
                    // Restore selected value if exists (for old input)
                    const oldClassId = '{{ old('student_class_id') }}';
                    if (oldClassId) {
                        classSelect.value = oldClassId;
                    }
                })
                .catch(error => {
                    console.error('Error fetching student classes:', error);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Error loading classes';
                    classSelect.appendChild(option);
                });
        }
    });

    // Generate password preview
    generatePasswordBtn.addEventListener('click', function() {
        const password = generateRandomPassword();
        passwordInput.value = password;
    });

    // Load classes if year is already selected (for old input)
    if (yearSelect.value) {
        yearSelect.dispatchEvent(new Event('change'));
    }

    function generateRandomPassword() {
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';
        const symbols = '!@#$%^&*';
        
        let password = '';
        password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
        password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
        password += numbers.charAt(Math.floor(Math.random() * numbers.length));
        password += symbols.charAt(Math.floor(Math.random() * symbols.length));
        
        const allChars = uppercase + lowercase + numbers + symbols;
        for (let i = 4; i < 8; i++) {
            password += allChars.charAt(Math.floor(Math.random() * allChars.length));
        }
        
        return password.split('').sort(() => Math.random() - 0.5).join('');
    }
});
</script>
@endsection
