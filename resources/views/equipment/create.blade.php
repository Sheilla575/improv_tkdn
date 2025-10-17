@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('master.equipment.index') }}" class="btn btn-outline p-2 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tambah Peralatan</h1>
                <p class="text-gray-600 dark:text-gray-400">Tambah data peralatan proyek</p>
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

    <!-- Equipment Form -->
    <div class="max-w-4xl">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Peralatan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('master.equipment.store') }}" method="POST" class="space-y-6" id="equipmentForm">
                    @csrf

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label">Nama Peralatan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input pl-10 w-full @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" required placeholder="Masukkan nama peralatan">
                            </div>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="form-label">Kategori</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <select name="category_id" id="category_id" class="form-input select2 pl-10 w-full @error('category_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Pilih kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Classification TKDN -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tkdn" class="form-label">TKDN (%)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="tkdn" id="tkdn" value="{{ old('tkdn') }}" class="form-input pl-10 w-full" step="0.01" placeholder="Masukkan persentase TKDN">
                            </div>
                            <!-- @error('tkdn') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror
                            @error('tkdn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror -->
                        </div>

                        <div>
                            <label for="classification_tkdn" class="form-label">Klasifikasi TKDN <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <select name="classification_tkdn" id="classification_tkdn" required class="form-input pl-10 w-full select2 @error('classification_tkdn') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Pilih Klasifikasi TKDN...</option>
                                    @foreach(\App\Models\Equipment::getClassificationOptions() as $key => $value)
                                    <option value="{{ $key }}" {{ old('classification_tkdn') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('classification_tkdn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="spesifikasi" class="form-label">Spesifikasi / Pemasok<span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="spesifikasi" id="spesifikasi" value="{{ old('spesifikasi') }}" class="form-input pl-10 w-full @error('spesifikasi') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" placeholder="Opsional">
                        </div>
                        @error('spesifikasi')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- input tambahan -->
                    <label for="dibuat" class="form-label">Kepemilikan Alat Kerja</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="dibuat" class="form-label">Dibuat</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="dibuat" id="dibuat" value="{{ old('dibuat') }}" class="form-input pl-10 w-full @error('dibuat') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" min="0" max="100" step="0.01" placeholder="Opsional">
                            </div>
                            @error('dibuat')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="Dimiliki" class="form-label">Dimiliki</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="dimiliki" id="dimiliki" value="{{ old('dimiliki') }}" class="form-input pl-10 w-full @error('Dimiliki') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" min="0" max="100" step="0.01" placeholder="Opsional">
                            </div>
                            @error('Dimiliki')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Field Satuan -->
                        <!-- <div>
                            <label for="satuan" class="form-label">Satuan<span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" class="form-input pl-10 w-full @error('satuan') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" placeholder="Opsional">
                            </div>
                            @error('satuan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div> -->
                    </div>

                    <!-- Technical Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Equipment Type Selection -->
                        <div class="col-span-2">
                            <label class="form-label">Jenis Barang <span class="text-red-500">*</span></label>
                            <div class="mt-4">
                                <div class="flex space-x-4">
                                    <!-- Barang Sekali Pakai -->
                                    <label class="equipment-type-option cursor-pointer flex items-center" data-type="disposable">
                                        <input type="radio" name="equipment_type" value="disposable" class="sr-only equipment-type-radio" {{ old('equipment_type') === 'disposable' ? 'checked' : '' }}>
                                        <div class="equipment-type-card relative px-3 py-1.5 rounded-md border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 flex items-center space-x-2">
                                            <div class="equipment-type-icon w-4 h-4 bg-red-100 dark:bg-red-900/30 rounded flex items-center justify-center">
                                                <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">Sekali Pakai</span>
                                        </div>
                                    </label>

                                    <!-- Barang Bukan Sekali Pakai -->
                                    <label class="equipment-type-option cursor-pointer flex items-center" data-type="reusable">
                                        <input type="radio" name="equipment_type" value="reusable" class="sr-only equipment-type-radio" {{ old('equipment_type') === 'reusable' || old('equipment_type') === null ? 'checked' : '' }}>
                                        <div class="equipment-type-card relative px-3 py-1.5 rounded-md border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 flex items-center space-x-2">
                                            <div class="equipment-type-icon w-4 h-4 bg-green-100 dark:bg-green-900/30 rounded flex items-center justify-center">
                                                <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">Dapat Dipakai Ulang</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @error('equipment_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="description" class="form-label">Keterangan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-input pl-10 w-full @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" placeholder="Keterangan tambahan (opsional)">
                            </div>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Period Input (Dynamic) -->
                        <!-- <div id="period-container">
                            <label for="period" class="form-label">
                                Period (Hari)
                                <span class="text-red-500">*</span>
                                <span id="period-help-text" class="text-sm font-normal text-gray-500 dark:text-gray-400"></span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="number" name="period" id="period" value="{{ old('period', 1) }}" class="form-input pl-10 w-full @error('period') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" min="0" required placeholder="Masukkan periode (hari)">
                                <div id="period-status" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <div class="hidden" data-status="disposable">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                            Habis Pakai
                                        </span>
                                    </div>
                                    <div class="hidden" data-status="reusable">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                            Dapat Dipakai Ulang
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @error('period')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div> -->
                    </div>

                    <!-- Pricing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="form-label">Harga <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <input type="text" name="price" id="price" value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" class="form-input pl-10 w-full @error('price') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" required placeholder="Masukkan harga peralatan">
                            </div>
                            @error('price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="form-label flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 0011.314-11.314l-4.243-4.243a4 4 0 00-5.657 5.657l4.243 4.243z"></path>
                                </svg>
                                Lokasi Peralatan
                            </label>
                            <select name="location" id="location" class="form-input select2-modern @error('location') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" style="width:100%">
                                <option value="">Pilih Kota Peralatan...</option>
                            </select>
                            @error('location')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('master.equipment.index') }}" class="btn btn-outline flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Peralatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .select2-container--default .select2-selection--single {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        min-height: 44px;
        padding: 8px 12px;
        font-size: 1rem;
        color: #111827;
        transition: border 0.2s;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single.select2-selection--focus {
        border-color: #2563eb;
        outline: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827;
        line-height: 28px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 10px;
    }

    .select2-dropdown {
        border-radius: 0.5rem;
        box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.08);
    }

    .select2-results__option {
        padding-left: 2.5rem;
        position: relative;
    }

    .select2-results__option .city-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #2563eb;
    }
</style>
@endpush

@push('scripts')
<script data-selected-location="{{ old('location') }}">
    $(function() {
        // Location Select2 Setup menggunakan global cities data
        const selectedLocation = $('script[data-selected-location]').attr('data-selected-location');
        const select = $('#location');

        // Setup location select menggunakan helper function global
        window.setupLocationSelect(select, selectedLocation);

        // Equipment Type Logic
        function updateEquipmentTypeUI() {
            // Update visual state of cards
            $('.equipment-type-option').each(function() {
                const $option = $(this);
                const $radio = $option.find('.equipment-type-radio');
                const $card = $option.find('.equipment-type-card');
                const $check = $option.find('.equipment-type-check');

                if ($radio.is(':checked')) {
                    $card.removeClass('border-gray-200 dark:border-gray-700')
                        .addClass('border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20');
                    $check.removeClass('hidden');
                } else {
                    $card.removeClass('border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20')
                        .addClass('border-gray-200 dark:border-gray-700');
                    $check.addClass('hidden');
                }
            });
        }

        function updatePeriodInput() {
            const selectedType = $('input[name="equipment_type"]:checked').val();
            const $periodInput = $('#period');
            const $periodHelpText = $('#period-help-text');
            const $periodStatusElements = $('#period-status [data-status]');

            // Hide all status indicators first
            $periodStatusElements.addClass('hidden');

            if (selectedType === 'disposable') {
                // Barang sekali pakai - period = 0
                $periodInput.val(0)
                    .prop('readonly', true)
                    .removeClass('bg-white dark:bg-gray-800')
                    .addClass('bg-gray-100 dark:bg-gray-700 cursor-not-allowed');
                $periodHelpText.text('(Otomatis diset 0 untuk barang sekali pakai)');
                $('#period-status [data-status="disposable"]').removeClass('hidden');
            } else if (selectedType === 'reusable') {
                // Barang bukan sekali pakai - period bisa diisi manual
                $periodInput.prop('readonly', false)
                    .removeClass('bg-gray-100 dark:bg-gray-700 cursor-not-allowed')
                    .addClass('bg-white dark:bg-gray-800');
                if ($periodInput.val() === '0') {
                    $periodInput.val(1);
                }
                $periodHelpText.text('(Masukkan periode penggunaan dalam hari)');
                $('#period-status [data-status="reusable"]').removeClass('hidden');
            }
        }

        // Handle equipment type selection
        $('.equipment-type-option').on('click', function() {
            const $radio = $(this).find('.equipment-type-radio');
            $radio.prop('checked', true);
            updateEquipmentTypeUI();
            updatePeriodInput();
        });

        // Initialize on page load
        updateEquipmentTypeUI();
        updatePeriodInput();

        // Handle period input validation for reusable equipment
        $('#period').on('input', function() {
            const selectedType = $('input[name="equipment_type"]:checked').val();
            const value = parseInt($(this).val());

            if (selectedType === 'reusable' && value < 1) {
                $(this).val(1);
            }
        });

        // Price formatting dengan pemisah titik
        const priceInput = $('#price');

        // Format angka saat input
        priceInput.on('input', function() {
            let value = this.value.replace(/[^\d]/g, ''); // Hapus semua karakter kecuali angka

            if (value) {
                // Format dengan pemisah titik setiap 3 digit
                value = parseInt(value).toLocaleString('id-ID');
                this.value = value;
            }
        });

        // Format angka saat focus out (untuk memastikan format yang benar)
        priceInput.on('blur', function() {
            let value = this.value.replace(/[^\d]/g, '');

            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
                this.value = value;
            }
        });

        // Format angka saat focus in (hapus pemisah untuk editing)
        priceInput.on('focus', function() {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                this.value = value;
            }
        });

        // Handle form submit - hapus pemisah titik sebelum submit
        $('#equipmentForm').on('submit', function(e) {
            const priceValue = priceInput.val();
            if (priceValue) {
                // Hapus semua karakter kecuali angka sebelum submit
                const cleanValue = priceValue.replace(/[^\d]/g, '');
                priceInput.val(cleanValue);
            }
        });
    });
</script>
@endpush