<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UAEGeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $countryId = DB::table('countries')->insertGetId([
                'name' => 'United Arab Emirates',
                'code' => 'ae',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $data = [
                'Abu Dhabi' => [
                    'Abu Dhabi' => ['Al Maryah Island', 'Al Raha', 'Al Khalidiyah', 'Al Khalifa Street'],
                    'Al Ain' => ['Al Jimi', 'Al Mutared', 'Al Hili'],
                ],
                'Dubai' => [
                    'Dubai' => ['Deira', 'Bur Dubai', 'Marina', 'Jumeirah', 'Downtown'],
                    'Hatta' => ['Hatta Village', 'Hatta Wadi Hub'],
                    'Jebel Ali' => ['JAFZA', 'Jebel Ali Village'],
                ],
                'Sharjah' => [
                    'Sharjah' => ['Al Majaz', 'Al Nahda', 'Al Khan']
                ],
                'Ajman' => [
                    'Ajman' => ['Ajman Corniche', 'Al Nuaimiya']
                ],
                'Umm Al Quwain' => [
                    'Umm Al Quwain' => ['UAQ Free Zone', 'Sinaiya']
                ],
                'Ras Al Khaimah' => [
                    'Ras Al Khaimah' => ['Al Nakheel', 'Al Hamra'],
                    'Dibba Al Hisn' => ['Dibba Corniche']
                ],
                'Fujairah' => [
                    'Fujairah' => ['Fujairah City Centre', 'Masafi', 'Khor Fakkan']
                ],
            ];

            foreach ($data as $province => $cities) {
                $provinceId = DB::table('provinces')->insertGetId([
                    'country_id' => $countryId,
                    'name' => $province,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                foreach ($cities as $city => $areas) {
                    $cityId = DB::table('cities')->insertGetId([
                        'province_id' => $provinceId,
                        'name' => $city,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    foreach ($areas as $area) {
                        DB::table('areas')->insert([
                            'city_id' => $cityId,
                            'name' => $area,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        });
    }
}
