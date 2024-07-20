<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_users')->insert([
            [
                'role_id' => 1,
                'full_name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('superadmin'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 2,
                'full_name' => 'System Inputer',
                'username' => 'inputer',
                'password' => Hash::make('inputer'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 3,
                'full_name' => 'System Viewer',
                'username' => 'viewer',
                'password' => Hash::make('viewer'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
