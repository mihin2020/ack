<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Statistiques</h2>
            <p class="text-gray-600 mt-1">Analyse et visualisation des données de l'académie</p>
        </div>
    </div>
    
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4">
            <div class="p-3 rounded-full bg-red-100">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Revenu Total</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($revenuTotal, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4">
            <div class="p-3 rounded-full bg-orange-100">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Utilisateurs Inscrits</p>
                <p class="text-2xl font-bold text-gray-800">{{ $utilisateursInscrits }}</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4">
            <div class="p-3 rounded-full bg-green-100">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Réservations Actives</p>
                <p class="text-2xl font-bold text-gray-800">{{ $reservationsActives }}</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4">
            <div class="p-3 rounded-full bg-blue-100">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Séances Effectuées</p>
                <p class="text-2xl font-bold text-gray-800">{{ $seancesEffectuees }}</p>
            </div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Évolution des inscriptions</h3>
            <div class="h-80">
                @if(count($statsMois) > 0)
                <canvas id="inscriptionsChart"></canvas>
                @else
                <div class="flex items-center justify-center h-full text-gray-500">
                    Aucune donnée disponible
                </div>
                @endif
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Répartition des inscriptions</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="repartitionChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Évolution des séances</h3>
            <div class="h-80">
                @if(count($seancesParMois) > 0)
                <canvas id="seancesChart"></canvas>
                @else
                <div class="flex items-center justify-center h-full text-gray-500">
                    Aucune donnée disponible
                </div>
                @endif
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Statut des séances</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="statutSeancesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Initialiser les graphiques une seule fois
document.addEventListener('DOMContentLoaded', function() {
    initCharts();
});

// Réinitialiser après mise à jour Livewire
Livewire.hook('message.processed', (message, component) => {
    initCharts();
});

function initCharts() {
    const statsMois = @json($statsMois);
    const inscriptionComplete = {{ $inscriptionComplete }};
    const abonnementSeance = {{ $abonnementSeance }};
    const seancesParStatut = @json($seancesParStatut);
    const seancesParMois = @json($seancesParMois);
    
    // Graphique des inscriptions
    const ctx1 = document.getElementById('inscriptionsChart');
    if (ctx1 && ctx1.chart) {
        ctx1.chart.destroy();
    }
    if (ctx1 && statsMois && statsMois.length > 0) {
        ctx1.chart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: statsMois.map(s => s.mois),
                datasets: [{
                    label: 'Inscriptions',
                    data: statsMois.map(s => s.inscriptions),
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Graphique de répartition
    const ctx2 = document.getElementById('repartitionChart');
    if (ctx2 && ctx2.chart) {
        ctx2.chart.destroy();
    }
    if (ctx2) {
        ctx2.chart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Payé', 'Non payé'],
                datasets: [{
                    data: [inscriptionComplete, abonnementSeance],
                    backgroundColor: ['#10B981', '#EF4444'],
                    borderColor: '#FFFFFF',
                    borderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20
                        }
                    }
                }
            }
        });
    }
    
    // Graphique des séances par mois
    const ctx3 = document.getElementById('seancesChart');
    if (ctx3 && ctx3.chart) {
        ctx3.chart.destroy();
    }
    if (ctx3 && seancesParMois && seancesParMois.length > 0) {
        ctx3.chart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: seancesParMois.map(s => s.mois),
                datasets: [
                    {
                        label: 'Séances effectuées',
                        data: seancesParMois.map(s => s.effectuees),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Séances réservées',
                        data: seancesParMois.map(s => s.reservees),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Graphique du statut des séances
    const ctx4 = document.getElementById('statutSeancesChart');
    if (ctx4 && ctx4.chart) {
        ctx4.chart.destroy();
    }
    if (ctx4 && seancesParStatut) {
        ctx4.chart = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: ['Réservées', 'Effectuées', 'Reportées'],
                datasets: [{
                    label: 'Nombre de séances',
                    data: [seancesParStatut.reservee, seancesParStatut.effectuee, seancesParStatut.reporter],
                    backgroundColor: ['#3B82F6', '#10B981', '#F97316'],
                    borderColor: '#FFFFFF',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}
</script>
