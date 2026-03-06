<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Parametre extends Model
{
    use HasUuids;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parametres';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cle',
        'valeur',
        'description',
    ];
    
    /**
     * Get the value of a parameter by its key
     */
    public static function getValeur(string $cle): ?string
    {
        $parametre = self::where('cle', $cle)->first();
        return $parametre ? $parametre->valeur : null;
    }
    
    /**
     * Set the value of a parameter by its key
     */
    public static function setValeur(string $cle, string $valeur): bool
    {
        return self::updateOrCreate(
            ['cle' => $cle],
            ['valeur' => $valeur]
        );
    }
}
