<?php

namespace App\Livewire\Admin;

use App\Models\Enfant;
use App\Models\ParentTuteur;
use App\Models\Seance;
use App\Models\Parametre;
use Livewire\Component;
use Livewire\WithPagination;

class ListeInscrits extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterPaiement = 'tous';
    
    // Modal de réservation
    public $enfantSelected = null;
    public $nbSeancesReservation = 0;
    public $datesSeancesReservation = [];
    public $moisCourantReservation = '';
    
    // Séances passées
    public $seancesPassees = [];
    
    // Séance en cours de replanification
    public $seanceAReplanifier = null;
    public $nouvelleDateSeance = null;
    public $showDatepicker = false;
    
    public function mount()
    {
        $this->moisCourantReservation = date('Y-m');
    }
    
    // Listeners pour les événements de notification
    protected $listeners = [
        'success' => 'handleSuccess',
        'error' => 'handleError',
        'info' => 'handleInfo',
    ];
    
    public function handleSuccess($message)
    {
        session()->flash('notification', ['type' => 'success', 'message' => $message]);
    }
    
    public function handleError($message)
    {
        session()->flash('notification', ['type' => 'error', 'message' => $message]);
    }
    
    public function handleInfo($message)
    {
        session()->flash('notification', ['type' => 'info', 'message' => $message]);
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilterPaiement()
    {
        $this->resetPage();
    }
    
    public function openReservationModal($enfantId)
    {
        $this->enfantSelected = Enfant::with('parent', 'seances')->findOrFail($enfantId);
        $this->nbSeancesReservation = $this->enfantSelected->nombre_seances_total;
        
        // Récupérer toutes les dates de séances (réservées uniquement)
        $this->datesSeancesReservation = $this->enfantSelected->seances
            ->where('statut', 'reservee')
            ->pluck('date_seance')
            ->map(function($date) {
                return $date->format('Y-m-d');
            })->toArray();
        
        // Détecter les séances passées (manquées/expirées)
        $this->seancesPassees = $this->enfantSelected->seances
            ->filter(function($seance) {
                // Séances passées avec statut 'reservee' (n'ont pas été marquées effectuées)
                return $seance->estPassee() && $seance->statut === 'reservee';
            })
            ->map(function($seance) {
                return [
                    'id' => $seance->id,
                    'date' => $seance->date_seance->format('Y-m-d'),
                    'date_formatee' => $seance->date_seance->format('d/m/Y'),
                    'statut' => $seance->statut,
                ];
            })->toArray();
        
        // Réinitialiser le mode replanification
        $this->seanceAReplanifier = null;
        $this->nouvelleDateSeance = null;
        $this->showDatepicker = false;
        
        $this->moisCourantReservation = date('Y-m');
    }
    
    public function closeReservationModal()
    {
        $this->enfantSelected = null;
        $this->nbSeancesReservation = 0;
        $this->datesSeancesReservation = [];
        $this->seancesPassees = [];
        $this->seanceAReplanifier = null;
        $this->nouvelleDateSeance = null;
        $this->showDatepicker = false;
    }
    
    /**
     * Marquer une séance passée comme effectuée
     */
    public function marquerSeanceEffectuee($seanceId)
    {
        $seance = Seance::findOrFail($seanceId);
        
        if ($seance->enfant_id !== $this->enfantSelected->id) {
            $this->dispatch('error', message: 'Erreur de sécurité');
            return;
        }
        
        $seance->update(['statut' => 'effectuee']);
        
        // Mettre à jour le nombre de séances utilisées
        $this->enfantSelected->increment('nombre_seances_utilisees');
        
        // Rafraîchir les données
        $this->openReservationModal($this->enfantSelected->id);
        
        $this->dispatch('success', message: 'Séance marquée comme effectuée');
    }
    
    /**
     * Initier le report d'une séance (mode replanification)
     */
    public function initierReportSeance($seanceId)
    {
        $seance = Seance::findOrFail($seanceId);
        
        if ($seance->enfant_id !== $this->enfantSelected->id) {
            $this->dispatch('error', message: 'Erreur de sécurité');
            return;
        }
        
        // Sauvegarder la séance à replanifier
        $this->seanceAReplanifier = $seance;
        $this->nouvelleDateSeance = null;
        $this->showDatepicker = true;
        
        $this->dispatch('info', message: 'Sélectionnez une nouvelle date pour reprogrammer la séance du ' . $seance->date_seance->format('d/m/Y'));
    }
    
    /**
     * Annuler le report
     */
    public function annulerReplanification()
    {
        $this->seanceAReplanifier = null;
        $this->nouvelleDateSeance = null;
        $this->showDatepicker = false;
    }
    
    /**
     * Confirmer la replanification
     */
    public function confirmerReplanification()
    {
        if (!$this->seanceAReplanifier || !$this->nouvelleDateSeance) {
            $this->dispatch('error', message: 'Veuillez sélectionner une nouvelle date');
            return;
        }
        
        $this->validate([
            'nouvelleDateSeance' => 'required|date|after_or_equal:today',
        ]);
        
        $ancienneDate = $this->seanceAReplanifier->date_seance->format('Y-m-d');
        $nouvelleDate = $this->nouvelleDateSeance;
        
        // Retirer l'ancienne date de la liste des dates sélectionnées
        $this->datesSeancesReservation = array_filter(
            $this->datesSeancesReservation, 
            fn($d) => $d !== $ancienneDate
        );
        
        // Ajouter la nouvelle date si elle n'existe pas déjà
        if (!in_array($nouvelleDate, $this->datesSeancesReservation)) {
            $this->datesSeancesReservation[] = $nouvelleDate;
        }
        
        // Marquer l'ancienne séance comme reportée
        $this->seanceAReplanifier->update(['statut' => 'reporter']);
        
        // Créer la nouvelle séance avec la nouvelle date
        Seance::create([
            'enfant_id' => $this->enfantSelected->id,
            'date_seance' => $nouvelleDate,
            'heure_debut' => null,
            'heure_fin' => null,
            'statut' => 'reservee',
            'note' => 'Reporté depuis le ' . $ancienneDate,
        ]);
        
        // Réinitialiser les variables
        $this->seanceAReplanifier = null;
        $this->nouvelleDateSeance = null;
        $this->showDatepicker = false;
        
        // Rafraîchir les données
        $this->openReservationModal($this->enfantSelected->id);
        
        $this->dispatch('success', message: 'Séance reprogrammée avec succès');
    }
    
    /**
     * Reporter une séance passée (ancienne méthode - sans replanification)
     */
    public function reporterSeance($seanceId)
    {
        $seance = Seance::findOrFail($seanceId);
        
        if ($seance->enfant_id !== $this->enfantSelected->id) {
            $this->dispatch('error', message: 'Erreur de sécurité');
            return;
        }
        
        // Retirer de la liste des dates sélectionnées
        $dateToRemove = $seance->date_seance->format('Y-m-d');
        $this->datesSeancesReservation = array_filter(
            $this->datesSeancesReservation, 
            fn($d) => $d !== $dateToRemove
        );
        
        // Marquer comme reportée
        $seance->update(['statut' => 'reporter']);
        
        // Rafraîchir les données
        $this->openReservationModal($this->enfantSelected->id);
        
        $this->dispatch('info', message: 'Séance reportée. Vous pouvez maintenant sélectionner une nouvelle date.');
    }
    
    public function prevMoisReservation()
    {
        $mois = new \DateTime($this->moisCourantReservation . '-01');
        $mois->modify('-1 month');
        $this->moisCourantReservation = $mois->format('Y-m');
    }
    
    public function nextMoisReservation()
    {
        $mois = new \DateTime($this->moisCourantReservation . '-01');
        $mois->modify('+1 month');
        $this->moisCourantReservation = $mois->format('Y-m');
    }
    
    public function toggleDateReservation($date)
    {
        $dates = $this->datesSeancesReservation;
        $totalSeances = $this->nbSeancesReservation;
        
        if (in_array($date, $dates)) {
            $dates = array_filter($dates, fn($d) => $d !== $date);
        } elseif (count($dates) < $totalSeances) {
            $dates[] = $date;
        }
        
        $this->datesSeancesReservation = array_values($dates);
    }
    
    /**
     * Marquer une séance comme effectuée directement depuis le calendrier
     * Fonctionne pour TOUTES les séances (passées ou futures)
     */
    public function marquerSeanceDepuisCalendrier($date)
    {
        if (!$this->enfantSelected) {
            return;
        }
        
        // Trouver la séance pour cette date, quel que soit le statut
        $seance = $this->enfantSelected->seances->firstWhere(function($s) use ($date) {
            return $s->date_seance->format('Y-m-d') === $date;
        });
        
        if (!$seance) {
            $this->dispatch('error', message: 'Séance non trouvée');
            return;
        }
        
        if ($seance->statut === 'reservee') {
            // Passer à effectuée
            $seance->update(['statut' => 'effectuee']);
            $this->enfantSelected->increment('nombre_seances_utilisees');
            $message = $seance->estPassee()
                ? 'Séance marquée comme effectuée'
                : 'Séance future marquée comme effectuée (rattrapage anticipé)';
            $this->dispatch('success', message: $message);
        } elseif ($seance->statut === 'effectuee') {
            // Revenir à réservée (correction d’erreur)
            $seance->update(['statut' => 'reservee']);
            if ($this->enfantSelected->nombre_seances_utilisees > 0) {
                $this->enfantSelected->decrement('nombre_seances_utilisees');
            }
            $this->dispatch('info', message: 'Séance rebasculée en réservée');
        } else {
            $this->dispatch('error', message: 'Action non disponible pour ce statut');
        }
        
        // Rafraîchir les données
        $this->openReservationModal($this->enfantSelected->id);
    }
    
    public function saveReservation()
    {
        // Validation
        $this->validate([
            'nbSeancesReservation' => 'required|integer|min:1',
            'datesSeancesReservation' => 'required|array',
        ]);
        
        // Vérifier qu'on a des dates sélectionnées
        if (empty($this->datesSeancesReservation)) {
            $this->dispatch('error', message: 'Veuillez sélectionner au moins une date');
            return;
        }
        
        try {
            // Mettre à jour le nombre de séances total de l'enfant
            $this->enfantSelected->update([
                'nombre_seances_total' => $this->nbSeancesReservation,
            ]);
            
            // Récupérer toutes les séances existantes avec statut 'reservee' ou 'manquee' de cet enfant
            $seancesExistantes = Seance::where('enfant_id', $this->enfantSelected->id)
                ->whereIn('statut', ['reservee', 'manquee'])
                ->get();
            
            $datesSeancesNouvelles = array_map('strval', $this->datesSeancesReservation);
            
            // Supprimer les séances qui ne sont plus dans la nouvelle sélection
            foreach ($seancesExistantes as $seance) {
                $dateSeance = $seance->date_seance->format('Y-m-d');
                if (!in_array($dateSeance, $datesSeancesNouvelles)) {
                    $seance->delete();
                }
            }
            
            // Créer les nouvelles séances qui n'existent pas encore
            $prix_seance = Parametre::where('cle', 'prix_seance')->first();
            $prix = $prix_seance ? (int)$prix_seance->valeur : 5000;
            foreach ($datesSeancesNouvelles as $dateSeance) {
                $existe = Seance::where('enfant_id', $this->enfantSelected->id)
                    ->whereDate('date_seance', $dateSeance)
                    ->whereIn('statut', ['reservee', 'manquee', 'effectuee'])
                    ->exists();
                
                if (!$existe) {
                    Seance::create([
                        'enfant_id' => $this->enfantSelected->id,
                        'date_seance' => $dateSeance,
                        'heure_debut' => null,
                        'heure_fin' => null,
                        'statut' => 'reservee',
                        'paiement_statut' => Seance::PAIEMENT_EN_ATTENTE,
                        'montant' => $prix,
                        'note' => null,
                    ]);
                }
            }
            
            // Rafraîchir l'enfant et ses séances
            $this->enfantSelected->refresh();
            $this->enfantSelected->load('seances');
            
            $this->dispatch('success', message: 'Réservations enregistrées avec succès !');
            
            // Fermer le modal
            $this->closeReservationModal();
            
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Valider le paiement d'une séance (depuis le calendrier admin)
     */
    public function validerPaiementDate($date)
    {
        if (!$this->enfantSelected) {
            return;
        }
        $seance = Seance::where('enfant_id', $this->enfantSelected->id)
            ->whereDate('date_seance', $date)
            ->where('statut', 'reservee')
            ->first();
        if (!$seance) {
            $this->dispatch('error', message: 'Séance non trouvée pour validation');
            return;
        }
        $seance->update(['paiement_statut' => Seance::PAIEMENT_VALIDE]);
        $this->dispatch('success', message: 'Paiement validé pour la date ' . $date);
        $this->openReservationModal($this->enfantSelected->id);
    }
    
    public function getMontantReservationProperty()
    {
        if (!$this->nbSeancesReservation) {
            return 0;
        }
        
        $prix_seance = Parametre::where('cle', 'prix_seance')->first();
        $prix = $prix_seance ? (int)$prix_seance->valeur : 5000;
        
        return $this->nbSeancesReservation * $prix;
    }
    
    /**
     * Basculer le statut de paiement (payé ↔ non payé)
     */
    public function toggleStatutPaiement($enfantId)
    {
        $enfant = Enfant::find($enfantId);
        
        if (!$enfant) {
            $this->dispatch('error', message: 'Enfant non trouvé');
            return;
        }
        
        // Inverser le statut
        $nouveauStatut = $enfant->statut_paiement === 'paye' ? 'non_paye' : 'paye';
        
        $enfant->update(['statut_paiement' => $nouveauStatut]);
        
        $statutTexte = $nouveauStatut === 'paye' ? 'Payé' : 'Non payé';
        $this->dispatch('success', message: "Statut changé à : {$statutTexte}");
    }
    
    /**
     * Rediriger vers la page de modification
     */
    public function editEnfant($enfantId)
    {
        return redirect()->route('admin.inscrits.details', $enfantId);
    }
    
    /**
     * Ouvrir le modal de confirmation de suppression
     */
    public function showDeleteConfirmation($enfantId)
    {
        $enfant = Enfant::find($enfantId);
        
        if (!$enfant) {
            $this->dispatch('error', message: 'Enfant non trouvé');
            return;
        }
        
        $this->dispatch('confirm-delete', enfantId: $enfantId, nom: $enfant->nom . ' ' . $enfant->prenom);
    }
    
    /**
     * Supprimer un enfant
     */
    public function deleteEnfant($enfantId)
    {
        $enfant = Enfant::find($enfantId);
        
        if (!$enfant) {
            $this->dispatch('error', message: 'Enfant non trouvé');
            return;
        }
        
        try {
            $nom = $enfant->nom . ' ' . $enfant->prenom;
            $enfant->delete();
            
            $this->dispatch('success', message: "L'enfant {$nom} a été supprimé");
            
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Erreur lors de la suppression');
        }
    }
    
    public function render()
    {
        $prix_seance = Parametre::where('cle', 'prix_seance')->first();
        $prix = $prix_seance ? (int)$prix_seance->valeur : 5000;
        
        $enfants = Enfant::with(['parent', 'seances'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('prenom', 'like', '%' . $this->search . '%')
                      ->orWhere('classe', 'like', '%' . $this->search . '%');
                })->orWhereHas('parent', function ($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('prenom', 'like', '%' . $this->search . '%')
                      ->orWhere('telephone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterPaiement !== 'tous', function ($query) {
                $query->where('statut_paiement', $this->filterPaiement);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('livewire.admin.liste-inscrits', [
            'enfants' => $enfants,
            'prix_seance' => $prix,
        ]);
    }
}
