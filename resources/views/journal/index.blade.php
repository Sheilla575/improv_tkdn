@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Journal List</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage journal data and information</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Delete All Button -->
                <button type="button" onclick="openDeleteAllModal()" class="btn btn-error flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete All
                </button>
                <!-- Import Button -->
                <button type="button" onclick="openImportModal()" class="btn btn-outline flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    Import Excel
                </button>
                <!-- Add Material Button -->
                <a href="{{ route('master.journal.create') }}" class="btn btn-primary flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Journal
                </a>
            </div>
        </div>
    </div>

    <!-- Notification Messages -->
    @if(session('success'))
    <div class="mb-6">
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Material Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Journal List</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Click row to view details
                </p>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pekerjaan</th>
                            <th>Spesifikasi atau Kualifikasi</th>
                            <th>Negara Asal</th>
                            <th>Harga Satuan</th>
                            <th>Classification TKDN</th>
                            <th>TKDN</th>
                            <th>Keterangan</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @forelse($journals as $journal)
                        <tr class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-600 transition-colors border-b border-gray-100 dark:border-gray-700" data-detail-url="{{ route('master.journal.show', $journal->id) }}" onclick="goToDetail(this, event)">
                            <td>{{ $no++ }}</td>
                            <td>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $journal->nama_pekerjaan }}</span>
                            </td>
                            <td>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $journal->spesifikasi_or_kualifikasi }}</span>
                            </td>
                            <td>
                                <span class="font-medium text-gray-900 dark:text-white badge badge-primary ">{{ $journal->negara_asal }}</span>
                            </td>
                            <td>
                                <span class="font-medium text-gray-900 dark:text-white">Rp. {{ number_format($journal->satuan_harga, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($journal->classification_tkdn == 1)
                                <span class="badge badge-secondary"></span>
                                @elseif($journal->classification_tkdn == 2)
                                <span class="badge badge-success">Alat Kerja / Fasilitas</span>
                                @elseif($journal->classification_tkdn == 3)
                                <span class="badge badge-warning">Konstruksi & Fabrikasi</span>
                                @elseif($journal->classification_tkdn == 4)
                                <span class="badge badge-primary font-medium text-gray-900 dark:text-white">Peralatan (Jasa Umum)</span>
                                @elseif($journal->classification_tkdn == 5)
                                <span class="badge badge-danger">Material (Bahan Baku)</span>
                                @elseif($journal->classification_tkdn == 6)
                                <span class="badge badge-primary">Peralatan (Barang Jadi)</span>
                                @elseif($journal->classification_tkdn == 7)
                                <span class="badge badge-orange">Summary</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $journal->tkdn }} %</span>
                            </td>
                            <td>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $journal->keterangan }}</span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center space-x-2" onclick="event.stopPropagation()">
                                    <a href="{{ route('master.journal.edit', $journal) }}" class="btn btn-outline p-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('master.journal.destroy', $journal) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this journal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No journal found</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first journal</p>
                                    <a href="{{ route('master.journal.create') }}" class="btn btn-primary">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah Journal
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($journals->hasPages())
        {{ $journals->links('components.pagination') }}
        @endif
    </div>
</div>

<!-- Import Errors Display -->
@if(session('import_errors'))
<div class="mb-6">
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-xl relative" role="alert">
        <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h4 class="font-medium mb-2">Import completed with some errors:</h4>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Import Journal from Excel</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Download the template first, fill in your data, then upload the completed file.
                </p>
                <a href="{{ route('master.journal.download-template') }}" class="btn btn-outline btn-sm w-full mb-3">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Template
                </a>
            </div>

            <form action="{{ route('master.journal.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="excel_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Excel File
                    </label>
                    <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Import
                    </button>
                    <button type="button" onclick="closeImportModal()" class="btn btn-outline flex-1">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete All Confirmation Modal -->
<div id="deleteAllModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-red-600 dark:text-red-400">Delete All Journal</h3>
                <button onclick="closeDeleteAllModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center mb-3">
                    <svg class="w-8 h-8 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">Are you sure?</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">This action cannot be undone.</p>
                    </div>
                </div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    This will permanently delete <strong>ALL</strong> material records from the database. 
                    All data will be lost and cannot be recovered.
                </p>
            </div>

            <form action="{{ route('master.journal.delete-all') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-error flex-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Yes, Delete All
                    </button>
                    <button type="button" onclick="closeDeleteAllModal()" class="btn btn-outline flex-1">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function goToDetail(element, event) {
        // Check if the click is on a button or link
        if (event.target.closest('button') || event.target.closest('a')) {
            return;
        }

        const detailUrl = element.getAttribute('data-detail-url');
        if (detailUrl) {
            window.location.href = detailUrl;
        }
    }

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('importModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImportModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImportModal();
        }
    });
</script>
@endsection