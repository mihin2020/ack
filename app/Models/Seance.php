<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Seance extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'enfant_id',
        'date_seance',
        'heure_debut',
        'heure_fin',
        'statut',
        'paiement_statut',
        'montant',
        'note',
    ];
    
    const STATUT_RESERVEE = 'reservee';
    const STATUT_EFFECTUEE = 'effectuee';
    const STATUT_REPORTER = 'reporter';
    const STATUT_MANQUEE = 'manquee';
    
    const PAIEMENT_EN_ATTENTE = 'en_attente';
    const PAIEMENT_VALIDE = 'valide';
    const PAIEMENT_ANNULE = 'annule';
    
    protected $casts = [
        'date_seance' => 'date',
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
    ];
    
    public function enfant()
    {
        return $this->belongsTo(Enfant::class, 'enfant_id');
    }
    
    public function estReservee()
    {
        return $this->statut === 'reservee';
    }
    
    public function estEffectuee()
    {
        return $this->statut === 'effectuee';
    }
    
    public function estReporter()
    {
        return $this->statut === 'reporter';
    }
    
    /**
     * Vérifie si la séance est passée
     */
    public function estPassee()
    {
        return \Carbon\Carbon::parse($this->date_seance)->isPast();
    }
    
    /**
     * Vérifie si la séance est à venir
     */
    public function estAVenir()
    {
        return \Carbon\Carbon::parse($this->date_seance)->isFuture();
    }
    
    /**
     * Scope pour récupérer les séances passées non effectuées
     */
    public function scopePasseesNonEffectuees($query)
    {
        return $query->where('statut', 'reservee')
                     ->whereDate('date_seance', '<', now());
    }
    
    /**
     * Scope pour récupérer les séances à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('statut', 'reservee')
                     ->whereDate('date_seance', '>=', now());
    }
    
    /**
     * Vérifie si la séance est manquée/expirée
     */
    public function estManquee()
    {
        return $this->statut === 'manquee' 
               || (\Carbon\Carbon::parse($this->date_seance)->isPast() 
                   && $this->statut === 'reservee');
    }
    
    /**
     * Scope pour récupérer les séances manquées
     */
    public function scopeManquees($query)
    {
        return $query->where('statut', 'manquee')
                     ->orWhere(function($q) {
                         $q->where('statut', 'reservee')
                           ->whereDate('date_seance', '<', now());
                     });
    }
    
    /**
     * Marquer la séance comme manquée automatiquement
     */
    public function marquerCommeManquee()
    {
        if ($this->estPassee() && $this->statut === 'reservee') {
            $this->update(['statut' => 'manquee']);
        }
    }
}
