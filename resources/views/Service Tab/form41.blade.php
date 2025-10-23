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

                <div class="flex items-center space-x-2">
                    <a href="" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">

            <!-- Service Items Table -->
            @php
            $tkdn = 1;
            @endphp

            @if($tkdn > 0)
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

                            <tr class="hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 text-right font-medium"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">

                                    </span>
                                </td>
                            </tr>
                            <!-- Sub Total -->

                            <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                <td colspan="7" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100"></td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-blue-600 dark:text-blue-400"></td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-red-600 dark:text-red-400"></td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100"></td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-blue-900 dark:text-blue-100">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">

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