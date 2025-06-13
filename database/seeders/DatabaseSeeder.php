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
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => '0',
            'hp' => '081234567890',
            'password' => bcrypt('P@55word'),
        ]);

        User::create([
            'nama' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'role' => '1',
            'hp' => '081234567890',
            'password' => bcrypt('P@55word'),
        ]);

        User::create([
            'nama' => 'Owner',
            'email' => 'owner@gmail.com',
            'role' => '3',
            'hp' => '081234567890',
            'password' => bcrypt('P@55word'),
        ]);
    }
}
