<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $currencies = [
            [
                'code' => 'AED',
                'name' => 'United Arab Emirates Dirham',
                'symbol' => 'د.إ',
                'decimal' => 2,
                'group_separator' => ',',
                'decimal_separator' => '.',
                'currency_position' => 'Right',
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'decimal' => 2,
                'group_separator' => ',',
                'decimal_separator' => '.',
                'currency_position' => 'Left',
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'decimal' => 2,
                'group_separator' => '.',
                'decimal_separator' => ',',
                'currency_position' => 'Left',
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'decimal' => 2,
                'group_separator' => ',',
                'decimal_separator' => '.',
                'currency_position' => 'Left',
            ],
            [
                'code' => 'INR',
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'decimal' => 2,
                'group_separator' => ',',
                'decimal_separator' => '.',
                'currency_position' => 'Left',
            ],
            [
                'code' => 'SAR',
                'name' => 'Saudi Riyal',
                'symbol' => 'ر.س',
                'decimal' => 2,
                'group_separator' => ',',
                'decimal_separator' => '.',
                'currency_position' => 'Right',
            ],
            [
                'code' => 'PKR',
                'name' => 'Pakistani Rupee',
                'symbol' => '₨',
                'decimal' => 2,
                'group_separator' => ',',
                'decimal_separator' => '.',
                'currency_position' => 'Left',
            ]
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->updateOrInsert(
                ['code' => $currency['code']],
                array_merge($currency, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }
}
