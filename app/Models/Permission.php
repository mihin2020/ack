<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Permission extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'nom',
        'slug',
        'description',
    ];
    
    /**
     * Get all users with this permission
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }
}
