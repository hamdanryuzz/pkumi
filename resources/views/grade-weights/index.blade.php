@extends('layouts.app')

@section('title', 'Manage Bobot Nilai - Sistem Penilaian PKUMI')
@section('page-title', 'Manage Bobot Nilai')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Weight Settings -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-bold text-gray-800">Pengaturan Bobot Penilaian</h5>
                <p class="text-sm text-gray-600 mt-1">Atur bobot untuk setiap komponen penilaian</p>
            </div>
            <div class="p-6">
                <form action="{{ route('grade-weights.update') }}" method="POST" id="weightForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Attendance Weight -->
                        <div class="space-y-2">
                            <label for="attendance_weight" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-calendar-check text-blue-500 mr-2"></i>Bobot Presensi (%)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm weight-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('attendance_weight') ring-2 ring-red-500 border-red-500 @enderror" 
                                       id="attendance_weight" 
                                       name="attendance_weight" 
                                       value="{{ $weights->attendance_weight }}"
                                       min="0" max="100" step="0.01" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-sm">%</span>
                                </div>
                            </div>
                            @error('attendance_weight')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assignment Weight -->
                        <div class="space-y-2">
                            <label for="assignment_weight" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-tasks text-green-500 mr-2"></i>Bobot Tugas (%)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm weight-input focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 @error('assignment_weight') ring-2 ring-red-500 border-red-500 @enderror" 
                                       id="assignment_weight" 
                                       name="assignment_weight" 
                                       value="{{ $weights->assignment_weight }}"
                                       min="0" max="100" step="0.01" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-sm">%</span>
                                </div>
                            </div>
                            @error('assignment_weight')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Midterm Weight -->
                        <div class="space-y-2">
                            <label for="midterm_weight" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-clipboard-list text-yellow-500 mr-2"></i>Bobot UTS (%)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm weight-input focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 @error('midterm_weight') ring-2 ring-red-500 border-red-500 @enderror" 
                                       id="midterm_weight" 
                                       name="midterm_weight" 
                                       value="{{ $weights->midterm_weight }}"
                                       min="0" max="100" step="0.01" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-sm">%</span>
                                </div>
                            </div>
                            @error('midterm_weight')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Final Weight -->
                        <div class="space-y-2">
                            <label for="final_weight" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-graduation-cap text-red-500 mr-2"></i>Bobot UAS (%)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm weight-input focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('final_weight') ring-2 ring-red-500 border-red-500 @enderror" 
                                       id="final_weight" 
                                       name="final_weight" 
                                       value="{{ $weights->final_weight }}"
                                       min="0" max="100" step="0.01" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-sm">%</span>
                                </div>
                            </div>
                            @error('final_weight')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Total Weight Display -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">
                                    Total Bobot: <span id="totalWeight" class="text-2xl text-blue-600">{{ $weights->getTotalWeight() }}</span>%
                                </h4>
                                <div id="weightStatus" class="mt-1"></div>
                            </div>
                            <div class="text-4xl">
                                <i id="statusIcon" class="fas fa-calculator text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <div class="bg-gray-200 rounded-full h-3">
                                <div id="progressBar" class="bg-blue-500 h-3 rounded-full transition-all duration-500" style="width: {{ $weights->getTotalWeight() }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    @error('total')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <span class="text-red-700">{{ $message }}</span>
                            </div>
                        </div>
                    @enderror
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                        <form action="{{ route('grade-weights.reset') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition duration-200"
                                    onclick="return confirm('Reset ke bobot default (10%, 20%, 30%, 40%)?')">
                                <i class="fas fa-undo mr-2"></i>Reset Default
                            </button>
                        </form>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" 
                                id="saveButton">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Grade Scale -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-lg font-bold text-gray-800">Skala Penilaian Huruf</h6>
                <p class="text-sm text-gray-600 mt-1">Konversi nilai angka ke huruf</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">95 - 100</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">A+</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">90 - 94</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">A</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">85 - 89</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">A-</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">80 - 84</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">B+</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">75 - 79</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">B</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">70 - 74</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">B-</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">< 70</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">C</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Example Calculation -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-lg font-bold text-gray-800">Contoh Perhitungan</h6>
                <p class="text-sm text-gray-600 mt-1">Simulasi perhitungan nilai akhir</p>
            </div>
            <div class="p-6">
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <span class="text-gray-700">
                            <i class="fas fa-calendar-check text-blue-500 mr-2"></i>Presensi: 90
                        </span>
                        <div class="text-right">
                            <div class="font-medium text-gray-900">× <span class="attendance-example">{{ $weights->attendance_weight }}</span>%</div>
                            <div class="text-sm text-gray-600">= <span class="attendance-result">{{ 90 * $weights->attendance_weight / 100 }}</span></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <span class="text-gray-700">
                            <i class="fas fa-tasks text-green-500 mr-2"></i>Tugas: 85
                        </span>
                        <div class="text-right">
                            <div class="font-medium text-gray-900">× <span class="assignment-example">{{ $weights->assignment_weight }}</span>%</div>
                            <div class="text-sm text-gray-600">= <span class="assignment-result">{{ 85 * $weights->assignment_weight / 100 }}</span></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <span class="text-gray-700">
                            <i class="fas fa-clipboard-list text-yellow-500 mr-2"></i>UTS: 88
                        </span>
                        <div class="text-right">
                            <div class="font-medium text-gray-900">× <span class="midterm-example">{{ $weights->midterm_weight }}</span>%</div>
                            <div class="text-sm text-gray-600">= <span class="midterm-result">{{ 88 * $weights->midterm_weight / 100 }}</span></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-gray-700">
                            <i class="fas fa-graduation-cap text-red-500 mr-2"></i>UAS: 92
                        </span>
                        <div class="text-right">
                            <div class="font-medium text-gray-900">× <span class="final-example">{{ $weights->final_weight }}</span>%</div>
                            <div class="text-sm text-gray-600">= <span class="final-result">{{ 92 * $weights->final_weight / 100 }}</span></div>
                        </div>
                    </div>
                    
                    <div class="border-t pt-3">
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border-2 border-purple-200">
                            <span class="text-lg font-bold text-gray-800">
                                <i class="fas fa-calculator text-purple-500 mr-2"></i>Total Nilai
                            </span>
                            <span class="text-xl font-bold text-purple-600 total-example">
                                {{ 90 * $weights->attendance_weight / 100 + 85 * $weights->assignment_weight / 100 + 88 * $weights->midterm_weight / 100 + 92 * $weights->final_weight / 100 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateTotal() {
    const attendance = parseFloat(document.getElementById('attendance_weight').value) || 0;
    const assignment = parseFloat(document.getElementById('assignment_weight').value) || 0;
    const midterm = parseFloat(document.getElementById('midterm_weight').value) || 0;
    const final = parseFloat(document.getElementById('final_weight').value) || 0;
    
    const total = attendance + assignment + midterm + final;
    document.getElementById('totalWeight').textContent = total.toFixed(2);
    
    const statusDiv = document.getElementById('weightStatus');
    const statusIcon = document.getElementById('statusIcon');
    const saveButton = document.getElementById('saveButton');
    const progressBar = document.getElementById('progressBar');
    
    // Update progress bar
    progressBar.style.width = Math.min(total, 100) + '%';
    
    if (total === 100) {
        statusDiv.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i><span class="text-green-700 font-medium">Bobot sudah benar (100%)</span>';
        statusIcon.className = 'fas fa-check-circle text-green-500';
        progressBar.className = 'bg-green-500 h-3 rounded-full transition-all duration-500';
        saveButton.disabled = false;
        saveButton.classList.remove('opacity-50', 'cursor-not-allowed');
        
        // Add success animation
        saveButton.classList.add('animate-pulse');
        setTimeout(() => {
            saveButton.classList.remove('animate-pulse');
        }, 2000);
        
    } else if (total > 100) {
        statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i><span class="text-red-700 font-medium">Total bobot melebihi 100% (' + total.toFixed(2) + '%)</span>';
        statusIcon.className = 'fas fa-exclamation-triangle text-red-500';
        progressBar.className = 'bg-red-500 h-3 rounded-full transition-all duration-500';
        saveButton.disabled = true;
        saveButton.classList.add('opacity-50', 'cursor-not-allowed');
        
    } else {
        statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i><span class="text-yellow-700 font-medium">Total bobot kurang dari 100% (' + total.toFixed(2) + '%)</span>';
        statusIcon.className = 'fas fa-exclamation-triangle text-yellow-500';
        progressBar.className = 'bg-yellow-500 h-3 rounded-full transition-all duration-500';
        saveButton.disabled = true;
        saveButton.classList.add('opacity-50', 'cursor-not-allowed');
    }
    
    // Update example calculation
    updateExample(attendance, assignment, midterm, final);
}

function updateExample(attendance, assignment, midterm, final) {
    document.querySelector('.attendance-example').textContent = attendance.toFixed(1);
    document.querySelector('.assignment-example').textContent = assignment.toFixed(1);
    document.querySelector('.midterm-example').textContent = midterm.toFixed(1);
    document.querySelector('.final-example').textContent = final.toFixed(1);
    
    document.querySelector('.attendance-result').textContent = (90 * attendance / 100).toFixed(1);
    document.querySelector('.assignment-result').textContent = (85 * assignment / 100).toFixed(1);
    document.querySelector('.midterm-result').textContent = (88 * midterm / 100).toFixed(1);
    document.querySelector('.final-result').textContent = (92 * final / 100).toFixed(1);
    
    const total = (90 * attendance / 100) + (85 * assignment / 100) + (88 * midterm / 100) + (92 * final / 100);
    document.querySelector('.total-example').textContent = total.toFixed(1);
}

// Add input event listeners with visual feedback
document.querySelectorAll('.weight-input').forEach(input => {
    input.addEventListener('input', function() {
        updateTotal();
        
        // Add visual feedback
        this.classList.add('ring-2', 'ring-blue-300', 'border-blue-500');
        setTimeout(() => {
            this.classList.remove('ring-2', 'ring-blue-300', 'border-blue-500');
        }, 1000);
    });
});

// Add form submission handler
document.getElementById('weightForm').addEventListener('submit', function(e) {
    const total = parseFloat(document.getElementById('totalWeight').textContent);
    if (total !== 100) {
        e.preventDefault();
        alert('Total bobot harus 100% sebelum menyimpan!');
        return false;
    }
    
    // Show loading state
    const saveButton = document.getElementById('saveButton');
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
});

// Initial calculation
updateTotal();
</script>
@endsection