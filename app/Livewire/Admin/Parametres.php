<?php

namespace App\Livewire\Admin;

use App\Models\Parametre;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Parametres extends Component
{
    public $activeTab = 'tarifs';
    
    public $prix_seance;
    public $tarif_inscription_annuelle;
    public $afficher_note_inscription;
    
    // Gestion utilisateurs
    public $users;
    public $showUserModal = false;
    public $userData = [
        'nom' => '',
        'prenom' => '',
        'email' => '',
        'telephone' => '',
        'password' => '',
        'password_confirmation' => '',
        'role' => 'secretaire'
    ];
    public $editingUser = null;
    
    // Gestion permissions
    public $permissions;
    public $userPermissions = [];
    public $selectedUser = null;
    
    protected $listeners = ['refreshComponent' => '$refresh'];
    
    public function mount()
    {
        $prix = Parametre::where('cle', 'prix_seance')->first();
        $tarif = Parametre::where('cle', 'tarif_inscription_annuelle')->first();
        $flag = Parametre::where('cle', 'afficher_note_inscription')->first();
        
        $this->prix_seance = $prix ? $prix->valeur : '5000';
        $this->tarif_inscription_annuelle = $tarif ? $tarif->valeur : '20000';
        $this->afficher_note_inscription = $flag ? (bool) ((int) $flag->valeur) : true;
        
        $this->loadUsers();
        $this->loadPermissions();
    }
    
    public function loadUsers()
    {
        $this->users = User::all();
    }
    
    public function loadPermissions()
    {
        $this->permissions = Permission::all();
    }
    
    // ============= GESTION DES TARIFS =============
    
    public function save()
    {
        $this->validate([
            'prix_seance' => 'required|numeric|min:1',
            'tarif_inscription_annuelle' => 'required|numeric|min:1',
        ]);
        
        Parametre::updateOrCreate(
            ['cle' => 'prix_seance'],
            ['valeur' => $this->prix_seance, 'description' => 'Prix unitaire d\'une séance en FCFA']
        );
        
        Parametre::updateOrCreate(
            ['cle' => 'tarif_inscription_annuelle'],
            ['valeur' => $this->tarif_inscription_annuelle, 'description' => 'Frais d\'inscription annuelle en FCFA']
        );
        
        Parametre::updateOrCreate(
            ['cle' => 'afficher_note_inscription'],
            ['valeur' => $this->afficher_note_inscription ? '1' : '0', 'description' => 'Afficher la note tarifs sur la page d\'inscription publique']
        );
        
        session()->flash('success', 'Paramètres sauvegardés avec succès !');
    }

    /**
     * Sauvegarder immédiatement quand le switch est basculé
     */
    public function updatedAfficherNoteInscription($value)
    {
        Parametre::updateOrCreate(
            ['cle' => 'afficher_note_inscription'],
            ['valeur' => $value ? '1' : '0', 'description' => 'Afficher la note tarifs sur la page d\'inscription publique']
        );
        session()->flash('success', 'Affichage de la note mis à jour.');
    }
    
    // ============= GESTION DES UTILISATEURS =============
    
    public function openUserModal($userId = null)
    {
        Log::info('openUserModal called', ['userId' => $userId]);
        $this->resetUserData();
        
        if ($userId) {
            $this->editingUser = User::findOrFail($userId);
            $this->userData = [
                'nom' => $this->editingUser->nom,
                'prenom' => $this->editingUser->prenom,
                'email' => $this->editingUser->email,
                'telephone' => $this->editingUser->telephone,
                'password' => '',
                'password_confirmation' => '',
                'role' => $this->editingUser->role
            ];
        }
        
        $this->showUserModal = true;
        Log::info('showUserModal set to true');
    }
    
    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->resetUserData();
    }
    
    public function resetUserData()
    {
        $this->editingUser = null;
        $this->userData = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'telephone' => '',
            'password' => '',
            'password_confirmation' => '',
            'role' => 'secretaire'
        ];
    }
    
    public function saveUser()
    {
        $rules = [
            'userData.nom' => 'required|string|max:255',
            'userData.prenom' => 'required|string|max:255',
            'userData.email' => 'required|email|unique:users,email,' . ($this->editingUser ? $this->editingUser->id : ''),
            'userData.telephone' => 'required|string|max:20',
            'userData.role' => 'required|in:administrateur,gestionnaire,secretaire'
        ];
        
        if ($this->editingUser) {
            // Édition : mot de passe optionnel
            if (!empty($this->userData['password'])) {
                $rules['userData.password'] = 'required|min:8|confirmed';
            }
        } else {
            // Création : mot de passe obligatoire
            $rules['userData.password'] = 'required|min:8|confirmed';
        }
        
        $this->validate($rules);
        
        $data = [
            'nom' => $this->userData['nom'],
            'prenom' => $this->userData['prenom'],
            'email' => $this->userData['email'],
            'telephone' => $this->userData['telephone'],
            'role' => $this->userData['role'],
            'actif' => true
        ];
        
        if (!empty($this->userData['password'])) {
            $data['password'] = Hash::make($this->userData['password']);
        }
        
        if ($this->editingUser) {
            $this->editingUser->update($data);
            session()->flash('success', 'Utilisateur modifié avec succès !');
        } else {
            User::create($data);
            session()->flash('success', 'Utilisateur créé avec succès !');
        }
        
        $this->loadUsers();
        $this->closeUserModal();
    }
    
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Ne pas permettre la suppression de soi-même
        if ($user->id === Auth::id()) {
            session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte !');
            return;
        }
        
        $user->delete();
        $this->loadUsers();
        session()->flash('success', 'Utilisateur supprimé avec succès !');
    }
    
    // ============= GESTION DES PERMISSIONS =============
    
    public function openPermissionsModal($userId)
    {
        $this->selectedUser = User::with('permissions')->findOrFail($userId);
        
        // Charger les permissions actuelles de l'utilisateur
        $this->userPermissions = $this->selectedUser->permissions->pluck('id')->toArray();
    }
    
    public function togglePermission($permissionId)
    {
        if (in_array($permissionId, $this->userPermissions)) {
            // Retirer la permission
            $this->userPermissions = array_values(array_diff($this->userPermissions, [$permissionId]));
        } else {
            // Ajouter la permission
            $this->userPermissions[] = $permissionId;
        }
    }
    
    public function savePermissions()
    {
        if (!$this->selectedUser) {
            session()->flash('error', 'Aucun utilisateur sélectionné');
            return;
        }
        
        // Synchroniser les permissions
        $this->selectedUser->permissions()->sync($this->userPermissions);
        
        session()->flash('success', 'Permissions mises à jour avec succès !');
        $this->selectedUser = null;
        $this->userPermissions = [];
    }
    
    public function closePermissionsModal()
    {
        $this->selectedUser = null;
        $this->userPermissions = [];
    }
    
    public function render()
    {
        return view('livewire.admin.parametres', [
            'users' => $this->users,
            'permissions' => $this->permissions
        ]);
    }
}
