<div class="p-6" x-data="{ showDeleteModal: false }">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Détails de l'inscrit</h2>
            <p class="text-gray-600 mt-1">{{ $enfant->prenom }} {{ $enfant->nom }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.liste-inscrits') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                Retour à la liste
            </a>
            @if(!$editing)
            <button wire:click="edit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Modifier
            </button>
            <button @click="showDeleteModal = true" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                Supprimer
            </button>
            @endif
        </div>
    </div>
    
    <!-- Informations de l'enfant -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations de l'enfant</h3>
        
        @if($editing)
        <form wire:submit.prevent="update" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" wire:model="nom" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    @error('nom') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                    <input type="text" wire:model="prenom" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    @error('prenom') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance *</label>
                    <input type="date" wire:model="date_naissance" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    @error('date_naissance') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Classe *</label>
                    <select wire:model="classe" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="CP1">CP1</option>
                        <option value="CP2">CP2</option>
                        <option value="CE1">CE1</option>
                        <option value="CE2">CE2</option>
                        <option value="CM1">CM1</option>
                        <option value="CM2">CM2</option>
                        <option value="6ème">6ème</option>
                        <option value="5ème">5ème</option>
                        <option value="4ème">4ème</option>
                        <option value="3ème">3ème</option>
                        <option value="2nde">2nde</option>
                        <option value="1ère">1ère</option>
                        <option value="Terminale">Terminale</option>
                    </select>
                    @error('classe') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut de paiement *</label>
                    <select wire:model="statut_paiement" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="non_paye">Non payé</option>
                        <option value="paye">Payé</option>
                    </select>
                    @error('statut_paiement') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de séances total *</label>
                    <input type="number" min="1" wire:model="nombre_seances_total" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    @error('nombre_seances_total') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" wire:click="cancel" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nom</p>
                <p class="font-semibold text-gray-800">{{ $enfant->nom }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Prénom</p>
                <p class="font-semibold text-gray-800">{{ $enfant->prenom }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Date de naissance</p>
                <p class="font-semibold text-gray-800">{{ $enfant->date_naissance->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Classe</p>
                <p class="font-semibold text-gray-800">{{ $enfant->classe }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Statut de paiement</p>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $enfant->statut_paiement === 'paye' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $enfant->statut_paiement === 'paye' ? 'Payé' : 'Non payé' }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Séances</p>
                <p class="font-semibold text-gray-800">{{ $enfant->nombre_seances_utilisees }} / {{ $enfant->nombre_seances_total }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Informations du parent -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations du parent / tuteur</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nom complet</p>
                <p class="font-semibold text-gray-800">{{ $enfant->parent->nom_complet }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Téléphone</p>
                <p class="font-semibold text-gray-800">{{ $enfant->parent->telephone }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-semibold text-gray-800">{{ $enfant->parent->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Adresse</p>
                <p class="font-semibold text-gray-800">{{ $enfant->parent->adresse ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <!-- Liste des séances -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Historique des séances</h3>
        
        @if($seances->count() > 0)
        
        @php
            // Regrouper les séances par mois
            $seancesParMois = $seances->groupBy(function($seance) {
                return $seance->date_seance->format('Y-m');
            });
        @endphp
        
        @foreach($seancesParMois as $mois => $seancesMois)
        <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-700 mb-3 px-4 py-2 bg-gray-100 rounded-lg">
                {{ \Carbon\Carbon::createFromFormat('Y-m', $mois)->locale('fr')->isoFormat('MMMM YYYY') }}
            </h4>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($seancesMois as $seance)
                <div class="border rounded-lg p-3 text-center hover:shadow-md transition-shadow">
                    <div class="text-lg font-semibold text-gray-800 mb-1">
                        {{ $seance->date_seance->format('d') }}
                    </div>
                    <div class="text-xs text-gray-600 mb-2">
                        {{ $seance->date_seance->locale('fr')->isoFormat('ddd') }}
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold 
                        {{ $seance->statut === 'effectuee' ? 'bg-green-100 text-green-700' : 
                           ($seance->statut === 'reporter' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                        @if($seance->statut === 'effectuee')
                            ✓
                        @elseif($seance->statut === 'reporter')
                            ⏭️
                        @else
                            📅
                        @endif
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        
        @else
        <p class="text-gray-500 text-center py-8">Aucune séance enregistrée</p>
        @endif
    </div>
    
    <!-- Modal de confirmation de suppression -->
    <div x-show="showDeleteModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showDeleteModal = false">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer l'inscription de {{ $enfant->prenom }} {{ $enfant->nom }} ? Cette action est irréversible.</p>
            
            <div class="flex justify-end gap-3">
                <button @click="showDeleteModal = false" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Annuler
                </button>
                <button wire:click="delete" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>





