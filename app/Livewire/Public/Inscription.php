<?php

namespace App\Livewire\Public;

use App\Models\ParentTuteur;
use App\Models\Enfant;
use App\Models\Seance;
use App\Models\Parametre;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Inscription extends Component
{
    // Informations parent
    public $nom_parent = '';
    public $prenom_parent = '';
    public $telephone_parent = '';
    public $email_parent = '';
    public $adresse_parent = '';
    public $contact_urgence_parent_1 = '';
    public $contact_urgence_parent_2 = '';
    public $infos_medicales_parent = '';
    
    // Informations enfants (multiple). nombre_seances à 0 au départ : le calendrier et les totaux s'affichent quand l'utilisateur saisit une valeur (validation min:1 à la soumission).
    public $enfants = [
        [
            'nom' => '',
            'prenom' => '',
            'date_naissance' => '',
            'classe' => '',
            'nombre_seances' => 0,
            'dates_seances' => [],
            'mois_courant' => '',
        ]
    ];
    
    // UI
    public $message_success = '';
    public $message_error = '';

    /** Ferme le popup de succès. */
    public function clearSuccessMessage()
    {
        $this->message_success = '';
    }
    
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
            'dates_seances' => [],
            'mois_courant' => date('Y-m'),
        ];
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
    
    public function supprimerEnfant($index)
    {
        if (count($this->enfants) > 1) {
            unset($this->enfants[$index]);
            $this->enfants = array_values($this->enfants);
        }
    }

    protected function rules()
    {
        $rules = [
            'nom_parent' => 'required|string|max:255',
            'prenom_parent' => 'required|string|max:255',
            'telephone_parent' => 'required|string',
            'email_parent' => 'nullable|email',
            'adresse_parent' => 'nullable|string',
            'contact_urgence_parent_1' => 'nullable|string',
            'contact_urgence_parent_2' => 'nullable|string',
            'infos_medicales_parent' => 'nullable|string',
        ];

        foreach ($this->enfants as $index => $enfant) {
            $rules["enfants.{$index}.nom"] = 'required|string|max:255';
            $rules["enfants.{$index}.prenom"] = 'required|string|max:255';
            $rules["enfants.{$index}.date_naissance"] = 'required|date';
            $rules["enfants.{$index}.classe"] = 'required|string';
            $rules["enfants.{$index}.nombre_seances"] = 'required|integer|min:1';
        }

        return $rules;
    }

    protected $messages = [
        'nom_parent.required' => 'Le nom du parent est requis.',
        'prenom_parent.required' => 'Le prénom du parent est requis.',
        'telephone_parent.required' => 'Le téléphone du parent est requis.',
        'enfants.*.nom.required' => 'Le nom de l\'enfant est requis.',
        'enfants.*.prenom.required' => 'Le prénom de l\'enfant est requis.',
        'enfants.*.date_naissance.required' => 'La date de naissance est requise.',
        'enfants.*.classe.required' => 'La classe est requise.',
        'enfants.*.nombre_seances.required' => 'Le nombre de séances est requis.',
        'enfants.*.nombre_seances.min' => 'Vous devez réserver au moins 1 séance par enfant.',
    ];

    /**
     * Logique d'inscription alignée sur la page admin (InscriptionManuelle) :
     * parent par téléphone, création enfants + séances, sans vérification doublon, sans transaction.
     * En cas d'erreur (validation ou exception), les données du formulaire ne sont jamais effacées.
     */
    public function inscrire()
    {
        $this->message_success = '';
        $this->message_error = '';

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Afficher les erreurs de validation sans réinitialiser : les données du formulaire restent telles qu'elles ont été envoyées.
            $this->setErrorBag($e->validator->errors());
            return;
        }

        try {
            // Même logique qu'admin : parent par téléphone (sans normalisation)
            $parent = ParentTuteur::where('telephone', $this->telephone_parent)->first();

            if (!$parent) {
                $parent = ParentTuteur::create([
                    'nom' => $this->nom_parent,
                    'prenom' => $this->prenom_parent,
                    'telephone' => $this->telephone_parent,
                    'email' => $this->email_parent ?: null,
                    'adresse' => $this->adresse_parent ?: null,
                    'contact_urgence' => $this->contact_urgence_parent_1 ?: null,
                    'contact_urgence_2' => $this->contact_urgence_parent_2 ?: null,
                    'infos_medicales' => $this->infos_medicales_parent ?: null,
                ]);
            }

            foreach ($this->enfants as $enfantData) {
                $enfant = Enfant::create([
                    'parent_id' => $parent->id,
                    'nom' => $enfantData['nom'],
                    'prenom' => $enfantData['prenom'],
                    'date_naissance' => $enfantData['date_naissance'],
                    'classe' => $enfantData['classe'],
                    'programme' => null,
                    'nombre_seances_total' => (int) ($enfantData['nombre_seances'] ?? 0),
                    'nombre_seances_utilisees' => 0,
                    'statut_paiement' => 'non_paye',
                ]);

                // Création des séances comme en admin (sans paiement_statut ni montant)
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

            $this->message_success = 'Inscription enregistrée avec succès !';
            $this->resetForm();
        } catch (\Exception $e) {
            Log::error('Public\\Inscription::inscrire exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->message_error = config('app.debug')
                ? 'Erreur : ' . $e->getMessage()
                : 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.';
            // Ne jamais réinitialiser le formulaire en cas d'erreur : les données restent affichées.
        }
    }
    
    private function resetForm()
    {
        $this->nom_parent = '';
        $this->prenom_parent = '';
        $this->telephone_parent = '';
        $this->email_parent = '';
        $this->adresse_parent = '';
        $this->contact_urgence_parent_1 = '';
        $this->contact_urgence_parent_2 = '';
        $this->infos_medicales_parent = '';
        $this->enfants = [[
            'nom' => '',
            'prenom' => '',
            'date_naissance' => '',
            'classe' => '',
            'nombre_seances' => 0,
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
            $total += ($enfant['nombre_seances'] ?? 0) * $prix;
        }
        
        return $total;
    }

    public function getNombreSeancesTotalProperty(): int
    {
        $totalSeances = 0;
        foreach ($this->enfants as $enfant) {
            $totalSeances += (int)($enfant['nombre_seances'] ?? 0);
        }
        return $totalSeances;
    }
    
    public function render()
    {
        $prix_seance = Parametre::where('cle', 'prix_seance')->first();
        $prix = $prix_seance ? (int)$prix_seance->valeur : 5000;
        $tarif_inscription = Parametre::where('cle', 'tarif_inscription_annuelle')->first();
        $tarif = $tarif_inscription ? (int)$tarif_inscription->valeur : 20000;
        $afficher_note = Parametre::getValeur('afficher_note_inscription');
        $afficher = $afficher_note === null ? true : ((int)$afficher_note === 1);

        // Recalcul des totaux à chaque render pour que la vue soit toujours à jour
        $montantTotal = 0;
        $nombreSeancesTotal = 0;
        foreach ($this->enfants as $enfant) {
            $nb = (int)($enfant['nombre_seances'] ?? 0);
            $nombreSeancesTotal += $nb;
            $montantTotal += $nb * $prix;
        }
        
        return view('livewire.public.inscription', [
            'prix_seance' => $prix,
            'tarif_inscription' => $tarif,
            'afficher_note_inscription' => $afficher,
            'montantTotal' => $montantTotal,
            'nombreSeancesTotal' => $nombreSeancesTotal,
        ]);
    }
}
