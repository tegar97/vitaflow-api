<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->insert([[
            'name' => 'Jhon Doe',
            'email' => 'jhon@email.com',
            'password' => bcrypt('password'),
        ], [
            'name' => 'Jane Doe',
            'email' => 'jane@email.com',
            'password' => bcrypt('password'),
        ]]);
    }
}
