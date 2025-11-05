<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@lelesegar.com',
            'password' => 'parallel',
        ]);

        // Seed accounting data
        $this->call([
            AccountSeeder::class,
            ExpenseCategorySeeder::class,
            RawMaterialSeeder::class,
        ]);
    }
}
