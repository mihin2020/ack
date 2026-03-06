<div>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Liste des Inscrits</h2>
            <p class="text-gray-600 mt-1">Gérez tous les enfants inscrits à l'académie</p>
        </div>
    </div>
    
    <!-- Barre de recherche et filtres -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Rechercher par nom, prénom, classe ou téléphone..." 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
            </div>
            <div>
                <select wire:model.live="filterPaiement" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                    <option value="tous">Tous les statuts de paiement</option>
                    <option value="paye">Payé</option>
                    <option value="non_paye">Non payé</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Tableau des inscrits -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-semibold text-gray-700">Nom</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Prénom</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Date de naissance</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Classe</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Parent/Tuteur</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Téléphone</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Statut paiement</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Réservation</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Séances</th>
                        <th class="px-6 py-3 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enfants as $enfant)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $enfant->nom }}</td>
                        <td class="px-6 py-4">{{ $enfant->prenom }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($enfant->date_naissance)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $enfant->classe }}</td>
                        <td class="px-6 py-4">{{ $enfant->parent->nom_complet ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $enfant->parent->telephone ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleStatutPaiement('{{ $enfant->id }}')" 
                                    class="px-3 py-1 rounded-full text-xs font-semibold cursor-pointer hover:opacity-80 transition-opacity border {{ $enfant->statut_paiement === 'paye' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300' }}">
                                {{ $enfant->statut_paiement === 'paye' ? '✓ Payé' : '✗ Non payé' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $prochaineSeance = $enfant->seances
                                    ->where('statut', 'reservee')
                                    ->sortBy('date_seance')
                                    ->first();
                            @endphp
                            
                            @if($prochaineSeance)
                                <button wire:click="openReservationModal('{{ $enfant->id }}')" class="text-blue-600 hover:text-blue-800 font-semibold" title="Prochaine séance">
                                    {{ \Carbon\Carbon::parse($prochaineSeance->date_seance)->format('d/m/Y') }}
                                </button>
                            @else
                                <button wire:click="openReservationModal('{{ $enfant->id }}')" class="text-red-600 hover:text-red-800 font-semibold">
                                    Non réservé
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ $enfant->nombre_seances_utilisees }} / {{ $enfant->nombre_seances_total }}
                                </span>
                                @php
                                    $pourcentage = $enfant->nombre_seances_total > 0 
                                        ? ($enfant->nombre_seances_utilisees / $enfant->nombre_seances_total) * 100 
                                        : 0;
                                    
                                    $couleurBarre = '#10B981';
                                    if ($pourcentage >= 50 && $pourcentage < 80) {
                                        $couleurBarre = '#F59E0B';
                                    } elseif ($pourcentage >= 80) {
                                        $couleurBarre = '#EF4444';
                                    }
                                @endphp
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden" style="max-width: 100px;">
                                    <div class="h-full transition-all duration-300" style="width: {{ $pourcentage }}%; background-color: {{ $couleurBarre }};"></div>
                                </div>
                                @if($enfant->nombre_seances_total > 0)
                                    <span class="text-xs text-gray-500">
                                        {{ number_format($pourcentage, 0) }}%
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.inscrits.details', $enfant->id) }}" class="text-blue-600 hover:text-blue-800" title="Voir détails">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.inscrits.details', $enfant->id) }}" class="text-green-600 hover:text-green-800" title="Éditer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button wire:click="deleteEnfant('{{ $enfant->id }}')" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enfant ?')"
                                        class="text-red-600 hover:text-red-800" title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            Aucun inscrit trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $enfants->links() }}
        </div>
    </div>
</div>

<!-- Modal de réservation -->
@if($enfantSelected)
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
     x-data="{ showToast: false, toastMessage: '', toastType: 'success' }"
     x-on:reservation-success.window="showToast = true; toastType = 'success'; toastMessage = $event.detail.message; setTimeout(() => showToast = false, 3000)"
     x-on:reservation-error.window="showToast = true; toastType = 'error'; toastMessage = $event.detail.message; setTimeout(() => showToast = false, 3000)"
     x-on:success.window="showToast = true; toastType = 'success'; toastMessage = $event.detail.message; setTimeout(() => showToast = false, 3000)"
     x-on:error.window="showToast = true; toastType = 'error'; toastMessage = $event.detail.message; setTimeout(() => showToast = false, 3000)"
     x-on:info.window="showToast = true; toastType = 'info'; toastMessage = $event.detail.message; setTimeout(() => showToast = false, 3000)">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Gestion des séances et réservations</h2>
            <button wire:click="closeReservationModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <div class="p-6">
            <!-- Toast Notification -->
            <div x-show="showToast" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-3"
                 :class="{
                     'bg-green-500 text-white': toastType === 'success',
                     'bg-red-500 text-white': toastType === 'error',
                     'bg-blue-500 text-white': toastType === 'info'
                 }">
                <svg x-show="toastType === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <svg x-show="toastType === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <svg x-show="toastType === 'info'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="toastMessage" class="font-semibold"></span>
            </div>
            
            <!-- Informations enfant -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2">Enfant</label>
                <p class="text-lg font-semibold text-gray-800">{{ $enfantSelected->nom }} {{ $enfantSelected->prenom }}</p>
            </div>
            
            <!-- Alerte séances passées/expirées -->
            @php
                $aujourdhui = new \DateTime();
                $seancesPasseesAlert = $enfantSelected->seances->filter(function($seance) use ($aujourdhui) {
                    return new \DateTime($seance->date_seance) < $aujourdhui && $seance->statut === 'reservee';
                });
            @endphp
            @if($seancesPasseesAlert->count() > 0)
            <div class="mb-6 p-4 bg-red-50 border border-red-300 rounded-lg">
                <h3 class="text-md font-semibold text-red-700 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ $seancesPasseesAlert->count() }} séance(s) expirée(s) non effectuée(s)
                </h3>
                <div class="text-sm text-red-700 mb-3">
                    Ces séances sont passées. Si l'enfant a participé, marquez "Effectuée". Sinon, reportez-la.
                </div>
                <div class="space-y-2">
                    @foreach($seancesPasseesAlert as $seancePassee)
                    <div class="flex items-center justify-between bg-white p-3 rounded border border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($seancePassee->date_seance)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($seancePassee->date_seance)->locale('fr')->isoFormat('dddd') }})
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="marquerSeanceEffectuee('{{ $seancePassee->id }}')" 
                                    class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded transition-colors">
                                ✓ Marquer effectuée
                            </button>
                            <button wire:click="initierReportSeance('{{ $seancePassee->id }}')" 
                                    class="px-3 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded transition-colors">
                                ⏭️ Reporter
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Modal de replanification de séance -->
            @if($seanceAReplanifier && $showDatepicker)
            <div class="mb-6 p-4 bg-blue-50 border border-blue-300 rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-md font-semibold text-blue-700 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Replanifier la séance du {{ \Carbon\Carbon::parse($seanceAReplanifier->date_seance)->format('d/m/Y') }}
                    </h3>
                    <button wire:click="annulerReplanification" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="bg-white p-4 rounded border border-blue-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sélectionnez la nouvelle date
                    </label>
                    <input type="date" 
                           wire:model="nouvelleDateSeance"
                           min="{{ date('Y-m-d') }}"
                           class="w-full border border-blue-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required/>
                    @error('nouvelleDateSeance') 
                        <span class="text-red-600 text-sm block mt-1">{{ $message }}</span> 
                    @enderror
                    
                    <div class="flex gap-2 mt-3">
                        <button wire:click="confirmerReplanification" 
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors">
                            ✓ Confirmer la replanification
                        </button>
                        <button wire:click="annulerReplanification" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Nombre de séances -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Nombre total de séances</label>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                        Montant total : {{ number_format($this->montantReservation, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <input type="number" 
                       min="1" 
                       wire:model.live="nbSeancesReservation" 
                       class="w-full border border-orange-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" 
                       placeholder="Ex : 20"/>
                @error('nbSeancesReservation') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Calendrier -->
            @if($nbSeancesReservation > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Planification des séances sur calendrier</label>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-600">
                        Séances restantes : 
                        <span class="font-bold text-orange-500">
                            {{ $nbSeancesReservation - count($datesSeancesReservation) }}
                        </span>
                    </span>
                    <span class="text-xs text-gray-500">Max 7 séances par semaine</span>
                </div>
                <div class="border border-orange-300 rounded-lg p-4 bg-white">
                    @include('livewire.admin.calendrier-reservation')
                </div>
            </div>
            @endif
        </div>
        
        <!-- Boutons -->
        <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end space-x-2">
            <button wire:click="closeReservationModal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Annuler
            </button>
            <button wire:click="saveReservation" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                Enregistrer
            </button>
        </div>
    </div>
</div>
@endif

<style>
    .calendrier-reservation-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .calendrier-reservation-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }
    .calendrier-reservation-jour {
        text-align: center;
        padding: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
    }
    .calendrier-reservation-date {
        text-align: center;
        padding: 10px;
        font-size: 13px;
        border-radius: 6px;
        transition: all 0.2s;
        background: #F9FAFB;
    }
    .calendrier-reservation-date:hover:not(.disabled) {
        background: #FED7AA;
    }
    .calendrier-reservation-date.selected {
        background: #F97316;
        color: white;
        font-weight: 600;
    }
    .calendrier-reservation-date.disabled {
        background: #F3F4F6;
        color: #D1D5DB;
        cursor: not-allowed;
    }
    .calendrier-reservation-date.autre-mois {
        color: #D1D5DB;
    }
    .calendrier-reservation-date.passe-non-effectuee {
        background: #FEE2E2 !important;
        border: 2px solid #EF4444;
        color: #DC2626;
        font-weight: 600;
    }
    .calendrier-reservation-date.effectuee {
        background: #D1FAE5 !important;
        border: 2px solid #10B981;
        color: #059669;
        font-weight: 600;
        font-weight: 600;
    }
    .calendrier-reservation-date.passe-non-effectuee:hover {
        background: #FECACA !important;
    }
    .calendrier-reservation-date.passe-non-effectuee {
        animation: pulse-alert 2s infinite;
    }
    @keyframes pulse-alert {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .calendrier-reservation-date.effectuee:hover {
        background: #A7F3D0 !important;
    }
</style>
</div>
