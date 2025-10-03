<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assets
        Account::create([
            'code' => '1-1000',
            'name' => 'Kas',
            'account_type' => 'asset',
            'normal_balance' => 'debit',
            'description' => 'Kas di tangan dan di bank',
        ]);

        Account::create([
            'code' => '1-2000',
            'name' => 'Piutang Usaha',
            'account_type' => 'asset',
            'normal_balance' => 'debit',
            'description' => 'Piutang dari penjualan',
        ]);

        Account::create([
            'code' => '1-3000',
            'name' => 'Persediaan Barang',
            'account_type' => 'asset',
            'normal_balance' => 'debit',
            'description' => 'Persediaan barang dagangan',
        ]);

        Account::create([
            'code' => '1-4000',
            'name' => 'Perlengkapan',
            'account_type' => 'asset',
            'normal_balance' => 'debit',
            'description' => 'Perlengkapan usaha',
        ]);

        Account::create([
            'code' => '1-5000',
            'name' => 'Peralatan',
            'account_type' => 'asset',
            'normal_balance' => 'debit',
            'description' => 'Peralatan usaha',
        ]);

        Account::create([
            'code' => '1-5100',
            'name' => 'Akumulasi Penyusutan Peralatan',
            'account_type' => 'asset',
            'normal_balance' => 'credit',
            'description' => 'Akumulasi penyusutan peralatan (contra asset)',
        ]);

        // Liabilities
        Account::create([
            'code' => '2-1000',
            'name' => 'Utang Usaha',
            'account_type' => 'liability',
            'normal_balance' => 'credit',
            'description' => 'Utang kepada supplier',
        ]);

        Account::create([
            'code' => '2-2000',
            'name' => 'Utang Gaji',
            'account_type' => 'liability',
            'normal_balance' => 'credit',
            'description' => 'Utang gaji karyawan',
        ]);

        // Equity
        Account::create([
            'code' => '3-1000',
            'name' => 'Modal',
            'account_type' => 'equity',
            'normal_balance' => 'credit',
            'description' => 'Modal pemilik',
        ]);

        Account::create([
            'code' => '3-2000',
            'name' => 'Prive',
            'account_type' => 'equity',
            'normal_balance' => 'debit',
            'description' => 'Pengambilan pemilik',
        ]);

        // Revenue
        Account::create([
            'code' => '4-1000',
            'name' => 'Pendapatan Penjualan',
            'account_type' => 'revenue',
            'normal_balance' => 'credit',
            'description' => 'Pendapatan dari penjualan',
        ]);

        Account::create([
            'code' => '4-2000',
            'name' => 'Pendapatan Lain-lain',
            'account_type' => 'revenue',
            'normal_balance' => 'credit',
            'description' => 'Pendapatan di luar usaha',
        ]);

        // Expenses
        Account::create([
            'code' => '5-1000',
            'name' => 'Harga Pokok Penjualan',
            'account_type' => 'expense',
            'normal_balance' => 'debit',
            'description' => 'Biaya pokok barang yang dijual',
        ]);

        Account::create([
            'code' => '5-2000',
            'name' => 'Beban Gaji',
            'account_type' => 'expense',
            'normal_balance' => 'debit',
            'description' => 'Beban gaji karyawan',
        ]);

        Account::create([
            'code' => '5-3000',
            'name' => 'Beban Listrik',
            'account_type' => 'expense',
            'normal_balance' => 'debit',
            'description' => 'Beban listrik',
        ]);

        Account::create([
            'code' => '5-4000',
            'name' => 'Beban Telepon dan Internet',
            'account_type' => 'expense',
            'normal_balance' => 'debit',
            'description' => 'Beban komunikasi',
        ]);

        Account::create([
            'code' => '5-5000',
            'name' => 'Beban Penyusutan',
            'account_type' => 'expense',
            'normal_balance' => 'debit',
            'description' => 'Beban penyusutan aset tetap',
        ]);

        Account::create([
            'code' => '5-6000',
            'name' => 'Beban Operasional Lainnya',
            'account_type' => 'expense',
            'normal_balance' => 'debit',
            'description' => 'Beban operasional lainnya',
        ]);
    }
}
