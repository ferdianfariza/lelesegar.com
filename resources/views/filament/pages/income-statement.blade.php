<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter Form -->
        <x-filament::section>
            <x-slot name="heading">
                Periode Laporan
            </x-slot>
            
            <form wire:submit.prevent="$refresh">
                {{ $this->form }}
                
                <div class="mt-4">
                    <x-filament::button type="submit">
                        Tampilkan Laporan
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        @php
            $data = $this->getData();
        @endphp

        <!-- Income Statement Report -->
        <x-filament::section>
            <x-slot name="heading">
                Laporan Laba Rugi
            </x-slot>
            <x-slot name="description">
                Periode: {{ \Carbon\Carbon::parse($data['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($data['end_date'])->format('d M Y') }}
            </x-slot>

            <div class="space-y-4">
                <!-- Revenue Section -->
                <div class="border-b pb-4">
                    <h3 class="text-lg font-semibold mb-3">Pendapatan</h3>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="ml-4">Pendapatan Penjualan</span>
                            <span class="font-medium">Rp {{ number_format($data['sales_revenue'], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between font-bold pt-2 border-t">
                            <span>Total Pendapatan</span>
                            <span>Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Expenses Section -->
                <div class="border-b pb-4">
                    <h3 class="text-lg font-semibold mb-3">Beban</h3>
                    
                    <div class="space-y-2">
                        <!-- Production Expenses -->
                        <div class="mt-2">
                            <div class="flex justify-between font-semibold">
                                <span class="ml-2">Beban Produksi</span>
                                <span></span>
                            </div>
                            
                            @if($data['production_expenses'] > 0)
                                <div class="flex justify-between">
                                    <span class="ml-6">Beban Produksi Lainnya</span>
                                    <span class="font-medium">Rp {{ number_format($data['production_expenses'], 0, ',', '.') }}</span>
                                </div>
                            @endif
                            
                            <!-- Raw Material Usage Details -->
                            @if($data['raw_material_usage_details']->count() > 0)
                                <div class="ml-6 mt-2">
                                    <div class="text-sm font-medium mb-1">Bahan Baku yang Digunakan:</div>
                                    @foreach($data['raw_material_usage_details'] as $material)
                                        <div class="flex justify-between text-sm">
                                            <span class="ml-4">{{ $material['name'] }} ({{ number_format($material['quantity'], 2) }} {{ $material['unit'] }})</span>
                                            <span class="font-medium">Rp {{ number_format($material['amount'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="flex justify-between font-semibold pt-2 border-t mt-2">
                                <span class="ml-4">Total Beban Produksi</span>
                                <span>Rp {{ number_format($data['total_production_expenses'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <!-- Other Expenses -->
                        @foreach($data['expenses'] as $expense)
                            <div class="flex justify-between">
                                <span class="ml-4">{{ $expense['category'] }}</span>
                                <span class="font-medium">Rp {{ number_format($expense['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        
                        <div class="flex justify-between font-bold pt-2 border-t">
                            <span>Total Beban</span>
                            <span>Rp {{ number_format($data['total_expenses'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Net Income Section -->
                <div class="pt-4">
                    <div class="flex justify-between text-xl font-bold {{ $data['net_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <span>{{ $data['net_income'] >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</span>
                        <span>Rp {{ number_format(abs($data['net_income']), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
