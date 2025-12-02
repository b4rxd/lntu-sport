<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'email' => 'qweqwe@qweqwe.qwe',
            'first_name' => 'Default',
            'last_name' => 'Admin',
            'role' => 'admin',
            'access_list' => json_encode([]),
            'location_access_list' => json_encode([]),
            'password' => Hash::make('qweqwe'),
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
