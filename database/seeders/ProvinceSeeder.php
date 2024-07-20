<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_provinces')->insert([
            [
                'province_name' => 'Jakarta',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'province_name' => 'Banten',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
