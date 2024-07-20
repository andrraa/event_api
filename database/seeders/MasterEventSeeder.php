<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_master_events')->insert([
            [
                'id' => 1,
                'name' => 'Event A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Event B',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
