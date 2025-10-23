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
             $hppItems44 = 1;
             @endphp

             @if($hppItems44 > 0)
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

                             <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                 <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-center"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">-</td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">-</td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">%</span>
                                 </td>
                                 {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"></td> --}}
                                 {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                               
                                 </span>
                                 </td> --}}
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400 text-right font-medium"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-medium"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900 dark:text-green-100 text-right font-medium"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-center">
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">%</span>
                                 </td>
                             </tr>

                             <!-- Sub Total -->
                             <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                 <td colspan="3" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">SUB TOTAL</td>
                                 <td class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">-</td>
                                 <td class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100">-</td>
                                 <td class="px-6 py-4 whitespace-nowrap text-center">
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">%</span>
                                 </td>
                                 <td colspan="2" class="px-6 py-4 text-center text-sm font-bold text-green-900 dark:text-green-100"></td>
                                 <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100"></td>
                                 <td class="px-6 py-4 text-right text-sm font-bold text-blue-900 dark:text-blue-100"></td>
                                 <td class="px-6 py-4 text-right text-sm font-bold text-red-900 dark:text-red-100"></td>
                                 <td class="px-6 py-4 text-right text-sm font-bold text-green-900 dark:text-green-100"></td>
                                 <td class="px-6 py-4 whitespace-nowrap text-center">
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">%</span>
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