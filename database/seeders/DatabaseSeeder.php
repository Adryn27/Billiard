<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nama' => 'Owner',
            'email' => 'owner@gmail.com',
            'role' => '0',
            'hp' => '081234567890',
            'password' => bcrypt('p@55word'),
        ]);
    }
}
