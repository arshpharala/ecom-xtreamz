<?php

namespace Database\Seeders;

use App\Models\CMS\Locale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Locale::updateOrCreate(['code' => 'en'], [
            'name' => 'English',
            'direction' => 'ltr'
        ]);
    }
}
