@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('master.journal.index') }}" class="btn btn-outline p-2 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Journal</h1>
                <p class="text-gray-600 dark:text-gray-400">Update journal information</p>
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

    <!-- Material Form -->
    <div class="max-w-4xl">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Journal Information</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('master.journal.update', $journal) }}" method="POST" class="space-y-6" id="materialForm">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label">Nama Pekerjaan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <input type="text" name="nama_pekerjaan" id="name" class="form-input pl-10 @error('nama_pekerjaan') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ $journal->nama_pekerjaan }}" required placeholder="Enter material pekerjaan">
                            </div>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="specification" class="form-label">Spesifikasi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="spesifikasi_or_kualifikasi" id="specification" class="form-input pl-10 @error('spesifikasi_or_kualifikasi') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ $journal->spesifikasi_or_kualifikasi }}" placeholder="Enter specifications">
                            </div>
                            @error('specification')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="classification_tkdn" class="form-label">Klasifikasi TKDN <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <input type="hidden" name="classification_tkdn" id="classification_tkdn" class="form-input pl-10 @error('classification_tkdn') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="4">
                                <input type="text" id="specification" class="form-input pl-10 @error('classification_tkdn') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="Peralatan (Jasa Umum)" readonly>
                                <!-- <select name="classification_tkdn" id="classification_tkdn" required class="form-input pl-10 select2 @error('classification_tkdn') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Pilih Klasifikasi TKDN...</option>
                                    @foreach(\App\Models\Material::getClassificationOptions() as $key => $value)
                                    <option value="{{ $key }}" {{ old('classification_tkdn') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select> -->
                            </div>
                            @error('classification_tkdn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="negara_asal" class="form-label">Negara Asal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <select name="negara_asal" id="negara_asal" class="form-input select2 pl-10 @error('negara_asal') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="{{ $journal->negara_asal }}" {{ old('negara_asal', $journal->negara_asal) == $journal->negara_asal ? 'selected' : '' }}>{{ $journal->negara_asal }}</option>
                                    <option value="WNI">WNI</option>
                                    <option value="WNA">WNA</option>
                                </select>
                            </div>
                            @error('negara_asal')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="form-label">Satuan Harga <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <input type="text" name="satuan_harga" id="price" value="{{ $journal->satuan_harga ? number_format($journal->satuan_harga, 0, ',', '.') : '' }}" class="form-input pl-10 w-full @error('satuan_harga') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" required placeholder="Masukkan harga peralatan">
                            </div>
                            @error('price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <hr>

                    <!-- Pricing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tkdn" class="form-label">TKDN (%)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="tkdn" id="tkdn" class="form-input pl-10 " placeholder="Enter TKDN value" value="{{ $journal->tkdn }}">
                            </div>
                            <!-- @error('tkdn') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror
                            @error('tkdn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror -->
                        </div>

                        <div>
                            <label for="description" class="form-label">Keterangan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="keterangan" id="description" class="form-input pl-10 @error('keterangan') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ $journal->keterangan }}" placeholder="Enter additional notes">
                            </div>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- <div>
                            <label for="price" class="form-label">Harga Satuan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <input type="text" name="price" id="price" required class="form-input pl-10 @error('price') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" placeholder="Enter price">
                            </div>
                            @error('price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div> -->
                    </div>

                    <!-- Additional Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('master.material.index') }}" class="btn btn-outline flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Journal
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
<script data-selected-location="{{ old('location', $journal->location) }}">
    // Menggunakan global cities data
    $(function() {
        const select = $('#location');
        const oldLocation = $('script[data-selected-location]').attr('data-selected-location');

        // Setup location select menggunakan helper function global
        window.setupLocationSelect(select, oldLocation);

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

        // Handle TKDN input - allow decimal with comma or dot
        const tkdnInput = $('#tkdn');
        tkdnInput.on('input', function() {
            let value = this.value;
            // Allow numbers, comma, and dot
            value = value.replace(/[^\d,\.]/g, '');
            // Ensure only one decimal separator
            const commaCount = (value.match(/,/g) || []).length;
            const dotCount = (value.match(/\./g) || []).length;
            
            if (commaCount > 1) {
                value = value.replace(/,([^,]*)$/, '$1');
            }
            if (dotCount > 1) {
                value = value.replace(/\.([^\.]*)$/, '$1');
            }
            
            this.value = value;
        });

        // Handle form submit - hapus pemisah titik sebelum submit
        $('#materialForm').on('submit', function(e) {
            const priceValue = priceInput.val();
            if (priceValue) {
                // Hapus semua karakter kecuali angka sebelum submit
                const cleanValue = priceValue.replace(/[^\d]/g, '');
                priceInput.val(cleanValue);
            }
            
            // Convert comma to dot for TKDN
            const tkdnValue = tkdnInput.val();
            if (tkdnValue) {
                tkdnInput.val(tkdnValue.replace(',', '.'));
            }
        });
    });
</script>
@endpush