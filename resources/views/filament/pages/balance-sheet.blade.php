<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter Form -->
        <x-filament::section>
            <x-slot name="heading">
                Pilih Tanggal
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

        <!-- Balance Sheet Report -->
        <x-filament::section>
            <x-slot name="heading">
                Laporan Posisi Keuangan (Neraca)
            </x-slot>
            <x-slot name="description">
                Per tanggal: {{ \Carbon\Carbon::parse($data['as_of_date'])->format('d M Y') }}
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ASSETS -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold border-b-2 border-gray-300 pb-2">ASET</h3>
                    
                    <div class="space-y-2">
                        <div class="font-semibold">Aset Lancar</div>
                        
                        <div class="flex justify-between ml-4">
                            <span>Kas</span>
                            <span class="font-medium">Rp {{ number_format($data['cash'], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between ml-4">
                            <span>Persediaan Bahan Baku</span>
                            <span class="font-medium">Rp {{ number_format($data['raw_material_inventory_value'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="font-semibold">Aset Tetap</div>
                        
                        <div class="flex justify-between ml-4">
                            <span>Peralatan, Bangunan, Kendaraan</span>
                            <span class="font-medium">Rp {{ number_format($data['fixed_assets_value'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t-2 border-gray-300 font-bold text-lg">
                        <span>Total Aset</span>
                        <span>Rp {{ number_format($data['total_assets'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- LIABILITIES & EQUITY -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold border-b-2 border-gray-300 pb-2">KEWAJIBAN & EKUITAS</h3>
                    
                    <div class="space-y-2">
                        <div class="font-semibold">Kewajiban</div>
                        
                        <div class="flex justify-between ml-4">
                            <span>Utang</span>
                            <span class="font-medium">Rp {{ number_format($data['total_liabilities'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="font-semibold">Ekuitas</div>
                        
                        <div class="flex justify-between ml-4">
                            <span>Modal (dari Laporan Ekuitas)</span>
                            <span class="font-medium">Rp {{ number_format($data['equity'], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between ml-4 pt-2 border-t font-semibold">
                            <span>Total Ekuitas</span>
                            <span>Rp {{ number_format($data['total_equity'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t-2 border-gray-300 font-bold text-lg">
                        <span>Total Kewajiban & Ekuitas</span>
                        <span>Rp {{ number_format($data['total_liabilities'] + $data['total_equity'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
