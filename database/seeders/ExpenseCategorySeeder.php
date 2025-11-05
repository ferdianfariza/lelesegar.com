<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['code' => 'PERALATAN', 'name' => 'Peralatan', 'description' => 'Pembelian peralatan usaha'],
            ['code' => 'BANGUNAN', 'name' => 'Bangunan', 'description' => 'Pembelian atau renovasi bangunan'],
            ['code' => 'KENDARAAN', 'name' => 'Kendaraan', 'description' => 'Pembelian kendaraan'],
            ['code' => 'BEBAN_PRODUKSI', 'name' => 'Beban Produksi', 'description' => 'Beban produksi dan operasional'],
            ['code' => 'PRIVE', 'name' => 'Prive', 'description' => 'Pengambilan pemilik'],
            ['code' => 'LAINNYA', 'name' => 'Lainnya', 'description' => 'Pengeluaran lainnya'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
