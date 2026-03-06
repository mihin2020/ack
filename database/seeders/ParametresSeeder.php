<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parametre;

class ParametresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parametres = [
            [
                'cle' => 'prix_seance',
                'valeur' => '5000',
                'description' => 'Prix unitaire d\'une séance en FCFA',
            ],
            [
                'cle' => 'tarif_inscription_annuelle',
                'valeur' => '20000',
                'description' => 'Frais d\'inscription annuelle en FCFA',
            ],
        ];
        
        foreach ($parametres as $parametre) {
            Parametre::create($parametre);
        }
    }
}
