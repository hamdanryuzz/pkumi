@extends('layouts.app')

@section('title', 'Student Account Generated - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-green-500 text-white px-6 py-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                    <h5 class="text-lg font-bold mb-0">Student Account Generated Successfully!</h5>
                </div>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h6 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user mr-2 text-blue-500"></i>
                            Student Information
                        </h6>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Name:</span>
                                <span class="text-gray-900">{{ $studentData['name'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">NIM:</span>
                                <span class="text-gray-900 font-mono bg-blue-50 px-2 py-1 rounded">{{ $studentData['nim'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Email:</span>
                                <span class="text-gray-900">{{ $studentData['email'] ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Generated Credentials -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <h6 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-key mr-2 text-green-500"></i>
                            Generated Credentials
                        </h6>
                        <div class="space-y-3">
                            <div>
                                <span class="block font-medium text-gray-600 mb-1">Username:</span>
                                <div class="flex items-center gap-2">
                                    <code class="bg-white px-3 py-2 rounded border text-sm flex-1">{{ $studentData['username'] }}</code>
                                    <button class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded text-sm transition" onclick="copyToClipboard('{{ $studentData['username'] }}', this)">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <span class="block font-medium text-gray-600 mb-1">Password:</span>
                                <div class="flex items-center gap-2">
                                    <code id="password" class="bg-white px-3 py-2 rounded border text-sm flex-1">{{ $studentData['generated_password'] }}</code>
                                    <button class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm transition" onclick="copyToClipboard('{{ $studentData['generated_password'] }}', this)">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-yellow-800 font-medium">Important Security Note:</p>
                                    <p class="text-xs text-yellow-700 mt-1">
                                        Please save these credentials securely. The password will not be shown again after leaving this page.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Mahasiswa Lagi
                    </a>
                    <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-list mr-2"></i>
                        View All Students
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        // Store original content
        const originalHTML = button.innerHTML;
        const originalClasses = button.className;
        
        // Change button appearance
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.className = button.className.replace('bg-blue-500 hover:bg-blue-600', 'bg-green-500')
                                         .replace('bg-gray-200 hover:bg-gray-300', 'bg-green-500 text-white');
        
        // Reset after 2 seconds
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.className = originalClasses;
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        
        // Fallback - show alert
        alert('Copied to clipboard: ' + text);
    });
}
</script>
@endsection