<?php

namespace App\Livewire\Admin;

use App\Models\Enfant;
use App\Models\Seance;
use Livewire\Component;

class DetailsInscrit extends Component
{
    public $enfant;
    public $seances;
    public $editing = false;
    
    public $nom;
    public $prenom;
    public $date_naissance;
    public $classe;
    public $statut_paiement;
    public $nombre_seances_total;
    
    public function mount($id)
    {
        $this->enfant = Enfant::with('parent', 'seances')->findOrFail($id);
        $this->loadData();
    }
    
    public function loadData()
    {
        $this->nom = $this->enfant->nom;
        $this->prenom = $this->enfant->prenom;
        $this->date_naissance = $this->enfant->date_naissance->format('Y-m-d');
        $this->classe = $this->enfant->classe;
        $this->statut_paiement = $this->enfant->statut_paiement;
        $this->nombre_seances_total = $this->enfant->nombre_seances_total;
    }
    
    public function edit()
    {
        $this->editing = true;
    }
    
    public function cancel()
    {
        $this->editing = false;
        $this->loadData();
    }
    
    public function update()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'classe' => 'required|string',
            'statut_paiement' => 'required|in:paye,non_paye',
            'nombre_seances_total' => 'required|integer|min:1',
        ]);
        
        $this->enfant->update([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date_naissance' => $this->date_naissance,
            'classe' => $this->classe,
            'statut_paiement' => $this->statut_paiement,
            'nombre_seances_total' => $this->nombre_seances_total,
        ]);
        
        $this->editing = false;
        $this->dispatch('updated-success', message: 'Informations mises à jour avec succès !');
    }
    
    public function delete()
    {
        $this->enfant->delete();
        return redirect()->route('admin.liste-inscrits')->with('success', 'Inscription supprimée avec succès !');
    }
    
    public function render()
    {
        $this->seances = Seance::where('enfant_id', $this->enfant->id)->orderBy('date_seance', 'desc')->get();
        
        return view('livewire.admin.details-inscrit');
    }
}









