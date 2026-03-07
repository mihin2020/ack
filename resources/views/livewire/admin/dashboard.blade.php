<div>
    <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Tableau de bord</h1>
        <div class="flex items-center">
            <a href="{{ route('admin.dashboard.export') }}" class="btn-primary px-3 py-2 sm:px-4 rounded-lg font-semibold flex items-center space-x-2 hover:opacity-90 transition-opacity text-sm sm:text-base">
                <span class="material-symbols-outlined text-xl sm:text-2xl">download</span>
                <span>Exporter les données</span>
            </a>
        </div>
    </header>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md flex items-center space-x-3 sm:space-x-4">
            <div class="p-3 rounded-full bg-red-soft">
                <span class="material-symbols-outlined text-red text-3xl">payments</span>
            </div>
            <div class="min-w-0">
                <p class="text-gray-500 text-xs sm:text-sm">Revenu Total</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 truncate">{{ number_format($revenuTotal, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md flex items-center space-x-3 sm:space-x-4">
            <div class="p-3 rounded-full bg-orange-soft shrink-0">
                <span class="material-symbols-outlined text-orange text-2xl sm:text-3xl">group</span>
            </div>
            <div class="min-w-0">
                <p class="text-gray-500 text-xs sm:text-sm">Utilisateurs Inscrits</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800">{{ number_format($utilisateursInscrits, 0, ',', ' ') }}</p>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md flex items-center space-x-3 sm:space-x-4 sm:col-span-2 lg:col-span-1">
            <div class="p-3 rounded-full bg-green-soft">
                <span class="material-symbols-outlined text-green-500 text-3xl">event</span>
            </div>
            <div class="min-w-0">
                <p class="text-gray-500 text-xs sm:text-sm">Réservations Actives</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800">{{ number_format($reservationsActives, 0, ',', ' ') }}</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Graphique des inscriptions -->
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md min-h-0">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Évolution des Inscriptions</h2>
            <div class="relative h-48 sm:h-64">
                <canvas id="inscriptionsChart"></canvas>
            </div>
        </div>
        
        <!-- Graphique de répartition -->
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md min-h-0">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Répartition des Inscriptions</h2>
            <div class="relative h-48 sm:h-64">
                <canvas id="repartitionChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tableau des nouvelles inscriptions -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">Nouvelles Inscriptions</h2>
        </div>
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <table class="w-full min-w-[600px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Parent</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dernieresInscriptions as $enfant)
                        <tr>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">{{ $enfant->nom }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">{{ $enfant->prenom }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">{{ $enfant->classe }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">{{ $enfant->parent->nom_complet ?? 'N/A' }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $enfant->statut_paiement === 'paye' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $enfant->statut_paiement === 'paye' ? 'Payé' : 'Non Payé' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Aucune inscription</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function() {
    var evolutionLabels = @json($evolutionLabels);
    var evolutionData = @json($evolutionData);
    var repartitionLabels = @json($repartitionLabels);
    var repartitionData = @json($repartitionData);
    var repartitionColors = @json($repartitionColors);

    function initCharts() {
        var elEvolution = document.getElementById('inscriptionsChart');
        var elRepartition = document.getElementById('repartitionChart');
        if (!elEvolution || !elRepartition) return;

        if (window._inscriptionsChartInstance) {
            window._inscriptionsChartInstance.destroy();
        }
        if (window._repartitionChartInstance) {
            window._repartitionChartInstance.destroy();
        }

        // Évolution des inscriptions (courbe)
        window._inscriptionsChartInstance = new Chart(elEvolution.getContext('2d'), {
            type: 'line',
            data: {
                labels: evolutionLabels,
                datasets: [{
                    label: 'Inscriptions',
                    data: evolutionData,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // Répartition des inscriptions (donut par classe)
        window._repartitionChartInstance = new Chart(elRepartition.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: repartitionLabels,
                datasets: [{
                    data: repartitionData,
                    backgroundColor: repartitionColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }
    document.addEventListener('livewire:navigated', initCharts);
})();
</script>
