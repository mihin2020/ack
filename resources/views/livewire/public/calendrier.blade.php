@php
    $mois = new \DateTime($enfant['mois_courant'] . '-01');
    $premierJour = new \DateTime($mois->format('Y-m-01'));
    $dernierJour = new \DateTime($mois->format('Y-m-t'));
    $premierJourSemaine = (int)$premierJour->format('N'); // 1 = lundi, 7 = dimanche
    
    $aujourdhui = new \DateTime();
    $datesSelectionnees = $enfant['dates_seances'] ?? [];
    $totalSeances = $enfant['nombre_seances'] ?? 0;
@endphp

<div class="calendrier-nav flex justify-between items-center mb-4">
    <button type="button" wire:click="prevMois({{ $index }})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm">
        ← Précédent
    </button>
    <span class="font-semibold text-gray-800 capitalize">
        {{ $mois->format('F Y') }}
    </span>
    <button type="button" wire:click="nextMois({{ $index }})" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm">
        Suivant →
    </button>
</div>

<div class="calendrier-grid">
    <!-- En-têtes des jours -->
    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
        <div class="calendrier-jour">{{ $jour }}</div>
    @endforeach
    
    <!-- Jours du mois précédent -->
    @for($i = $premierJourSemaine - 1; $i > 0; $i--)
        @php
            $date = (clone $premierJour)->modify("-{$i} days");
            $dateStr = $date->format('Y-m-d');
            $estPasse = $date < $aujourdhui;
            $estSelectionne = in_array($dateStr, $datesSelectionnees);
        @endphp
        <div class="calendrier-date autre-mois disabled">
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
        @endphp
        <div wire:click="toggleDate({{ $index }}, '{{ $dateStr }}')"
             class="calendrier-date {{ $estSelectionne ? 'selected' : '' }} {{ !$peutSelectionner ? 'disabled' : '' }}"
             style="cursor: {{ $peutSelectionner ? 'pointer' : 'not-allowed' }}">
            {{ $jour }}
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
        <div class="calendrier-date autre-mois disabled">
            {{ $date->format('j') }}
        </div>
    @endfor
</div>









