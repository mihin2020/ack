<?php

namespace App\Livewire\Admin;

use App\Models\Enfant;
use App\Models\Seance;
use App\Models\Parametre;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $revenuTotal = 0;
    public $utilisateursInscrits = 0;
    public $reservationsActives = 0;

    /** Labels et données pour le graphique Évolution des inscriptions (6 derniers mois). */
    public $evolutionLabels = [];
    public $evolutionData = [];

    /** Labels et données pour le graphique Répartition des inscriptions (par classe). */
    public $repartitionLabels = [];
    public $repartitionData = [];
    public $repartitionColors = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadEvolutionInscriptions();
        $this->loadRepartitionInscriptions();
    }

    public function loadStats()
    {
        $prixSeance = (int) (Parametre::where('cle', 'prix_seance')->first()->valeur ?? 5000);
        $this->revenuTotal = Enfant::sum('nombre_seances_total') * $prixSeance;
        $this->utilisateursInscrits = Enfant::count();
        $this->reservationsActives = Seance::where('statut', 'reservee')->count();
    }

    /** Évolution des inscriptions : nombre d’inscriptions par mois (6 derniers mois). */
    protected function loadEvolutionInscriptions(): void
    {
        $this->evolutionLabels = [];
        $this->evolutionData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $debut = $date->copy()->startOfMonth();
            $fin = $date->copy()->endOfMonth();

            $this->evolutionLabels[] = $date->locale('fr')->translatedFormat('M Y');
            $this->evolutionData[] = Enfant::whereBetween('created_at', [$debut, $fin])->count();
        }
    }

    /** Répartition des inscriptions : par classe. */
    protected function loadRepartitionInscriptions(): void
    {
        $classes = Enfant::query()
            ->selectRaw('classe, COUNT(*) as total')
            ->whereNotNull('classe')
            ->where('classe', '!=', '')
            ->groupBy('classe')
            ->orderBy('classe')
            ->get();

        if ($classes->isEmpty()) {
            $this->repartitionLabels = ['Aucune donnée'];
            $this->repartitionData = [0];
            $this->repartitionColors = ['#9CA3AF'];
            return;
        }

        $this->repartitionLabels = $classes->pluck('classe')->toArray();
        $this->repartitionData = $classes->pluck('total')->toArray();

        $palette = ['#EF4444', '#F97316', '#22C55E', '#3B82F6', '#8B5CF6', '#EC4899', '#14B8A6', '#EAB308'];
        $this->repartitionColors = [];
        foreach (range(0, count($this->repartitionLabels) - 1) as $i) {
            $this->repartitionColors[] = $palette[$i % count($palette)];
        }
    }

    public function render()
    {
        $dernieresInscriptions = Enfant::with('parent')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin.dashboard', [
            'dernieresInscriptions' => $dernieresInscriptions,
        ]);
    }
}
