<div class="max-w-4xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
        <p class="text-gray-600 mt-2">Gérez vos informations personnelles et votre mot de passe</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations Personnelles -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <span class="material-symbols-outlined mr-2">person</span>
                    Informations Personnelles
                </h2>
                
                <form wire:submit.prevent="updateProfile">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input type="text" wire:model="nom" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input type="text" wire:model="prenom" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('prenom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="text" wire:model="telephone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('telephone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- Changer le mot de passe -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <span class="material-symbols-outlined mr-2">lock</span>
                    Changer le mot de passe
                </h2>
                
                <form wire:submit.prevent="updatePassword">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                            <input type="password" wire:model="password_current" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('password_current') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                            <input type="password" wire:model="password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                            <input type="password" wire:model="password_confirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        
                        <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition">
                            Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informations compte -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <span class="material-symbols-outlined mr-2">info</span>
                    Informations du compte
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-500">Rôle</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-500">Compte</label>
                        <div class="mt-1">
                            @if($user->actif)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Inactif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-500">Membre depuis</label>
                        <div class="mt-1 text-gray-900">
                            {{ $user->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





