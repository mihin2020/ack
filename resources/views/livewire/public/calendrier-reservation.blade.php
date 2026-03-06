@php
    $mois = new \DateTime($mois_courant . '-01');
    $premierJour = new \DateTime($mois->format('Y-m-01'));
    $dernierJour = new \DateTime($mois->format('Y-m-t'));
    $premierJourSemaine = (int)$premierJour->format('N');
    
    $seances = \App\Models\Seance::where('enfant_id', $enfant->id)
        ->whereYear('date_seance', $mois->format('Y'))
        ->whereMonth('date_seance', $mois->format('m'))
        ->get()
        ->keyBy(function($seance) {
            return $seance->date_seance->format('Y-m-d');
        });
@endphp

<div class="calendrier-grid">
    <!-- En-têtes des jours -->
    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
        <div class="calendrier-jour">{{ $jour }}</div>
    @endforeach
    
    <!-- Jours du mois précédent -->
    @for($i = $premierJourSemaine - 1; $i > 0; $i--)
        @php $date = (clone $premierJour)->modify("-{$i} days"); @endphp
        <div class="calendrier-date autre-mois disabled">
            {{ $date->format('j') }}
        </div>
    @endfor
    
    <!-- Jours du mois actuel -->
    @for($jour = 1; $jour <= $dernierJour->format('j'); $jour++)
        @php
            $date = new \DateTime($mois->format("Y-m-{$jour}"));
            $dateStr = $date->format('Y-m-d');
            $seance = $seances->get($dateStr);
            $aujourdhui = new \DateTime();
            $aujourdhui->setTime(0, 0, 0);
            $dateCopy = clone $date;
            $dateCopy->setTime(0, 0, 0);
            $estAujourdhui = $dateCopy == $aujourdhui;
            $estPasse = $dateCopy < $aujourdhui;
            
            $bgColor = 'bg-gray-100';
            $borderColor = 'border-gray-300';
            
            if ($seance) {
                switch($seance->statut) {
                    case 'reservee':
                        $bgColor = 'bg-red-100';
                        $borderColor = 'border-red-400';
                        break;
                    case 'effectuee':
                        $bgColor = 'bg-green-100';
                        $borderColor = 'border-green-400';
                        break;
                    case 'reporter':
                        $bgColor = 'bg-orange-100';
                        $borderColor = 'border-orange-400';
                        break;
                }
            }
            
            // Mise en évidence du jour actuel
            if ($estAujourdhui) {
                $borderColor = 'border-blue-500 border-4';
            }
        @endphp
        <div 
            @if(!$estPasse)
                wire:click="toggleDate('{{ $dateStr }}')"
                title="{{ $seance ? 'Cliquer pour annuler la réservation' : 'Cliquer pour réserver cette date' }}"
            @endif
            class="calendrier-date border-2 {{ $bgColor }} {{ $borderColor }} {{ !$estPasse ? 'cursor-pointer hover:shadow-lg' : '' }} {{ $estPasse && !$seance ? 'opacity-60' : '' }}"
        >
            <span class="text-lg font-bold {{ $estAujourdhui ? 'text-blue-600' : '' }}">{{ $jour }}</span>
            @if($seance && $seance->heure_debut)
            <div class="text-xs mt-1 opacity-75">
                {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}
            </div>
            @endif
            @if($seance)
            <div class="text-[10px] mt-1 opacity-70 capitalize">
                {{ $seance->statut }}
                @if($seance->paiement_statut === 'en_attente')
                    <span class="ml-1 inline-block px-2 py-0.5 text-[10px] rounded bg-amber-100 text-amber-800">à payer</span>
                @elseif($seance->paiement_statut === 'valide')
                    <span class="ml-1 inline-block px-2 py-0.5 text-[10px] rounded bg-emerald-100 text-emerald-800">payée</span>
                @endif
            </div>
            @endif
            
        </div>
    @endfor
    
    <!-- Jours du mois suivant -->
    @php
        $jourActuel = $dernierJour->format('j');
        $joursAvant = $premierJourSemaine - 1;
        $totalCases = ceil(($joursAvant + $jourActuel) / 7) * 7;
        $joursApres = $totalCases - ($joursAvant + $jourActuel);
    @endphp
    @for($i = 1; $i <= $joursApres; $i++)
        @php $date = (clone $dernierJour)->modify("+{$i} days"); @endphp
        <div class="calendrier-date autre-mois disabled">
            {{ $date->format('j') }}
        </div>
    @endfor
</div>





