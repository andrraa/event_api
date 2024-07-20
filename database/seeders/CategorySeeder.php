<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_categories')->insert([
            [
                'category_name' => 'Konser',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Seminar',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
