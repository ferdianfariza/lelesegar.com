<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RawMaterial;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawMaterials = [
            [
                'code' => 'CUP001',
                'name' => 'Cup Plastik',
                'description' => 'Cup plastik untuk minuman',
                'unit' => 'pcs',
                'price_per_unit' => 500,
                'beginning_stock' => 1000,
                'current_stock' => 1000,
                'minimum_stock' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'TEH001',
                'name' => 'Teh Celup',
                'description' => 'Teh celup untuk minuman',
                'unit' => 'pack',
                'price_per_unit' => 15000,
                'beginning_stock' => 50,
                'current_stock' => 50,
                'minimum_stock' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'KOPI001',
                'name' => 'Kopi Bubuk',
                'description' => 'Kopi bubuk untuk minuman',
                'unit' => 'kg',
                'price_per_unit' => 80000,
                'beginning_stock' => 20,
                'current_stock' => 20,
                'minimum_stock' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'GULA001',
                'name' => 'Gula Pasir',
                'description' => 'Gula pasir untuk minuman',
                'unit' => 'kg',
                'price_per_unit' => 15000,
                'beginning_stock' => 50,
                'current_stock' => 50,
                'minimum_stock' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'SUSU001',
                'name' => 'Susu Kental Manis',
                'description' => 'Susu kental manis untuk minuman',
                'unit' => 'kaleng',
                'price_per_unit' => 12000,
                'beginning_stock' => 48,
                'current_stock' => 48,
                'minimum_stock' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($rawMaterials as $rawMaterial) {
            RawMaterial::create($rawMaterial);
        }
    }
}
