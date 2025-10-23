    @extends('layouts.app')

    @section('content')

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <!-- Hero Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=" 60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" %3E%3Cg fill="none" fill-rule="evenodd" %3E%3Cg fill="%23ffffff" fill-opacity="0.05" %3E%3Ccircle cx="30" cy="30" r="2" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1 min-w-0">
                        <!-- Breadcrumb -->
                        <nav class="flex mb-6" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('service.index') }}" class="inline-flex items-center text-blue-200 hover:text-white transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        Services
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-1 text-blue-200 md:ml-2">Detail Service</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>

                        <!-- Main Header Content -->
                        <div class="flex items-start space-x-4 mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h1 class="text-4xl font-bold text-white mb-2">{{ $service->service_name }}</h1>
                                <p class="text-xl text-blue-100 mb-2">{{ $service->getFormTitle() }}</p>
                                <p class="text-lg text-blue-200 mb-4">
                                    @if($projectType === 'tkdn_jasa')
                                    TKDN Jasa (Form 3.1 - 3.5)
                                    @elseif($projectType === 'tkdn_barang_jasa')
                                    TKDN Barang & Jasa (Form 4.1 - 4.7)
                                    @else
                                    {{ $service->getFormCategoryLabel() }}
                                    @endif
                                </p>

                                <!-- Status and Category Badges -->
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 text-white border border-white/30 backdrop-blur-sm">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                        {{ ucfirst($service->status) }}
                                    </span>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $projectType === 'tkdn_jasa' ? 'bg-blue-500/30 text-blue-100 border border-blue-400/30' : 'bg-green-500/30 text-green-100 border border-green-400/30' }} backdrop-blur-sm">
                                        @if($projectType === 'tkdn_jasa')
                                        TKDN Jasa
                                        @elseif($projectType === 'tkdn_barang_jasa')
                                        TKDN Barang & Jasa
                                        @else
                                        {{ $service->getFormCategoryLabel() }}
                                        @endif
                                    </span>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-500/30 text-purple-100 border border-purple-400/30 backdrop-blur-sm">
                                        {{ $service->getServiceTypeLabel() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 lg:mt-0 lg:ml-8">
                        <div class="flex flex-col sm:flex-row gap-3">
                            @if($service->status === 'draft')
                            <!-- Edit Button -->
                            <!-- <a href="{{ route('service.edit', $service) }}" class="inline-flex items-center justify-center px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-sm font-medium text-white hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Service
                            </a> -->

                            <!-- Generate All Forms Button -->
                            <!-- <form action="{{ route('service.generate', $service) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 border border-transparent rounded-xl text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4m-6 6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4"></path>
                                    </svg>
                                    Generate All Forms
                                </button>
                            </form> -->

                            <!-- Submit Button -->
                            <!-- <form action="{{ route('service.submit', $service) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 border border-transparent rounded-xl text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Submit for Approval
                                </button>
                            </form> -->

                            <!-- Approve Button -->
                            <button onclick="openApproveModal()" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 border border-transparent rounded-xl text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Setujui
                            </button>

                            <!-- Add Comment Button -->
                            <button onclick="openCommentModal()" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 border border-transparent rounded-xl text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Tambah Komentar
                            </button>

                            @endif
                        </div>

                        <!-- Secondary Actions - Generate Individual Forms -->
                        @if($service->status === 'draft')
                        <!-- <div class="mt-4">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-blue-100 font-medium">Generate Individual Forms:</span>
                                <div class="flex flex-wrap gap-2">
                                    <form action="{{ route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.1']) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white/20 hover:bg-white/30 border border-white/30 rounded-lg text-xs font-medium text-white hover:text-white transition-all duration-200 backdrop-blur-sm">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Form 3.1
                                        </button>
                                    </form>
                                    <form action="{{ route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.2']) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 border border-green-200 dark:border-green-700 rounded-md text-xs font-medium text-green-700 dark:text-green-300 hover:text-green-800 dark:hover:text-green-200 transition-all duration-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            Form 3.2
                                        </button>
                                    </form>
                                    <form action="{{ route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.3']) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 border border-purple-200 dark:border-purple-700 rounded-md text-xs font-medium text-purple-700 dark:text-purple-300 hover:text-purple-800 dark:hover:text-purple-200 transition-all duration-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            Form 3.3
                                        </button>
                                    </form>
                                    <form action="{{ route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.4']) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/30 dark:hover:bg-orange-900/50 border border-orange-200 dark:border-orange-700 rounded-md text-xs font-medium text-orange-700 dark:text-orange-300 hover:text-orange-800 dark:hover:text-orange-200 transition-all duration-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Form 3.4
                                        </button>
                                    </form>
                                    <form action="{{ route('service.generate-form', ['service' => $service->id, 'formNumber' => '3.5']) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-700 rounded-md text-xs font-medium text-indigo-700 dark:text-indigo-300 hover:text-indigo-800 dark:hover:text-indigo-200 transition-all duration-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Form 3.5
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div> -->
                        @endif


                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content Container -->
        <div class="py-8">
            <!-- Project Type Information -->
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Informasi Project Type
                        </h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $projectType === 'tkdn_jasa' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' }}">
                            @if($projectType === 'tkdn_jasa')
                            TKDN Jasa
                            @elseif($projectType === 'tkdn_barang_jasa')
                            TKDN Barang & Jasa
                            @else
                            {{ ucfirst($projectType) }}
                            @endif
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Form yang Tersedia:</h4>
                            <div class="flex flex-wrap gap-2">
                                @if($projectType === 'tkdn_jasa')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">Form 3.1</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">Form 3.2</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">Form 3.3</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200">Form 3.4</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">Form 3.5</span>
                                @elseif($projectType === 'tkdn_barang_jasa')
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">Form 4.1</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">Form 4.2</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">Form 4.3</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200">Form 4.4</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">Form 4.5</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-200">Form 4.6</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-200">Form 4.7</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data yang Tersedia:</h4>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Service Items:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $groupedItems->flatten()->count() }} item</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">HPP Items:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ isset($hppItems) ? $hppItems->flatten()->count() : 0 }} item</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Form yang Digenerate:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $groupedItems->keys()->count() }} form</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Status and Type Badges -->
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <div class="inline-flex items-center border border-blue-200 px-3 py-1 rounded-full text-sm font-medium {{ $service->getStatusBadgeClass() }} shadow-sm">
                    @switch($service->status)
                    @case('draft')
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Draft
                    @break
                    @case('submitted')
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Submitted
                    @break
                    @case('approved')
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approved
                    @break
                    @case('rejected')
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Rejected
                    @break
                    @case('generated')
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Generated
                    @break
                    @default
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Draft
                    @endswitch
                </div>
                <div class="inline-flex items-center ml-2 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 border border-blue-200 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    {{ $service->getServiceTypeLabel() }}
                </div>
            </div>

            <!-- Enhanced Action Buttons Section -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 100 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                        Service Actions
                    </h3>
                </div>

                <!-- Primary Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @if($service->status === 'draft')
                    <!-- Edit Service -->
                    <!-- <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100">Edit Service</h4>
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 mb-3">Modify service details and configuration</p>
                        <a href="{{ route('service.edit', $service) }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md text-xs font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Service
                        </a>
                    </div> -->

                    <!-- Generate All Forms -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-purple-900 dark:text-purple-100">Generate All Forms</h4>
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4m-6 6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-purple-700 dark:text-purple-300 mb-3">Generate all TKDN forms at once</p>
                        <form action="{{ route('service.generate', $service) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-purple-600 hover:bg-purple-700 border border-transparent rounded-md text-xs font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4m-6 6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4"></path>
                                </svg>
                                Generate All
                            </button>
                        </form>
                    </div>

                    <!-- Submit for Approval -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100">Submit for Approval</h4>
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 mb-3">Submit service for approval process</p>
                        <form action="{{ route('service.submit', $service) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md text-xs font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Submit
                            </button>
                        </form>
                    </div>
                    @endif

                    @if($service->status === 'submitted')
                    <!-- Approve Service -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-green-900 dark:text-green-100">Approve Service</h4>
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-green-700 dark:text-green-300 mb-3">Approve the submitted service</p>
                        <form action="{{ route('service.approve', $service) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-md text-xs font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve
                            </button>
                        </form>
                    </div>

                    <!-- Reject Service -->
                    <div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-4 border border-red-200 dark:border-red-700">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-red-900 dark:text-red-100">Reject Service</h4>
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <p class="text-xs text-red-700 dark:text-red-300 mb-3">Reject the submitted service</p>
                        <form action="{{ route('service.reject', $service) }}" method="POST" class="inline w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-md text-xs font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <!-- Secondary Actions - Generate Individual Forms -->
                @if($service->status === 'generated' || $service->status === 'approved')
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Export Forms</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Select specific TKDN forms to generate</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @if($projectType === 'tkdn_jasa')
                        <!-- TKDN Jasa Forms (3.1 - 3.5) -->
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-md text-xs font-medium text-blue-700 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Form 3.1
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 border border-green-200 dark:border-green-700 rounded-md text-xs font-medium text-green-700 dark:text-green-300 hover:text-green-800 dark:hover:text-green-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Form 3.2
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 border border-purple-200 dark:border-purple-700 rounded-md text-xs font-medium text-purple-700 dark:text-purple-300 hover:text-purple-800 dark:hover:text-purple-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Form 3.3
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/30 dark:hover:bg-orange-900/50 border border-orange-200 dark:border-orange-700 rounded-md text-xs font-medium text-orange-700 dark:text-orange-300 hover:text-orange-800 dark:hover:text-orange-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Form 3.4
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-700 rounded-md text-xs font-medium text-indigo-700 dark:text-indigo-300 hover:text-indigo-800 dark:hover:text-indigo-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Form 3.5
                            </button>
                        </form>
                        @elseif($projectType === 'tkdn_barang_jasa')
                        <!-- TKDN Barang & Jasa Forms (4.1 - 4.7) -->
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-md text-xs font-medium text-blue-700 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Form 4.1
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 border border-green-200 dark:border-green-700 rounded-md text-xs font-medium text-green-700 dark:text-green-300 hover:text-green-800 dark:hover:text-green-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Form 4.2
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 border border-purple-200 dark:border-purple-700 rounded-md text-xs font-medium text-purple-700 dark:text-purple-300 hover:text-purple-800 dark:hover:text-purple-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Form 4.3
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/30 dark:hover:bg-orange-900/50 border border-orange-200 dark:border-orange-700 rounded-md text-xs font-medium text-orange-700 dark:text-orange-300 hover:text-orange-800 dark:hover:text-orange-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Form 4.4
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 border border-yellow-200 dark:border-yellow-700 rounded-md text-xs font-medium text-yellow-700 dark:text-yellow-300 hover:text-yellow-800 dark:hover:text-yellow-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Form 4.5
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-teal-100 hover:bg-teal-200 dark:bg-teal-900/30 dark:hover:bg-teal-900/50 border border-teal-200 dark:border-teal-700 rounded-md text-xs font-medium text-teal-700 dark:text-teal-300 hover:text-teal-800 dark:hover:text-teal-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Form 4.6
                            </button>
                        </form>
                        <form action="" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-pink-100 hover:bg-pink-200 dark:bg-pink-900/30 dark:hover:bg-pink-900/50 border border-pink-200 dark:border-pink-700 rounded-md text-xs font-medium text-pink-700 dark:text-pink-300 hover:text-pink-800 dark:hover:text-pink-200 transition-all duration-200">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Form 4.7
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endif

            </div> <!-- Detail Item Service berdasarkan Kategori -->

            <!-- Form Navigation Tabs -->
            <div class="mb-8">
                <div class="flex flex-wrap gap-2 overflow-x-auto pb-2">
                    @if($projectType === 'tkdn_jasa')
                    <!-- Individual Form Tabs (3.1, 3.2, 3.3, 3.4) -->
                    <button
                        onclick="showForm('form-3-1')"
                        id="tab-3-1"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium whitespace-nowrap transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Form 3.1
                    </button>
                    <button
                        onclick="showForm('form-3-2')"
                        id="tab-3-2"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Form 3.2
                    </button>
                    <button
                        onclick="showForm('form-3-3')"
                        id="tab-3-3"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Form 3.3
                    </button>
                    <button
                        onclick="showForm('form-3-4')"
                        id="tab-3-4"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Form 3.4
                    </button>

                    <!-- Summary Form Tab (3.5) - Different Style -->
                    <div class="flex items-center mx-2">
                        <div class="w-px h-8 bg-gray-300 dark:bg-gray-600"></div>
                    </div>
                    <button
                        onclick="showForm('form-3-5')"
                        id="tab-3-5"
                        class="inline-flex items-center px-6 py-3 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl font-medium whitespace-nowrap transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border-2 border-purple-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-semibold">Form 3.5</span>
                        <span class="ml-2 px-2 py-0.5 bg-gray-300/20 rounded-full text-xs font-medium">Summary</span>
                    </button>
                    @elseif($projectType === 'tkdn_barang_jasa')
                    <!-- Form 4.x Tabs -->
                    <button
                        onclick="showForm('form-4-1')"
                        id="tab-4-1"
                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium whitespace-nowrap transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Form 4.1
                    </button>
                    <button
                        onclick="showForm('form-4-2')"
                        id="tab-4-2"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Form 4.2
                    </button>
                    <button
                        onclick="showForm('form-4-3')"
                        id="tab-4-3"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        </svg>
                        Form 4.3
                    </button>
                    <button
                        onclick="showForm('form-4-4')"
                        id="tab-4-4"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Form 4.4
                    </button>
                    <button
                        onclick="showForm('form-4-5')"
                        id="tab-4-5"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        Form 4.5
                    </button>
                    <button
                        onclick="showForm('form-4-6')"
                        id="tab-4-6"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Form 4.6
                    </button>
                    <button
                        onclick="showForm('form-4-7')"
                        id="tab-4-7"
                        class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Form 4.7
                    </button>
                    @endif
                </div>

                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-6 px-4 py-2" aria-label="Tabs">
                        <button
                            onclick="showServiceTab('data-service-tab')"
                            id="data-service-tab-btn"
                            class="service-tab-button group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-gray-900 py-3 px-4 text-center text-sm font-medium transition-colors duration-150 ease-in-out hover:text-gray-700 dark:hover:text-gray-300 focus:z-10 active">
                            <span class="active-indicator absolute inset-x-0 bottom-0 h-0.5 bg-blue-500 dark:bg-blue-400 opacity-0 transition-opacity duration-200"></span>
                            Data Service
                        </button>
                        <button
                            onclick="showServiceTab('log-service-tab')"
                            id="log-service-tab-btn"
                            class="service-tab-button group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-gray-900 py-3 px-4 text-center text-sm font-medium transition-colors duration-150 ease-in-out hover:text-gray-700 dark:hover:text-gray-300 focus:z-10">
                            <span class="active-indicator absolute inset-x-0 bottom-0 h-0.5 bg-blue-500 dark:bg-blue-400 opacity-0 transition-opacity duration-200"></span>
                            Log Activity Service
                        </button>
                    </nav>
                </div>

                <style>
                    .tab-button.active .active-indicator {
                        opacity: 1;
                    }
                </style>
                <!-- Tab Content -->

                <!-- Log Activity Service Tab -->
                <div id="log-service-tab" class="service-tab-content hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Log Activity Service
                            </h3>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        {{-- Log creation --}}
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->creator->name ?? 'System' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($service->creator)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    {{ $service->creator->role ?? 'User' }}
                                                </span>
                                                @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">System</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                Service TKDN dibuat: {{ $service->service_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    Created
                                                </span>
                                            </td>
                                        </tr>

                                        {{-- Log updates --}}
                                        @if($service->updated_at != $service->created_at)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->updated_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->updater->name ?? 'System' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($service->updater)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    {{ $service->updater->role ?? 'User' }}
                                                </span>
                                                @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">System</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                Service TKDN diperbarui
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                                    Updated
                                                </span>
                                            </td>
                                        </tr>
                                        @endif

                                        {{-- Log generate forms --}}
                                        @if($service->status === 'generated' || $service->status === 'approved')
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->generated_at ? \Carbon\Carbon::parse($service->generated_at)->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->generator->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($service->generator)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    {{ $service->generator->role ?? 'User' }}
                                                </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                Form TKDN digenerate dari data HPP
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
                                                    Generated
                                                </span>
                                            </td>
                                        </tr>
                                        @endif

                                        {{-- Log status changes --}}
                                        @if($service->status === 'submitted')
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->submitted_at ? \Carbon\Carbon::parse($service->submitted_at)->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->submitter->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($service->submitter)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    {{ $service->submitter->role ?? 'User' }}
                                                </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                Service diajukan untuk persetujuan
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    Submitted
                                                </span>
                                            </td>
                                        </tr>
                                        @endif

                                        @if($service->status === 'approved')
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->approved_at ? \Carbon\Carbon::parse($service->approved_at)->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->approver->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($service->approver)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                    {{ $service->approver->role ?? 'Approver' }}
                                                </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                Service disetujui{{ $service->approval_notes ? ': ' . $service->approval_notes : '' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                    Approved
                                                </span>
                                            </td>
                                        </tr>
                                        @endif

                                        @if($service->status === 'rejected')
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->rejected_at ? \Carbon\Carbon::parse($service->rejected_at)->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $service->rejector->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($service->rejector)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                    {{ $service->rejector->role ?? 'Approver' }}
                                                </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                Service ditolak{{ $service->rejection_notes ? ': ' . $service->rejection_notes : '' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                    Rejected
                                                </span>
                                            </td>
                                        </tr>
                                        @endif

                                        {{-- Display logs from service_logs table --}}
                                        @foreach($service->logs as $log)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $log->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $log->user->name ?? 'System' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($log->user)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $log->action === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' }}">
                                                    {{ $log->user->role ?? 'User' }}
                                                </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                @if($log->action === 'approved')
                                                Service disetujui{{ $log->notes ? ': ' . $log->notes : '' }}
                                                @elseif($log->action === 'commented')
                                                Komentar: {{ $log->notes }}
                                                @else
                                                {{ ucfirst($log->action) }}{{ $log->notes ? ': ' . $log->notes : '' }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($log->action === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                    Approved
                                                </span>
                                                @elseif($log->action === 'commented')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
                                                    Comment
                                                </span>
                                                @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    {{ ucfirst($log->action) }}
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                        @php
                                        // Check if there are no additional logs to show
                                        $hasAdditionalLogs = $service->logs->count() > 0 ||
                                        ($service->updated_at != $service->created_at) ||
                                        ($service->status !== 'draft');
                                        @endphp

                                        {{-- If no additional activity logs exist --}}
                                        @if(!$hasAdditionalLogs)
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                    <p class="text-sm font-medium">Belum ada activity log lainnya</p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Activity log akan muncul setelah ada perubahan status atau update data</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Log Activity Service Tab -->

                <!-- Data Service Tab -->
                <div id="data-service-tab" class="service-tab-content">
                    <!-- Form Content -->
                    @if($projectType === 'tkdn_jasa')
                    <div id="form-3-1" class="form-content hidden">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-blue-50 dark:bg-blue-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Form 3.1: TKDN Jasa untuk Overhead & Manajemen
                                    </h3>

                                    <!-- Export Excel Button for Form 3.1 -->
                                    @if($service->status === 'generated' || $service->status === 'approved')
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.1']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Export Excel
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">


                                <!-- HPP Data Table -->
                                @if($projectType === 'tkdn_jasa')
                                @php
                                // Filter HPP items for Form 3.1 (Overhead & Manajemen) - classification_tkdn = 1
                                $hppItems31 = $allHppItemsFlat->filter(function($item) {
                                // Handle both object and array formats
                                $masterClassification = is_array($item)
                                ? ($item['master_classification'] ?? [])
                                : ($item->master_classification ?? []);

                                return (
                                (isset($masterClassification['worker']) && $masterClassification['worker'] == 1) ||
                                (isset($masterClassification['material']) && $masterClassification['material'] == 1) ||
                                (isset($masterClassification['equipment']) && $masterClassification['equipment'] == 1)
                                );
                                });
                                @endphp
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Informasi Umum - Form 3.1
                                        </h5>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="space-y-4">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Penyedia Barang / Jasa</label>
                                                        <p class="text-base font-semibold text-blue-900 dark:text-blue-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Alamat</label>
                                                        <p class="text-base font-semibold text-blue-900 dark:text-blue-100">{{ $service->provider_address ?: 'Jl. Sudirman No. 123, Jakarta Pusat' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Nama Jasa</label>
                                                        <p class="text-base font-semibold text-blue-900 dark:text-blue-100">{{ $service->service_name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="space-y-4">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Pengguna Barang/Jasa</label>
                                                            <p class="text-base font-semibold text-indigo-900 dark:text-indigo-100">{{ $service->user_name ?: 'PT Pembangunan Indonesia' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">No. Dokumen Jasa</label>
                                                            <p class="text-base font-semibold text-indigo-900 dark:text-indigo-100">{{ $service->document_number ?: 'DOC-2024-001' }}</p>
                                                        </div>
                                                    </div>
                                                </div> -->
                                        </div>
                                    </div>
                                </div>

                                <!-- HPP Data Table -->
                                @if($projectType === 'tkdn_jasa')
                                @php
                                // Calculate total from all HPP items for percentage calculation
                                $totalHppValue = collect($allHppItemsFlat)->sum('total_price');

                                // Create fixed items for Form 3.1
                                $overheadAmount = $totalHppValue * 0.08; // 8%
                                $managementAmount = $totalHppValue * 0.12; // 12%

                                $fixedItems31 = [
                                [
                                'description' => 'Overhead',
                                'amount' => $overheadAmount
                                ],
                                [
                                'description' => 'Management',
                                'amount' => $managementAmount
                                ]
                                ];
                                @endphp
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Data HPP - TKDN Classification 3.1
                                        </h5>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kualifikasi</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">WN</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Upah (Rupiah)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="8" class="px-6 py-2"></th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">KDN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($fixedItems31 as $index => $item)
                                                <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item['description'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">WNI</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            <!-- 100% -->
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">1</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">1 paket</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($item['amount'], 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($item['amount'], 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">-</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($item['amount'], 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach

                                                <!-- Sub Total -->
                                                @php
                                                $subtotal31 = $overheadAmount + $managementAmount;
                                                @endphp
                                                <tr class="bg-blue-50 dark:bg-blue-900/20 font-semibold">
                                                    <td colspan="7" class="px-6 py-4 text-center text-sm font-bold text-blue-900 dark:text-blue-100">SUB TOTAL</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-blue-900 dark:text-blue-100">{{ number_format($subtotal31, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-blue-900 dark:text-blue-100">{{ number_format($subtotal31, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-center text-sm font-bold text-blue-900 dark:text-blue-100">-</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-blue-900 dark:text-blue-100">{{ number_format($subtotal31, 0, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-24 h-24 mx-auto mb-6 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data HPP</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data HPP dengan TKDN Classification 3.1 yang ditemukan untuk project ini.</p>
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Data akan muncul setelah generate form TKDN dari HPP yang tersedia.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="form-3-2" class="form-content hidden">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-green-50 dark:bg-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Form 3.2: TKDN Jasa untuk Alat / Fasilitas Kerja
                                    </h3>

                                    <!-- Export Excel Button for Form 3.2 -->
                                    @if($service->status === 'generated' || $service->status === 'approved')
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.2']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Export Excel
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Header Information -->
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-700">
                                    <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Informasi Umum - Form 3.2
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Penyedia Barang / Jasa</label>
                                                    <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Alamat</label>
                                                    <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_address ?: 'Jl. Sudirman No. 123, Jakarta Pusat' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Nama Jasa</label>
                                                    <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->service_name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="space-y-4">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-emerald-700 dark:text-emerald-300 mb-1">Pengguna Barang/Jasa</label>
                                                            <p class="text-base font-semibold text-emerald-900 dark:text-emerald-100">{{ $service->user_name ?: 'PT Pembangunan Indonesia' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-emerald-700 dark:text-emerald-300 mb-1">No. Dokumen Jasa</label>
                                                            <p class="text-base font-semibold text-emerald-900 dark:text-emerald-100">{{ $service->document_number ?: 'DOC-2024-001' }}</p>
                                                        </div>
                                                    </div>
                                                </div> -->
                                    </div>
                                </div>

                                <!-- HPP Data Table -->
                                @if($projectType === 'tkdn_jasa')
                                @php
                                // Filter HPP items for Form 3.2 (Alat Kerja) - classification_tkdn = 2
                                $hppItems32 = $allHppItemsFlat->filter(function($item) {
                                // Handle both object and array formats
                                $masterClassification = is_array($item)
                                ? ($item['master_classification'] ?? [])
                                : ($item->master_classification ?? []);

                                return (
                                (isset($masterClassification['worker']) && $masterClassification['worker'] == 2) ||
                                (isset($masterClassification['material']) && $masterClassification['material'] == 2) ||
                                (isset($masterClassification['equipment']) && $masterClassification['equipment'] == 2)
                                );
                                });
                                @endphp
                                @php
                                $hppItems32 = $service->items()->where('tkdn_classification', '3.2')->get();
                                @endphp

                                @if($hppItems32->isNotEmpty())
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Data HPP - TKDN Classification 3.2
                                        </h5>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kualifikasi</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dibuat</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dimiliki</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Upah (Rupiah)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="9" class="px-6 py-2"></th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">KDN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                                                @foreach($hppItems32 as $index => $serviceItem)
                                                @php
                                                $tkdnPercent = is_array($serviceItem) ? ($serviceItem['tkdn_percentage'] ?? 0) : ($serviceItem->tkdn_percentage ?? 0);
                                                $totalPrice = is_array($serviceItem) ? ($serviceItem['total_price'] ?? 0) : ($serviceItem->total_price ?? 0);
                                                $kdn = is_array($serviceItem) ? ($serviceItem['domestic_cost'] ?? 0) : ($serviceItem->domestic_cost ?? 0);
                                                $kln = is_array($serviceItem) ? ($serviceItem['foreign_cost'] ?? 0) : ($serviceItem->foreign_cost ?? 0);

                                                @endphp
                                                <tr class="hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ is_array($serviceItem) ? $serviceItem['description'] : $serviceItem->description }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ is_array($serviceItem) ? ($serviceItem['spesifikasi']) : $serviceItem->spesifikasi }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ is_array($serviceItem) ? ($serviceItem['dibuat']) : $serviceItem->dibuat }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ is_array($serviceItem) ? ($serviceItem['dimiliki']) : $serviceItem->dimiliki }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                            {{ number_format($tkdnPercent, 0) }}%
                                                        </span>
                                                    </td>
                                                    @php
                                                    $quantity = is_array($serviceItem) ? ($serviceItem['koefisien'] ?? 1) : ($serviceItem->estimationItem->koefisien ?? '-');
                                                    @endphp
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
                                                        {{ optional( $serviceItem->hpp?->hppItems->first()?->estimation?->estimationItems->first() )->koefisien ?? '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">1 ls</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($kdn, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($kln, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach

                                                <!-- Sub Total -->
                                                @php
                                                $totalKdn32 = collect($hppItems32)->sum(function($item) {
                                                return is_array($item) ? ($item['domestic_cost'] ?? 0) : ($item->domestic_cost ?? 0);
                                                });
                                                $totalKln32 = collect($hppItems32)->sum(function($item) {
                                                return is_array($item) ? ($item['foreign_cost'] ?? 0) : ($item->foreign_cost ?? 0);
                                                });
                                                $subtotal32 = collect($hppItems32)->sum(function($item) {
                                                return is_array($item) ? ($item['total_price'] ?? 0) : ($item->total_price ?? 0);
                                                });
                                                @endphp
                                                <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                                    <td colspan="8" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($subtotal32, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalKdn32, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($totalKln32, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($subtotal32, 0, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-24 h-24 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data HPP</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data HPP dengan TKDN Classification 3.2 yang ditemukan untuk project ini.</p>
                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                            <p class="text-sm text-green-800 dark:text-green-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Data akan muncul setelah generate form TKDN dari HPP yang tersedia.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @else
                                <div class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-24 h-24 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data HPP</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data HPP dengan TKDN Classification 3.2 yang ditemukan untuk project ini.</p>
                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                            <p class="text-sm text-green-800 dark:text-green-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Data akan muncul setelah generate form TKDN dari HPP yang tersedia.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="form-3-3" class="form-content hidden">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-purple-50 dark:bg-purple-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Form 3.3: TKDN Jasa untuk Konstruksi Fabrikasi
                                    </h3>

                                    <!-- Export Excel Button for Form 3.3 -->
                                    @if($service->status === 'generated' || $service->status === 'approved')
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.3']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Export Excel
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Header Information -->
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-6 mb-8 border border-purple-200 dark:border-purple-700">
                                    <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Informasi Umum - Form 3.3
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Penyedia Barang / Jasa</label>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Alamat</label>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">{{ $service->provider_address ?: 'Jl. Sudirman No. 123, Jakarta Pusat' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Nama Jasa</label>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">{{ $service->service_name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="space-y-4">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-violet-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-violet-700 dark:text-violet-300 mb-1">Pengguna Barang/Jasa</label>
                                                            <p class="text-base font-semibold text-violet-900 dark:text-violet-100">{{ $service->user_name ?: 'PT Pembangunan Indonesia' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-violet-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-violet-700 dark:text-violet-300 mb-1">No. Dokumen Jasa</label>
                                                            <p class="text-base font-semibold text-violet-900 dark:text-violet-100">{{ $service->document_number ?: 'DOC-2024-001' }}</p>
                                                        </div>
                                                    </div>
                                                </div> -->
                                    </div>
                                </div>

                                <!-- HPP Data Table -->
                                @if($projectType === 'tkdn_jasa')
                                @php
                                // Filter HPP items for Form 3.3 (Konstruksi & Fabrikasi) - classification_tkdn = 3
                                $hppItems33 = $allHppItemsFlat->filter(function($item) {
                                // Handle both object and array formats
                                $masterClassification = is_array($item)
                                ? ($item['master_classification'] ?? [])
                                : ($item->master_classification ?? []);

                                return (
                                (isset($masterClassification['worker']) && $masterClassification['worker'] == 3) ||
                                (isset($masterClassification['material']) && $masterClassification['material'] == 3) ||
                                (isset($masterClassification['equipment']) && $masterClassification['equipment'] == 3)
                                );
                                });
                                @endphp
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Data HPP
                                        </h5>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kualifikasi</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">WN</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Upah (Rupiah)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="8" class="px-6 py-2"></th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">KDN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($hppItems33 as $index => $serviceItem)
                                                @php
                                                $tkdnPercent = is_array($serviceItem) ? ($serviceItem['tkdn_percentage'] ?? 0) : ($serviceItem->tkdn_percentage ?? 0);
                                                $totalPrice = is_array($serviceItem) ? ($serviceItem['total_price'] ?? 0) : ($serviceItem->total_price ?? 0);
                                                $kdn = is_array($serviceItem) ? ($serviceItem['domestic_cost'] ?? 0) : ($serviceItem->domestic_cost ?? 0);
                                                $kln = is_array($serviceItem) ? ($serviceItem['foreign_cost'] ?? 0) : ($serviceItem->foreign_cost ?? 0);
                                                @endphp
                                                <tr class="hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ is_array($serviceItem) ? $serviceItem['description'] : $serviceItem->description }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">WNI</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                            {{ number_format($tkdnPercent, 0) }}%
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ is_array($serviceItem) ? $serviceItem['volume'] : $serviceItem->volume }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($kdn, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($kln, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach

                                                <!-- Sub Total -->
                                                @php
                                                $totalKdn33 = $hppItems33->sum(function($item) {
                                                return is_array($item) ? ($item['domestic_cost'] ?? 0) : ($item->domestic_cost ?? 0);
                                                });
                                                $totalKln33 = $hppItems33->sum(function($item) {
                                                return is_array($item) ? ($item['foreign_cost'] ?? 0) : ($item->foreign_cost ?? 0);
                                                });
                                                $totalPrice33 = $hppItems33->sum(function($item) {
                                                return is_array($item) ? ($item['total_price'] ?? 0) : ($item->total_price ?? 0);
                                                });
                                                @endphp
                                                <tr class="bg-purple-50 dark:bg-purple-900/20 font-semibold">
                                                    <td colspan="7" class="px-6 py-4 text-center text-sm font-bold text-purple-900 dark:text-purple-100">SUB TOTAL</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-purple-900 dark:text-purple-100">{{ number_format($totalPrice33, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalKdn33, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($totalKln33, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-purple-900 dark:text-purple-100">{{ number_format($totalPrice33, 0, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-24 h-24 mx-auto mb-6 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data HPP</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data HPP dengan TKDN Classification 3.3 yang ditemukan untuk project ini.</p>
                                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-4">
                                            <p class="text-sm text-purple-800 dark:text-purple-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Data akan muncul setelah generate form TKDN dari HPP yang tersedia.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="form-3-4" class="form-content hidden">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-orange-50 dark:bg-orange-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Form 3.4: TKDN Jasa untuk Peralatan (Umum)
                                    </h3>

                                    <!-- Export Excel Button for Form 3.4 -->
                                    @if($service->status === 'generated' || $service->status === 'approved')
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.4']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Export Excel
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Header Information -->
                                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-6 mb-8 border border-orange-200 dark:border-orange-700">
                                    <h4 class="text-lg font-semibold text-orange-900 dark:text-orange-100 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Informasi Umum - Form 3.4
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-orange-700 dark:text-orange-300 mb-1">Penyedia Barang / Jasa</label>
                                                    <p class="text-base font-semibold text-orange-900 dark:text-orange-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-orange-700 dark:text-orange-300 mb-1">Alamat</label>
                                                    <p class="text-base font-semibold text-orange-900 dark:text-orange-100">{{ $service->provider_address ?: 'Jl. Sudirman No. 123, Jakarta Pusat' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                                <div>
                                                    <label class="block text-sm font-medium text-orange-700 dark:text-orange-300 mb-1">Nama Jasa</label>
                                                    <p class="text-base font-semibold text-orange-900 dark:text-orange-100">{{ $service->service_name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="space-y-4">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-amber-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-amber-700 dark:text-amber-300 mb-1">Pengguna Barang/Jasa</label>
                                                            <p class="text-base font-semibold text-amber-900 dark:text-amber-100">{{ $service->user_name ?: 'PT Pembangunan Indonesia' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-2 h-2 bg-amber-500 rounded-full mt-2"></div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-amber-700 dark:text-amber-300 mb-1">No. Dokumen Jasa</label>
                                                            <p class="text-base font-semibold text-amber-900 dark:text-amber-100">{{ $service->document_number ?: 'DOC-2024-001' }}</p>
                                                        </div>
                                                    </div>
                                                </div> -->
                                    </div>
                                </div>

                                <!-- Data Table -->
                                @if($projectType === 'tkdn_jasa')
                                @php
                                // Filter HPP items for Form 3.4 (Jasa Umum) - classification_tkdn = 4
                                $hppItems34 = $allHppItemsFlat->filter(function($item) {
                                // Handle both object and array formats
                                $masterClassification = is_array($item)
                                ? ($item['master_classification'] ?? [])
                                : ($item->master_classification ?? []);

                                return (
                                (isset($masterClassification['worker']) && $masterClassification['worker'] == 4) ||
                                (isset($masterClassification['material']) && $masterClassification['material'] == 4) ||
                                (isset($masterClassification['equipment']) && $masterClassification['equipment'] == 4)
                                );
                                });



                                // Keep the existing groupedItems structure for backward compatibility
                                $hppItems4 = $groupedItems['3.4'] ?? collect();
                                @endphp
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            @if($hppItems34->count() > 0)
                                            Data HPP - TKDN Classification 3.4
                                            @else
                                            Data Service - TKDN Classification 3.4
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kualifikasi</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">WN</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Upah (Rupiah)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="2">BIAYA (Rupiah)</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="7" class="px-6 py-2"></th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">KDN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                    <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @if($hppItems34->count() > 0)
                                                @foreach($hppItems34 as $index => $serviceItem)
                                                @php
                                                $tkdnPercent = is_array($serviceItem) ? ($serviceItem['tkdn_percentage'] ?? 0) : ($serviceItem->tkdn_percentage ?? 0);
                                                $totalPrice = is_array($serviceItem) ? ($serviceItem['total_price'] ?? 0) : ($serviceItem->total_price ?? 0);
                                                $kdn = is_array($serviceItem) ? ($serviceItem['domestic_cost'] ?? 0) : ($serviceItem->domestic_cost ?? 0);
                                                $kln = is_array($serviceItem) ? ($serviceItem['foreign_cost'] ?? 0) : ($serviceItem->foreign_cost ?? 0);
                                                @endphp
                                                <tr class="hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ is_array($serviceItem) ? $serviceItem['description'] : $serviceItem->description }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">WNI</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                            {{ number_format($tkdnPercent, 0) }}%
                                                        </span>
                                                    </td>
                                                    <!-- ini data koefisien ahs?? -->
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ is_array($serviceItem) ? $serviceItem['volume'] : $serviceItem->volume }}</td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-left font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($kdn, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($kln, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                @foreach($hppItems4 as $index => $serviceItem)
                                                <tr class="hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $serviceItem->item_number }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ is_array($serviceItem) ? $serviceItem['description'] : $serviceItem->description }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $serviceItem->qualification ?? '-' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $serviceItem->nationality }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            {{ number_format($serviceItem->tkdn_percentage, 1) }}%
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ $serviceItem->quantity }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{
                                                                            number_format(
                                                                                is_array($serviceItem) 
                                                                                ? ($serviceItem['total_price'] ?? 0) 
                                                                                : ($serviceItem->total_price ?? 0), 
                                                                                0, ',', '.'
                                                                            ) 
                                                                            }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($serviceItem->wage, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($serviceItem->domestic_cost, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($serviceItem->foreign_cost, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($serviceItem->total_cost, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                                @endif

                                                <!-- Sub Total -->
                                                <tr class="bg-orange-50 dark:bg-orange-900/20 font-semibold">
                                                    <td colspan="6" class="px-6 py-4 text-center text-sm font-bold text-orange-900 dark:text-orange-100">SUB TOTAL</td>
                                                    @if($hppItems34->count() > 0)
                                                    @php
                                                    $totalKdn34 = $hppItems34->sum(function($item) {
                                                    return is_array($item) ? ($item['domestic_cost'] ?? 0) : ($item->domestic_cost ?? 0);
                                                    });
                                                    $totalKln34 = $hppItems34->sum(function($item) {
                                                    return is_array($item) ? ($item['foreign_cost'] ?? 0) : ($item->foreign_cost ?? 0);
                                                    });
                                                    $totalPrice34 = $hppItems34->sum(function($item) {
                                                    return is_array($item) ? ($item['total_price'] ?? 0) : ($item->total_price ?? 0);
                                                    });
                                                    @endphp
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-orange-900 dark:text-orange-100">{{ number_format($totalPrice34, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalKdn34, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($totalKln34, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-orange-900 dark:text-orange-100">{{ number_format($totalPrice34, 0, ',', '.') }}</td>
                                                    @else
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-orange-900 dark:text-orange-100">{{ number_format($hppItems4->sum('wage'), 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-orange-900 dark:text-orange-100">{{ number_format($hppItems4->sum('domestic_cost'), 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-orange-900 dark:text-orange-100">{{ number_format($hppItems4->sum('foreign_cost'), 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 text-right text-sm font-bold text-orange-900 dark:text-orange-100">{{ number_format($hppItems4->sum('total_cost'), 0, ',', '.') }}</td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-24 h-24 mx-auto mb-6 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data HPP</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data HPP dengan TKDN Classification 3.4 yang ditemukan untuk project ini.</p>
                                        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg p-4">
                                            <p class="text-sm text-orange-800 dark:text-orange-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Data akan muncul setelah generate form TKDN dari HPP yang tersedia.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="form-3-5" class="form-content hidden">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Form 3.5: Summary
                                    </h3>

                                    <!-- Export Excel Button for Form 3.5 -->
                                    @if($service->status === 'generated' || $service->status === 'approved')
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.5']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Export Excel
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Summary Info Box -->
                                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border border-purple-200 dark:border-purple-700 rounded-xl p-6 mb-8">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-2">Rangkuman TKDN Jasa</h4>
                                            <p class="text-sm text-purple-700 dark:text-purple-300 mb-3">Formulir ini berisi rangkuman dari semua klasifikasi TKDN (Form 3.1, 3.2, 3.3, dan 3.4) yang telah dibuat sebelumnya.</p>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    Form 3.1: Manajemen Proyek
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Form 3.2: Alat Kerja
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    Form 3.3: Konstruksi
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                    Form 3.4: Konsultasi
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Statistics Cards -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                                    @php
                                    // Calculate totals for each form
                                    $form31Total = isset($groupedItems['3.1']) ? $groupedItems['3.1']->sum('total_cost') : 0;
                                    $form32Total = isset($groupedItems['3.2']) ? $groupedItems['3.2']->sum('total_cost') : 0;
                                    $form33Total = isset($groupedItems['3.3']) ? $groupedItems['3.3']->sum('total_cost') : 0;
                                    $form34Total = isset($groupedItems['3.4']) ? $groupedItems['3.4']->sum('total_cost') : 0;
                                    @endphp

                                    <!-- Form 3.1 Card -->
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Form 3.1</p>
                                                <p class="text-xs text-blue-500 dark:text-blue-500">Manajemen Proyek</p>
                                            </div>
                                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">Rp {{ number_format($form31Total, 0, ',', '.') }}</p>
                                            <p class="text-xs text-blue-600 dark:text-blue-400">Total Biaya</p>
                                        </div>
                                    </div>

                                    <!-- Form 3.2 Card -->
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Form 3.2</p>
                                                <p class="text-xs text-green-500 dark:text-green-500">Alat Kerja</p>
                                            </div>
                                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-2xl font-bold text-green-900 dark:text-green-100">Rp {{ number_format($form32Total, 0, ',', '.') }}</p>
                                            <p class="text-xs text-green-600 dark:text-green-400">Total Biaya</p>
                                        </div>
                                    </div>

                                    <!-- Form 3.3 Card -->
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Form 3.3</p>
                                                <p class="text-xs text-purple-500 dark:text-purple-500">Konstruksi</p>
                                            </div>
                                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">Rp {{ number_format($form33Total, 0, ',', '.') }}</p>
                                            <p class="text-xs text-purple-600 dark:text-purple-400">Total Biaya</p>
                                        </div>
                                    </div>

                                    <!-- Form 3.4 Card -->
                                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Form 3.4</p>
                                                <p class="text-xs text-orange-500 dark:text-orange-500">Konsultasi</p>
                                            </div>
                                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">Rp {{ number_format($form34Total, 0, ',', '.') }}</p>
                                            <p class="text-xs text-orange-600 dark:text-orange-400">Total Biaya</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rangkuman TKDN Table -->
                                @if($projectType === 'tkdn_jasa' && isset($groupedItems['3.5']) && $groupedItems['3.5']->isNotEmpty())
                                @php
                                // Ambil data dari Service Items untuk Form 3.5 (rangkuman)
                                $summaryItems35 = $groupedItems['3.5'];

                                // Hitung total dari semua form
                                $totalKdn = $summaryItems35->sum('domestic_cost');
                                $totalKln = $summaryItems35->sum('foreign_cost');
                                $totalBiaya = $summaryItems35->sum('total_cost');

                                // Hitung TKDN percentage keseluruhan
                                $tkdnPercentage = $totalBiaya > 0 ? ($totalKdn / $totalBiaya) * 100 : 0;
                                @endphp

                                @if($summaryItems35->count() > 0)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-b border-purple-200 dark:border-purple-600">
                                        <h5 class="text-lg font-semibold text-purple-900 dark:text-purple-100 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Rangkuman TKDN Jasa
                                        </h5>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">(1)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA *) (Rupiah)</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">(5)</th>
                                                </tr>
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">URAIAN PEKERJAAN</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">KDN</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">KLN</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TOTAL</th>
                                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN Jasa (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($summaryItems35 as $item)
                                                @if($item->description !== 'Total Jasa')
                                                <tr class="hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $item->description }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($item->domestic_cost, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($item->foreign_cost, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($item->total_cost, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                            {{ number_format($item->tkdn_percentage, 2) }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach



                                                <!-- Total Jasa -->
                                                <tr class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 font-bold border-t-2 border-purple-200 dark:border-purple-700">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100">Total Jasa</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100 text-right">{{ number_format($totalKdn, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100 text-right">{{ number_format($totalKln, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100 text-right bg-purple-100 dark:bg-purple-800">{{ number_format($totalBiaya, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                                                            {{ number_format($tkdnPercentage, 2) }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-24 h-24 mx-auto mb-6 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data Rangkuman</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Form 3.5 akan menampilkan rangkuman setelah form individual (3.1, 3.2, 3.3, 3.4) di-generate.</p>
                                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-lg p-4">
                                            <p class="text-sm text-purple-800 dark:text-purple-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Generate form TKDN terlebih dahulu untuk melihat rangkuman.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif

                                <!-- Footnotes -->
                                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                        <p><span class="font-medium">*)</span> Nilai Jasa dapat diambil dari nilai job order, lelang, atau kontrak.</p>
                                        <p>Capaian nilai TKDN diatas dinyatakan sendiri oleh <span class="font-medium">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</span></p>
                                    </div>
                                </div>

                                <!-- Signature Section -->
                                <!-- <div class="mt-8 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl border border-indigo-200 dark:border-indigo-700">
                                            <div class="text-right">
                                                <p class="text-gray-600 dark:text-gray-400 mb-2">Jakarta, {{ now()->format('d F Y') }}</p>
                                                <p class="text-gray-600 dark:text-gray-400 mb-1">Dinyatakan Oleh,</p>
                                                <p class="font-bold text-gray-900 dark:text-white mb-8">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                                <div class="mt-16">
                                                    <p class="font-bold text-gray-900 dark:text-white text-lg">_________________________</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Verifikator TKDN</p>
                                                </div>
                                            </div>
                                        </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                @endif


                @elseif($projectType === 'tkdn_barang_jasa')
                <!-- Form 4.1 - Jasa Teknik dan Rekayasa -->
                <div id="form-4-1" class="form-content ">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-green-50 dark:bg-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    Form 4.1: TKDN Jasa untuk Material (Bahan Baku)
                                </h3>

                                <!-- Export Excel Button for Form 4.1 -->
                                @if($service->status === 'generated' || $service->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '4.1']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Header Information -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-700">
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Umum - Form 4.1
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Penyedia Barang / Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Alamat</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_address ?: 'Jl. Sudirman No. 123, Jakarta Pusat' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Nama Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->service_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="space-y-4">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Pengguna Barang/Jasa</label>
                                                        <p class="text-base font-semibold text-indigo-900 dark:text-indigo-100">{{ $service->user_name ?: 'PT Pembangunan Indonesia' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">No. Dokumen Jasa</label>
                                                        <p class="text-base font-semibold text-indigo-900 dark:text-indigo-100">{{ $service->document_number ?: 'DOC-2024-001' }}</p>
                                                    </div>
                                                </div>
                                            </div> -->
                                </div>
                            </div>

                            <!-- Service Items Table -->
                            @php
                            $hppItems41 = $service->items()->where('tkdn_classification', '4.1')->get();
                            @endphp

                            @if($hppItems41->isNotEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Data Service Items - TKDN Classification 4.1
                                    </h5>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spesifikasi</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pemasok/ Negara Asal</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Satuan</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>

                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN Barang (%)</th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" class="px-6 py-2"></th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">KDN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider"></th>
                                            </tr>

                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($hppItems41 as $index => $serviceItem)
                                            @php
                                            $tkdnPercent = data_get($serviceItem, 'tkdn_percentage', 0);
                                            $totalPrice = data_get($serviceItem, 'total_cost', 0);
                                            $kdn = data_get($serviceItem, 'domestic_cost', 0);
                                            $kln = data_get($serviceItem, 'foreign_cost', 0);
                                            @endphp
                                            <tr class="hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ data_get($serviceItem, 'description', '-') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">WNI</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                        {{ number_format($tkdnPercent, 0) }}%
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ data_get($serviceItem, 'quantity', 1) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ data_get($serviceItem, 'duration', '-') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format(data_get($serviceItem, 'wage', 0), 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($kdn, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($kln, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                        {{ number_format($tkdnPercent, 0) }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach

                                            <!-- Sub Total -->
                                            @php
                                            $totalWage41 = $hppItems41->sum('wage');
                                            $totalKdn41 = $hppItems41->sum('domestic_cost');
                                            $totalKln41 = $hppItems41->sum('foreign_cost');
                                            $totalPrice41 = $hppItems41->sum('total_cost');
                                            $avgTkdn41 = $totalPrice41 > 0 ? ($totalKdn41 / $totalPrice41) * 100 : 0;
                                            @endphp
                                            <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                                <td colspan="7" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalWage41, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalKdn41, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($totalKln41, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalPrice41, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-center text-sm font-bold text-blue-900 dark:text-blue-100">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $avgTkdn41 >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($avgTkdn41 >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                        {{ number_format($avgTkdn41, 0) }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data HPP</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data HPP dengan TKDN Classification 4.1 yang ditemukan untuk project ini.</p>
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                        <p class="text-sm text-green-800 dark:text-green-200">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Data akan muncul setelah generate form TKDN dari HPP yang tersedia.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form 4.2 - Jasa Pengadaan dan Logistik -->
                <div id="form-4-2" class="form-content hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-green-50 dark:bg-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Form 4.2: TKDN Jasa untuk Peralatan (Barang Jadi)
                                </h3>

                                <!-- Export Excel Button for Form 4.2 -->
                                @if($service->status === 'generated' || $service->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '4.2']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Header Information -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-700">
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Umum - Form 4.2
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Penyedia Barang / Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Alamat</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_address ?: 'Jl. Sudirman No. 123, Jakarta Pusat' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Nama Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->service_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="space-y-4">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">Pengguna Barang/Jasa</label>
                                                        <p class="text-base font-semibold text-indigo-900 dark:text-indigo-100">{{ $service->user_name ?: 'PT Pembangunan Indonesia' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-1">No. Dokumen Jasa</label>
                                                        <p class="text-base font-semibold text-indigo-900 dark:text-indigo-100">{{ $service->document_number ?: 'DOC-2024-001' }}</p>
                                                    </div>
                                                </div>
                                            </div> -->
                                </div>
                            </div>

                            <!-- Service Items Table -->
                            @php
                            $hppItems42 = $service->items()->where('tkdn_classification', '4.2')->get();
                            @endphp

                            @if($hppItems42->isNotEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Data Service Items - TKDN Classification 4.2
                                    </h5>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spesifikasi</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">WPemasok / Negara AsalN</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Satuan</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN Barang (%)</th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" class="px-6 py-2"></th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">KDN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($hppItems42 as $index => $serviceItem)
                                            @php
                                            $tkdnPercent = data_get($serviceItem, 'tkdn_percentage', 0);
                                            $totalPrice = data_get($serviceItem, 'total_cost', 0);
                                            $kdn = data_get($serviceItem, 'domestic_cost', 0);
                                            $kln = data_get($serviceItem, 'foreign_cost', 0);
                                            @endphp
                                            <tr class="hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ data_get($serviceItem, 'description', '-') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">WNI</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                        {{ number_format($tkdnPercent, 0) }}%
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ data_get($serviceItem, 'quantity', 1) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ data_get($serviceItem, 'duration', '-') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format(data_get($serviceItem, 'wage', 0), 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($kdn, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($kln, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">{{ number_format($tkdnPercent, 0) }}%</span>
                                                </td>
                                            </tr>
                                            @endforeach

                                            <!-- Sub Total -->
                                            @php
                                            $totalWage42 = $hppItems42->sum('wage');
                                            $totalKdn42 = $hppItems42->sum('domestic_cost');
                                            $totalKln42 = $hppItems42->sum('foreign_cost');
                                            $totalPrice42 = $hppItems42->sum('total_cost');
                                            $avgTkdn42 = $totalPrice42 > 0 ? ($totalKdn42 / $totalPrice42) * 100 : 0;
                                            @endphp
                                            <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                                <td colspan="7" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalWage42, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalKdn42, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($totalKln42, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalPrice42, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $avgTkdn42 >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($avgTkdn42 >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">{{ number_format($avgTkdn42, 0) }}%</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data HPP</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data HPP dengan TKDN Classification 4.2 yang ditemukan untuk project ini.</p>
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                        <p class="text-sm text-green-800 dark:text-green-200">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Data akan muncul setelah generate form TKDN dari HPP yang tersedia.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form 4.3 - Jasa Operasi dan Pemeliharaan -->
                <div id="form-4-3" class="form-content hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-green-50 dark:bg-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    </svg>
                                    Form 4.3: TKDN Jasa untuk Overhead & Manajemen
                                </h3>

                                <!-- Export Excel Button for Form 4.3 -->
                                @if($service->status === 'generated' || $service->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '4.3']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Header Information -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-700">
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Umum - Form 4.3
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Penyedia Barang / Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Classification</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">4.3 - Jasa Operasi dan Pemeliharaan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Percentage</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">100% (WNI)</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Status</label>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Items Table -->
                            @php
                            // Calculate total from all service items for percentage calculation
                            $totalServiceValue = $service->items->sum('total_cost');

                            // Create fixed items for Form 4.3 - Overhead and Management as separate rows
                            $overheadAmount = $totalServiceValue * 0.08; // 8%
                            $managementAmount = $totalServiceValue * 0.07; // 7%

                            $fixedItems43 = [
                            [
                            'description' => 'Overhead',
                            'amount' => $overheadAmount
                            ],
                            [
                            'description' => 'Management',
                            'amount' => $managementAmount
                            ]
                            ];
                            @endphp
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Data Service Items - TKDN Classification 4.3
                                    </h5>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kualifikasi</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">WN</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Upah (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" class="px-6 py-2"></th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">KDN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($fixedItems43 as $index => $item)
                                            <tr class="hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item['description'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">WNI</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <!-- 100% -->
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">1</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">1 paket</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($item['amount'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($item['amount'], 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium">{{ number_format($item['amount'], 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach

                                            <!-- Sub Total -->
                                            @php
                                            $subtotal43 = $overheadAmount + $managementAmount;
                                            @endphp
                                            <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                                <td colspan="8" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($subtotal43, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">-</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($subtotal43, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form 4.4 - Jasa Pelatihan dan Sertifikasi -->
                <div id="form-4-4" class="form-content hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-green-50 dark:bg-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Form 4.4: TKDN Jasa untuk Alat / Fasilitas Kerja
                                </h3>

                                <!-- Export Excel Button for Form 4.4 -->
                                @if($service->status === 'generated' || $service->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '4.4']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Header Information -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-700">
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Umum - Form 4.4
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Penyedia Barang / Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Classification</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">4.4 - Jasa Pelatihan dan Sertifikasi</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="space-y-4">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Percentage</label>
                                                        <p class="text-base font-semibold text-green-900 dark:text-green-100">100% (WNI)</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Status</label>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            {{ ucfirst($service->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div> -->
                                </div>
                            </div>

                            <!-- Service Items Table -->
                            @php
                            $hppItems44 = $service->items()->where('tkdn_classification', '4.4')->get();
                            @endphp

                            @if($hppItems44->isNotEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Data Service Items - TKDN Classification 4.4
                                    </h5>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spesifikasi / Pemasok </th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">Kepemilikan Alat Kerja</th>
                                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">WN</th> --}}
                                                {{-- <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th> --}}
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Satuan / Durasi</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Biaya Depresiasi / Sewa Alat (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN Jasa (%)</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="px-6 py-2"></th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dibuat</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dimiliki</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alokasi KDN (%)</th>
                                                <th colspan="3" class="px-6 py-2"></th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">KDN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">TKDN JASA</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($hppItems44 as $index => $item)
                                            @php
                                            $tkdnPercent = data_get($item, 'tkdn_percentage', 0);
                                            $kdn = data_get($item, 'domestic_cost', 0);
                                            $kln = data_get($item, 'foreign_cost', 0);
                                            $badgeColor = $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200');
                                            @endphp
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->description }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->qualification ?: '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">{{ number_format($tkdnPercent, 0) }}%</span>
                                                </td>
                                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->nationality }}</td> --}}
                                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                {{ number_format($item->tkdn_percentage, 0) }}%
                                                </span>
                                                </td> --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ $item->quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ $item->duration }} {{ $item->duration_unit }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($item->wage, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($kdn, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($kln, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900 dark:text-green-100 text-right font-medium">{{ number_format($item->total_cost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">{{ number_format($tkdnPercent, 0) }}%</span>
                                                </td>
                                            </tr>
                                            @endforeach

                                            <!-- Sub Total -->
                                            @php
                                            $totalWage44 = $hppItems44->sum('wage');
                                            $totalKdn44 = $hppItems44->sum('domestic_cost');
                                            $totalKln44 = $hppItems44->sum('foreign_cost');
                                            $totalPrice44 = $hppItems44->sum('total_cost');
                                            $avgTkdn44 = $totalPrice44 > 0 ? ($totalKdn44 / $totalPrice44) * 100 : 0;
                                            $avgBadgeColor44 = $avgTkdn44 >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($avgTkdn44 >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200');
                                            @endphp
                                            <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                                <td colspan="3" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                                <td class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">-</td>
                                                <td class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $avgBadgeColor44 }}">{{ number_format($avgTkdn44, 0) }}%</span>
                                                </td>
                                                <td colspan="2" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100"></td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalWage44, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalKdn44, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-red-900 dark:text-red-100">{{ number_format($totalKln44, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalPrice44, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $avgBadgeColor44 }}">{{ number_format($avgTkdn44, 0) }}%</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data service items dengan TKDN Classification 4.4 yang ditemukan.</p>
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                        <p class="text-sm text-green-800 dark:text-green-200">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Data akan muncul setelah generate form TKDN.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form 4.5 - Jasa Teknologi Informasi -->
                <div id="form-4-5" class="form-content hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-green-50 dark:bg-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                    </svg>
                                    Form 4.5: TKDN Jasa untuk Konstruksi & Fabrikasi
                                </h3>

                                <!-- Export Excel Button for Form 4.5 -->
                                @if($service->status === 'generated' || $service->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '4.5']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Header Information -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-700">
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Umum - Form 4.5
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Penyedia Barang / Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Classification</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">4.5 - Jasa Teknologi Informasi</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Percentage</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">70%</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Status</label>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Items Table -->
                            @php
                            $hppItems45 = $service->items()->where('tkdn_classification', '4.5')->get();
                            @endphp

                            @if($hppItems45->isNotEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Data Service Items - TKDN Classification 4.5
                                    </h5>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian Pekerjaan</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kewarganegaraan</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Upah (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN Jasa (%)</th>
                                            </tr>
                                            <tr>
                                                <th colspan="7" class="px-6 py-2"></th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">KDN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">KLN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">TKDN JASA</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($hppItems45 as $index => $item)
                                            @php
                                            $tkdnPercent = data_get($item, 'tkdn_percentage', 0);
                                            $badgeColor = $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200');
                                            @endphp
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">1 {{ $item->description }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->nationality }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                                        {{ number_format($tkdnPercent, 0) }}%
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ $item->quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ $item->duration }} {{ $item->duration_unit }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($item->wage, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($item->domestic_cost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($item->foreign_cost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900 dark:text-green-100 text-right font-medium">{{ number_format($item->total_cost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">{{ number_format($tkdnPercent, 0) }}%</span>
                                                </td>
                                            </tr>
                                            @endforeach

                                            <!-- Sub Total -->
                                            @php
                                            $totalKdn45 = $hppItems45->sum('domestic_cost');
                                            $totalKln45 = $hppItems45->sum('foreign_cost');
                                            $totalPrice45 = $hppItems45->sum('total_cost');
                                            $avgTkdn45 = $totalPrice45 > 0 ? ($totalKdn45 / $totalPrice45) * 100 : 0;
                                            $avgBadgeColor45 = $avgTkdn45 >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($avgTkdn45 >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200');
                                            @endphp
                                            <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                                <td colspan="7" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalKdn45, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-red-900 dark:text-red-100">{{ number_format($totalKln45, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalPrice45, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $avgBadgeColor45 }}">{{ number_format($avgTkdn45, 0) }}%</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data service items dengan TKDN Classification 4.5 yang ditemukan.</p>
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                        <p class="text-sm text-green-800 dark:text-green-200">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Data akan muncul setelah generate form TKDN.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form 4.6 - Jasa Lingkungan dan Keamanan -->
                <div id="form-4-6" class="form-content hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-green-50 dark:bg-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Form 4.6: TKDN Jasa untuk Peralatan
                                </h3>

                                <!-- Export Excel Button for Form 4.6 -->
                                @if($service->status === 'generated' || $service->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '4.6']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Header Information -->
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-700">
                                <h4 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Umum - Form 4.6
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Penyedia Barang / Jasa</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">{{ $service->provider_name ?: 'PT Konstruksi Maju' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Classification</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">4.6 - Jasa Lingkungan dan Keamanan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">TKDN Percentage</label>
                                                <p class="text-base font-semibold text-green-900 dark:text-green-100">100% (WNI)</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div>
                                                <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Status</label>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Items Table -->
                            @php
                            $hppItems46 = $service->items()->where('tkdn_classification', '4.6')->get();
                            @endphp

                            @if($hppItems46->isNotEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Data Service Items - TKDN Classification 4.6
                                    </h5>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-green-50 dark:bg-green-900/20">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 dark:text-green-300 uppercase tracking-wider">No</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uraian</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spesifikasi/Kualifikasi</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Negara Asal / Kepemilikan / Warga Negara</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN (%)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Satuan / Durasi</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan (Rp)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (Rupiah)</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TKDN JASA (%)</th>
                                            </tr>
                                            <tr>
                                                <th colspan="8" class="px-6 py-2"></th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">DN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">LN</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">TOTAL</th>
                                                <th class="px-6 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">US$</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($hppItems46 as $index => $item)
                                            @php
                                            $tkdnPercent = data_get($item, 'tkdn_percentage', 0);
                                            $kdn = data_get($item, 'domestic_cost', 0);
                                            $kln = data_get($item, 'foreign_cost', 0);
                                            $badgeColor = $tkdnPercent >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($tkdnPercent >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200');
                                            @endphp
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->description }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->qualification ?: '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->nationality }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                                        {{ number_format($tkdnPercent, 0) }}%
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ $item->quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">{{ $item->duration }} {{ $item->duration_unit }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">{{ number_format($item->wage, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium">{{ number_format($kdn, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium">{{ number_format($kln, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900 dark:text-green-100 text-right font-medium">{{ number_format($item->total_cost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">{{ number_format($tkdnPercent, 0) }}%</span>
                                                </td>
                                            </tr>
                                            @endforeach

                                            <!-- Sub Total -->
                                            @php
                                            $totalWage46 = $hppItems46->sum('wage');
                                            $totalKdn46 = $hppItems46->sum('domestic_cost');
                                            $totalKln46 = $hppItems46->sum('foreign_cost');
                                            $totalPrice46 = $hppItems46->sum('total_cost');
                                            $avgTkdn46 = $totalPrice46 > 0 ? ($totalKdn46 / $totalPrice46) * 100 : 0;
                                            $avgBadgeColor46 = $avgTkdn46 >= 75 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($avgTkdn46 >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200');
                                            @endphp
                                            <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                                <td colspan="7" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalWage46, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalKdn46, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-red-900 dark:text-red-100">{{ number_format($totalKln46, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100">{{ number_format($totalPrice46, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $avgBadgeColor46 }}">{{ number_format($avgTkdn46, 0) }}%</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <div class="w-24 h-24 mx-auto mb-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Tidak ada data service items dengan TKDN Classification 4.6 yang ditemukan.</p>
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                        <p class="text-sm text-green-800 dark:text-green-200">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Data akan muncul setelah generate form TKDN.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form 4.7 - Jasa Lainnya -->
                <div id="form-4-7" class="form-content hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-pink-50 dark:bg-pink-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Form 4.7: Summary
                                </h3>

                                <!-- Export Excel Button for Form 4.7 -->
                                @if($service->status === 'generated' || $service->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '4.7']) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Summary Info Box -->
                            <div class="bg-gradient-to-r from-pink-50 to-purple-50 dark:from-pink-900/20 dark:to-purple-900/20 border border-pink-200 dark:border-pink-700 rounded-xl p-6 mb-8">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-pink-600 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold text-pink-900 dark:text-pink-100 mb-2">Rangkuman TKDN Barang & Jasa</h4>
                                        <p class="text-sm text-pink-700 dark:text-pink-300 mb-3">Formulir ini berisi rangkuman dari semua klasifikasi TKDN (Form 4.1, 4.2, 4.3, 4.4, 4.5, dan 4.6) yang telah dibuat sebelumnya.</p>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                Form 4.1: Material
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Form 4.2: Peralatan
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                Form 4.3: Overhead
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                Form 4.4: Alat Kerja
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Form 4.5: Konstruksi
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                                Form 4.6: Peralatan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Summary Statistics Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                @php
                                // Calculate overhead and management amounts (same as Form 4.3)
                                $totalServiceValue = $service->items()->sum('total_cost');
                                $overheadAmount = $totalServiceValue * 0.08; // 8%
                                $managementAmount = $totalServiceValue * 0.07; // 7%

                                // Calculate totals for each form
                                $form41Total = isset($groupedItems['4.1']) ? $groupedItems['4.1']->sum('total_cost') : 0;
                                $form42Total = isset($groupedItems['4.2']) ? $groupedItems['4.2']->sum('total_cost') : 0;
                                $form43Total = $overheadAmount + $managementAmount; // Form 4.3 = Overhead + Management
                                $form44Total = isset($groupedItems['4.4']) ? $groupedItems['4.4']->sum('total_cost') : 0;
                                $form45Total = isset($groupedItems['4.5']) ? $groupedItems['4.5']->sum('total_cost') : 0;
                                $form46Total = isset($groupedItems['4.6']) ? $groupedItems['4.6']->sum('total_cost') : 0;
                                @endphp

                                <!-- Form 4.1 Card -->
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Form 4.1</p>
                                            <p class="text-xs text-blue-500 dark:text-blue-500">Material</p>
                                        </div>
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">Rp {{ number_format($form41Total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-blue-600 dark:text-blue-400">Total Biaya</p>
                                    </div>
                                </div>

                                <!-- Form 4.2 Card -->
                                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Form 4.2</p>
                                            <p class="text-xs text-green-500 dark:text-green-500">Peralatan</p>
                                        </div>
                                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">Rp {{ number_format($form42Total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-green-600 dark:text-green-400">Total Biaya</p>
                                    </div>
                                </div>

                                <!-- Form 4.3 Card -->
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Form 4.3</p>
                                            <p class="text-xs text-purple-500 dark:text-purple-500">Overhead</p>
                                        </div>
                                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">Rp {{ number_format($form43Total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-purple-600 dark:text-purple-400">Total Biaya</p>
                                    </div>
                                </div>

                                <!-- Form 4.4 Card -->
                                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Form 4.4</p>
                                            <p class="text-xs text-orange-500 dark:text-orange-500">Alat Kerja</p>
                                        </div>
                                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">Rp {{ number_format($form44Total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-orange-600 dark:text-orange-400">Total Biaya</p>
                                    </div>
                                </div>

                                <!-- Form 4.5 Card -->
                                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-6 border border-yellow-200 dark:border-yellow-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Form 4.5</p>
                                            <p class="text-xs text-yellow-500 dark:text-yellow-500">Konstruksi</p>
                                        </div>
                                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">Rp {{ number_format($form45Total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-yellow-600 dark:text-yellow-400">Total Biaya</p>
                                    </div>
                                </div>

                                <!-- Form 4.6 Card -->
                                <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 rounded-xl p-6 border border-teal-200 dark:border-teal-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-teal-600 dark:text-teal-400">Form 4.6</p>
                                            <p class="text-xs text-teal-500 dark:text-teal-500">Peralatan</p>
                                        </div>
                                        <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-2xl font-bold text-teal-900 dark:text-teal-100">Rp {{ number_format($form46Total, 0, ',', '.') }}</p>
                                        <p class="text-xs text-teal-600 dark:text-teal-400">Total Biaya</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Detailed Summary Table -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Rincian Komponen Biaya TKDN
                                    </h5>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">A</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Komponen Biaya</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Biaya Komponen Dalam Negeri<br>a</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Biaya Komponen Luar Negeri<br>b</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Biaya Total<br>c = a + b</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">% TKDN<br>d = a/c</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                            @php
                                            // Calculate domestic and foreign costs for each form
                                            $form41DomesticCost = isset($groupedItems['4.1']) ? $groupedItems['4.1']->sum('domestic_cost') : 0;
                                            $form41ForeignCost = isset($groupedItems['4.1']) ? $groupedItems['4.1']->sum('foreign_cost') : 0;
                                            $form42DomesticCost = isset($groupedItems['4.2']) ? $groupedItems['4.2']->sum('domestic_cost') : 0;
                                            $form42ForeignCost = isset($groupedItems['4.2']) ? $groupedItems['4.2']->sum('foreign_cost') : 0;
                                            // Form 4.3 uses calculated overhead and management (100% domestic)
                                            $form43DomesticCost = $overheadAmount + $managementAmount; // 100% domestic
                                            $form43ForeignCost = 0; // No foreign cost for overhead/management
                                            $form44DomesticCost = isset($groupedItems['4.4']) ? $groupedItems['4.4']->sum('domestic_cost') : 0;
                                            $form44ForeignCost = isset($groupedItems['4.4']) ? $groupedItems['4.4']->sum('foreign_cost') : 0;
                                            $form45DomesticCost = isset($groupedItems['4.5']) ? $groupedItems['4.5']->sum('domestic_cost') : 0;
                                            $form45ForeignCost = isset($groupedItems['4.5']) ? $groupedItems['4.5']->sum('foreign_cost') : 0;
                                            $form46DomesticCost = isset($groupedItems['4.6']) ? $groupedItems['4.6']->sum('domestic_cost') : 0;
                                            $form46ForeignCost = isset($groupedItems['4.6']) ? $groupedItems['4.6']->sum('foreign_cost') : 0;
                                            @endphp

                                            <!-- Section I: Barang -->
                                            <tr class="bg-blue-50 dark:bg-blue-900/20">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-900 dark:text-blue-100">I</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-900 dark:text-blue-100">Barang</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-blue-900 dark:text-blue-100"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-blue-900 dark:text-blue-100"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-blue-900 dark:text-blue-100"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-blue-900 dark:text-blue-100"></td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">1</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Biaya Material Langsung (Barang Baku)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form41DomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form41ForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form41Total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $form41Total > 0 ? number_format(($form41DomesticCost / $form41Total) * 100, 0) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">2</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Biaya Peralatan (Barang Sub bahan)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form42DomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form42ForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form42Total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $form42Total > 0 ? number_format(($form42DomesticCost / $form42Total) * 100, 0) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">3</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Sub Jumlah</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">{{ number_format($form41DomesticCost + $form42DomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">{{ number_format($form41ForeignCost + $form42ForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">{{ number_format($form41Total + $form42Total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ ($form41Total + $form42Total) > 0 ? number_format((($form41DomesticCost + $form42DomesticCost) / ($form41Total + $form42Total)) * 100, 0) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>

                                            <!-- Section II: Jasa -->
                                            <tr class="bg-green-50 dark:bg-green-900/20">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-900 dark:text-green-100">II</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-900 dark:text-green-100">Jasa</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-green-900 dark:text-green-100"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-green-900 dark:text-green-100"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-green-900 dark:text-green-100"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-green-900 dark:text-green-100"></td>
                                            </tr>
                                            {{-- Overhead and Management amounts are already calculated above, same as Form 4.3 --}}
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">1</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Biaya Overhead (Form 4.3)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($overheadAmount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">0</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($overheadAmount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <!-- 100% -->
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">2</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Biaya Management (Form 4.3)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($managementAmount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">0</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($managementAmount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <!-- 100% -->
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">3</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Biaya Alat Kerja/Fasilitas (Form 4.4)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form44DomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form44ForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form44Total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $form44Total > 0 ? 0 : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">4</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Biaya Konstruksi/Fabrikasi (Form 4.5)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form45DomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form45ForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form45Total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $form45Total > 0 ? 100 : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">5</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Biaya Jasa Umum (Form 4.6)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form46DomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form46ForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($form46Total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $form46Total > 0 ? 100 : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">6</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Sub Jumlah</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">{{ number_format($overheadAmount + $managementAmount + $form44DomesticCost + $form45DomesticCost + $form46DomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">{{ number_format($form44ForeignCost + $form45ForeignCost + $form46ForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">{{ number_format($overheadAmount + $managementAmount + $form44Total + $form45Total + $form46Total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    @php
                                                    $totalJasaDomestic = $overheadAmount + $managementAmount + $form44DomesticCost + $form45DomesticCost + $form46DomesticCost;
                                                    $totalJasaAll = $overheadAmount + $managementAmount + $form44Total + $form45Total + $form46Total;
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $totalJasaAll > 0 ? number_format(($totalJasaDomestic / $totalJasaAll) * 100, 0) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>

                                            <!-- Section III: Jumlah Biaya Barang + Jasa -->
                                            @php
                                            $totalDomesticCost = $form41DomesticCost + $form42DomesticCost + $overheadAmount + $managementAmount + $form44DomesticCost + $form45DomesticCost + $form46DomesticCost;
                                            $totalForeignCost = $form41ForeignCost + $form42ForeignCost + $form44ForeignCost + $form45ForeignCost + $form46ForeignCost;
                                            $grandTotal = $form41Total + $form42Total + $overheadAmount + $managementAmount + $form44Total + $form45Total + $form46Total;
                                            @endphp
                                            <tr class="bg-pink-50 dark:bg-pink-900/20 font-semibold">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-pink-900 dark:text-pink-100">III</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-pink-900 dark:text-pink-100">Jumlah Biaya Barang + Jasa</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-pink-900 dark:text-pink-100">{{ number_format($totalDomesticCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-pink-900 dark:text-pink-100">{{ number_format($totalForeignCost, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-pink-900 dark:text-pink-100">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200">
                                                        {{ $grandTotal > 0 ? number_format(($totalDomesticCost / $grandTotal) * 100, 1) : 0 }}%
                                                    </span>
                                                </td>
                                            </tr>

                                            <!-- Additional Rows -->
                                            <tr class="bg-gray-50 dark:bg-gray-800">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">B</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Komponen Bukan Biaya (Non Cost Component)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">-</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">-</td>
                                            </tr>
                                            <tr class="bg-gray-100 dark:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">C</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Jumlah Nilai Penawaran (Total Quoted Price)</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Grand Total Card -->
                            <div class="bg-gradient-to-r from-pink-100 to-purple-100 dark:from-pink-900/30 dark:to-purple-900/30 rounded-xl p-8 border-2 border-pink-300 dark:border-pink-600">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="w-16 h-16 bg-gradient-to-r from-pink-600 to-purple-600 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-pink-900 dark:text-pink-100 mb-2">Total Keseluruhan</h3>
                                    <p class="text-4xl font-bold text-pink-800 dark:text-pink-200 mb-2">Rp {{ number_format($form41Total + $form42Total + $form43Total + $form44Total + $form45Total + $form46Total, 0, ',', '.') }}</p>
                                    <p class="text-sm text-pink-600 dark:text-pink-400">Total dari semua klasifikasi TKDN Barang & Jasa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ` @endif

                <!-- Detail Sections that should only show in Data Service Tab -->
                <div class="data-service-only-sections">
                    <!-- Detail Item Service berdasarkan Kategori -->
                    <!-- fix -->

                    <!--  -->
                    <div id="detail-service-section" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span id="detail-service-title">Detail Item Service berdasarkan Kategori</span>
                            </h5>
                        </div>
                        <div id="detail-service-content" class="p-6">
                            <!-- Dynamic content will be loaded here -->
                            <div id="default-message" class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Pilih Form untuk Melihat Detail</h3>
                                <p class="text-gray-500 dark:text-gray-400">Klik salah satu tab form di atas untuk melihat detail item service berdasarkan kategori TKDN</p>
                            </div>
                        </div>

                        <!-- Ringkasan TKDN -->
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mt-8">
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-xl font-bold text-purple-900 dark:text-purple-100 flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Ringkasan TKDN
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl border border-blue-200 dark:border-blue-700">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                            {{ number_format($service->tkdn_percentage, 2) }}%
                                        </div>
                                        <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Persentase TKDN</div>
                                    </div>

                                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-xl border border-green-200 dark:border-green-700">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                                            Rp {{ number_format($service->total_domestic_cost, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm font-medium text-green-700 dark:text-green-300">Total Biaya KDN</div>
                                    </div>

                                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl border border-purple-200 dark:border-purple-700">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                                            Rp {{ number_format($service->total_cost, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Total Biaya</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Export Summary Section -->
                        @if($service->status === 'generated' || $service->status === 'approved')
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mt-8">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold text-green-900 dark:text-green-100 flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export Excel TKDN Forms
                                    </h3>

                                    <!-- Export All Forms Button -->
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => 'all']) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4m-6 6l.586-.586a2 2 0 012.828 0L20 8m-6-6L16 4"></path>
                                        </svg>
                                        Export Semua Form Excel
                                    </a>
                                </div>

                                <p class="text-green-700 dark:text-green-300 text-sm mt-2">Download semua form TKDN dalam satu file Excel atau pilih form tertentu</p>
                            </div>
                            <div class="p-6">
                                <!-- Quick Export Buttons -->
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.1']) }}" class="flex flex-col items-center p-4 bg-white dark:bg-gray-800 rounded-xl border-2 border-blue-200 dark:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mb-3">
                                            <span class="text-blue-600 dark:text-blue-400 text-lg font-bold">3.1</span>
                                        </div>
                                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300 text-center mb-1">Manajemen</span>
                                        <span class="text-xs text-blue-600 dark:text-blue-400 text-center">Proyek & Perekayasaan</span>
                                    </a>
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.2']) }}" class="flex flex-col items-center p-4 bg-white dark:bg-gray-800 rounded-xl border-2 border-green-200 dark:border-green-700 hover:bg-green-50 dark:hover:bg-green-900/20 hover:border-green-300 dark:hover:border-green-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center mb-3">
                                            <span class="text-green-600 dark:text-green-400 text-lg font-bold">3.2</span>
                                        </div>
                                        <span class="text-sm font-medium text-green-700 dark:text-green-300 text-center mb-1">Alat Kerja</span>
                                        <span class="text-xs text-green-600 dark:text-green-400 text-center">& Peralatan</span>
                                    </a>
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.3']) }}" class="flex flex-col items-center p-4 bg-white dark:bg-gray-800 rounded-xl border-2 border-purple-200 dark:border-purple-700 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:border-purple-300 dark:hover:border-purple-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/40 rounded-full flex items-center justify-center mb-3">
                                            <span class="text-blue-600 dark:text-blue-400 text-lg font-bold">3.3</span>
                                        </div>
                                        <span class="text-sm font-medium text-purple-700 dark:text-purple-300 text-center mb-1">Konstruksi</span>
                                        <span class="text-xs text-purple-600 dark:text-purple-400 text-center">& Fabrikasi</span>
                                    </a>
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.4']) }}" class="flex flex-col items-center p-4 bg-white dark:bg-gray-800 rounded-xl border-2 border-orange-200 dark:border-orange-700 hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:border-orange-300 dark:hover:border-orange-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/40 rounded-full flex items-center justify-center mb-3">
                                            <span class="text-orange-600 dark:text-orange-400 text-lg font-bold">3.4</span>
                                        </div>
                                        <span class="text-sm font-medium text-orange-700 dark:text-orange-300 text-center mb-1">Konsultasi</span>
                                        <span class="text-xs text-orange-600 dark:text-orange-400 text-center">& Pengawasan</span>
                                    </a>
                                    <a href="{{ route('service.export.excel', ['service' => $service->id, 'classification' => '3.5']) }}" class="flex flex-col items-center p-4 bg-white dark:bg-gray-800 rounded-xl border-2 border-indigo-200 dark:border-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center mb-3">
                                            <span class="text-indigo-600 dark:text-indigo-400 text-lg font-bold">3.5</span>
                                        </div>
                                        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300 text-center mb-1">Rangkuman</span>
                                        <span class="text-xs text-indigo-600 dark:text-indigo-400 text-center">Semua Form</span>
                                    </a>
                                </div>

                                <!-- Export Info -->
                                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            <p class="font-medium mb-1">Cara Export Excel:</p>
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Klik tombol "Export Excel" di header masing-masing form untuk download form tertentu</li>
                                                <li>Atau gunakan tombol "Export Semua Form Excel" untuk download semua form dalam satu file</li>
                                                <li>File Excel akan otomatis terdownload dengan format yang sesuai standar TKDN</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div> <!-- End data-service-only-sections -->

                    <div class="flex justify-end mt-8">
                        <a href="{{ route('service.index') }}" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-700 dark:hover:text-blue-400 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div> <!-- End Data Service Tab -->


                @push('scripts')
                <script>
                    // TKDN Classification data (from Laravel backend)
                    const serviceItems = json.parse('@json($service->items)');
                    console.log('=== INITIAL serviceItems DEBUG ===');
                    console.log('Total serviceItems:', serviceItems.length);

                    // Check for duplicates by ID
                    const itemIds = serviceItems.map(item => item.id);
                    const uniqueIds = [...new Set(itemIds)];
                    console.log('Unique IDs count:', uniqueIds.length);
                    if (itemIds.length !== uniqueIds.length) {
                        console.log('⚠️ DUPLICATE IDs DETECTED in serviceItems!');
                        console.log('Total items:', itemIds.length, 'Unique IDs:', uniqueIds.length);

                        // Find duplicates
                        const duplicates = itemIds.filter((id, index) => itemIds.indexOf(id) !== index);
                        console.log('Duplicate IDs:', [...new Set(duplicates)]);
                    } else {
                        console.log('✅ No duplicate IDs found in serviceItems');
                    }

                    // Show classification distribution
                    const classificationCounts = {};
                    serviceItems.forEach(item => {
                        const classification = item.classification_tkdn;
                        classificationCounts[classification] = (classificationCounts[classification] || 0) + 1;
                    });
                    console.log('Classification distribution:', classificationCounts);

                    // Show detailed breakdown of items by classification
                    console.log('=== DETAILED ITEMS BY CLASSIFICATION ===');
                    Object.keys(classificationCounts).forEach(classification => {
                        const items = serviceItems.filter(item => item.classification_tkdn == classification);
                        console.log(`Classification ${classification} (${intToClassificationTkdn(parseInt(classification))}):`, items.length, 'items');
                        items.forEach((item, index) => {
                            console.log(`  Item ${index + 1}:`, {
                                id: item.id,
                                description: item.description,
                                price: item.total_cost || item.total_price || item.price || item.amount || item.cost || 'NO_PRICE'
                            });
                        });
                    });

                    if (serviceItems.length > 0) {
                        console.log('Sample item structure:', serviceItems[0]);
                        console.log('Sample item classification_tkdn (integer):', serviceItems[0].classification_tkdn);
                        console.log('Sample item classification_string:', intToClassificationTkdn(serviceItems[0].classification_tkdn));
                    }
                    console.log('=== END INITIAL DEBUG ===');
                    // Form to classification mapping
                    const formMapping = {
                        'form-3-1': ['3.1'],
                        'form-3-2': ['3.2'],
                        'form-3-3': ['3.3'],
                        'form-3-4': ['3.4'],
                        'form-3-5': ['3.1', '3.2', '3.3', '3.4'], // Summary
                        'form-4-1': ['4.1'],
                        'form-4-2': ['4.2'],
                        'form-4-3': ['4.3'],
                        'form-4-4': ['4.4'],
                        'form-4-5': ['4.5'],
                        'form-4-6': ['4.6'],
                        'form-4-7': ['4.1', '4.2', '4.3', '4.4', '4.5', '4.6'] // Summary
                    };

                    // Classification details
                    const classificationDetails = {
                        '3.1': {
                            name: 'Overhead & Manajemen',
                            color: 'blue',
                            description: 'Form 3.1 - Overhead & Manajemen'
                        },
                        '3.2': {
                            name: 'Alat Kerja / Fasilitas',
                            color: 'green',
                            description: 'Form 3.2 - Alat Kerja / Fasilitas'
                        },
                        '3.3': {
                            name: 'Konstruksi & Fabrikasi',
                            color: 'purple',
                            description: 'Form 3.3 - Konstruksi & Fabrikasi'
                        },
                        '3.4': {
                            name: 'Jasa Umum',
                            color: 'orange',
                            description: 'Form 3.4 - Jasa Umum'
                        },
                        '4.1': {
                            name: 'Material (Bahan Baku)',
                            color: 'indigo',
                            description: 'Form 4.1 - Material (Bahan Baku)'
                        },
                        '4.2': {
                            name: 'Peralatan (Barang Jadi)',
                            color: 'teal',
                            description: 'Form 4.2 - Peralatan (Barang Jadi)'
                        },
                        '4.3': {
                            name: 'Overhead & Manajemen',
                            color: 'green',
                            description: 'Form 4.3 - Overhead & Manajemen'
                        },
                        '4.4': {
                            name: 'Alat Kerja / Fasilitas',
                            color: 'green',
                            description: 'Form 4.4 - Alat Kerja / Fasilitas'
                        },
                        '4.5': {
                            name: 'Konstruksi & Fabrikasi',
                            color: 'purple',
                            description: 'Form 4.5 - Konstruksi & Fabrikasi'
                        },
                        '4.6': {
                            name: 'Jasa Umum',
                            color: 'orange',
                            description: 'Form 4.6 - Jasa Umum'
                        }
                    };

                    // DEPRECATED: tkdnCategoryMapping removed to prevent duplicate items across forms
                    // Now using direct classification_tkdn matching with unique integer mapping
                    // const tkdnCategoryMapping = { ... };

                    // Fungsi untuk memfilter items berdasarkan klasifikasi TKDN - FIXED TO PREVENT DUPLICATES
                    function filterItemsByClassification(items, classification) {
                        console.log('=== DEBUG filterItemsByClassification ===');
                        console.log('Input classification:', classification);
                        console.log('Total items received:', items.length);

                        // Convert string classification to integer for database comparison
                        const intClassification = stringToIntClassification(classification);
                        console.log('Converted to integer classification:', intClassification);

                        if (!intClassification) {
                            console.log('❌ Invalid classification, returning empty array');
                            return [];
                        }

                        // Direct filter by classification_tkdn only - no category overlap
                        const filtered = items.filter((item, index) => {
                            console.log(`Processing item ${index}:`, {
                                id: item.id,
                                description: item.description,
                                classification_tkdn: item.classification_tkdn,
                                classification_string: intToClassificationTkdn(item.classification_tkdn)
                            });

                            // Filter berdasarkan classification_tkdn (integer comparison) only
                            const match = item.classification_tkdn === intClassification;
                            console.log('Classification match:', match ? '✅' : '❌', item.classification_tkdn, '===', intClassification);
                            return match;
                        });

                        console.log('Final filtered results:', filtered.length);
                        console.log('Final filtered items:', filtered.map(item => ({
                            id: item.id,
                            description: item.description
                        })));
                        console.log('=== END DEBUG ===');

                        return filtered;
                    }

                    // Tab functionality
                    function showForm(formId) {
                        // Hide all form content
                        const allForms = document.querySelectorAll('.form-content');
                        allForms.forEach(form => {
                            form.classList.add('hidden');
                        });

                        // Remove active state from all tabs and reset to default styling
                        const allTabs = document.querySelectorAll('[id^="tab-"]');
                        allTabs.forEach(tab => {
                            // Remove active state classes (both blue and green themes)
                            tab.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'bg-green-600', 'hover:bg-green-700', 'text-white', 'shadow-lg', 'hover:shadow-xl', 'transform', 'hover:-translate-y-0.5');

                            // Reset to default inactive state
                            tab.classList.add('bg-white', 'dark:bg-gray-800', 'border-2', 'border-gray-200', 'dark:border-gray-700', 'text-gray-700', 'dark:text-gray-300', 'shadow-sm');
                        });

                        // Show selected form
                        const selectedForm = document.getElementById(formId);
                        if (selectedForm) {
                            selectedForm.classList.remove('hidden');
                        }

                        // Update active tab with proper styling based on form type
                        const tabId = formId.replace('form-', 'tab-');
                        const activeTab = document.getElementById(tabId);
                        if (activeTab) {
                            // Remove default inactive classes
                            activeTab.classList.remove('bg-white', 'dark:bg-gray-800', 'border-2', 'border-gray-200', 'dark:border-gray-700', 'text-gray-700', 'dark:text-gray-300', 'shadow-sm');

                            // Add active state classes based on form type
                            if (formId.startsWith('form-4-')) {
                                // TKDN Barang & Jasa - use green theme
                                activeTab.classList.add('bg-green-600', 'hover:bg-green-700', 'text-white', 'shadow-lg', 'hover:shadow-xl', 'transform', 'hover:-translate-y-0.5');
                            } else {
                                // TKDN Jasa - use blue theme
                                activeTab.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'shadow-lg', 'hover:shadow-xl', 'transform', 'hover:-translate-y-0.5');
                            }
                        }

                        // Update detail service section
                        updateDetailServiceSection(formId);
                    }

                    // Update detail service section based on selected form
                    function updateDetailServiceSection(formId) {
                        console.log('🔄 updateDetailServiceSection called for:', formId);

                        // Only update if data-service-tab is active/visible
                        const dataServiceTab = document.getElementById('data-service-tab');
                        if (!dataServiceTab || dataServiceTab.classList.contains('hidden')) {
                            console.log('⏩ Skipping updateDetailServiceSection - data service tab not active');
                            return;
                        }

                        const detailTitle = document.getElementById('detail-service-title');
                        const detailContent = document.getElementById('detail-service-content');
                        const defaultMessage = document.getElementById('default-message');

                        if (!formMapping[formId]) {
                            console.warn('No mapping found for form:', formId);
                            return;
                        }

                        const classifications = formMapping[formId];
                        const formNumber = formId.replace('form-', '').replace('-', '.');

                        console.log('Processing classifications:', classifications);

                        // Update title with additional info
                        const classificationNames = classifications.map(c => classificationDetails[c]?.name).join(', ');
                        detailTitle.textContent = `Detail Item Service - Form ${formNumber} (${classificationNames})`;

                        // Hide default message
                        if (defaultMessage) {
                            defaultMessage.style.display = 'none';
                        }

                        // Generate content
                        let content = '';

                        if (classifications.length === 1) {
                            // Single classification
                            const classification = classifications[0];
                            const detail = classificationDetails[classification];

                            console.log('🎯 Single classification mode for:', classification);
                            content = generateSingleClassificationContent(classification, detail, serviceItems);
                        } else {
                            // Multiple classifications (summary)
                            console.log('📋 Multiple classification mode for:', classifications);
                            content = generateMultipleClassificationContent(classifications);
                        }

                        console.log('✅ Content generated, updating DOM');
                        detailContent.innerHTML = content;
                    }


                    // Fungsi mapping kategori estimation_category ke kode TKDN
                    function getTkdnCategory(estimationCategory) {
                        const mapping = {
                            // TKDN Jasa
                            "alat": "3.2 get Alat / Fasilitas Kerja",
                            "konstruksi fabrikasi": "3.3 get Konstruksi Fabrikasi",
                            "peralatan umum": "3.4 get Peralatan (Umum)",

                            // TKDN Barang & Jasa
                            "material": "4.1 get Material (Bahan Baku)",
                            "peralatan barang jadi": "4.2 get Peralatan (Barang Jadi)",
                            "alat fasilitas kerja": "4.4 get Alat / Fasilitas Kerja",
                            "konstruksi & fabrikasi": "4.5 get Konstruksi & Fabrikasi",
                            "peralatan jasa umum": "4.6 get Peralatan (Jasa Umum)",
                        };

                        const key = estimationCategory.toLowerCase();

                        return mapping[key] || "Kategori tidak ditemukan";
                    }


                    // Generate content for single classification - Fixed duplication
                    function generateSingleClassificationContent(classification, detail, allItems) {
                        console.log('=== generateSingleClassificationContent START ===');
                        console.log('Classification:', classification);
                        console.log('Total input items:', allItems.length);
                        console.log('Sample input items:', allItems.slice(0, 3).map(item => ({
                            id: item.id,
                            desc: item.description,
                            class: item.classification_tkdn
                        })));

                        const colorClasses = getColorClasses(detail.color);

                        // Filter items based on classification - single filter only
                        const filteredItems = filterItemsByClassification(allItems, classification);

                        // Debug: Show all filtered items with their properties
                        console.log('Filtered items details (should show expected items):');
                        filteredItems.forEach((item, index) => {
                            console.log(`Item ${index}:`, {
                                id: item.id,
                                description: item.description,
                                classification_tkdn: item.classification_tkdn,
                                total_cost: item.total_cost,
                                total_price: item.total_price,
                                price: item.price,
                                amount: item.amount,
                                cost: item.cost
                            });

                            // Check if price properties exist
                            const priceValue = item.total_cost || item.total_price || item.price || item.amount || item.cost;
                            console.log(`Item ${index} price value:`, priceValue, 'Type:', typeof priceValue);
                        });

                        // For now, use all filtered items without additional deduplication to see if we get the right data
                        // We can re-add deduplication later if needed
                        const uniqueItems = filteredItems;

                        console.log('Final result - Using all filtered items:', uniqueItems.length);
                        console.log('Items to display:', uniqueItems.map((item, index) => ({
                            index: index + 1,
                            id: item.id,
                            desc: item.description,
                            price: item.total_cost || item.total_price || item.price || item.amount || item.cost || 0
                        })));
                        console.log('=== generateSingleClassificationContent END ===');

                        let content = `
            <div class="mb-6">
                <h6 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-3 h-3 ${colorClasses.dot} rounded-full mr-3"></span>
                    ${detail.name}
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">(${detail.description})</span>
                </h6>
                
                <div class="${colorClasses.bg} rounded-lg p-4 border ${colorClasses.border}">
                    <h7 class="text-sm font-medium ${colorClasses.text} mb-2">${detail.description}</h7>`;

                        if (uniqueItems && uniqueItems.length > 0) {
                            content += `<div class="space-y-2">`;
                            uniqueItems.forEach(item => {
                                // Tampilkan informasi tambahan berdasarkan klasifikasi
                                let additionalInfo = '';
                                if (classification === '4.4' && item.estimation_category) {
                                    additionalInfo = item.estimation_category.toLowerCase().includes('bahan baku') ? ' (Bahan Baku)' : ' (Bukan Bahan Baku)';
                                } else if (classification === '4.6' && item.estimation_category) {
                                    additionalInfo = item.estimation_category.toLowerCase().includes('jasa umum') ? ' (Jasa Umum)' : ' (Bukan Jasa Umum)';
                                }

                                content += `
                    <div class="bg-white dark:bg-gray-800 rounded p-3 border ${colorClasses.itemBorder}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${classification || 'N/A'}${additionalInfo} -- ${item.description || 'N/A'}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${item.qualification || 'N/A'}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Klasifikasi TKDN: ${intToClassificationTkdn(item.classification_tkdn)}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium ${colorClasses.price}">Rp ${formatCurrency(item.total_cost || item.total_price || item.price || item.amount || item.cost || 0)}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${item.tkdn_percentage || 0}% TKDN</p>
                            </div>
                        </div>
                    </div>`;
                            });
                            content += `</div>`;

                            // Add total with multiple property fallbacks and NaN safety
                            const totalCost = uniqueItems.reduce((sum, item) => {
                                const itemCost = item.total_cost || item.total_price || item.price || item.amount || item.cost || 0;
                                const numericCost = typeof itemCost === 'number' ? itemCost : parseFloat(itemCost) || 0;
                                console.log('Adding to total:', item.description, 'Cost:', numericCost);
                                return sum + numericCost;
                            }, 0);

                            console.log('Total calculated:', totalCost);
                            const safeTotalCost = isNaN(totalCost) ? 0 : totalCost;

                            content += `
                <div class="mt-4 pt-3 border-t ${colorClasses.borderTop}">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Total (${uniqueItems.length} item):</span>
                        <span class="text-lg font-bold ${colorClasses.price}">Rp ${formatCurrency(safeTotalCost)}</span>
                    </div>
                </div>`;
                        } else {
                            // Tampilkan pesan jika tidak ada data
                            content += `
                <div class="text-center py-6">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic mb-2">Belum ada data untuk ${detail.description}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Klasifikasi: ${classification}</p>
                </div>`;
                        }

                        content += `</div></div>`;

                        return content;
                    }

                    // Generate content for multiple classifications (summary) - Updated
                    function generateMultipleClassificationContent(classifications) {
                        let content = `
            <div class="space-y-6">
                <div class="text-center mb-6">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Ringkasan Semua Kategori</h6>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Menampilkan detail dari semua kategori yang relevan berdasarkan klasifikasi TKDN</p>
                </div>`;

                        let grandTotal = 0;
                        const processedItemIds = new Set(); // Track processed items to prevent duplicates

                        classifications.forEach(classification => {
                            const detail = classificationDetails[classification];
                            const filteredItems = filterItemsByClassification(serviceItems, classification);
                            const colorClasses = getColorClasses(detail.color);

                            // Remove duplicates based on item ID and classification combination
                            const uniqueFilteredItems = filteredItems.filter(item => {
                                const itemKey = `${item.id}_${classification}`;
                                if (processedItemIds.has(itemKey)) {
                                    console.log('Skipping duplicate item:', item.id, item.description, 'for classification:', classification);
                                    return false;
                                }
                                processedItemIds.add(itemKey);
                                console.log('Processing unique item:', item.id, item.description, 'for classification:', classification);
                                return true;
                            });

                            if (uniqueFilteredItems && uniqueFilteredItems.length > 0) {
                                const subtotal = uniqueFilteredItems.reduce((sum, item) => {
                                    const itemCost = item.total_cost || item.total_price || item.price || item.amount || item.cost || 0;
                                    const numericCost = typeof itemCost === 'number' ? itemCost : parseFloat(itemCost) || 0;
                                    return sum + numericCost;
                                }, 0);
                                const safeSubtotal = isNaN(subtotal) ? 0 : subtotal;
                                grandTotal += safeSubtotal;

                                content += `
                    <div class="mb-6">
                        <h6 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-3 h-3 ${colorClasses.dot} rounded-full mr-3"></span>
                                ${detail.name}
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">(${detail.description})</span>
                            </div>
                            <span class="text-sm font-medium ${colorClasses.text}">${uniqueFilteredItems.length} item</span>
                        </h6>
                        
                        <div class="${colorClasses.bg} rounded-lg p-4 border ${colorClasses.border}">
                            <div class="space-y-2">`;

                                uniqueFilteredItems.forEach(item => {
                                    let additionalInfo = '';
                                    if (classification === '4.4' && item.estimation_category) {
                                        additionalInfo = item.estimation_category.toLowerCase().includes('bahan baku') ? ' (Bahan Baku)' : ' (Bukan Bahan Baku)';
                                    } else if (classification === '4.6' && item.estimation_category) {
                                        additionalInfo = item.estimation_category.toLowerCase().includes('jasa umum') ? ' (Jasa Umum)' : ' (Bukan Jasa Umum)';
                                    }

                                    content += `
                        <div class="bg-white dark:bg-gray-800 rounded p-3 border ${colorClasses.itemBorder}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">${item.estimation_category || 'N/A'}${additionalInfo} -- ${item.description || 'N/A'}</p>
                                   
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Klasifikasi: ${intToClassificationTkdn(item.classification_tkdn)}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium ${colorClasses.price}">Rp ${formatCurrency(item.total_cost || item.total_price || item.price || item.amount || item.cost || 0)}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">${item.tkdn_percentage || 0}% TKDN</p>
                                </div>
                            </div>
                        </div>`;
                                });

                                content += `
                            </div>
                            <div class="mt-4 pt-3 border-t ${colorClasses.borderTop}">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Subtotal ${detail.name}:</span>
                                    <span class="text-base font-bold ${colorClasses.price}">Rp ${formatCurrency(safeSubtotal)}</span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                            } else {
                                // Tampilkan informasi jika tidak ada data untuk klasifikasi ini
                                content += `
                    <div class="mb-6">
                        <h6 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <span class="w-3 h-3 ${colorClasses.dot} rounded-full mr-3"></span>
                            ${detail.name}
                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">(${detail.description})</span>
                        </h6>
                        
                        <div class="${colorClasses.bg} rounded-lg p-4 border ${colorClasses.border}">
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic mb-1">Belum ada data untuk ${detail.description}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Klasifikasi: ${classification}</p>
                            </div>
                        </div>
                    </div>`;
                            }
                        });

                        // Add grand total with NaN safety
                        const safeGrandTotal = isNaN(grandTotal) ? 0 : grandTotal;
                        console.log('=== SUMMARY DEBUG ===');
                        console.log('Total processed items across all classifications:', processedItemIds.size);
                        console.log('Grand total amount:', grandTotal, 'Safe grand total:', safeGrandTotal);
                        console.log('=== END SUMMARY DEBUG ===');

                        content += `
                <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h7 class="text-lg font-bold text-gray-900 dark:text-white">Grand Total</h7>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total biaya dari ${processedItemIds.size} item unik</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp ${formatCurrency(safeGrandTotal)}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Rupiah</p>
                        </div>
                    </div>
                </div>
            </div>`;

                        return content;
                    }

                    // Get color classes for styling
                    function getColorClasses(color) {
                        const colorMap = {
                            blue: {
                                dot: 'bg-blue-500',
                                bg: 'bg-blue-50 dark:bg-blue-900/20',
                                border: 'border-blue-200 dark:border-blue-700',
                                itemBorder: 'border-blue-200 dark:border-blue-600',
                                borderTop: 'border-blue-200 dark:border-blue-600',
                                text: 'text-blue-900 dark:text-blue-100',
                                price: 'text-blue-600 dark:text-blue-400'
                            },
                            green: {
                                dot: 'bg-green-500',
                                bg: 'bg-green-50 dark:bg-green-900/20',
                                border: 'border-green-200 dark:border-green-700',
                                itemBorder: 'border-green-200 dark:border-green-600',
                                borderTop: 'border-green-200 dark:border-green-600',
                                text: 'text-green-900 dark:text-green-100',
                                price: 'text-green-600 dark:text-green-400'
                            },
                            purple: {
                                dot: 'bg-purple-500',
                                bg: 'bg-purple-50 dark:bg-purple-900/20',
                                border: 'border-purple-200 dark:border-purple-700',
                                itemBorder: 'border-purple-200 dark:border-purple-600',
                                borderTop: 'border-purple-200 dark:border-purple-600',
                                text: 'text-purple-900 dark:text-purple-100',
                                price: 'text-purple-600 dark:text-purple-400'
                            },
                            orange: {
                                dot: 'bg-orange-500',
                                bg: 'bg-orange-50 dark:bg-orange-900/20',
                                border: 'border-orange-200 dark:border-orange-700',
                                itemBorder: 'border-orange-200 dark:border-orange-600',
                                borderTop: 'border-orange-200 dark:border-orange-600',
                                text: 'text-orange-900 dark:text-orange-100',
                                price: 'text-orange-600 dark:text-orange-400'
                            },
                            indigo: {
                                dot: 'bg-indigo-500',
                                bg: 'bg-indigo-50 dark:bg-indigo-900/20',
                                border: 'border-indigo-200 dark:border-indigo-700',
                                itemBorder: 'border-indigo-200 dark:border-indigo-600',
                                borderTop: 'border-indigo-200 dark:border-indigo-600',
                                text: 'text-indigo-900 dark:text-indigo-100',
                                price: 'text-indigo-600 dark:text-indigo-400'
                            },
                            teal: {
                                dot: 'bg-teal-500',
                                bg: 'bg-teal-50 dark:bg-teal-900/20',
                                border: 'border-teal-200 dark:border-teal-700',
                                itemBorder: 'border-teal-200 dark:border-teal-600',
                                borderTop: 'border-teal-200 dark:border-teal-600',
                                text: 'text-teal-900 dark:text-teal-100',
                                price: 'text-teal-600 dark:text-teal-400'
                            }
                        };

                        return colorMap[color] || colorMap.blue;
                    }

                    // Convert string classification to integer for database comparison - FIXED UNIQUE MAPPING
                    function stringToIntClassification(stringClassification) {
                        const mapping = {
                            '3.1': 1, // Overhead & Manajemen (TKDN Jasa)
                            '3.2': 2, // Alat Kerja / Fasilitas (TKDN Jasa)
                            '3.3': 3, // Konstruksi & Fabrikasi (TKDN Jasa)
                            '3.4': 4, // Peralatan (Jasa Umum) (TKDN Jasa)
                            '4.1': 11, // Material (Bahan Baku) (TKDN Barang & Jasa)
                            '4.2': 12, // Peralatan (Barang Jadi) (TKDN Barang & Jasa)
                            '4.3': 13, // Overhead & Manajemen (TKDN Barang & Jasa)
                            '4.4': 14, // Alat Kerja / Fasilitas (TKDN Barang & Jasa)
                            '4.5': 15, // Konstruksi & Fabrikasi (TKDN Barang & Jasa)
                            '4.6': 16 // Peralatan (Jasa Umum) (TKDN Barang & Jasa)
                        };

                        return mapping[stringClassification] || null;
                    }

                    // Convert integer classification to string description - UPDATED FOR UNIQUE MAPPING
                    function intToClassificationTkdn(classification) {
                        if (classification === null || classification === undefined) {
                            return 'N/A';
                        }

                        const mapping = {
                            1: 'Overhead & Manajemen (TKDN Jasa)',
                            2: 'Alat Kerja / Fasilitas (TKDN Jasa)',
                            3: 'Konstruksi & Fabrikasi (TKDN Jasa)',
                            4: 'Peralatan Jasa Umum (TKDN Jasa)',
                            11: 'Material Bahan Baku (TKDN Barang & Jasa)',
                            12: 'Peralatan Barang Jadi (TKDN Barang & Jasa)',
                            13: 'Overhead & Manajemen (TKDN Barang & Jasa)',
                            14: 'Alat Kerja / Fasilitas (TKDN Barang & Jasa)',
                            15: 'Konstruksi & Fabrikasi (TKDN Barang & Jasa)',
                            16: 'Peralatan Jasa Umum (TKDN Barang & Jasa)',
                            7: 'Summary'
                        };

                        return mapping[classification] || 'N/A';
                    }

                    // Format currency with NaN safety
                    function formatCurrency(amount) {
                        if (amount === null || amount === undefined || isNaN(amount)) {
                            return '0';
                        }
                        const numericAmount = typeof amount === 'number' ? amount : parseFloat(amount);
                        if (isNaN(numericAmount)) {
                            return '0';
                        }
                        return new Intl.NumberFormat('id-ID').format(numericAmount);
                    }

                    // Initialize with appropriate form active based on project type
                    document.addEventListener('DOMContentLoaded', function() {
                        const projectType = '{{ $projectType }}';
                        if (projectType === 'tkdn_barang_jasa') {
                            showForm('form-4-1');
                        } else {
                            showForm('form-3-1');
                        }
                    });

                    // Regenerate Form 3.4 function
                    function regenerateForm34() {
                        if (confirm('Apakah Anda yakin ingin regenerate Form 3.4? Ini akan menghapus data yang ada dan membuat ulang.')) {
                            const serviceId = '{{ $service->id }}';
                            const url = `/service/${serviceId}/regenerate-form-34`;

                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Show success message
                                        const successMessage = document.createElement('div');
                                        successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                                        successMessage.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            ${data.message}
                        </div>
                    `;
                                        document.body.appendChild(successMessage);

                                        // Remove message after 3 seconds
                                        setTimeout(() => {
                                            successMessage.remove();
                                        }, 3000);

                                        // Reload page to show updated data
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        // Show error message
                                        alert('Error: ' + data.error);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan saat regenerate Form 3.4');
                                });
                        }
                    }



                    // Generate options dropdown functionality - Removed as no longer needed

                    // Service Tab Functionality
                    function showServiceTab(tabId) {
                        console.log('🔄 showServiceTab called with:', tabId);

                        // Hide/Show specific tabs based on selection
                        const dataServiceTab = document.getElementById('data-service-tab');
                        const logServiceTab = document.getElementById('log-service-tab');

                        console.log('📋 Tab elements found:', {
                            dataServiceTab: dataServiceTab ? 'YES' : 'NO',
                            logServiceTab: logServiceTab ? 'YES' : 'NO'
                        });

                        if (tabId === 'data-service-tab') {
                            if (dataServiceTab) {
                                dataServiceTab.classList.remove('hidden');
                                console.log('✅ Data Service Tab - SHOWN');
                            }
                            if (logServiceTab) {
                                logServiceTab.classList.add('hidden');
                                console.log('❌ Log Service Tab - HIDDEN');
                            }
                        } else if (tabId === 'log-service-tab') {
                            if (dataServiceTab) {
                                dataServiceTab.classList.add('hidden');
                                console.log('❌ Data Service Tab - HIDDEN');
                            }
                            if (logServiceTab) {
                                logServiceTab.classList.remove('hidden');
                                console.log('✅ Log Service Tab - SHOWN');
                            }
                        }

                        // Remove active class from all service tab buttons
                        document.querySelectorAll('.service-tab-button').forEach(btn => {
                            btn.classList.remove('active');
                        });

                        // Add active class to selected service tab button
                        const selectedBtn = document.getElementById(tabId + '-btn');
                        if (selectedBtn) {
                            selectedBtn.classList.add('active');
                            console.log('✅ Button active class added to:', tabId + '-btn');
                        } else {
                            console.warn('⚠️ Button not found:', tabId + '-btn');
                        }

                        // Update detail section if data-service-tab is active
                        if (tabId === 'data-service-tab') {
                            const activeForm = document.querySelector('.form-content:not(.hidden)');
                            if (activeForm) {
                                const formId = activeForm.id;
                                updateDetailServiceSection(formId);
                            }
                        }
                    }

                    // Initialize service tabs on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        showServiceTab('data-service-tab');
                    });
                </script>

                <style>
                    .service-tab-button {
                        @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300;
                    }

                    .service-tab-button.active {
                        @apply border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-500;
                    }

                    .service-tab-content {
                        @apply space-y-6;
                    }

                    .service-tab-content.hidden {
                        @apply hidden;
                    }
                </style>

                <!-- Approve Modal -->
                <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Setujui Service</h3>
                            <form action="{{ route('service.approve', $service) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Persetujuan (Opsional)</label>
                                    <textarea id="approve_notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Tambahkan catatan persetujuan..."></textarea>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="closeModal('approveModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Batal</button>
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Setujui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Comment Modal -->
                <div id="commentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Komentar</h3>
                            <form action="{{ route('service.comment', $service) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="comment_text" class="block text-sm font-medium text-gray-700 mb-2">Komentar <span class="text-red-500">*</span></label>
                                    <textarea id="comment_text" name="comment" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tulis komentar Anda..." required></textarea>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="closeModal('commentModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Batal</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    // Modal functions
                    function openApproveModal() {
                        document.getElementById('approveModal').classList.remove('hidden');
                    }

                    function openCommentModal() {
                        document.getElementById('commentModal').classList.remove('hidden');
                    }

                    function closeModal(modalId) {
                        document.getElementById(modalId).classList.add('hidden');
                    }
                </script>

                @endpush

                @endsection