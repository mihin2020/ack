<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profil extends Component
{
    public $user;
    public $nom;
    public $prenom;
    public $email;
    public $telephone;
    public $password;
    public $password_confirmation;
    public $password_current;
    
    protected $rules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email',
        'telephone' => 'nullable|string|max:20',
        'password' => 'nullable|min:8|confirmed',
    ];
    
    public function mount()
    {
        $this->user = Auth::user();
        $this->nom = $this->user->nom;
        $this->prenom = $this->user->prenom;
        $this->email = $this->user->email;
        $this->telephone = $this->user->telephone;
    }
    
    public function updateProfile()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'telephone' => 'nullable|string|max:20',
        ]);
        
        $this->user->update([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
        ]);
        
        session()->flash('success', 'Profil mis à jour avec succès !');
    }
    
    public function updatePassword()
    {
        $this->validate([
            'password_current' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        // Vérifier le mot de passe actuel
        if (!Hash::check($this->password_current, $this->user->password)) {
            session()->flash('error', 'Le mot de passe actuel est incorrect !');
            return;
        }
        
        // Mettre à jour le mot de passe
        $this->user->update([
            'password' => Hash::make($this->password),
        ]);
        
        // Réinitialiser les champs
        $this->password_current = '';
        $this->password = '';
        $this->password_confirmation = '';
        
        session()->flash('success', 'Mot de passe modifié avec succès !');
    }
    
    public function render()
    {
        return view('livewire.admin.profil', [
            'user' => $this->user
        ]);
    }
}





