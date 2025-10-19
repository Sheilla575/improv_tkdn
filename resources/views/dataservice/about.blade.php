@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Detail Data Service Project</h1>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        @php
        $volume = $hppahs->volume ?? '-';
        @endphp
        @foreach ($groupedItems as $classification => $items)
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                TKDN Classification: {{ $classification }}
            </h2>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium uppercase">Uraian</th>
                        <th class="px-3 py-2 text-left text-xs font-medium uppercase">Kualifikasi</th>
                        <th class="px-3 py-2 text-left text-xs font-medium uppercase">Dibuat</th>
                        <th class="px-3 py-2 text-left text-xs font-medium uppercase">Dimiliki</th>
                        <th class="px-3 py-2 text-center text-xs font-medium uppercase">TKDN %</th>
                        <th class="px-3 py-2 text-center text-xs font-medium uppercase">Jumlah</th>
                        <th class="px-3 py-2 text-center text-xs font-medium uppercase">Koefisien</th>
                        <th class="px-3 py-2 text-center text-xs font-medium uppercase">Volume</th>
                        <th class="px-3 py-2 text-center text-xs font-medium uppercase">Total</th>
                        <th class="px-3 py-2 text-center text-xs font-medium uppercase">Durasi</th>
                        <th class="px-3 py-2 text-right text-xs font-medium uppercase">Upah</th>
                        <th class="px-3 py-2 text-right text-xs font-medium uppercase">KDN</th>
                        <th class="px-3 py-2 text-right text-xs font-medium uppercase">KLN</th>
                        <th class="px-3 py-2 text-right text-xs font-medium uppercase">Total</th>
                    </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($items as $item)
                    <tr>
                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">
                            {{ $item->description }}
                            <br>
                            <span class="text-xs text-gray-500">{{ $item->estimation_item_id }}</span>
                        </td>
                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">{{ $item->qualification ?? '-' }}</td>
                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">{{ $item->estimationItem->dibuat ?? '-' }}</td>
                        <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">{{ $item->estimationItem->dimiliki ?? '-' }}</td>
                        <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-white">{{ number_format($item->tkdn_percentage, 0, ',', '.') }}%</td>
                        <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                        <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-white">{{ $item->estimationItem->coefficient ?? '-' }}</td>
                        <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-white">{{ $volume }}</td>
                        @php
                        $total = $item->quantity * $volume * ($item->estimationItem->coefficient ?? 1);
                        @endphp
                        <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-white">{{ $total }}</td>
                        <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-white">{{ number_format($item->duration, 0, ',', '.') }} {{ $item->duration_unit }}</td>
                        <td class="px-3 py-2 text-sm text-right text-gray-900 dark:text-white">Rp {{ number_format($item->wage, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-sm text-right text-gray-900 dark:text-white">Rp {{ number_format($item->domestic_cost, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-sm text-right text-gray-900 dark:text-white">Rp {{ number_format($item->foreign_cost, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-sm text-right text-gray-900 dark:text-white">Rp {{ number_format($item->total_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
</div>
@endsection