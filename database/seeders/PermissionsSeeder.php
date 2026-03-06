<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'nom' => 'Gestion Inscriptions',
                'slug' => 'gerer_inscriptions',
                'description' => 'Permission de gérer les inscriptions des enfants'
            ],
            [
                'nom' => 'Gestion Paiements',
                'slug' => 'gerer_paiements',
                'description' => 'Permission de gérer les paiements'
            ],
            [
                'nom' => 'Gestion Réservations',
                'slug' => 'gerer_reservations',
                'description' => 'Permission de gérer les réservations de séances'
            ],
            [
                'nom' => 'Visualisation Statistiques',
                'slug' => 'voir_statistiques',
                'description' => 'Permission de visualiser les statistiques'
            ],
            [
                'nom' => 'Gestion Utilisateurs',
                'slug' => 'gerer_utilisateurs',
                'description' => 'Permission de gérer les utilisateurs administrateurs'
            ],
            [
                'nom' => 'Gestion Permissions',
                'slug' => 'gerer_permissions',
                'description' => 'Permission de gérer les permissions des utilisateurs'
            ],
            [
                'nom' => 'Export Données',
                'slug' => 'exporter_donnees',
                'description' => 'Permission d\'exporter les données'
            ],
        ];
        
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        
        $this->command->info('Permissions créées avec succès !');
    }
}
