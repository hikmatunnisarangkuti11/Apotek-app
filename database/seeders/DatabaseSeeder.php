<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
//import agar hash dapat digunakan
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            "name" => "Administrator",
            "email" => "admin@gmail.com",
            // hash : enkripsi agar password tersimpan berisi teks acak agar tidak bisa diprediksi/dibaca orang
            // hash -> bcrypt
            "password" => Hash::make("adminapotek"),
            "role" => "admin",
        ]);
        User::create([
            "name" => "Kasir Apotek",
            "email" => "kasir@gmail.com",
            "password" => Hash::make("kasirapotek"),
            "role" => "cashier",
        ]);
    }
}
