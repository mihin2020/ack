<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Administrateur',
            'telephone' => '+226 50 12 34 56',
            'email' => 'admin@ack.com',
            'password' => Hash::make('password'),
            'role' => 'administrateur',
            'actif' => true,
        ]);
    }
}
