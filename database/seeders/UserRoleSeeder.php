<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_user_roles')->insert([
            [
                'role_name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_name' => 'Inputer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_name' => 'Viewer',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
