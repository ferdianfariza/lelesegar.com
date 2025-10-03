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
            ['code' => 'GAJI', 'name' => 'Gaji Karyawan', 'description' => 'Biaya gaji karyawan'],
            ['code' => 'BAHAN', 'name' => 'Pembelian Bahan Baku', 'description' => 'Pembelian bahan baku produksi'],
            ['code' => 'LISTRIK', 'name' => 'Listrik', 'description' => 'Biaya listrik'],
            ['code' => 'AIR', 'name' => 'Air', 'description' => 'Biaya air'],
            ['code' => 'TELP', 'name' => 'Telepon & Internet', 'description' => 'Biaya komunikasi'],
            ['code' => 'TRANS', 'name' => 'Transportasi', 'description' => 'Biaya transportasi'],
            ['code' => 'MAINT', 'name' => 'Pemeliharaan', 'description' => 'Biaya pemeliharaan peralatan'],
            ['code' => 'RENT', 'name' => 'Sewa', 'description' => 'Biaya sewa tempat usaha'],
            ['code' => 'OTHER', 'name' => 'Lain-lain', 'description' => 'Biaya operasional lainnya'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
