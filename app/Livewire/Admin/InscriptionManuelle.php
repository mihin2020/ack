<?php

namespace App\Livewire\Admin;

use App\Models\ParentTuteur;
use App\Models\Enfant;
use App\Models\Seance;
use App\Models\Parametre;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Locked;

class InscriptionManuelle extends Component
{
    // Informations parent
    public $nom_parent = '';
    public $prenom_parent = '';
    public $telephone_parent = '';
    public $email_parent = '';
    public $adresse_parent = '';
    public $contact_urgence_1 = '';
    public $contact_urgence_2 = '';
    public $infos_medicales_parent = '';
    
    // Informations enfants (multiple)
    public $enfants = [
        [
            'nom' => '',
            'prenom' => '',
            'date_naissance' => '',
            'classe' => '',
            'nombre_seances' => 0,
            'statut_paiement' => 'non_paye',
            'dates_seances' => [],
            'mois_courant' => '',
        ]
    ];
    
    // UI
    public $message_success = '';
    public $message_error = '';
    
    public function mount()
    {
        $this->enfants[0]['mois_courant'] = date('Y-m');
    }
    
    public function ajouterEnfant()
    {
        $this->enfants[] = [
            'nom' => '',
            'prenom' => '',
            'date_naissance' => '',
            'classe' => '',
            'nombre_seances' => 0,
            'statut_paiement' => 'non_paye',
            'dates_seances' => [],
            'mois_courant' => date('Y-m'),
        ];
    }
    
    public function supprimerEnfant($index)
    {
        if (count($this->enfants) > 1) {
            unset($this->enfants[$index]);
            $this->enfants = array_values($this->enfants);
        }
    }
    
    public function prevMois($index)
    {
        if (isset($this->enfants[$index])) {
            $mois = new \DateTime($this->enfants[$index]['mois_courant'] . '-01');
            $mois->modify('-1 month');
            $this->enfants[$index]['mois_courant'] = $mois->format('Y-m');
        }
    }
    
    public function nextMois($index)
    {
        if (isset($this->enfants[$index])) {
            $mois = new \DateTime($this->enfants[$index]['mois_courant'] . '-01');
            $mois->modify('+1 month');
            $this->enfants[$index]['mois_courant'] = $mois->format('Y-m');
        }
    }
    
    public function toggleDate($index, $date)
    {
        if (!isset($this->enfants[$index])) {
            return;
        }
        
        $dates = $this->enfants[$index]['dates_seances'] ?? [];
        $totalSeances = $this->enfants[$index]['nombre_seances'] ?? 0;
        
        if (in_array($date, $dates)) {
            $dates = array_filter($dates, fn($d) => $d !== $date);
        } elseif (count($dates) < $totalSeances) {
            $dates[] = $date;
        }
        
        $this->enfants[$index]['dates_seances'] = array_values($dates);
    }
    
    protected function rules()
    {
        $rules = [
            'nom_parent' => 'required|string|max:255',
            'prenom_parent' => 'required|string|max:255',
            'telephone_parent' => 'required|string',
            'email_parent' => 'nullable|email',
            'adresse_parent' => 'nullable|string',
            'contact_urgence_1' => 'nullable|string',
            'contact_urgence_2' => 'nullable|string',
            'infos_medicales_parent' => 'nullable|string',
        ];
        
        foreach ($this->enfants as $index => $enfant) {
            $rules["enfants.{$index}.nom"] = 'required|string|max:255';
            $rules["enfants.{$index}.prenom"] = 'required|string|max:255';
            $rules["enfants.{$index}.date_naissance"] = 'required|date';
            $rules["enfants.{$index}.classe"] = 'required|string';
            $rules["enfants.{$index}.nombre_seances"] = 'required|integer|min:1';
            $rules["enfants.{$index}.statut_paiement"] = 'required|in:paye,non_paye';
        }
        
        return $rules;
    }
    
    public function inscrire()
    {
        $this->message_success = '';
        $this->message_error = '';
        
        Log::info('Méthode inscrire appelée', ['data' => $this->enfants]);
        
        $this->validate();
        
        try {
            // Vérifier ou créer le parent
            $parent = ParentTuteur::where('telephone', $this->telephone_parent)->first();
            
            if (!$parent) {
                $parent = ParentTuteur::create([
                    'nom' => $this->nom_parent,
                    'prenom' => $this->prenom_parent,
                    'telephone' => $this->telephone_parent,
                    'email' => $this->email_parent ?: null,
                    'adresse' => $this->adresse_parent ?: null,
                    'contact_urgence' => $this->contact_urgence_1 ?: null,
                    'contact_urgence_2' => $this->contact_urgence_2 ?: null,
                    'infos_medicales' => $this->infos_medicales_parent ?: null,
                ]);
            }
            
            // Créer les enfants
            foreach ($this->enfants as $enfantData) {
                $enfant = Enfant::create([
                    'parent_id' => $parent->id,
                    'nom' => $enfantData['nom'],
                    'prenom' => $enfantData['prenom'],
                    'date_naissance' => $enfantData['date_naissance'],
                    'classe' => $enfantData['classe'],
                    'programme' => null,
                    'nombre_seances_total' => $enfantData['nombre_seances'],
                    'nombre_seances_utilisees' => 0,
                    'statut_paiement' => $enfantData['statut_paiement'],
                ]);
                
                // Créer les séances pour cet enfant
                if (isset($enfantData['dates_seances']) && is_array($enfantData['dates_seances'])) {
                    foreach ($enfantData['dates_seances'] as $dateSeance) {
                        Seance::create([
                            'enfant_id' => $enfant->id,
                            'date_seance' => $dateSeance,
                            'heure_debut' => null,
                            'heure_fin' => null,
                            'statut' => 'reservee',
                            'note' => null,
                        ]);
                    }
                }
            }
            
            $this->dispatch('inscription-success', message: 'Inscription enregistrée avec succès !');
            $this->resetForm();
            
        } catch (\Exception $e) {
            Log::error('Erreur inscription: ' . $e->getMessage());
            $this->dispatch('inscription-error', message: 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
        }
    }
    
    private function resetForm()
    {
        $this->nom_parent = '';
        $this->prenom_parent = '';
        $this->telephone_parent = '';
        $this->email_parent = '';
        $this->adresse_parent = '';
        $this->contact_urgence_1 = '';
        $this->contact_urgence_2 = '';
        $this->infos_medicales_parent = '';
        $this->enfants = [[
            'nom' => '',
            'prenom' => '',
            'date_naissance' => '',
            'classe' => '',
            'nombre_seances' => 0,
            'statut_paiement' => 'non_paye',
            'dates_seances' => [],
            'mois_courant' => date('Y-m'),
        ]];
    }
    
    public function getMontantTotalProperty()
    {
        $prix_seance = Parametre::where('cle', 'prix_seance')->first();
        $prix = $prix_seance ? (int)$prix_seance->valeur : 5000;
        
        $total = 0;
        foreach ($this->enfants as $enfant) {
            $nombreSeances = (int)($enfant['nombre_seances'] ?? 0);
            $total += $nombreSeances * $prix;
        }
        
        return $total;
    }
    
    public function render()
    {
        $prix_seance = Parametre::where('cle', 'prix_seance')->first();
        $prix = $prix_seance ? (int)$prix_seance->valeur : 5000;

        $tarif_inscription = Parametre::where('cle', 'tarif_inscription_annuelle')->first();
        $tarif = $tarif_inscription ? (int)$tarif_inscription->valeur : 20000;

        return view('livewire.admin.inscription-manuelle', [
            'prix_seance' => $prix,
            'tarif_inscription' => $tarif,
        ]);
    }
}
