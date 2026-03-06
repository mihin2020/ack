@php
    $mois = new \DateTime($moisCourantReservation . '-01');
    $premierJour = new \DateTime($mois->format('Y-m-01'));
    $dernierJour = new \DateTime($mois->format('Y-m-t'));
    $premierJourSemaine = (int)$premierJour->format('N'); // 1 = lundi, 7 = dimanche
    
    $aujourdhui = new \DateTime();
    $datesSelectionnees = $datesSeancesReservation ?? [];
    $totalSeances = $nbSeancesReservation ?? 0;
    
    // Récupérer toutes les séances pour affichage
    $seancesPassees = [];
    $seancesEffectuees = [];
    $seancesReserveesFutures = [];
    
    if ($enfantSelected && $enfantSelected->seances) {
        foreach ($enfantSelected->seances as $seance) {
            $dateSeance = new \DateTime($seance->date_seance);
            $dateStr = $seance->date_seance->format('Y-m-d');
            
            // Séances passées non effectuées (expirées)
            if ($dateSeance < $aujourdhui && $seance->statut === 'reservee') {
                $seancesPassees[] = [
                    'id' => $seance->id,
                    'date' => $dateStr,
                    'statut' => $seance->statut,
                ];
            }
            // Séances effectuées
            elseif ($seance->statut === 'effectuee') {
                $seancesEffectuees[] = $dateStr;
            }
            // Séances réservées à venir
            elseif ($dateSeance >= $aujourdhui && $seance->statut === 'reservee') {
                $seancesReserveesFutures[] = $dateStr;
            }
        }
    }
@endphp

<div class="calendrier-reservation-nav">
    <button type="button" wire:click="prevMoisReservation" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm">
        ← Précédent
    </button>
    <span class="font-semibold text-gray-800 capitalize">
        {{ $mois->format('F Y') }}
    </span>
    <button type="button" wire:click="nextMoisReservation" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm">
        Suivant →
    </button>
</div>

<div class="calendrier-reservation-grid">
    <!-- En-têtes des jours -->
    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
        <div class="calendrier-reservation-jour">{{ $jour }}</div>
    @endforeach
    
    <!-- Jours du mois précédent -->
    @for($i = $premierJourSemaine - 1; $i > 0; $i--)
        @php
            $date = (clone $premierJour)->modify("-{$i} days");
            $dateStr = $date->format('Y-m-d');
            $estPasse = $date < $aujourdhui;
        @endphp
        <div class="calendrier-reservation-date autre-mois disabled">
            {{ $date->format('j') }}
        </div>
    @endfor
    
    <!-- Jours du mois actuel -->
    @for($jour = 1; $jour <= $dernierJour->format('j'); $jour++)
        @php
            $date = new \DateTime($mois->format("Y-m-{$jour}"));
            $dateStr = $date->format('Y-m-d');
            $estPasse = $date < $aujourdhui;
            $estSelectionne = in_array($dateStr, $datesSelectionnees);
            $peutSelectionner = !$estPasse && (count($datesSelectionnees) < $totalSeances || $estSelectionne);
            
            // Vérifier le statut de la date
            $estSeancePassee = in_array($dateStr, array_column($seancesPassees, 'date'));
            $estSeanceEffectuee = in_array($dateStr, $seancesEffectuees);
            
            // Chercher la séance correspondante
            $seanceActuelle = null;
            if ($enfantSelected && $enfantSelected->seances) {
                foreach ($enfantSelected->seances as $seance) {
                    if ($seance->date_seance->format('Y-m-d') === $dateStr) {
                        $seanceActuelle = $seance;
                        break;
                    }
                }
            }
        @endphp
        <div wire:click="toggleDateReservation('{{ $dateStr }}')"
             @if(isset($seanceActuelle) && $seanceActuelle)
                 wire:dblclick="marquerSeanceDepuisCalendrier('{{ $dateStr }}')"
                 title="{{ $seanceActuelle->statut === 'reservee' ? 'Double-clic: marquer comme effectuée' : ($seanceActuelle->statut === 'effectuee' ? 'Double-clic: revenir à réservée' : '') }}"
             @endif
             class="calendrier-reservation-date {{ $estSelectionne ? 'selected' : '' }} {{ $estSeancePassee ? 'passe-non-effectuee' : '' }} {{ $estSeanceEffectuee ? 'effectuee' : '' }} {{ !$peutSelectionner && !$estSeancePassee && !$estSeanceEffectuee ? 'disabled' : '' }} {{ ($peutSelectionner || $estSeancePassee || $estSeanceEffectuee) ? 'cursor-pointer' : 'cursor-not-allowed' }})">
            {{ $jour }}
            @if($estSeancePassee)
                <span class="text-xs block text-red-600 font-bold">⚠️</span>
                <span class="text-xs text-gray-600" style="font-size: 9px;">↪ Db</span>
            @endif
            @if($estSeanceEffectuee)
                <span class="text-xs block text-green-600 font-bold">✓</span>
            @endif
            @if(isset($seanceActuelle) && $seanceActuelle && $seanceActuelle->statut === 'reservee' && !$estSeancePassee)
                <div class="mt-1 flex items-center justify-center gap-1">
                    @if($seanceActuelle->paiement_statut === 'en_attente')
                        <button type="button" wire:click.stop="validerPaiementDate('{{ $dateStr }}')" class="px-1.5 py-0.5 text-[10px] rounded bg-amber-100 text-amber-800 border border-amber-200 hover:bg-amber-200">Valider</button>
                    @elseif($seanceActuelle->paiement_statut === 'valide')
                        <span class="px-1.5 py-0.5 text-[10px] rounded bg-emerald-100 text-emerald-800 border border-emerald-200">Payée</span>
                    @endif
                    <span class="text-xs text-blue-600" style="font-size: 9px;">↪ Db</span>
                </div>
            @endif
        </div>
    @endfor
    
    <!-- Jours du mois suivant pour compléter la grille -->
    @php
        $jourActuel = $dernierJour->format('j');
        $joursAvant = $premierJourSemaine - 1;
        $totalCases = ceil(($joursAvant + $jourActuel) / 7) * 7;
        $joursApres = $totalCases - ($joursAvant + $jourActuel);
    @endphp
    @for($i = 1; $i <= $joursApres; $i++)
        @php
            $date = (clone $dernierJour)->modify("+{$i} days");
        @endphp
        <div class="calendrier-reservation-date autre-mois disabled">
            {{ $date->format('j') }}
        </div>
    @endfor
</div>
