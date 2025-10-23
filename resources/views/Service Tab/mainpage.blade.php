@extends('layouts.app')
@section('content')
<div class="min-h-screen from-slate-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
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
                            <h1 class="text-4xl font-bold text-white mb-2">Project Name</h1>
                            <p class="text-xl text-blue-100 mb-2">TKDN Jasa (Form 3.1 - 3.5)</p>
                            <p class="text-lg text-blue-200 mb-4">

                            </p>

                            <!-- Status and Category Badges -->
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 text-white border border-white/30 backdrop-blur-sm">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                    Status
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-8 lg:mt-0 lg:ml-8">
                    <div class="flex flex-col sm:flex-row gap-3">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Tab Menu -->
    <div class="py-8">

        <!-- Tabs Navigation -->
        <div class="border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-6 px-4 py-2" aria-label="Tabs">
                <button
                    onclick="showServiceTab('data-service-tab')"
                    id="data-service-tab-btn"
                    class="service-tab-button group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-gray-900 py-3 px-4 text-center text-sm font-medium transition-colors duration-150 ease-in-out hover:text-gray-700 dark:hover:text-gray-300 focus:z-10 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active">
                    <span class="active-indicator absolute inset-x-0 bottom-0 h-0.5 bg-blue-500 dark:bg-blue-400 opacity-0 transition-opacity duration-200"></span>
                    Data Service
                </button>
                <button
                    onclick="showServiceTab('log-service-tab')"
                    id="log-service-tab-btn"
                    class="service-tab-button group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-gray-900 py-3 px-4 text-center text-sm font-medium transition-colors duration-150 ease-in-out hover:text-gray-700 dark:hover:text-gray-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:z-10">
                    <span class="active-indicator absolute inset-x-0 bottom-0 h-0.5 bg-blue-500 dark:bg-blue-400 opacity-0 transition-opacity duration-200"></span>
                    Log Activity Service
                </button>
            </nav>
        </div>

    </div>
    <!-- Main Content Container -->
    <div class="py-2">

        <!-- Form Navigation Tabs -->
        <div id="data-service-tab" class="service-tab-content">

            <!-- Informasi Client -->
            <div class="py-3">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Umum
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    <div>
                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Penyedia Barang / Jasa</label>
                                        <p class="text-base font-semibold text-blue-900 dark:text-blue-100">PT Konstruksi Maju</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    <div>
                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Alamat</label>
                                        <p class="text-base font-semibold text-blue-900 dark:text-blue-100">Jl. Sudirman No. 123, Jakarta Pusat</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    <div>
                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Nama Jasa</label>
                                        <p class="text-base font-semibold text-blue-900 dark:text-blue-100">Nama Jasa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Emd Informasi Client -->

            <!-- Tabs Form TKDN -->
            <div class="mb-2 py-3">
                @php
                $tipe = "TKDN Jasa"
                @endphp
                <div class="flex flex-wrap gap-2 overflow-x-auto pb-2">
                    @if($tipe !== $tipe)
                    <button onclick="showForm('form-3-1')" id="tab-3-1" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium whitespace-nowrap transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Form 3.1
                    </button>
                    <button onclick="showForm('form-3-2')" id="tab-3-2" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Form 3.2
                    </button>
                    <button onclick="showForm('form-3-3')" id="tab-3-3" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Form 3.3
                    </button>
                    <button onclick="showForm('form-3-4')" id="tab-3-4" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Form 3.4
                    </button>
                    <!-- Summary Form Tab (3.5) - Different Style -->
                    <div class="flex items-center mx-2">
                        <div class="w-px h-8 bg-gray-300 dark:bg-gray-600"></div>
                    </div>
                    <button onclick="showForm('form-3-5')" id="tab-3-5" class="inline-flex items-center px-6 py-3  rounded-xl font-medium whitespace-nowrap transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border-2 border-purple-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-semibold">Form 3.5</span>
                        <span class="ml-2 px-2 py-0.5 bg-gray-300/20 rounded-full text-xs font-medium">Summary</span>
                    </button>
                    @else
                    <!-- Form 4.x Tabs -->
                    <button onclick="showForm('form-4-1')" id="tab-4-1" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium whitespace-nowrap transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Form 4.1
                    </button>
                    <button onclick="showForm('form-4-2')" id="tab-4-2" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Form 4.2
                    </button>
                    <button onclick="showForm('form-4-3')" id="tab-4-3" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        </svg>
                        Form 4.3
                    </button>
                    <button onclick="showForm('form-4-4')" id="tab-4-4" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Form 4.4
                    </button>
                    <button onclick="showForm('form-4-5')" id="tab-4-5" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        Form 4.5
                    </button>
                    <button onclick="showForm('form-4-6')" id="tab-4-6" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Form 4.6
                    </button>
                    <button onclick="showForm('form-4-7')" id="tab-4-7" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium whitespace-nowrap hover:border-green-300 dark:hover:border-green-600 hover:text-green-700 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Form 4.7
                    </button>
                    @endif
                </div>
            </div>
            <!-- End Tabs Form TKDN -->
            <div class="py-2">
                <!-- Content Service Tab -->
                <div id="data-service-tab" class="service-tab-content">
                    @include('Service Tab.form31')
                    @include('Service Tab.form32')
                    @include('Service Tab.form33')
                    @include('Service Tab.form34')
                    @include('Service Tab.form35')
                    @include('Service Tab.form41')
                    @include('Service Tab.form42')
                    @include('Service Tab.form43')
                    @include('Service Tab.form44')
                    @include('Service Tab.form45')
                    @include('Service Tab.form46')
                    @include('Service Tab.form47')
                </div>
            </div>
        </div>

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
                            <tbody>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Log Activity Service Tab -->
    </div>

</div>
<script>
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
</script>
@endsection