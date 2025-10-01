<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::insert([
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'role' => 'user',
                'password' => bcrypt('password')
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('password')
            ]
        ]);

        Category::insert([
            [
                'nama_kategori' => "Kendaraan",
                "deskripsi"     => "Untuk Kendaraan"
            ],
            [
                'nama_kategori' => "Rumah",
                "deskripsi"     => "Untuk Rumah"
            ],
            [
                'nama_kategori' => "Barang Antik",
                "deskripsi"     => "Untuk Rumah"
            ],
            [
                'nama_kategori' => "Perlengkapan Olahraga",
                "deskripsi"     => "Untuk Rumah"
            ],
        ]);
    }
}
