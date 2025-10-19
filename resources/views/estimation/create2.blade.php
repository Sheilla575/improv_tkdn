@extends('layouts.app')

@push('styles')
<style>
    /* Select2 Integration with Tailwind */
    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        padding: 0 12px !important;
        display: flex !important;
        align-items: center !important;
        background-color: #ffffff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #374151 !important;
        line-height: 42px !important;
        padding-left: 0 !important;
        padding-right: 20px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 10px !important;
        top: 1px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #6b7280 transparent transparent transparent !important;
        border-style: solid !important;
        border-width: 5px 4px 0 4px !important;
    }

    .select2-dropdown {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
    }

    .select2-results__option {
        padding: 8px 12px !important;
        color: #374151 !important;
    }

    .select2-results__option--highlighted {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }

    .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 4px 8px !important;
    }

    /* Dark mode support */
    .dark .select2-container .select2-selection--single {
        background-color: #374151 !important;
        border-color: #4b5563 !important;
        color: #f9fafb !important;
    }

    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f9fafb !important;
    }

    .dark .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #9ca3af transparent transparent transparent !important;
    }

    .dark .select2-dropdown {
        background-color: #374151 !important;
        border-color: #4b5563 !important;
    }

    .dark .select2-results__option {
        color: #f9fafb !important;
        background-color: #374151 !important;
    }

    .dark .select2-results__option--highlighted {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }

    .dark .select2-search__field {
        background-color: #4b5563 !important;
        border-color: #6b7280 !important;
        color: #f9fafb !important;
    }

    /* Ensure compatibility with form-input class */
    .select2-container .select2-selection--single.form-input,
    .select2-container.form-input .select2-selection--single {
        height: 42px !important;
        border: 1px solid #d1d5db !important;
    }

    /* Loading state styling */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ route('master.estimation.index') }}" class="btn btn-outline p-2 mr-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tambah AHS</h1>
            <p class="text-gray-600 dark:text-gray-400">Tambah Analisa Harga Satuan pekerjaan baru ke sistem</p>
        </div>
    </div>
</div>

@if($errors->any())
<div class="mb-6">
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <div class="font-medium">Terjadi kesalahan:</div>
            <ul class="mt-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if(session('status'))
<div class="mb-6">
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span>{{ session('status') }}</span>
    </div>
</div>
@endif

<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-8 w-full mx-auto mb-8 border border-gray-100 dark:border-gray-800">
    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4" />
            </svg>
            Informasi AHS
        </h2>
    </div>
    <form action="{{ route('master.estimation.store') }}" method="POST" class="space-y-8" id="estimation-form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="relative">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul AHS <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                        </svg>
                    </span>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-input w-full pl-10 @error('title') border-red-500 @enderror" required placeholder="Judul AHS">
                </div>
                @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="relative">
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <select name="location" id="location" class="form-input w-full pl-10 @error('location') border-red-500 @enderror">
                        <option value="">Pilih Lokasi...</option>
                    </select>
                </div>
                @error('location')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="relative">
                <label for="total_unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 text-gray-400">
                            <text x="2" y="17" font-size="14" font-family="Arial, sans-serif" fill="currentColor" font-weight="bold">Rp</text>
                        </svg>
                    </span>
                    <input type="number" name="total_unit_price" id="total_unit_price" value="{{ old('total_unit_price', 0) }}" class="form-input w-full pl-10 dark:text-white bg-gray-100 dark:bg-gray-700 @error('total_unit_price') border-red-500 @enderror" placeholder="Harga Satuan" readonly>
                </div>
                @error('total_unit_price')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4" />
                    </svg>
                    Item AHS
                </h3>
                <button type="button" class="btn btn-secondary flex items-center gap-2" onclick="addItemRow()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Item
                </button>
            </div>
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 50px;">No</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 175px;">Kategori</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 125px;">Kode</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama/Peralatan</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 100px;">Satuan</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 130px;">Koefisien</th>
                            <!-- // add kolom baru -->
                            <!-- <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 185px;">Jumlah(Qty)</th> -->

                            <!-- //End -->
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 185px;">Harga</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase" style="width: 185px;">Jumlah Harga</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('master.estimation.index') }}" class="btn btn-outline flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancel
            </a>
            <button type="submit" class="btn btn-primary flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan AHS
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<div id="app-data"
    data-workers="{{ json_encode($workers) }}"
    data-materials="{{ json_encode($materials) }}"
    data-equipment="{{ json_encode($equipment) }}"
    style="display:none;">
</div>
<script data-selected-location="{{ old('location') }}">
    // Initialize when dependencies are ready
    $(document).ready(function() {
        console.log('🚀 Initializing AHS functionality...');

        // Initialize data from HTML data attributes
        const appData = document.getElementById('app-data');
        window.workersData = JSON.parse(appData.dataset.workers);
        window.materialsData = JSON.parse(appData.dataset.materials);
        window.equipmentData = JSON.parse(appData.dataset.equipment);

        // All JavaScript code will go here
        let itemIndex = 0;

        console.log('📦 Data loaded - Workers:', window.workersData?.length || 0, 'Materials:', window.materialsData?.length || 0, 'Equipment:', window.equipmentData?.length || 0);

        // Setup location select
        const locationSelect = $('#location');
        if (locationSelect.length) {
            window.setupLocationSelect(locationSelect, $('script[data-selected-location]').attr('data-selected-location'));

            // Handle location change to filter data
            locationSelect.on('change', function() {
                const selectedLocation = $(this).val();
                console.log('📍 Location changed to:', selectedLocation);
                filterDataByLocation(selectedLocation);
            });
        }

        // Filter data based on selected location
        function filterDataByLocation(location) {
            if (!location) {
                console.log('⚠️ No location selected, showing all data');
                // Reset filtered data to original if no location selected
                window.filteredWorkersData = null;
                window.filteredMaterialsData = null;
                window.filteredEquipmentData = null;
                return;
            }

            console.log('🔍 Filtering data for location:', location);

            // Filter workers by location
            const filteredWorkers = window.workersData.filter(worker =>
                worker.location === location
            );

            // Filter materials by location
            const filteredMaterials = window.materialsData.filter(material =>
                material.location === location
            );

            // Filter equipment by location
            const filteredEquipment = window.equipmentData.filter(equipment =>
                equipment.location === location
            );

            console.log('📊 Filtered data - Workers:', filteredWorkers.length, 'Materials:', filteredMaterials.length, 'Equipment:', filteredEquipment.length);

            // Update global data with filtered results
            window.filteredWorkersData = filteredWorkers;
            window.filteredMaterialsData = filteredMaterials;
            window.filteredEquipmentData = filteredEquipment;

            // TIDAK refresh existing item rows - biarkan data yang sudah ada tetap utuh
            console.log('ℹ️ Location changed but existing AHS items remain unchanged');
        }

        // Refresh existing item rows with filtered data - DISABLED untuk location change
        function refreshExistingItemRows() {
            // Function ini tidak akan dipanggil saat location berubah
            // Hanya untuk keperluan lain jika diperlukan
            console.log('⚠️ refreshExistingItemRows called - this should not happen on location change');
        }

        // Simple select2 initialization  
        function initSelect2(element, placeholder) {
            $(element).select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
            console.log('✅ Select2 initialized for:', placeholder);
            return true;
        }

        function addItemRow(item = {}) {
            const tbody = document.getElementById('items-body');
            const row = document.createElement('tr');
            row.innerHTML = `
        <td class="px-3 py-2 item-no dark:text-white">${itemIndex + 1}</td>
        <td class="px-2 py-2">
            <select name="items[${itemIndex}][category]" class="form-input" required onchange="toggleEquipmentInput(this)">
                <option value="">Pilih Kategori</option>
                <option value="worker" ${item.category === 'worker' ? 'selected' : ''}>Tenaga Kerja</option>
                <option value="material" ${item.category === 'material' ? 'selected' : ''}>Material</option>
                <option value="equipment" ${item.category === 'equipment' ? 'selected' : ''}>Peralatan</option>
            </select>
        </td>
        <td class="px-2 py-2"><input type="text" name="items[${itemIndex}][code]" class="form-input dark:text-white bg-gray-100 dark:bg-gray-700" value="${item.code || ''}" readonly></td>
                <td class="px-2 py-2" data-label="Nama/Peralatan">
                    <input type="hidden" name="items[${itemIndex}][reference_id]" class="reference-id-input" value="${item.reference_id || ''}">
                    <input type="text" name="items[${itemIndex}][equipment_name]" class="form-input equipment-name-input" value="${item.equipment_name || ''}" placeholder="Nama/Peralatan">
                </td>
                <td class="px-2 py-2">
                    <input type="text" name="items[${itemIndex}][unit]" class="form-input unit-input bg-gray-100 dark:bg-gray-700" value="${item.unit || ''}" placeholder="Satuan" readonly>
                </td>
                <td class="px-2 py-2"><input type="number" name="items[${itemIndex}][coefficient]" class="form-input" value="${item.coefficient || ''}" step="0.00001" oninput="updateTotalPrice(this)" placeholder="Koefisien"></td>
                <td class="px-2 py-2"><input type="number" name="items[${itemIndex}][unit_price]" class="form-input dark:text-white bg-gray-100 dark:bg-gray-700" value="${item.unit_price || ''}" step="0.00001" oninput="updateTotalPrice(this)" readonly placeholder="Harga Satuan"></td>
                <td class="px-2 py-2"><input type="number" name="items[${itemIndex}][total_price]" class="form-input dark:text-white bg-gray-100 dark:bg-gray-700" value="${item.total_price || ''}" readonly placeholder="Jumlah Harga" readonly></td>
        <td class="px-2 py-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)" title="Hapus Item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
        </td>
    `;
            tbody.appendChild(row);

            // Initialize category select if it has a value
            const categorySelect = row.querySelector('select[name*="[category]"]');
            if (item.category) {
                toggleEquipmentInput(categorySelect);
            }

            itemIndex++;
        }

        function toggleEquipmentInput(select) {
            const row = select.closest('tr');
            const equipmentNameTd = row.querySelector('.equipment-name-input, .equipment-name-select')?.closest('td') || row.querySelector('td:nth-child(4)');
            const referenceIdInput = row.querySelector('.reference-id-input');
            const unitPriceInput = row.querySelector('input[name*="[unit_price]"]');
            const unitInput = row.querySelector('input[name*="[unit]"]');
            const category = select.value;

            // Store the reference_id field name before clearing
            const referenceIdName = referenceIdInput ? referenceIdInput.name : `items[${row.rowIndex - 1}][reference_id]`;

            // Destroy existing select2 if exists
            const existingSelect = equipmentNameTd.querySelector('.equipment-name-select');
            if (existingSelect && typeof $ !== 'undefined' && $.fn.select2 && $(existingSelect).data('select2')) {
                $(existingSelect).select2('destroy');
            }

            // Clear previous input and recreate reference_id field
            equipmentNameTd.innerHTML = `<input type="hidden" name="${referenceIdName}" class="reference-id-input" value="">`;

            // Clear unit price and total price when category changes
            unitPriceInput.value = '';
            const totalPriceInput = row.querySelector('input[name*="[total_price]"]');
            if (totalPriceInput) {
                totalPriceInput.value = '';
            }
            updateMainTotal();

            if (category === 'worker') {
                // Create select2 dropdown for workers
                const selectElement = document.createElement('select');
                selectElement.name = referenceIdName.replace('[reference_id]', '[equipment_name]');
                selectElement.className = 'form-input equipment-name-select';
                selectElement.innerHTML = '<option value="">Pilih Pekerja</option>';

                // Add workers options (filtered by location if available)
                // Untuk items yang sudah ada, gunakan data original jika filtered data tidak tersedia
                const workersToShow = window.filteredWorkersData || window.workersData;
                workersToShow.forEach(worker => {
                    const option = document.createElement('option');
                    option.value = worker.id;
                    option.textContent = `${worker.name} (${worker.unit})`;
                    option.setAttribute('data-price', worker.price);
                    option.setAttribute('data-name', worker.name);
                    option.setAttribute('data-unit', worker.unit);
                    option.setAttribute('data-code', worker.code);
                    selectElement.appendChild(option);
                });

                equipmentNameTd.appendChild(selectElement);

                // Initialize select2 directly
                initSelect2(selectElement, 'Pilih Pekerja');
                $(selectElement).on('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const currentReferenceIdInput = equipmentNameTd.querySelector('.reference-id-input');
                    const codeInput = row.querySelector('input[name*="[code]"]');

                    if (selectedOption && selectedOption.value) {
                        currentReferenceIdInput.value = selectedOption.value;
                        unitPriceInput.value = selectedOption.getAttribute('data-price') || '';
                        unitInput.value = selectedOption.getAttribute('data-unit') || '';

                        // Fill code automatically
                        if (codeInput) {
                            codeInput.value = selectedOption.getAttribute('data-code') || '';
                        }

                        // Remove existing hidden equipment_name input if exists
                        const existingHidden = equipmentNameTd.querySelector('input[type="hidden"]:not(.reference-id-input)');
                        if (existingHidden) {
                            existingHidden.remove();
                        }

                        // Create new hidden equipment_name input
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = selectElement.name;
                        hiddenInput.value = selectedOption.getAttribute('data-name') || '';
                        equipmentNameTd.appendChild(hiddenInput);
                    } else {
                        currentReferenceIdInput.value = '';
                        unitPriceInput.value = '';
                        unitInput.value = '';
                        if (codeInput) {
                            codeInput.value = '';
                        }
                        // Remove hidden equipment_name input when cleared
                        const existingHidden = equipmentNameTd.querySelector('input[type="hidden"]:not(.reference-id-input)');
                        if (existingHidden) {
                            existingHidden.remove();
                        }
                    }
                    updateTotalPrice(unitPriceInput);
                });
            } else if (category === 'material') {
                // Create select2 dropdown for materials
                const selectElement = document.createElement('select');
                selectElement.name = referenceIdName.replace('[reference_id]', '[equipment_name]');
                selectElement.className = 'form-input equipment-name-select';
                selectElement.innerHTML = '<option value="">Pilih Material</option>';

                // Add materials options (filtered by location if available)
                // Untuk items yang sudah ada, gunakan data original jika filtered data tidak tersedia
                const materialsToShow = window.filteredMaterialsData || window.materialsData;
                materialsToShow.forEach(material => {
                    const option = document.createElement('option');
                    option.value = material.id;
                    option.textContent = `${material.name}${material.specification ? ' - ' + material.specification : ''} (${material.unit})`;
                    option.setAttribute('data-price', material.price);
                    option.setAttribute('data-name', `${material.name}${material.specification ? ' - ' + material.specification : ''}`);
                    option.setAttribute('data-unit', material.unit);
                    option.setAttribute('data-code', material.code);
                    selectElement.appendChild(option);
                });

                equipmentNameTd.appendChild(selectElement);

                // Initialize select2 with delay
                setTimeout(() => {
                    if (typeof $ !== 'undefined' && $.fn.select2) {
                        $(selectElement).select2({
                            placeholder: 'Pilih Material',
                            allowClear: true,
                            width: '100%'
                        }).on('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const currentReferenceIdInput = equipmentNameTd.querySelector('.reference-id-input');
                            const codeInput = row.querySelector('input[name*="[code]"]');

                            if (selectedOption && selectedOption.value) {
                                currentReferenceIdInput.value = selectedOption.value;
                                unitPriceInput.value = selectedOption.getAttribute('data-price') || '';
                                unitInput.value = selectedOption.getAttribute('data-unit') || '';

                                // Fill code automatically
                                if (codeInput) {
                                    codeInput.value = selectedOption.getAttribute('data-code') || '';
                                }

                                // Remove existing hidden equipment_name input if exists
                                const existingHidden = equipmentNameTd.querySelector('input[type="hidden"]:not(.reference-id-input)');
                                if (existingHidden) {
                                    existingHidden.remove();
                                }

                                // Create new hidden equipment_name input
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = selectElement.name;
                                hiddenInput.value = selectedOption.getAttribute('data-name') || '';
                                equipmentNameTd.appendChild(hiddenInput);
                            } else {
                                currentReferenceIdInput.value = '';
                                unitPriceInput.value = '';
                                unitInput.value = '';
                                if (codeInput) {
                                    codeInput.value = '';
                                }
                                // Remove hidden equipment_name input when cleared
                                const existingHidden = equipmentNameTd.querySelector('input[type="hidden"]:not(.reference-id-input)');
                                if (existingHidden) {
                                    existingHidden.remove();
                                }
                            }
                            updateTotalPrice(unitPriceInput);
                        });
                    }
                }, 100);

            } else if (category === 'equipment') {
                // Create select2 dropdown for equipment
                const selectElement = document.createElement('select');
                selectElement.name = referenceIdName.replace('[reference_id]', '[equipment_name]');
                selectElement.className = 'form-input equipment-name-select';
                selectElement.innerHTML = '<option value="">Pilih Peralatan</option>';

                // Add equipment options (filtered by location if available)
                // Untuk items yang sudah ada, gunakan data original jika filtered data tidak tersedia
                const equipmentToShow = window.filteredEquipmentData || window.equipmentData;
                equipmentToShow.forEach(equipment => {
                    const option = document.createElement('option');
                    option.value = equipment.id;
                    option.textContent = `${equipment.name}${equipment.description ? ' - ' + equipment.description : ''} (${equipment.period} jam)`;
                    option.setAttribute('data-price', equipment.price);
                    option.setAttribute('data-name', `${equipment.name}${equipment.description ? ' - ' + equipment.description : ''}`);
                    option.setAttribute('data-unit', 'jam');
                    option.setAttribute('data-code', equipment.code);
                    selectElement.appendChild(option);
                });

                equipmentNameTd.appendChild(selectElement);

                // Initialize select2 with delay
                setTimeout(() => {
                    if (typeof $ !== 'undefined' && $.fn.select2) {
                        $(selectElement).select2({
                            placeholder: 'Pilih Peralatan',
                            allowClear: true,
                            width: '100%'
                        }).on('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const currentReferenceIdInput = equipmentNameTd.querySelector('.reference-id-input');
                            const codeInput = row.querySelector('input[name*="[code]"]');

                            if (selectedOption && selectedOption.value) {
                                currentReferenceIdInput.value = selectedOption.value;
                                unitPriceInput.value = selectedOption.getAttribute('data-price') || '';
                                unitInput.value = selectedOption.getAttribute('data-unit') || '';

                                // Fill code automatically
                                if (codeInput) {
                                    codeInput.value = selectedOption.getAttribute('data-code') || '';
                                }

                                // Remove existing hidden equipment_name input if exists
                                const existingHidden = equipmentNameTd.querySelector('input[type="hidden"]:not(.reference-id-input)');
                                if (existingHidden) {
                                    existingHidden.remove();
                                }

                                // Create new hidden equipment_name input
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = selectElement.name;
                                hiddenInput.value = selectedOption.getAttribute('data-name') || '';
                                equipmentNameTd.appendChild(hiddenInput);
                            } else {
                                currentReferenceIdInput.value = '';
                                unitPriceInput.value = '';
                                unitInput.value = '';
                                if (codeInput) {
                                    codeInput.value = '';
                                }
                                // Remove hidden equipment_name input when cleared
                                const existingHidden = equipmentNameTd.querySelector('input[type="hidden"]:not(.reference-id-input)');
                                if (existingHidden) {
                                    existingHidden.remove();
                                }
                            }
                            updateTotalPrice(unitPriceInput);
                        });
                    }
                }, 100);

            } else {
                // Default - free text input
                const inputElement = document.createElement('input');
                inputElement.type = 'text';
                inputElement.name = referenceIdName.replace('[reference_id]', '[equipment_name]');
                inputElement.className = 'form-input equipment-name-input';
                inputElement.placeholder = 'Nama Peralatan';

                equipmentNameTd.appendChild(inputElement);

                // Clear reference_id for equipment
                const currentReferenceIdInput = equipmentNameTd.querySelector('.reference-id-input');
                if (currentReferenceIdInput) {
                    currentReferenceIdInput.value = '';
                }
            }
        }

        function removeItemRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateMainTotal();
            updateItemNumbers();
        }

        function updateItemNumbers() {
            const rows = document.querySelectorAll('#items-body tr');
            rows.forEach((row, index) => {
                const itemNoCell = row.querySelector('.item-no');
                if (itemNoCell) {
                    itemNoCell.textContent = index + 1;
                }
            });
        }

        function updateTotalPrice(input) {
            const row = input.closest('tr');
            const coef = parseFloat(row.querySelector('input[name*="[coefficient]"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
            const totalPrice = Math.ceil(coef * unitPrice);

            row.querySelector('input[name*="[total_price]"]').value = totalPrice;

            // Update main totals
            updateMainTotal();
        }

        function updateMainTotal() {
            const itemRows = document.querySelectorAll('#items-body tr');
            let totalPrice = 0;

            itemRows.forEach(row => {
                const totalPriceInput = row.querySelector('input[name*="[total_price]"]');
                if (totalPriceInput && totalPriceInput.value) {
                    totalPrice += parseFloat(totalPriceInput.value) || 0;
                }
            });

            // Update the main total input
            const mainTotalInput = document.getElementById('total');
            if (mainTotalInput) {
                mainTotalInput.value = Math.ceil(totalPrice);
            }

            // update unit price = total (tanpa margin)
            document.getElementById('total_unit_price').value = Math.ceil(totalPrice);
        }

        // Make functions global so they can be called from HTML
        window.addItemRow = addItemRow;
        window.toggleEquipmentInput = toggleEquipmentInput;
        window.removeItemRow = removeItemRow;
        window.updateTotalPrice = updateTotalPrice;
        window.updateMainTotal = updateMainTotal;

        // Handle form submission
        const form = document.getElementById('estimation-form');
        const submitButton = form.querySelector('button[type="submit"]');
        const submitButtonText = submitButton.innerHTML;

        // Calculate initial totals if there are existing items
        updateMainTotal();



        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Validate form
            if (!validateForm()) {
                return false;
            }

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = `
            <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        `;

            // Submit form after short delay
            setTimeout(() => {
                form.submit();
            }, 500);
        });

        function validateForm() {
            let isValid = true;

            // Clear previous error states
            const inputs = form.querySelectorAll('.border-red-500');
            inputs.forEach(input => {
                input.classList.remove('border-red-500');
            });

            // Validate required fields
            const title = document.getElementById('title');
            if (!title.value.trim()) {
                title.classList.add('border-red-500');
                showError('Judul AHS harus diisi');
                isValid = false;
            }

            // Validate AHS items
            const itemRows = document.querySelectorAll('#items-body tr');
            if (itemRows.length === 0) {
                showError('Minimal harus ada satu item AHS');
                isValid = false;
            } else {
                // Validate each item row
                itemRows.forEach((row, index) => {
                    const category = row.querySelector('select[name*="[category]"]');
                    const code = row.querySelector('input[name*="[code]"]');
                    const equipmentName = row.querySelector('input[name*="[equipment_name]"]');
                    const coefficient = row.querySelector('input[name*="[coefficient]"]');
                    const unitPrice = row.querySelector('input[name*="[unit_price]"]');

                    if (!category.value) {
                        category.classList.add('border-red-500');
                        showError(`Kategori pada item ${index + 1} harus dipilih`);
                        isValid = false;
                    }

                    if (!equipmentName.value.trim()) {
                        equipmentName.classList.add('border-red-500');
                        showError(`Nama/Peralatan pada item ${index + 1} harus diisi`);
                        isValid = false;
                    }

                    if (!coefficient.value || parseFloat(coefficient.value) <= 0) {
                        coefficient.classList.add('border-red-500');
                        showError(`Koefisien pada item ${index + 1} harus lebih dari 0`);
                        isValid = false;
                    }

                    if (!unitPrice.value || parseFloat(unitPrice.value) <= 0) {
                        unitPrice.classList.add('border-red-500');
                        showError(`Harga satuan pada item ${index + 1} harus lebih dari 0`);
                        isValid = false;
                    }
                });
            }

            if (!isValid) {
                // Reset button state if validation fails
                submitButton.disabled = false;
                submitButton.innerHTML = submitButtonText;
            }

            return isValid;
        }

        function showError(message) {
            // Remove existing error alerts
            const existingAlert = document.querySelector('.error-alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            // Create new error alert
            const alert = document.createElement('div');
            alert.className = 'error-alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            alert.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;

            // Insert alert at the top of the form
            form.insertBefore(alert, form.firstChild);

            // Scroll to top to show error
            form.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            // Auto remove alert after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        console.log('✅ All AHS functions loaded and ready');
    }); // End of $(document).ready
</script>
@endpush