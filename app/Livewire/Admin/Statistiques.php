<?php

namespace App\Livewire\Admin;

use App\Models\Enfant;
use App\Models\ParentTuteur;
use App\Models\Seance;
use App\Models\Parametre;
use Livewire\Component;

class Statistiques extends Component
{
    public function render()
    {
        $prixSeance = Parametre::where('cle', 'prix_seance')->first();
        $prix = $prixSeance ? (int)$prixSeance->valeur : 5000;
        
        // Calculs des statistiques
        $revenuTotal = Enfant::sum('nombre_seances_total') * $prix;
        $utilisateursInscrits = Enfant::count();
        $reservationsActives = Seance::where('statut', 'reservee')->count();
        $seancesEffectuees = Seance::where('statut', 'effectuee')->count();
        
        // Statistiques par mois (12 derniers mois)
        $statsMois = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $inscriptionsMois = Enfant::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $revenuMois = Enfant::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('nombre_seances_total') * $prix;
            
            $statsMois[] = [
                'mois' => $date->format('M Y'),
                'inscriptions' => $inscriptionsMois,
                'revenue' => $revenuMois,
            ];
        }
        
        // Statistiques par statut de séances
        $seancesParStatut = [
            'reservee' => Seance::where('statut', 'reservee')->count(),
            'effectuee' => Seance::where('statut', 'effectuee')->count(),
            'reporter' => Seance::where('statut', 'reporter')->count(),
        ];
        
        // Statistiques des séances par mois (basé sur date_seance, pas date de création)
        $seancesParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $annee = $date->year;
            $mois = $date->month;
            
            // Séances effectuées ce mois-ci
            $effectuees = Seance::where('statut', 'effectuee')
                ->whereYear('date_seance', $annee)
                ->whereMonth('date_seance', $mois)
                ->count();
            
            // Séances réservées pour ce mois
            $reservees = Seance::where('statut', 'reservee')
                ->whereYear('date_seance', $annee)
                ->whereMonth('date_seance', $mois)
                ->count();
            
            $seancesParMois[] = [
                'mois' => $date->format('M Y'),
                'effectuees' => $effectuees,
                'reservees' => $reservees,
            ];
        }
        
        // S'assurer que les données de séances par mois ne sont pas vides
        // Si pas de données, créer un tableau vide mais structuré
        
        // Répartition des inscriptions
        $inscriptionComplete = Enfant::where('statut_paiement', 'paye')->count();
        $abonnementSeance = Enfant::where('statut_paiement', 'non_paye')->count();
        
        return view('livewire.admin.statistiques', [
            'revenuTotal' => $revenuTotal,
            'utilisateursInscrits' => $utilisateursInscrits,
            'reservationsActives' => $reservationsActives,
            'seancesEffectuees' => $seancesEffectuees,
            'statsMois' => $statsMois,
            'seancesParStatut' => $seancesParStatut,
            'seancesParMois' => $seancesParMois,
            'inscriptionComplete' => $inscriptionComplete,
            'abonnementSeance' => $abonnementSeance,
        ]);
    }
}
