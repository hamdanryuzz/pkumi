@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Student Account Generated Successfully!</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Student Information:</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $studentData['name'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NIM:</strong></td>
                                    <td>{{ $studentData['nim'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $studentData['email'] ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Generated Credentials:</h6>
                            <div class="alert alert-info">
                                <p><strong>Username:</strong> {{ $studentData['username'] }}</p>
                                <p><strong>Password:</strong> 
                                    <code id="password">{{ $studentData['generated_password'] }}</code>
                                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyPassword()">
                                        Copy
                                    </button>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Please save these credentials securely. The password will not be shown again.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('students.create') }}" class="btn btn-primary">
                            Generate Another Student
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-success">
                            View All Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyPassword() {
    const passwordElement = document.getElementById('password');
    const password = passwordElement.textContent;
    
    navigator.clipboard.writeText(password).then(function() {
        // Change button text temporarily
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-secondary');
        
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
@endsection
