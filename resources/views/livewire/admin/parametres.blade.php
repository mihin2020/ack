<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Paramètres</h2>
            <p class="text-gray-600 mt-1">Gérez les tarifs, utilisateurs et paramètres de l'application</p>
        </div>
    </div>
    
    @if(session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session()->has('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif
    
    <!-- Onglets -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex space-x-1 border-b border-gray-200">
            <button wire:click="$set('activeTab', 'tarifs')" class="px-4 py-2 font-medium text-sm {{ $activeTab === 'tarifs' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-600 hover:text-gray-900' }}">
                Tarifs
            </button>
            <button wire:click="$set('activeTab', 'utilisateurs')" class="px-4 py-2 font-medium text-sm {{ $activeTab === 'utilisateurs' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-600 hover:text-gray-900' }}">
                Utilisateurs
            </button>
        </div>
    </div>
    
    <!-- Contenu des onglets -->
    @if($activeTab === 'tarifs')
        <!-- Section Tarifs -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tarifs</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="prix_seance" class="block text-sm font-medium text-gray-700 mb-2">
                                Prix d'une séance (FCFA)
                            </label>
                            <input type="number" 
                                   id="prix_seance"
                                   wire:model="prix_seance" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                   placeholder="Ex: 5000"/>
                            @error('prix_seance') 
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                            <p class="text-sm text-gray-500 mt-2">Prix unitaire d'une séance individuelle</p>
                        </div>
                        
                        <div>
                            <label for="tarif_inscription_annuelle" class="block text-sm font-medium text-gray-700 mb-2">
                                Tarif inscription annuelle (FCFA)
                            </label>
                            <input type="number" 
                                   id="tarif_inscription_annuelle"
                                   wire:model="tarif_inscription_annuelle" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                   placeholder="Ex: 20000"/>
                            @error('tarif_inscription_annuelle') 
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                            <p class="text-sm text-gray-500 mt-2">Frais d'inscription annuelle pour un enfant</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" wire:model="afficher_note_inscription" class="sr-only peer">
                            <span class="mr-3 text-sm text-gray-700">Afficher la note tarifs sur la page d'inscription (public)</span>
                            <span class="w-10 h-6 rounded-full shadow-inner relative bg-gray-200 peer-checked:bg-green-400 transition-colors">
                                <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></span>
                            </span>
                        </label>
                        <div class="text-xs text-gray-500 mt-1" wire:loading.remove>
                            État: <span class="font-semibold">{{ $afficher_note_inscription ? 'Affiché' : 'Masqué' }}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" wire:loading>
                            Sauvegarde en cours...
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                        Sauvegarder les paramètres
                    </button>
                </div>
            </form>
        </div>
    @endif
    
    @if($activeTab === 'utilisateurs')
        <!-- Section Utilisateurs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Gestion des utilisateurs</h3>
                <button wire:click="openUserModal()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors flex items-center space-x-2">
                    <span class="material-symbols-outlined text-sm">add</span>
                    <span>Ajouter un utilisateur</span>
                </button>
            </div>
            
            <!-- Liste des utilisateurs -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users ?? [] as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->prenom }} {{ $user->nom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user->telephone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->role === 'administrateur' ? 'bg-red-100 text-red-800' : 
                                           ($user->role === 'gestionnaire' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button wire:click="openPermissionsModal('{{ $user->id }}')" class="text-blue-600 hover:text-blue-900">
                                        Permissions
                                    </button>
                                    <button wire:click="openUserModal('{{ $user->id }}')" class="text-green-600 hover:text-green-900">
                                        Modifier
                                    </button>
                                    @if($user->id !== Auth::id())
                                        <button wire:click="deleteUser('{{ $user->id }}')" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')" class="text-red-600 hover:text-red-900">
                                            Supprimer
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Aucun utilisateur trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Modal Création/Modification Utilisateur -->
@if($showUserModal)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="modal-user" style="display: block !important;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $editingUser ? 'Modifier l\'utilisateur' : 'Ajouter un utilisateur' }}
                </h3>
                <button wire:click="closeUserModal" class="text-gray-500 hover:text-gray-700">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form wire:submit.prevent="saveUser">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input type="text" wire:model="userData.nom" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('userData.nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                        <input type="text" wire:model="userData.prenom" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('userData.prenom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="userData.email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('userData.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="text" wire:model="userData.telephone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('userData.telephone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <select wire:model="userData.role" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="administrateur">Administrateur</option>
                            <option value="gestionnaire">Gestionnaire</option>
                            <option value="secretaire">Secrétaire</option>
                        </select>
                        @error('userData.role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    @if(!$editingUser)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input type="password" wire:model="userData.password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('userData.password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <input type="password" wire:model="userData.password_confirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe (optionnel)</label>
                        <input type="password" wire:model="userData.password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('userData.password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <input type="password" wire:model="userData.password_confirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    @endif
                </div>
                
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" wire:click="closeUserModal" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                        {{ $editingUser ? 'Modifier' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal Permissions -->
@if($selectedUser)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="modal-permissions">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">
                    Permissions de {{ $selectedUser->prenom }} {{ $selectedUser->nom }}
                </h3>
                <button wire:click="closePermissionsModal" class="text-gray-500 hover:text-gray-700">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="space-y-3 mb-6">
                @forelse($permissions ?? [] as $permission)
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" 
                               wire:click="togglePermission('{{ $permission->id }}')"
                               {{ in_array($permission->id, $userPermissions ?? []) ? 'checked' : '' }}
                               class="mr-3">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $permission->nom }}</div>
                            <div class="text-sm text-gray-500">{{ $permission->description }}</div>
                        </div>
                    </label>
                @empty
                    <div class="text-center text-gray-500 py-4">Aucune permission disponible</div>
                @endforelse
            </div>
            
            <div class="flex justify-end space-x-2">
                <button wire:click="closePermissionsModal" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Annuler
                </button>
                <button wire:click="savePermissions" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
@endif
