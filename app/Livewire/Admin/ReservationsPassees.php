<?php

namespace App\Livewire\Admin;

use App\Models\Seance;
use App\Models\Enfant;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class ReservationsPassees extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterStatut = 'tous'; // tous, reservee, reporter
    public $filterEnfant = '';
    
    // Modal de report
    public $seanceSelected = null;
    public $nouvelleDate = '';
    public $noteReport = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilterStatut()
    {
        $this->resetPage();
    }
    
    public function openReportModal($seanceId)
    {
        $this->seanceSelected = Seance::with('enfant.parent')->findOrFail($seanceId);
        $this->nouvelleDate = '';
        $this->noteReport = '';
        $this->dispatch('open-report-modal');
    }
    
    public function closeReportModal()
    {
        $this->seanceSelected = null;
        $this->nouvelleDate = '';
        $this->noteReport = '';
        $this->dispatch('close-report-modal');
    }
    
    public function confirmerReport()
    {
        $this->validate([
            'nouvelleDate' => 'required|date|after_or_equal:today',
            'noteReport' => 'nullable|string|max:255',
        ]);
        
        try {
            // Créer une nouvelle séance avec la nouvelle date
            $nouvelleSeance = Seance::create([
                'enfant_id' => $this->seanceSelected->enfant_id,
                'date_seance' => $this->nouvelleDate,
                'heure_debut' => $this->seanceSelected->heure_debut,
                'heure_fin' => $this->seanceSelected->heure_fin,
                'statut' => 'reservee',
                'note' => $this->noteReport ? "Reporté de {$this->seanceSelected->date_seance->format('d/m/Y')}. {$this->noteReport}" : "Reporté de {$this->seanceSelected->date_seance->format('d/m/Y')}",
            ]);
            
            // Mettre à jour l'ancienne séance comme reportée
            $this->seanceSelected->update([
                'statut' => 'reporter',
                'note' => $this->noteReport ? "Reporté à {$this->nouvelleDate}. {$this->noteReport}" : "Reporté à {$this->nouvelleDate}",
            ]);
            
            $this->dispatch('report-success', message: 'Réservation reportée avec succès !');
            $this->closeReportModal();
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('report-error', message: 'Une erreur est survenue lors du report.');
        }
    }
    
    public function marquerCommeEffectuee($seanceId)
    {
        try {
            $seance = Seance::findOrFail($seanceId);
            $seance->update(['statut' => 'effectuee']);
            
            $this->dispatch('success', message: 'Séance marquée comme effectuée !');
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Une erreur est survenue.');
        }
    }
    
    public function supprimerReservation($seanceId)
    {
        try {
            $seance = Seance::findOrFail($seanceId);
            $seance->delete();
            
            $this->dispatch('success', message: 'Réservation supprimée avec succès !');
            $this->resetPage();
            
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Une erreur est survenue.');
        }
    }
    
    public function render()
    {
        $seancesPassees = Seance::with(['enfant.parent'])
            ->where('date_seance', '<', Carbon::today())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('enfant', function ($q) {
                        $q->where('nom', 'like', '%' . $this->search . '%')
                          ->orWhere('prenom', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->filterStatut !== 'tous', function ($query) {
                $query->where('statut', $this->filterStatut);
            })
            ->orderBy('date_seance', 'desc')
            ->paginate(10);
        
        $enfants = Enfant::orderBy('nom')->get();
        
        return view('livewire.admin.reservations-passees', [
            'seancesPassees' => $seancesPassees,
            'enfants' => $enfants,
        ]);
    }
}

