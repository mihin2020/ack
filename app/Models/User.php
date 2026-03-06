<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'role',
        'actif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    /**
     * Get the full name of the user
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Check if user is administrator
     */
    public function isAdministrateur(): bool
    {
        return $this->role === 'administrateur';
    }

    /**
     * Check if user is gestionnaire
     */
    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    /**
     * Check if user is secretaire
     */
    public function isSecretaire(): bool
    {
        return $this->role === 'secretaire';
    }

    /**
     * Get all permissions for this user
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdministrateur()) {
            return true;
        }

        return $this->permissions()->where('slug', $permission)->exists();
    }
}
