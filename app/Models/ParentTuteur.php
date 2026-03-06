<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ParentTuteur extends Model
{
    use HasUuids;
    
    protected $table = 'parents';
    
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'adresse',
        'contact_urgence',
        'contact_urgence_2',
        'infos_medicales',
    ];
    
    public function enfants()
    {
        return $this->hasMany(Enfant::class, 'parent_id');
    }
    
    public function getNomCompletAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
