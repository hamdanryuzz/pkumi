@extends('layouts.slicing')

@section('title', 'Submit Rubrik Opini')

@section('content')

{{-- Style khusus untuk file upload --}}
<style>
    .file-drop-zone {
        transition: background-color 0.2s ease;
    }
    .file-drop-zone.drag-over {
        background-color: #f0f7ff; /* bg-blue-50 */
        border-color: #007BFF; /* border-blue-primary */
    }
</style>

<div class="w-full max-w-5xl mb-4">
    {{-- TOMBOL KEMBALI --}}
    <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center text-black/70 hover:text-black transition-colors text-lg font-medium mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Dashboard
    </a>
</div>

{{-- Wrapper Form --}}
<div class="bg-white rounded-xl shadow-sm w-full max-w-5xl p-8 md:p-10 lg:p-12">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Submit Rubrik Opini</h1>
        <p class="text-sm text-gray-500 mt-1">Kirimkan artikel rubrik opini kamu untuk di-review oleh admin.</p>
    </div>

    <form id="rubrik-form" method="POST" action="{{-- route('mahasiswa.rubrik-opini.store') --}}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label for="judul" class="block text-sm font-medium text-gray-700">Judul Rubrik Opini</label>
            <input type="text" id="judul" name="judul" placeholder="Tulis judul yang menarik"
                   class="mt-1 block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-primary focus:border-transparent">
        </div>

        <div>
            <label for="ringkasan" class="block text-sm font-medium text-gray-700">Ringkasan Rubrik Opini</label>
            <textarea id="ringkasan" name="ringkasan" rows="3" placeholder="Tulis ringkasan singkat (maks 200 karakter)"
                      class="mt-1 block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-primary focus:border-transparent"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Gambar Cover</label>
            <div id="file-drop-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg file-drop-zone">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-primary hover:text-blue-500 focus-within:outline-none">
                            <span id="file-name">Pilih gambar</span>
                            <input id="file-upload" name="cover_image" type="file" class="sr-only">
                        </label>
                        <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">Gambar cover maksimal 2MB</p>
                </div>
            </div>
        </div>

        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags Rubrik Opini</label>
            <input type="text" id="tags" name="tags" placeholder="Contoh: kampus, mahasiswa, opini (pisahkan dengan koma)"
                   class="mt-1 block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-primary focus:border-transparent">
        </div>

        <div>
            <label for="konten" class="block text-sm font-medium text-gray-700">Konten Rubrik Opini</label>
            <textarea id="konten" name="konten" rows="10" placeholder="Tulis konten artikel kamu di sini..."
                      class="mt-1 block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-primary focus:border-transparent"></textarea>
            <p class="mt-2 text-xs text-gray-500">Admin mungkin akan mengedit tulisanmu sebelum dipublikasi.</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('mahasiswa.dashboard') }}" class="px-6 py-2.5 text-sm font-medium tracking-wide text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium tracking-wide text-white bg-blue-primary rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-primary">
                Kirim
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropZone = document.getElementById('file-drop-zone');
    const fileInput = document.getElementById('file-upload');
    const fileNameDisplay = document.getElementById('file-name');

    if (dropZone) {
        // Highlight on drag over
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-over');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileNameDisplay.textContent = e.dataTransfer.files[0].name;
            }
        });
        
        // Update name on file select
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                fileNameDisplay.textContent = fileInput.files[0].name;
            } else {
                fileNameDisplay.textContent = 'Pilih gambar';
            }
        });
    }
});
</script>
@endsection