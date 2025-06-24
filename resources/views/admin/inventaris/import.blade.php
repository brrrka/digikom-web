{{-- resources/views/admin/inventaris/import.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Import Inventaris')
@section('page-title', 'Import Inventaris')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Import Data Inventaris</h2>
                        <p class="text-sm text-gray-600 mt-1">Upload file Excel untuk mengimpor data inventaris secara massal
                        </p>
                    </div>
                    <a href="{{ route('admin.inventaris.index') }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Petunjuk Import</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>File harus berformat Excel (.xlsx, .xls) atau CSV</li>
                                    <li>Ukuran file maksimal 2MB</li>
                                    <li>Kolom yang wajib: <strong>nama_inventaris, kuantitas, status</strong></li>
                                    <li>Status harus berupa: "tersedia" atau "tidak tersedia"</li>
                                    <li>Jika nama inventaris sudah ada, data akan diupdate</li>
                                    <li>Download template di bawah untuk format yang benar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Download Template -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Template Excel</h3>
                                <p class="text-sm text-green-700">Download template dengan format dan contoh data yang benar
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.inventaris.download-template') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download Template
                        </a>
                    </div>
                </div>

                <!-- Upload Form -->
                <form action="{{ route('admin.inventaris.import') }}" method="POST" enctype="multipart/form-data"
                    id="import-form" class="space-y-6">
                    @csrf

                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            File Excel/CSV <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <div id="file-preview" class="hidden">
                                    <div class="flex items-center justify-center">
                                        <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 11-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span id="file-name" class="ml-2 text-sm text-gray-700"></span>
                                    </div>
                                </div>
                                <div id="upload-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600">
                                        <label for="file"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Pilih file</span>
                                            <input id="file" name="file" type="file" class="sr-only"
                                                accept=".xlsx,.xls,.csv" onchange="previewFile(event)" required>
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">Excel atau CSV hingga 2MB</p>
                                </div>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Opsi Import</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input id="update_existing" name="update_existing" type="checkbox" checked
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="update_existing" class="ml-2 block text-sm text-gray-700">
                                    Update data yang sudah ada (berdasarkan nama inventaris)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="skip_errors" name="skip_errors" type="checkbox" checked
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="skip_errors" class="ml-2 block text-sm text-gray-700">
                                    Lewati baris yang bermasalah dan lanjutkan import
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.inventaris.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" id="submit-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            Import Data
                        </button>
                    </div>
                </form>

                <!-- Progress Bar (Hidden by default) -->
                <div id="progress-container" class="hidden">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div id="progress-bar" class="bg-primary-600 h-2 rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                    <p id="progress-text" class="text-sm text-gray-600 mt-2 text-center">Memproses...</p>
                </div>
            </div>
        </div>

        <!-- Import Results Modal (if there are validation errors) -->
        @if (session('validation_errors'))
            <div id="error-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center mb-4">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.168 18.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Kesalahan Validasi</h3>
                                <p class="text-sm text-gray-500">Terdapat kesalahan dalam file yang diupload:</p>
                            </div>
                        </div>

                        <div class="max-h-60 overflow-y-auto bg-gray-50 rounded-lg p-4">
                            @foreach (session('validation_errors') as $error)
                                <div class="text-sm text-red-600 mb-2">• {{ $error }}</div>
                            @endforeach
                        </div>

                        <div class="flex justify-end mt-4">
                            <button onclick="closeErrorModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Import Results Summary (if successful) -->
        @if (session('import_details'))
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Hasil Import</h3>
                @php $details = session('import_details'); @endphp

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $details['created'] ?? 0 }}</div>
                        <div class="text-sm text-green-800">Data Baru</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $details['updated'] ?? 0 }}</div>
                        <div class="text-sm text-blue-800">Data Diupdate</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $details['skipped'] ?? 0 }}</div>
                        <div class="text-sm text-yellow-800">Data Dilewati</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $details['errors'] ?? 0 }}</div>
                        <div class="text-sm text-red-800">Error</div>
                    </div>
                </div>

                @if (!empty($details['error_details']))
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Detail Error:</h4>
                        <div class="bg-red-50 rounded-lg p-3 max-h-40 overflow-y-auto">
                            @foreach ($details['error_details'] as $error)
                                <div class="text-sm text-red-600 mb-1">• {{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <script>
        function previewFile(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('file-preview').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            }
        }

        function closeErrorModal() {
            document.getElementById('error-modal').classList.add('hidden');
        }

        // Form submission with progress
        document.getElementById('import-form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submit-btn');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            // Show progress
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0021.001 8c0 .522-.042 1.04-.122 1.548m-2.526 1.117l-.982-.982A6.996 6.996 0 0019.001 8c0-1.039-.223-2.028-.624-2.928l-2.082 2.082m-2.584-2.584L12.001 6.172V4a8.001 8.001 0 012.928.624l-2.082 2.082m-2.584-2.584A6.996 6.996 0 008.001 4v2.172L6.293 4.464A8.001 8.001 0 004.001 8c0 .522.042 1.04.122 1.548"></path></svg>Mengimport...';

            progressContainer.classList.remove('hidden');

            // Simulate progress (since we can't track real progress easily)
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;

                progressBar.style.width = progress + '%';
                progressText.textContent = `Memproses... ${Math.round(progress)}%`;
            }, 200);

            // Clear interval when form actually submits
            setTimeout(() => {
                clearInterval(interval);
                progressBar.style.width = '100%';
                progressText.textContent = 'Menyelesaikan...';
            }, 3000);
        });

        // Auto-hide success messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.animate-slide-in');
            alerts.forEach(function(alert) {
                if (alert) {
                    alert.style.transition = 'opacity 0.3s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }
            });
        }, 5000);

        // Drag and drop functionality
        const dropArea = document.querySelector('.border-dashed');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropArea.classList.add('border-primary-500', 'bg-primary-50');
        }

        function unhighlight(e) {
            dropArea.classList.remove('border-primary-500', 'bg-primary-50');
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                document.getElementById('file').files = files;
                previewFile({
                    target: {
                        files: files
                    }
                });
            }
        }
    </script>
@endsection
