<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Enfant extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'parent_id',
        'nom',
        'prenom',
        'date_naissance',
        'classe',
        'programme',
        'nombre_seances_total',
        'nombre_seances_utilisees',
        'statut_paiement',
    ];
    
    protected $casts = [
        'date_naissance' => 'date',
    ];
    
    public function parent()
    {
        return $this->belongsTo(ParentTuteur::class, 'parent_id');
    }
    
    public function seances()
    {
        return $this->hasMany(Seance::class, 'enfant_id');
    }
    
    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_naissance)->age;
    }
    
    public function getSeancesRestantesAttribute()
    {
        return $this->nombre_seances_total - $this->nombre_seances_utilisees;
    }
    
    public function estPaye()
    {
        return $this->statut_paiement === 'paye';
    }
}
