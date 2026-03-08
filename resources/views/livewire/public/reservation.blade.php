<div class="container mx-auto px-4 py-8 max-w-6xl">
    <header class="text-center mb-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Réservation de Séances</h2>
        <p class="text-gray-600">Recherchez vos enfants et consultez leur calendrier de séances</p>
    </header>
    
    <!-- Recherche par téléphone -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-8">
        <form wire:submit.prevent="search" class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <input type="tel" 
                   wire:model="telephone_parent" 
                   placeholder="Numéro de téléphone" 
                   class="w-full min-w-0 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"/>
            <button type="submit" class="w-full sm:w-auto shrink-0 px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                Rechercher
            </button>
        </form>
    </div>
    
    <!-- Liste des enfants trouvés -->
    @if(count($enfants) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @foreach($enfants as $enfantData)
        <div class="bg-white rounded-lg shadow-md p-6 border-2 {{ $enfant_selected == $enfantData->id ? 'border-green-500' : 'border-gray-200' }}">
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $enfantData->prenom }} {{ $enfantData->nom }}</h3>
            <div class="space-y-2 text-sm text-gray-600 mb-4">
                <p><span class="font-semibold">Classe:</span> {{ $enfantData->classe }}</p>
                <p><span class="font-semibold">Séances:</span> {{ $enfantData->nombre_seances_utilisees }} / {{ $enfantData->nombre_seances_total }}</p>
                <p>
                    <span class="font-semibold">Statut:</span>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $enfantData->statut_paiement === 'paye' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $enfantData->statut_paiement === 'paye' ? 'Payé' : 'Non payé' }}
                    </span>
                </p>
            </div>
            <button wire:click="selectEnfant('{{ $enfantData->id }}')" 
                    class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                Voir le calendrier
            </button>
        </div>
        @endforeach
    </div>
    @elseif($telephone_parent)
    <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 px-6 py-4 rounded-lg mb-8">
        Aucun enfant trouvé pour ce numéro de téléphone.
    </div>
    @endif
    
    <!-- Calendrier des séances -->
    @if($enfant && $enfant_selected)
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-xl font-bold text-gray-800">Calendrier de {{ $enfant->prenom }} {{ $enfant->nom }}</h3>
            <div class="flex items-center gap-4">
                <button wire:click="prevMois" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 font-semibold">
                    ← Précédent
                </button>
                <span class="font-semibold text-gray-800 capitalize">
                    {{ \Carbon\Carbon::parse($mois_courant . '-01')->format('F Y') }}
                </span>
                <button wire:click="nextMois" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 font-semibold">
                    Suivant →
                </button>
            </div>
        </div>
        <div class="mb-4 text-sm text-gray-700 flex items-center justify-between">
            <span>Prix par séance: <span class="font-semibold">{{ number_format($this->prixSeance, 0, ',', ' ') }} FCFA</span></span>
            <span>Cliquez une date future vide pour réserver. Cliquez à nouveau pour annuler.</span>
        </div>
        
        <div class="mb-10 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-100 border border-slate-300 text-slate-900 rounded-lg px-4 py-3">
                <div class="text-sm">Total réservations (ensemble)</div>
                <div class="text-2xl font-bold">{{ $nbReservationsTotal }}</div>
            </div>
            <div class="bg-amber-100 border border-amber-300 text-amber-950 rounded-lg px-4 py-3">
                <div class="flex items-center justify-between text-sm">
                    <span>En attente (ensemble)</span>
                    <span class="font-semibold">{{ number_format($totalEnAttenteTotal, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="text-xl font-bold">{{ $nbEnAttenteTotal }}</div>
            </div>
            <div class="bg-emerald-100 border border-emerald-300 text-emerald-950 rounded-lg px-4 py-3">
                <div class="flex items-center justify-between text-sm">
                    <span>Validées (ensemble)</span>
                    <span class="font-semibold">{{ number_format($totalValideTotal, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="text-xl font-bold">{{ $nbValideesTotal }}</div>
            </div>
        </div>
        
        @include('livewire.public.calendrier-reservation', ['enfant' => $enfant, 'mois_courant' => $mois_courant])
        
        <!-- Légende -->
        <div class="mt-6 flex flex-wrap gap-4 justify-center">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-200 border border-red-400"></div>
                <span class="text-sm text-gray-600">Réservée</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-200 border border-green-400"></div>
                <span class="text-sm text-gray-600">Effectuée</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-orange-200 border border-orange-400"></div>
                <span class="text-sm text-gray-600">Reportée</span>
            </div>
        </div>
    </div>
    @endif
</div>
