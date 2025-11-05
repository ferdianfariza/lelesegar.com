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

        <!-- Equity Statement Report -->
        <x-filament::section>
            <x-slot name="heading">
                Laporan Perubahan Ekuitas
            </x-slot>
            <x-slot name="description">
                Periode: {{ \Carbon\Carbon::parse($data['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($data['end_date'])->format('d M Y') }}
            </x-slot>

            <div class="space-y-4">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-semibold">Modal Awal</span>
                        <span class="font-medium">Rp {{ number_format($data['initial_capital'], 0, ',', '.') }}</span>
                    </div>

                    @if($data['additional_capital'] > 0)
                        <div class="flex justify-between ml-4">
                            <span>Tambahan Modal</span>
                            <span class="text-green-600">+ Rp {{ number_format($data['additional_capital'], 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between ml-4 {{ $data['net_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <span>{{ $data['net_income'] >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</span>
                        <span>{{ $data['net_income'] >= 0 ? '+' : '-' }} Rp {{ number_format(abs($data['net_income']), 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between pt-4 border-t-2 border-gray-300 text-xl font-bold">
                        <span>Modal Akhir</span>
                        <span>Rp {{ number_format($data['ending_equity'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
