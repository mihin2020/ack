<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler tous les seeders
        $this->call([
            PermissionsSeeder::class,
            ParametresSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
