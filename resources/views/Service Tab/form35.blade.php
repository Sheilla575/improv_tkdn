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
                <div class="flex items-center space-x-2">
                    <a href="#" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="py-6">
            <!-- Summary Info Box -->
            <div class="m-5 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border border-purple-200 dark:border-purple-700 rounded-xl p-6 mb-8">
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
            <div class="m-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
            <!-- Table Data -->
            <div class="m-5 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
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
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" colspan="3">BIAYA (*) (Rupiah)</th>
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

                            <tr class="hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">

                                    </span>
                                </td>
                            </tr>
                            <!-- Total Jasa -->
                            <tr class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 font-bold border-t-2 border-purple-200 dark:border-purple-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100">Total Jasa</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100 text-right"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100 text-right"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-900 dark:text-purple-100 text-right bg-purple-100 dark:bg-purple-800"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white">

                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>