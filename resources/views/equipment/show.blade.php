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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Equipment Details</h1>
                <p class="text-gray-600 dark:text-gray-400">View equipment information</p>
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

    <!-- Equipment Details -->
    <div class="max-w-4xl">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Equipment Information</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('master.equipment.edit', $equipment) }}" class="btn btn-outline btn-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('master.equipment.destroy', $equipment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this equipment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Equipment Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Equipment Name</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2M9 17H7a2 2 0 01-2-2v-2a2 2 0 012-2h2a2 2 0 012 2v2m0 0v2m0-2h2m-2 0h-2" />
                            </svg>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $equipment->name }}</span>
                        </div>
                    </div>
                    <!-- Equipment Code -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Equipment Code</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $equipment->code ?: 'Not specified' }}</span>
                        </div>
                    </div>
                    <!-- Category -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Category</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $equipment->category ? $equipment->category->name : 'Not specified' }}</span>
                        </div>
                    </div>
                    <!-- TKDN -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">TKDN (%)</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-gray-900 dark:text-white font-medium">{{ number_format($equipment->tkdn, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $equipment->tkdn }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Equipment Type & Pricing Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Equipment Type -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Equipment Type</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            @if($equipment->isDisposable())
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $equipment->getTypeLabel() }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Barang habis pakai / consumable</div>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $equipment->getTypeLabel() }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Peralatan dengan masa pakai tertentu</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Period -->
                    <!-- <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Period (Days)</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            @if($equipment->isDisposable())
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Habis Pakai</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Tidak memiliki periode penggunaan</div>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $equipment->period }} Hari</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Periode penggunaan peralatan</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div> -->
                </div>

                <!-- field Data tambahan Kepemilikan Alat Kerja -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Dibuat</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <span class="text-gray-900 dark:text-white font-medium">{{ $equipment->dibuat }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Dimiliki</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <span class="text-gray-900 dark:text-white font-medium">{{ $equipment->dimiliki }}</span>
                        </div>
                    </div>

                    

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Spesifikasi / Pemasok</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                           
                            <span class="text-gray-900 dark:text-white font-medium">{{ $equipment->spesifikasi ?? '' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mt-6">
                    <!-- Price -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Price</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            <span class="text-gray-900 dark:text-white font-medium">Rp {{ number_format($equipment->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <!-- TKDN Status -->
                <div class="mt-6 space-y-2">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">TKDN Status</label>
                    <div class="p-4 rounded-lg border">
                        @php $tkdn = $equipment->tkdn; @endphp
                        @if($tkdn >= 80)
                        <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <div class="font-medium text-green-900 dark:text-green-100">Excellent TKDN Compliance</div>
                                <div class="text-sm text-green-700 dark:text-green-300">This equipment meets high TKDN requirements</div>
                            </div>
                        </div>
                        @elseif($tkdn >= 60)
                        <div class="flex items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <div class="font-medium text-yellow-900 dark:text-yellow-100">Good TKDN Compliance</div>
                                <div class="text-sm text-yellow-700 dark:text-yellow-300">This equipment meets moderate TKDN requirements</div>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <div class="font-medium text-red-900 dark:text-red-100">Low TKDN Compliance</div>
                                <div class="text-sm text-red-700 dark:text-red-300">This equipment has low TKDN percentage</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- Description -->
                <div class="mt-6 space-y-2">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Description</label>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <span class="text-gray-900 dark:text-white">{{ $equipment->description }}</span>
                    </div>
                </div>
                <!-- Timestamps -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Created Date -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Created Date</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-900 dark:text-white">{{ $equipment->created_at ? $equipment->created_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>
                    <!-- Last Updated -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Last Updated</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="w-5 h-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-900 dark:text-white">{{ $equipment->updated_at ? $equipment->updated_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('master.equipment.index') }}" class="btn btn-outline flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Equipment
                    </a>
                    <a href="{{ route('master.equipment.edit', $equipment) }}" class="btn btn-primary flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Equipment
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection