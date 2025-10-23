@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Tambah HPP</h1>
            <p class="text-gray-600 dark:text-gray-400">Buat Harga Pokok Pembelian baru</p>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <form action="{{ route('hpp.store') }}" method="POST" class="space-y-6" id="hppForm">
        @csrf

        <!-- Informasi Umum -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Umum</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="project_id" class="form-label">Pilih Project <span class="text-red-500">*</span></label>
                        <select id="project_id" name="project_id" class="form-select @error('project_id') border-red-500 @enderror" required>
                            <option value="">Pilih Project</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <div id="project-info" class="hidden p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">Informasi Project</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Project</label>
                                    <p id="project-name" class="text-gray-900 dark:text-white"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Project</label>
                                    <p id="project-type" class="text-gray-900 dark:text-white"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Perusahaan</label>
                                    <p id="project-company" class="text-gray-900 dark:text-white"></p>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                                    <p id="project-description" class="text-gray-900 dark:text-white"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Pekerjaan -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Item Pekerjaan</h3>

                </div>
            </div>
            <div class="card-body">
                <!-- Summary AHS (shown once) -->
                <div id="ahs-summary" class="hidden mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Detail AHS Terpilih</h4>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm text-blue-800 dark:text-blue-200">
                        <div>
                            <span class="font-medium">Kode AHS:</span>
                            <span id="ahs-summary-code" class="font-mono"></span>
                        </div>
                        <div>
                            <span class="font-medium">Judul AHS:</span>
                            <span id="ahs-summary-title"></span>
                        </div>
                        <div class="md:col-span-3">
                            <span class="font-medium">Deskripsi:</span>
                            <span id="ahs-summary-description"></span>
                        </div>
                    </div>
                </div>

                <!-- Data Selector (one-time selector) -->
                <div id="ahs-form" class="mb-4">
                    <label class="form-label">Pilih Data</label>
                    <div class="relative">
                        <input type="text" id="ahs-selected" class="form-input" readonly placeholder="Klik untuk pilih data AHS atau Master Data">
                        <input type="hidden" id="ahs-selected-id" name="ahs_selected_id">
                        <button type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="openAhsModal(this)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="items-container" data-count="0" data-ahs='@json($ahsData)' data-projects='@json($projects)'>
                    <!-- Items will be added here dynamically -->
                </div>
            </div>
        </div>

        <!-- Kalkulasi -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kalkulasi</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="overhead_percentage" class="form-label">Overhead (%) <span class="text-red-500">*</span></label>
                        <input type="number" id="overhead_percentage" name="overhead_percentage" value="{{ old('overhead_percentage', 8) }}" step="0.01" min="0" max="100" class="form-input @error('overhead_percentage') border-red-500 @enderror" required>
                        @error('overhead_percentage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="margin_percentage" class="form-label">Margin (%) <span class="text-red-500">*</span></label>
                        <input type="number" id="margin_percentage" name="margin_percentage" value="{{ old('margin_percentage', 12) }}" step="0.01" min="0" max="100" class="form-input @error('margin_percentage') border-red-500 @enderror" required>
                        @error('margin_percentage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ppn_percentage" class="form-label">PPN (%) <span class="text-red-500">*</span></label>
                        <input type="number" id="ppn_percentage" name="ppn_percentage" value="{{ old('ppn_percentage', 11) }}" step="0.01" min="0" max="100" class="form-input @error('ppn_percentage') border-red-500 @enderror" required>
                        @error('ppn_percentage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" class="form-textarea @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('hpp.index') }}" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                Simpan HPP
            </button>
        </div>
    </form>
</div>



<!-- Template for first item -->
<template id="item-template">
    <div class="item-row">

        <!-- Data AHS -->
        <div class="flex gap-2 mt-5">
            <div class="flex-[0]">
                <h4 class="text-md font-medium text-gray-900 dark:text-white"><span class="item-number"></span>.</h4>
            </div>
            <input type="hidden" name="ahs[GROUP_INDEX][estimation_item_id]" class="estimation-item-id-input">
            <input type="hidden" name="ahs[GROUP_INDEX][ahs_type]" class="ahs-type-input">
            <div class="flex-1">
                <label class="form-label">Volume <span class="text-red-500">*</span></label>
                <input type="number" name="ahs[GROUP_INDEX][volume]" class="form-input w-full volume-input" step="0.01" min="0" value="1">
            </div>
            <div class="flex-1">
                <label class="form-label">Satuan <span class="text-red-500">*</span></label>
                <input type="text" name="ahs[GROUP_INDEX][unit]" class="form-input w-full unit-input" value="Ls" disabled>
            </div>
            <div class="flex-1" style="display: none;">
                <label class="form-label" hidden>Durasi <span class="text-red-500">*</span></label>
                <input type="number" name="ahs[GROUP_INDEX][duration]" class="form-input w-full" min="1" value="1">
            </div>
            <div class="flex-1" style="display: none;">
                <label class="form-label" hidden>Satuan Durasi <span class="text-red-500">*</span></label>
                <select name="ahs[GROUP_INDEX][duration_unit]" class="form-select w-full">
                    <option value="Hari">Hari</option>
                    <option value="Minggu">Minggu</option>
                    <option value="Bulan">Bulan</option>
                    <option value="Tahun">Tahun</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="form-label">Harga Satuan (Rp)</label>
                <!-- input unit_price masuknya ke sub_total di hpp -->
                <input type="number" name="ahs[GROUP_INDEX][unit_price]" class="form-input w-full unit-price-input" step="0.01" min="0" value="0" readonly>
            </div>
            <div class="flex-1">
                <label class="form-label">Jumlah Harga (Rp)</label>
                <!-- input total_price masuknya ke sub_grand_total_hpp_item setelah dikalikan volume dan durasi -->
                <input type="number" name="ahs[GROUP_INDEX][total_price]" class="form-input w-full total-price-input" step="0.01" readonly>
            </div>
        </div>


        <!-- Detail Per Item AHS  -->
        <div class="flex gap-2 mt-5" style="margin-left: 70px;">
            <div class="flex-1">
                <label class="form-label">Uraian Barang/Pekerjaan </label>
                <input type="text" name="items[INDEX][description]" class="form-input w-full item-ahs-input" readonly>
            </div>
            <div class="flex-1">
                <label class="form-label">Satuan</label>
                <input type="text" name="items[INDEX][unit]" class="form-input w-full" readonly>
            </div>
            <div class="flex-1">
                <label class="form-label">Koefisien</label>
                <input type="number" name="items[INDEX][coefficient]" class="form-input w-full item-coef" min="0" step="0.0001" readonly>
            </div>
            <div class="flex-1">
                <label class="form-label">Harga Satuan</label>
                <input type="number" name="items[INDEX][unit_price]" class="form-input w-full item-unit-price" min="0" step="0.01" readonly>
            </div>
            <div class="flex-1">
                <label class="form-label">Grand Total</label>
                <input type="number" name="items[INDEX][grand_total]" class="form-input w-full item-grand-total" placeholder="0.00" readonly>
            </div>

            <button type="button" style="height: max-content" onclick="removeItem(this)" class="btn btn-outline text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 my-8">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>


    </div>
</template>

<!-- Template for AHS Group -->
<template id="ahs-group-template">
    <div class="ahs-group border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4" data-group-index="GROUP_INDEX">
        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-400">AHS</div>
                <div class="text-lg font-medium text-gray-900 dark:text-white">
                    <span class="ahs-group-title"></span>
                </div>
            </div>
            <button type="button" class="btn btn-outline text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" onclick="removeAhsGroup(this)">Hapus</button>
        </div>

        <input type="hidden" name="ahs[GROUP_INDEX][item_type]" value="ahs">
        <input type="hidden" name="ahs[GROUP_INDEX][ahs_id]" class="ahs-group-id">
        <input type="hidden" name="ahs[GROUP_INDEX][reference_id]" class="ahs-group-reference-id">

        <!-- Form untuk AHS -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3">
            <div>
                <label class="form-label">Data AHS</label>
                <input type="text" name="ahs[GROUP_INDEX][description]" class="form-input ahs-group-description" readonly>
            </div>
            <div>
                <label class="form-label">Volume <span class="text-red-500">*</span></label>
                <input type="number" name="ahs[GROUP_INDEX][volume]" class="form-input ahs-group-volume" step="0.01" min="0" value="1">
            </div>
            <div>
                <label class="form-label">Satuan <span class="text-red-500">*</span></label>
                <input type="text" name="ahs[GROUP_INDEX][unit]" class="form-input ahs-group-unit" value="Ls" disabled>
            </div>
            <div style="display: none;">
                <label class="form-label" hidden>Durasi <span class="text-red-500">*</span></label>
                <input type="number" name="ahs[GROUP_INDEX][duration]" class="form-input ahs-group-duration" min="1" value="1">
            </div>
            <div style="display: none;">
                <label class="form-label" hidden>Satuan Durasi <span class="text-red-500">*</span></label>
                <select name="ahs[GROUP_INDEX][duration_unit]" class="form-select ahs-group-duration-unit">
                    <option value="Hari">Hari</option>
                    <option value="Minggu">Minggu</option>
                    <option value="Bulan">Bulan</option>
                    <option value="Tahun">Tahun</option>
                </select>
            </div>
            <div>
                <label class="form-label">Harga Satuan (Rp)</label>
                <input type="number" name="ahs[GROUP_INDEX][unit_price]" class="form-input ahs-group-unit-price" step="0.01" min="0" value="0" readonly>
            </div>
            <div>
                <label class="form-label">Jumlah Harga (Rp)</label>
                <input type="number" name="ahs[GROUP_INDEX][total_price]" class="form-input ahs-group-total-price" step="0.01" readonly>
            </div>
        </div>

        <!-- Detail Per Item AHS (dynamic from JSON) -->
        <div x-data="{ showDetail: true }" class="detail-section space-y-2">
            <button type="button" @click="showDetail = !showDetail" class="detail-toggle text-md font-medium text-gray-700 dark:text-gray-300 hover:text-success-900 dark:focus:text-white transition-colors">
                <span x-show="!showDetail">Tampilkan Detail Item ▼</span>
                <span x-show="showDetail">Sembunyikan Detail Item ▲</span>
            </button>
            <div x-show="showDetail" class="ahs-group-items space-y-2">
                <!-- Items will be added here dynamically -->
            </div>
        </div>
    </div>
</template>

<!-- Template for Item Group (Worker, Material, Equipment) -->
<template id="item-group-template">
    <div class="item-group border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4" data-group-index="GROUP_INDEX">
        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-400 item-group-type"></div>
                <div class="text-lg font-medium text-gray-900 dark:text-white">
                    <span class="item-group-title"></span>
                </div>
            </div>
            <button type="button" class="btn btn-outline text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" onclick="removeItemGroup(this)">Hapus</button>
        </div>

        <input type="hidden" name="ahs[GROUP_INDEX][item_type]" class="item-group-item-type">
        <input type="hidden" name="ahs[GROUP_INDEX][ahs_id]" value="">
        <input type="hidden" name="ahs[GROUP_INDEX][reference_id]" class="item-group-reference-id">

        <!-- Form untuk Item -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3">
            <div>
                <label class="form-label">Data Item</label>
                <input type="text" name="ahs[GROUP_INDEX][description]" class="form-input item-group-description" readonly>
            </div>
            <div>
                <label class="form-label">Koefisien <span class="text-red-500">*</span></label>
                <input type="number" name="ahs[GROUP_INDEX][coefficient]" class="form-input item-group-coefficient" step="0.0001" min="0" value="1">
            </div>
            <div>
                <label class="form-label">Volume <span class="text-red-500">*</span></label>
                <input type="number" name="ahs[GROUP_INDEX][volume]" class="form-input item-group-volume" step="0.01" min="0" value="1">
            </div>
            <div>
                <label class="form-label">Harga Satuan (Rp)</label>
                <input type="number" name="ahs[GROUP_INDEX][unit_price]" class="form-input item-group-unit-price" step="0.01" min="0" readonly>
            </div>
            <div>
                <label class="form-label">Total (Rp)</label>
                <input type="number" name="ahs[GROUP_INDEX][total_price]" class="form-input item-group-total-price" step="0.01" readonly>
                <small class="text-gray-500">Volume × (Koefisien × Harga Satuan)</small>
            </div>
        </div>
    </div>
</template>

<!-- Template for Journal Worker Group -->
<template id="journal-group-template">
    <div class="journal-group border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4" data-group-index="GROUP_INDEX">
        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Journal Worker</div>
                <div class="text-lg font-medium text-gray-900 dark:text-white">
                    <span class="journal-group-title"></span>
                </div>
            </div>
            <button type="button" class="btn btn-outline text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" onclick="removeJournalGroup(this)">Hapus</button>
        </div>

        <input type="hidden" name="ahs[GROUP_INDEX][item_type]" value="journal_worker">
        <input type="hidden" name="ahs[GROUP_INDEX][ahs_id]" value="">
        <input type="hidden" name="ahs[GROUP_INDEX][reference_id]" class="journal-group-reference-id">

        <!-- Form untuk Journal Worker -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
            <div>
                <label class="form-label">Data Journal</label>
                <input type="text" name="ahs[GROUP_INDEX][description]" class="form-input journal-group-description" readonly>
            </div>
            <div>
                <label class="form-label">Volume <span class="text-red-500">*</span></label>
                <input type="number" name="ahs[GROUP_INDEX][volume]" class="form-input journal-group-volume" step="0.01" min="0" value="1">
            </div>
            <div>
                <label class="form-label">Harga Satuan (Rp)</label>
                <input type="number" name="ahs[GROUP_INDEX][unit_price]" class="form-input journal-group-unit-price" step="0.01" min="0" readonly>
            </div>
            <div class="md:col-span-3">
                <label class="form-label">Total (Rp)</label>
                <input type="number" name="ahs[GROUP_INDEX][total_price]" class="form-input journal-group-total-price" step="0.01" readonly>
                <small class="text-gray-500">Volume × Harga Satuan</small>
            </div>
        </div>
    </div>
</template>

<!-- AHS Modal - Step 1: Pilih AHS -->
<div id="ahsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pilih Data</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih AHS atau Master Data untuk menambahkan item pekerjaan</p>
                </div>
                <button type="button" onclick="closeAhsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Info Box -->
            <div class="mb-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                Data Tersedia untuk Proyek
                            </h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                <p>Menampilkan data yang sesuai dengan jenis proyek: <span id="projectTypeInfo" class="font-semibold"></span></p>
                                <p class="mt-1">• <strong>AHS:</strong> Klik untuk menambahkan semua item pekerjaan secara otomatis</p>
                                <p class="mt-1">• <strong>Master Data:</strong> Klik untuk menambahkan item individual (Pekerja, Material, Peralatan, Journal Worker)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <input type="text" id="ahsSearch" placeholder="Cari data AHS, Pekerja, Material, Peralatan, atau Journal Worker..." class="form-input w-full">
            </div>

            <div id="ahsList" class="space-y-3 max-h-100 overflow-y-auto">
                <!-- AHS data will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>


<script>
    const containerEl = document.getElementById('items-container');
    let itemIndex = 0;
    let ahsData = [];
    let projects = [];
    let currentProjectType = '';

    try {
        projects = JSON.parse(containerEl.dataset.projects || '[]');
    } catch (e) {
        projects = [];
    }

    // Initialize itemIndex based on existing items
    function initializeItemIndex() {
        const existingItems = containerEl.querySelectorAll('.item-row');
        itemIndex = existingItems.length;

        // Update data-count attribute
        containerEl.setAttribute('data-count', itemIndex);
    }

    function addItem() {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-template');
        const clone = template.content.cloneNode(true);

        // Update index
        const inputs = clone.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            input.name = input.name.replace('INDEX', itemIndex);
        });

        // Update item number
        const itemNumber = clone.querySelector('.item-number');
        itemNumber.textContent = itemIndex + 1;

        container.appendChild(clone);


        // Add event listeners for calculation
        const volumeInput = container.lastElementChild.querySelector('.volume-input');
        const unitPriceInput = container.lastElementChild.querySelector('.unit-price-input');
        const totalPriceInput = container.lastElementChild.querySelector('.total-price-input');

        volumeInput.addEventListener('input', function() {
            calculateTotal(container.lastElementChild);
        });
        unitPriceInput.addEventListener('input', function() {
            calculateTotal(container.lastElementChild);
        });

        itemIndex++;
    }

    function removeItem(button) {
        const itemRow = button.closest('.item-row');
        itemRow.remove();
    }

    function calculateTotal(row) {
        const volume = parseFloat(row.querySelector('.volume-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
        const totalPrice = volume * unitPrice;
        row.querySelector('.total-price-input').value = totalPrice.toFixed(2);
    }

    function openAhsModal(button) {
        if (!currentProjectType) {
            alert('Pilih proyek terlebih dahulu');
            return;
        }

        document.getElementById('ahsModal').classList.remove('hidden');
        loadAhsData();
    }

    function closeAhsModal() {
        document.getElementById('ahsModal').classList.add('hidden');
    }

    // Update project type when project selection changes
    function updateProjectType() {
        const projectSelect = document.getElementById('project_id');
        const selectedProject = projects.find(p => p.id == projectSelect.value);
        currentProjectType = selectedProject ? selectedProject.project_type : '';

        // Update project type info
        const projectTypeInfo = document.getElementById('projectTypeInfo');
        if (projectTypeInfo) {
            projectTypeInfo.textContent = currentProjectType === 'tkdn_jasa' ? 'TKDN Jasa' : 'TKDN Barang Jasa';
        }
    }

    function loadAhsData() {
        if (!currentProjectType) {
            alert('Pilih proyek terlebih dahulu');
            return;
        }

        const ahsList = document.getElementById('ahsList');
        ahsList.innerHTML = '<div class="text-center py-4"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div><p class="mt-2 text-gray-500">Memuat data AHS...</p></div>';

        // Load AHS data based on project type
        fetch(`/hpp/get-ahs-data-only/${currentProjectType}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })


            .then(data => {
                console.log('AHS:', data.ahs);
                console.log('Worker:', data.worker);
                console.log('Material:', data.material);
                console.log('Equipment:', data.equipment);
                allData = data;
                displayAllData(); // tampilkan semuanya sekaligus (atau pakai tab)
            })
            .catch(error => {
                console.error('Error:', error);
                ahsList.innerHTML = '<div class="text-center py-4 text-red-500">Gagal memuat data AHS: ' + error.message + '</div>';
            });
    }

    // function displayAhsData() {
    //     const ahsList = document.getElementById('ahsList');
    //     ahsList.innerHTML = '';

    //     if (ahsData.length === 0) {
    //         ahsList.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada AHS yang sesuai dengan jenis proyek ini</div>';
    //         return;
    //     }

    //     // Get All Data AHS

    //     ahsData.forEach(function(item) {
    //         const div = document.createElement('div');
    //         div.className = 'p-4 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
    //         div.onclick = function() {
    //             selectAhsForItems(item);
    //         };
    //         div.innerHTML = `
    //         <div class="flex justify-between items-start">
    //             <div class="flex-1">
    //                 <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kode: ${item.description.includes(' - ') ? item.description.split(' - ')[1] : item.description}</div>
    //                 <div class="text-sm text-gray-500 dark:text-gray-400">Jumlah Item: ${item.item_count}</div>
    //             </div>
    //             <div class="text-right ml-4">
    //                 <div class="text-xs text-gray-500 dark:text-gray-400">AHS</div>
    //                 <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Klik untuk tambahkan item</div>
    //             </div>
    //         </div>
    //     `;
    //         ahsList.appendChild(div);
    //     });
    // }

    function filterDataByType(type) {
        return allData.filter(item => item.type === type);
    }

    function displayAllData() {
        const container = document.getElementById('ahsList');
        container.innerHTML = '';

        const sections = [{
                title: 'Analisa Harga Satuan (AHS)',
                type: 'ahs'
            },
            {
                title: 'Pekerja (Worker)',
                type: 'worker'
            },
            {
                title: 'Material',
                type: 'material'
            },
            {
                title: 'Peralatan (Equipment)',
                type: 'equipment'
            },
            {
                title: 'Journal Worker',
                type: 'journal_worker'
            },
        ];

        sections.forEach(section => {
            const data = allData.filter(item => item.type === section.type);

            // === Header Section ===
            const header = document.createElement('div');
            header.className = 'text-lg font-semibold mt-6 mb-2 text-gray-800 dark:text-gray-100 border-b pb-1';
            header.textContent = section.title;
            container.appendChild(header);

            if (data.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'text-gray-500 text-sm italic mb-4';
                empty.textContent = `Tidak ada data ${section.title}`;
                container.appendChild(empty);
                return;
            }

            // === List Item ===
            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'p-4 border border-gray-200 dark:border-gray-700 rounded-lg mb-2 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition';

                // Tampilkan berdasarkan tipe
                if (section.type === 'ahs') {
                    div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${item.title}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Kode: ${item.code}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">Jumlah Item: ${item.item_count}</div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400">AHS</div>
                            <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Klik untuk tambahkan semua item</div>
                        </div>
                    </div>
                `;
                } else if (section.type === 'worker') {
                    div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${item.title}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Kode: ${item.code}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Upah: Rp ${Number(item.unit_price).toLocaleString()}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">Unit: ${item.unit || '-'}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">TKDN: ${item.tkdn || '-'}</div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400">PEKERJA</div>
                            <div class="text-sm text-green-600 dark:text-green-400 font-medium">Klik untuk tambahkan item</div>
                        </div>
                    </div>
                `;
                } else if (section.type === 'material') {
                    div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${item.title}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Kode: ${item.code}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Harga: Rp ${Number(item.unit_price).toLocaleString()}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">Unit: ${item.unit || '-'}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">TKDN: ${item.tkdn || '-'}</div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400">MATERIAL</div>
                            <div class="text-sm text-orange-600 dark:text-orange-400 font-medium">Klik untuk tambahkan item</div>
                        </div>
                    </div>
                `;
                } else if (section.type === 'equipment') {
                    div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${item.title}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Kode: ${item.code}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Harga: Rp ${Number(item.unit_price).toLocaleString()}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">Periode: ${item.period || '-'}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">TKDN: ${item.tkdn || '-'}</div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400">PERALATAN</div>
                            <div class="text-sm text-purple-600 dark:text-purple-400 font-medium">Klik untuk tambahkan item</div>
                        </div>
                    </div>
                `;
                } else if (section.type === 'journal_worker') {
                    div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${item.title}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Kode: ${item.code}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Harga: Rp ${Number(item.unit_price).toLocaleString()}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">Unit: ${item.unit || '-'}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">TKDN: ${item.tkdn || '-'}</div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400">JOURNAL</div>
                            <div class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Klik untuk tambahkan item</div>
                        </div>
                    </div>
                `;
                }

                div.onclick = function() {
                    if (section.type === 'ahs') {
                        selectAhsForItems(item); // untuk AHS, gunakan function existing
                    } else {
                        selectMasterDataItem(item); // untuk master data individual
                    }
                };

                container.appendChild(div);
            });
        });
    }

    function selectAhsForItems(ahs) {
        // Load AHS items
        fetch(`/hpp/get-ahs-items/${ahs.id}/${currentProjectType}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Render grouped AHS UI
                renderAhsGroup(ahs, data.items);

                // Close modal
                closeAhsModal();

                // Show success message
                showNotification(`Berhasil menambahkan ${data.items.length} item dari AHS ${ahs.code}`, 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data AHS items');
            });
    }

    function selectMasterDataItem(item) {
        // Load individual master data item
        fetch(`/hpp/get-master-data-item/${item.type}/${item.id}/${currentProjectType}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Create a single item array for consistency with AHS structure
                const singleItem = [data.item];

                // Create a mock AHS object for the individual item
                const mockAhs = {
                    id: item.id,
                    code: item.code,
                    title: item.title,
                    description: item.description,
                    type: item.type
                };

                // Render as appropriate group based on type
                if (item.type === 'journal_worker') {
                    renderJournalGroup(mockAhs, singleItem);
                } else {
                    renderItemGroup(mockAhs, singleItem);
                }

                // Close modal
                closeAhsModal();

                // Show success message
                showNotification(`Berhasil menambahkan ${item.title}`, 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data item');
            });
    }

    function findEmptyRows() {
        const allRows = containerEl.querySelectorAll('.item-row');
        const emptyRows = [];

        allRows.forEach(function(row) {
            const itemAhsInput = row.querySelector('.item-ahs-input');
            if (itemAhsInput && !itemAhsInput.value.trim()) {
                emptyRows.push(row);
            }
        });

        return emptyRows;
    }

    function fillExistingRowWithAhsItem(row, item, ahs) {
        // Fill data
        const estimationItemIdInput = row.querySelector('.estimation-item-id-input');
        const unitPriceInput = row.querySelector('.unit-price-input');
        const unitInput = row.querySelector('.unit-input');
        const volumeInput = row.querySelector('.volume-input');
        const ItemAhsInput = row.querySelector('.item-ahs-input');
        const ahsTypeInput = row.querySelector('.ahs-type-input');
        // Data Per Item AHS
        ItemAhsInput.value = item.description;
        estimationItemIdInput.value = item.id;
        unitPriceInput.value = item.unit_price;
        unitInput.value = item.unit || 'Unit';
        volumeInput.value = item.coefficient || 1;
        if (ahsTypeInput) {
            ahsTypeInput.value = (typeof ahs.type !== 'undefined' && ahs.type) ? ahs.type : (currentProjectType || '');
        }

        // Jangan tampilkan detail AHS per baris lagi (hanya di summary atas)

        // Calculate total
        calculateTotal(row);
    }

    function addAhsItem(item, ahs) {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-template');
        const clone = template.content.cloneNode(true);

        // Update index
        const inputs = clone.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            input.name = input.name.replace('INDEX', itemIndex);
        });

        // Update item number
        const itemNumber = clone.querySelector('.item-number');
        itemNumber.textContent = itemIndex + 1;

        // Fill data
        const estimationItemIdInput = clone.querySelector('.estimation-item-id-input');
        const unitPriceInput = clone.querySelector('.unit-price-input');
        const unitInput = clone.querySelector('.unit-input');
        const volumeInput = clone.querySelector('.volume-input');
        const ItemAhsInput = clone.querySelector('.item-ahs-input');
        const ahsTypeInput = clone.querySelector('.ahs-type-input');
        ItemAhsInput.value = item.description;
        estimationItemIdInput.value = item.id;
        unitPriceInput.value = item.unit_price;
        unitInput.value = item.unit || 'Unit';
        volumeInput.value = item.coefficient || 1;
        if (ahsTypeInput) {
            ahsTypeInput.value = (typeof ahs.type !== 'undefined' && ahs.type) ? ahs.type : (currentProjectType || '');
        }

        // Jangan tampilkan detail AHS per baris lagi (hanya di summary atas)

        container.appendChild(clone);

        // Add event listeners for calculation
        const volumeInputEl = container.lastElementChild.querySelector('.volume-input');
        const unitPriceInputEl = container.lastElementChild.querySelector('.unit-price-input');
        const totalPriceInputEl = container.lastElementChild.querySelector('.total-price-input');

        volumeInputEl.addEventListener('input', function() {
            calculateTotal(container.lastElementChild);
        });
        unitPriceInputEl.addEventListener('input', function() {
            calculateTotal(container.lastElementChild);
        });

        // Calculate initial total
        calculateTotal(container.lastElementChild);

        itemIndex++;
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
        notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
        </div>
    `;

        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }


    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Helper function to get project type label
    function getProjectTypeLabel(projectType) {
        switch (projectType) {
            case 'tkdn_jasa':
                return 'TKDN Jasa (Form 3.1 - 3.5)';
            case 'tkdn_barang_jasa':
                return 'TKDN Barang & Jasa (Form 4.1 - 4.7)';
            default:
                return projectType || '-';
        }
    }

    // Search functionality
    const ahsSearchEl = document.getElementById('ahsSearch');
    if (ahsSearchEl) {
        ahsSearchEl.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const ahsItems = document.querySelectorAll('#ahsList > div');

            ahsItems.forEach(function(item) {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Close modal when clicking outside
    const ahsModalEl = document.getElementById('ahsModal');
    if (ahsModalEl) {
        ahsModalEl.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAhsModal();
            }
        });
    }

    // Form validation
    function validateForm() {

        // Optional lightweight numeric sanity for the first group
        const firstGroup = groups[0];
        const vol = parseFloat(firstGroup.querySelector('.ahs-group-volume')?.value || '0');
        const dur = parseInt(firstGroup.querySelector('.ahs-group-duration')?.value || '0', 10);
        if (vol < 0 || dur < 1) {
            alert('Volume harus >= 0 dan Durasi minimal 1.');
            return false;
        }

        return true;
    }

    // Initialize everything on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize itemIndex based on existing items
        initializeItemIndex();

        // Add first item if none exist
        // if (containerEl.querySelectorAll('.item-row').length === 0) {
        //     addItem();
        // }

        // Setup project selection handler
        const projectSelect = document.getElementById('project_id');
        if (projectSelect) {
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                const projectInfo = document.getElementById('project-info');

                if (projectId) {
                    const project = projects.find(function(p) {
                        return p.id === projectId;
                    });
                    if (project) {
                        document.getElementById('project-name').textContent = project.name;
                        // document.getElementById('project-type').textContent = getProjectTypeLabel(project.project_type);
                        document.getElementById('project-company').textContent = project.company || '-';
                        document.getElementById('project-description').textContent = project.description || '-';
                        projectInfo.classList.remove('hidden');
                    }
                } else {
                    projectInfo.classList.add('hidden');
                }

                // Update project type for AHS filtering
                updateProjectType();
            });

            // Initialize project type on page load
            updateProjectType();
        }

        // Add form validation
        const form = document.getElementById('hppForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                if (!validateForm()) {
                    console.log('Form validation failed');
                    e.preventDefault();
                    return false;
                }
                console.log('Form validation passed, submitting...');
            });
        }
    });

    function updateAhsSummary(ahs) {
        const summary = document.getElementById('ahs-summary');
        if (!summary) {
            return;
        }
        const codeEl = document.getElementById('ahs-summary-code');
        const titleEl = document.getElementById('ahs-summary-title');
        const descEl = document.getElementById('ahs-summary-description');

        if (codeEl) {
            codeEl.textContent = ahs.code || '-';
        }
        if (titleEl) {
            titleEl.textContent = ahs.title || ahs.description || '-';
        }
        if (descEl) {
            descEl.textContent = ahs.description || '-';
        }

        summary.classList.remove('hidden');
    }

    function renderAhsGroup(ahs, items) {
        const container = document.getElementById('items-container');
        const tmpl = document.getElementById('ahs-group-template');
        if (!tmpl) {
            return;
        }
        const clone = tmpl.content.cloneNode(true);

        const groupIndex = container.querySelectorAll('.ahs-group, .item-group, .journal-group').length;
        const groupEl = clone.querySelector('.ahs-group');
        groupEl.setAttribute('data-group-index', groupIndex);

        // Replace GROUP_INDEX in input names
        const inputs = clone.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            if (input.name) {
                input.name = input.name.replace('GROUP_INDEX', groupIndex);
            }
        });

        // Header
        groupEl.querySelector('.ahs-group-title').textContent = ahs.title || ahs.description || '';
        groupEl.querySelector('.ahs-group-description').value = ahs.description || '';
        groupEl.querySelector('.ahs-group-id').value = ahs.id;
        groupEl.querySelector('.ahs-group-reference-id').value = ahs.id; // Set reference_id to AHS ID

        // Calculate total unit price from items
        let totalUnitPrice = 0;
        items.forEach(function(it) {
            totalUnitPrice += (parseFloat(it.unit_price) || 0) * (parseFloat(it.coefficient) || 1);
        });

        groupEl.querySelector('.ahs-group-unit-price').value = totalUnitPrice.toFixed(2);

        // Items
        const itemsWrap = groupEl.querySelector('.ahs-group-items');
        items.forEach(function(it, idx) {
            const row = document.createElement('div');
            row.className = 'grid grid-cols-1 md:grid-cols-5 gap-3';
            row.innerHTML = `
                <input type="hidden" name="items[${groupIndex}][detail][${idx}][estimation_item_id]" value="${it.id}">
                <input type="hidden" name="items[${groupIndex}][detail][${idx}][reference_id]" value="${it.id}">
                <input type="hidden" name="items[${groupIndex}][detail][${idx}][item_type]" value="estimation_item">
                <div class="md:col-span-2">
                    <label class="form-label">Uraian Barang/Pekerjaan</label>
                    <input type="text" class="form-input" name="items[${groupIndex}][detail][${idx}][description]" value="${it.description}" readonly>
                </div>
                <div>
                    <label class="form-label">Koefisien</label>
                    <input type="number" class="form-input item-coef" name="items[${groupIndex}][detail][${idx}][coefficient]" value="${it.coefficient || 1}" step="0.0001" min="0" readonly>
                </div>
                <div>
                    <label class="form-label">Harga Satuan</label>
                    <input type="number" class="form-input item-unit-price" name="items[${groupIndex}][detail][${idx}][unit_price]" value="${it.unit_price || 0}"  min="0" readonly>
                </div>
                <div>
                    <label class="form-label">Grand Total</label>
                    <input type="number" class="form-input item-grand-total" name="items[${groupIndex}][detail][${idx}][grand_total]" value="0" step="0.01" min="0" readonly>
                </div>
            `;
            itemsWrap.appendChild(row);
        });

        container.appendChild(clone);

        // After append, wire up calculations for this group
        const appendedGroup = container.querySelectorAll('.ahs-group')[container.querySelectorAll('.ahs-group').length - 1];
        wireAhsGroupCalculations(appendedGroup);
        // Initial compute
        computeAhsGroupTotals(appendedGroup);
    }

    function renderItemGroup(item, items) {
        const container = document.getElementById('items-container');
        const tmpl = document.getElementById('item-group-template');
        if (!tmpl) {
            return;
        }
        const clone = tmpl.content.cloneNode(true);

        const groupIndex = container.querySelectorAll('.ahs-group, .item-group, .journal-group').length;
        const groupEl = clone.querySelector('.item-group');
        groupEl.setAttribute('data-group-index', groupIndex);

        // Replace GROUP_INDEX in input names
        const inputs = clone.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            if (input.name) {
                input.name = input.name.replace('GROUP_INDEX', groupIndex);
            }
        });

        // Set item type and configure form based on type
        groupEl.querySelector('.item-group-item-type').value = item.type;
        groupEl.querySelector('.item-group-reference-id').value = item.id; // Set reference_id to master data ID
        const typeLabel = getItemTypeLabel(item.type);
        groupEl.querySelector('.item-group-type').textContent = typeLabel;

        // Header - show item type and name
        groupEl.querySelector('.item-group-title').textContent = `${typeLabel}: ${item.title}`;
        groupEl.querySelector('.item-group-description').value = item.description || '';

        container.appendChild(clone);

        // After append, get the actual DOM element and set values
        const appendedGroup = container.querySelectorAll('.item-group')[container.querySelectorAll('.item-group').length - 1];

        // Get the first item data
        const itemData = items[0];
        if (!itemData) return;

        // Set values after element is in DOM
        const unitPriceElement = appendedGroup.querySelector('.item-group-unit-price');
        const coefficientElement = appendedGroup.querySelector('.item-group-coefficient');

        if (unitPriceElement) {
            const unitPrice = parseFloat(itemData.unit_price || 0);
            unitPriceElement.value = unitPrice.toFixed(2);
        }

        if (coefficientElement) {
            coefficientElement.value = parseFloat(itemData.coefficient || 1);
        }

        // Wire up calculations and compute initial totals
        wireItemGroupCalculations(appendedGroup);
        computeItemGroupTotals(appendedGroup);
    }

    function renderJournalGroup(item, items) {
        const container = document.getElementById('items-container');
        const tmpl = document.getElementById('journal-group-template');
        if (!tmpl) {
            return;
        }
        const clone = tmpl.content.cloneNode(true);

        const groupIndex = container.querySelectorAll('.ahs-group, .item-group, .journal-group').length;
        const groupEl = clone.querySelector('.journal-group');
        groupEl.setAttribute('data-group-index', groupIndex);

        // Replace GROUP_INDEX in input names
        const inputs = clone.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            if (input.name) {
                input.name = input.name.replace('GROUP_INDEX', groupIndex);
            }
        });

        // Set reference_id to master data ID
        groupEl.querySelector('.journal-group-reference-id').value = item.id;

        // Header - show item type and name
        groupEl.querySelector('.journal-group-title').textContent = `Journal Worker: ${item.title}`;
        groupEl.querySelector('.journal-group-description').value = item.description || '';

        container.appendChild(clone);

        // After append, get the actual DOM element and set values
        const appendedGroup = container.querySelectorAll('.journal-group')[container.querySelectorAll('.journal-group').length - 1];

        // Get the first item data
        const itemData = items[0];
        if (!itemData) return;

        // Set values after element is in DOM
        const unitPriceElement = appendedGroup.querySelector('.journal-group-unit-price');

        if (unitPriceElement) {
            const unitPrice = parseFloat(itemData.unit_price || 0);
            unitPriceElement.value = unitPrice.toFixed(2);
        }

        // Wire up calculations and compute initial totals
        wireJournalGroupCalculations(appendedGroup);
        computeJournalGroupTotals(appendedGroup);
    }

    function getItemTypeLabel(type) {
        switch (type) {
            case 'worker':
                return 'Pekerja';
            case 'material':
                return 'Material';
            case 'equipment':
                return 'Peralatan';
            case 'journal_worker':
                return 'Journal Worker';
            default:
                return 'Item';
        }
    }

    function wireAhsGroupCalculations(groupEl) {
        // Per-item coefficient and unit_price changes affect grand total and group rollup (for AHS)
        groupEl.querySelectorAll('.item-coef, .item-unit-price').forEach(function(input) {
            input.addEventListener('input', function() {
                computeAhsGroupTotals(groupEl);
            });
        });

        // Volume changes
        const vol = groupEl.querySelector('.ahs-group-volume');
        if (vol) {
            vol.addEventListener('input', function() {
                computeAhsGroupTotals(groupEl);
            });
        }

        // Duration changes for AHS only
        const dur = groupEl.querySelector('.ahs-group-duration');
        if (dur) {
            dur.addEventListener('input', function() {
                computeAhsGroupTotals(groupEl);
            });
        }
    }

    function wireItemGroupCalculations(groupEl) {
        // Coefficient changes
        const coef = groupEl.querySelector('.item-group-coefficient');
        if (coef) {
            coef.addEventListener('input', function() {
                computeItemGroupTotals(groupEl);
            });
        }

        // Volume changes
        const vol = groupEl.querySelector('.item-group-volume');
        if (vol) {
            vol.addEventListener('input', function() {
                computeItemGroupTotals(groupEl);
            });
        }

        // Unit price changes
        const unitPrice = groupEl.querySelector('.item-group-unit-price');
        if (unitPrice) {
            unitPrice.addEventListener('input', function() {
                computeItemGroupTotals(groupEl);
            });
        }
    }

    function wireJournalGroupCalculations(groupEl) {
        // Volume changes
        const vol = groupEl.querySelector('.journal-group-volume');
        if (vol) {
            vol.addEventListener('input', function() {
                computeJournalGroupTotals(groupEl);
            });
        }

        // Unit price changes
        const unitPrice = groupEl.querySelector('.journal-group-unit-price');
        if (unitPrice) {
            unitPrice.addEventListener('input', function() {
                computeJournalGroupTotals(groupEl);
            });
        }
    }

    function computeAhsGroupTotals(groupEl) {
        const vol = parseFloat(groupEl.querySelector('.ahs-group-volume')?.value || '0');
        const headerUnitPriceEl = groupEl.querySelector('.ahs-group-unit-price');
        const totalEl = groupEl.querySelector('.ahs-group-total-price');

        let total = 0;

        // AHS calculation: sum of item details, then multiply by volume and duration
        let unitPriceSum = 0;
        const itemRows = groupEl.querySelectorAll('.ahs-group-items > div');
        itemRows.forEach(function(row) {
            const coef = parseFloat(row.querySelector('.item-coef')?.value || '0');
            const unitPrice = parseFloat(row.querySelector('.item-unit-price')?.value || '0');
            const grand = coef * unitPrice;
            const grandEl = row.querySelector('.item-grand-total');
            if (grandEl) {
                grandEl.value = grand.toFixed(2);
            }
            unitPriceSum += grand;
        });

        // Set header unit_price to sum of item grands
        if (headerUnitPriceEl) {
            headerUnitPriceEl.value = unitPriceSum.toFixed(2);
        }

        // total_price = volume * unit_price_sum * duration (for AHS)
        const dur = parseFloat(groupEl.querySelector('.ahs-group-duration')?.value || '1');
        total = vol * unitPriceSum * dur;

        // Set total price
        if (totalEl) {
            totalEl.value = total.toFixed(2);
        }
    }

    function computeItemGroupTotals(groupEl) {
        const vol = parseFloat(groupEl.querySelector('.item-group-volume')?.value || '0');
        const coefficient = parseFloat(groupEl.querySelector('.item-group-coefficient')?.value || '1');
        const unitPrice = parseFloat(groupEl.querySelector('.item-group-unit-price')?.value || '0');
        const totalEl = groupEl.querySelector('.item-group-total-price');

        // Item calculation: volume * (coefficient * unit_price)
        const total = vol * (coefficient * unitPrice);

        // Set total price
        if (totalEl) {
            totalEl.value = total.toFixed(2);
        }
    }

    function computeJournalGroupTotals(groupEl) {
        const vol = parseFloat(groupEl.querySelector('.journal-group-volume')?.value || '0');
        const unitPrice = parseFloat(groupEl.querySelector('.journal-group-unit-price')?.value || '0');
        const totalEl = groupEl.querySelector('.journal-group-total-price');

        // Journal Worker calculation: volume * unit_price
        const total = vol * unitPrice;

        // Set total price
        if (totalEl) {
            totalEl.value = total.toFixed(2);
        }
    }

    function removeAhsGroup(btn) {
        const grp = btn.closest('.ahs-group');
        if (grp) {
            grp.remove();
        }
    }

    function removeItemGroup(btn) {
        const grp = btn.closest('.item-group');
        if (grp) {
            grp.remove();
        }
    }

    function removeJournalGroup(btn) {
        const grp = btn.closest('.journal-group');
        if (grp) {
            grp.remove();
        }
    }
</script>
@endsection