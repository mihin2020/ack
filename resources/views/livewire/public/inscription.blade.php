<div class="p-6"
     x-data="{ loading: false }"
     x-on:livewire:submit="loading = true"
     x-on:livewire:response="loading = false">
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Inscription</h2>
    </div>
    <div class="mb-6 flex justify-center">
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 w-full max-w-xl text-center">
            <p class="text-sm text-blue-900">
                Frais d'inscription: <span class="font-semibold">{{ number_format($tarif_inscription, 0, ',', ' ') }} FCFA</span> payable une fois —
                Prix par séance: <span class="font-semibold">{{ number_format($prix_seance, 0, ',', ' ') }} FCFA</span>
            </p>
            <p class="text-xs text-blue-800 mt-1">
                NB: le montant total des séances = nombre de séances × prix par séance.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        @if($message_error || $errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                @if($message_error)
                    <p class="font-medium mb-2">{{ $message_error }}</p>
                @endif
                @if ($errors->any())
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <form wire:submit.prevent="inscrire" class="space-y-8">
            <!-- Informations Enfants -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations des enfants</h3>

                @foreach($enfants as $index => $enfant)
                <div class="border-2 border-red-200 rounded-lg p-6 mb-4 bg-red-50" wire:key="enfant-{{ $index }}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-semibold text-red-600">Enfant {{ $index + 1 }}</h4>
                        @if(count($enfants) > 1)
                        <button type="button" wire:click="supprimerEnfant({{ $index }})" class="text-red-500 hover:text-red-700 font-semibold">
                            Supprimer
                        </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                            <input type="text" wire:model="enfants.{{ $index }}.nom" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
                            @error("enfants.{$index}.nom") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                            <input type="text" wire:model="enfants.{{ $index }}.prenom" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
                            @error("enfants.{$index}.prenom") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance *</label>
                            <input type="date" wire:model="enfants.{{ $index }}.date_naissance" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
                            @error("enfants.{$index}.date_naissance") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Classe *</label>
                            <select wire:model="enfants.{{ $index }}.classe" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                                <option value="">Sélectionner la classe</option>
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
                            @error("enfants.{$index}.classe") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de séances *</label>
                            <input type="number" min="0" wire:model.live="enfants.{{ $index }}.nombre_seances" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Ex : 5, 10, 20"/>
                            @error("enfants.{$index}.nombre_seances") <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            @if(($enfant['nombre_seances'] ?? 0) > 0)
                                <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm font-semibold text-green-700">
                                        Montant : {{ number_format((int)($enfant['nombre_seances'] ?? 0) * $prix_seance, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Calendrier : affiché quand une valeur est saisie dans Nombre de séances -->
                        @if(($enfant['nombre_seances'] ?? 0) > 0)
                        <div class="md:col-span-2 mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Planification des séances sur calendrier</label>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-gray-600">
                                    Séances restantes : <span class="font-bold text-red-600">{{ (int)($enfant['nombre_seances'] ?? 0) - count($enfant['dates_seances'] ?? []) }}</span>
                                </span>
                            </div>
                            <div class="border border-red-300 rounded-lg p-4 bg-white">
                                @include('livewire.public.calendrier', ['index' => $index, 'enfant' => $enfant])
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- Bouton Ajouter Enfant -->
                <div class="flex justify-center mb-4">
                    <button type="button" wire:click="ajouterEnfant" class="flex items-center px-6 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                        <span class="mr-2">+</span>
                        Ajouter un autre enfant
                    </button>
                </div>
            </div>

            <!-- Informations Parent -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du parent / tuteur</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                        <input type="text" wire:model="nom_parent" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
                        @error('nom_parent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                        <input type="text" wire:model="prenom_parent" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
                        @error('prenom_parent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                        <input type="tel" wire:model="telephone_parent" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
                        @error('telephone_parent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email_parent" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
                        @error('email_parent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <textarea wire:model="adresse_parent" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact d'urgence 1</label>
                        <input type="text" wire:model="contact_urgence_parent_1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Ex: 01234567"/>
                        @error('contact_urgence_parent_1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact d'urgence 2</label>
                        <input type="text" wire:model="contact_urgence_parent_2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Ex: 08765432"/>
                        @error('contact_urgence_parent_2') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Informations médicales</label>
                        <textarea wire:model="infos_medicales_parent" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- Résumé Montants -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <div>
                        <p class="text-sm text-gray-600">Frais d'inscription (annuels)</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($tarif_inscription, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total des réservations (séances)</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($montantTotal ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Montant total potentiel</p>
                        <p class="text-xl font-bold text-orange-600">{{ number_format($tarif_inscription + ($montantTotal ?? 0), 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-700">
                    Vous pouvez régler uniquement les frais d'inscription maintenant. Les réservations de séances peuvent être payées plus tard.
                </p>
            </div>

            <!-- Bouton Submit -->
            <div class="flex justify-end pt-4">
                <button type="submit"
                        x-bind:disabled="loading"
                        class="px-8 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Enregistrer l'inscription</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enregistrement...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Popup succès --}}
    @if($message_success)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/50 transition-opacity" wire:click="clearSuccessMessage"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center"
             x-data
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Inscription enregistrée</h3>
            <p class="text-gray-600 mb-8">{{ $message_success }}</p>
            <button type="button"
                    wire:click="clearSuccessMessage"
                    class="w-full px-6 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Fermer
            </button>
        </div>
    </div>
    @endif
</div>
