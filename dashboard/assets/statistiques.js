// Données des inscrits (même structure que dans liste-inscrits.js)
let inscritsData = [
  {
    id: 1,
    nom: 'Dupont',
    prenom: 'Jean',
    dateNaissance: '15/03/2010',
    age: 14,
    classe: '3ème',
    parent: 'Marie Dupont',
    parentPrenom: 'Marie',
    telephone: '01 23 45 67 89',
    email: 'marie.dupont@email.com',
    adresse: '123 Rue de la Paix, 75001 Paris',
    programme: 'Football',
    contactUrgence: 'Pierre Dupont - 01 98 76 54 32',
    infosMedicales: 'Néant',
    statutPaiement: 'non-paye',
    dateReservation: '2024-02-15',
    dateInscription: '2024-01-15'
  },
  {
    id: 2,
    nom: 'Martin',
    prenom: 'Sophie',
    dateNaissance: '22/07/2012',
    age: 12,
    classe: 'CM2',
    parent: 'Pierre Martin',
    parentPrenom: 'Pierre',
    telephone: '02 34 56 78 90',
    email: 'pierre.martin@email.com',
    adresse: '456 Avenue des Champs, 75008 Paris',
    programme: 'Basketball',
    contactUrgence: 'Claire Martin - 02 87 65 43 21',
    infosMedicales: 'Allergie aux arachides',
    statutPaiement: 'paye',
    dateReservation: '2024-03-20',
    dateInscription: '2024-01-20'
  },
  {
    id: 3,
    nom: 'Bernard',
    prenom: 'Lucas',
    dateNaissance: '08/11/2008',
    age: 16,
    classe: '1ère',
    parent: 'Claire Bernard',
    parentPrenom: 'Claire',
    telephone: '03 45 67 89 01',
    email: 'claire.bernard@email.com',
    adresse: '789 Boulevard Saint-Germain, 75006 Paris',
    programme: 'Natation',
    contactUrgence: 'Paul Bernard - 03 76 54 32 10',
    infosMedicales: 'Asthme léger',
    statutPaiement: 'non-paye',
    dateReservation: null,
    dateInscription: '2024-02-01'
  },
  {
    id: 4,
    nom: 'Moreau',
    prenom: 'Emma',
    dateNaissance: '12/05/2011',
    age: 13,
    classe: '6ème',
    parent: 'Thomas Moreau',
    parentPrenom: 'Thomas',
    telephone: '04 56 78 90 12',
    email: 'thomas.moreau@email.com',
    adresse: '321 Rue de Rivoli, 75001 Paris',
    programme: 'Football',
    contactUrgence: 'Sarah Moreau - 04 65 43 21 09',
    infosMedicales: 'Néant',
    statutPaiement: 'paye',
    dateReservation: '2024-04-10',
    dateInscription: '2024-02-10'
  },
  {
    id: 5,
    nom: 'Leroy',
    prenom: 'Alexandre',
    dateNaissance: '03/09/2009',
    age: 15,
    classe: '2nde',
    parent: 'Isabelle Leroy',
    parentPrenom: 'Isabelle',
    telephone: '05 67 89 01 23',
    email: 'isabelle.leroy@email.com',
    adresse: '654 Avenue Montaigne, 75008 Paris',
    programme: 'Basketball',
    contactUrgence: 'Marc Leroy - 05 43 21 09 87',
    infosMedicales: 'Néant',
    statutPaiement: 'paye',
    dateReservation: '2024-05-05',
    dateInscription: '2024-02-15'
  },
  {
    id: 6,
    nom: 'Petit',
    prenom: 'Léa',
    dateNaissance: '25/12/2013',
    age: 10,
    classe: 'CE2',
    parent: 'Nicolas Petit',
    parentPrenom: 'Nicolas',
    telephone: '06 78 90 12 34',
    email: 'nicolas.petit@email.com',
    adresse: '987 Rue de la République, 75011 Paris',
    programme: 'Natation',
    contactUrgence: 'Julie Petit - 06 21 09 87 65',
    infosMedicales: 'Néant',
    statutPaiement: 'non-paye',
    dateReservation: null,
    dateInscription: '2024-03-01'
  }
];

// Variables pour stocker les graphiques
let ageChart, programmeChart, paiementChart, classeChart, evolutionChart, reservationChart;

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
  calculateStats();
  createCharts();
  updateDetailedStats();
});

// Fonction principale de calcul des statistiques
function calculateStats() {
  const totalInscrits = inscritsData.length;
  const totalPaiements = inscritsData.filter(inscrit => inscrit.statutPaiement === 'paye').length;
  const totalReservations = inscritsData.filter(inscrit => inscrit.dateReservation).length;
  const tauxPaiement = totalInscrits > 0 ? Math.round((totalPaiements / totalInscrits) * 100) : 0;

  // Mise à jour des cartes principales
  document.getElementById('totalInscrits').textContent = totalInscrits;
  document.getElementById('totalPaiements').textContent = totalPaiements;
  document.getElementById('totalReservations').textContent = totalReservations;
  document.getElementById('tauxPaiement').textContent = tauxPaiement + '%';
}

// Fonction pour créer tous les graphiques
function createCharts() {
  createAgeChart();
  createProgrammeChart();
  createPaiementChart();
  createClasseChart();
  createEvolutionChart();
  createReservationChart();
}

// Graphique de répartition par âge
function createAgeChart() {
  const ctx = document.getElementById('ageChart').getContext('2d');
  
  // Calculer la répartition par tranches d'âge
  const ageGroups = {
    '5-10 ans': inscritsData.filter(i => i.age >= 5 && i.age <= 10).length,
    '11-15 ans': inscritsData.filter(i => i.age >= 11 && i.age <= 15).length,
    '16-18 ans': inscritsData.filter(i => i.age >= 16 && i.age <= 18).length,
    '18+ ans': inscritsData.filter(i => i.age > 18).length
  };

  ageChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: Object.keys(ageGroups),
      datasets: [{
        data: Object.values(ageGroups),
        backgroundColor: [
          '#FF6384',
          '#36A2EB',
          '#FFCE56',
          '#4BC0C0'
        ],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 15,
            usePointStyle: true,
            font: {
              size: 12
            }
          }
        }
      }
    }
  });
}

// Graphique des programmes populaires
function createProgrammeChart() {
  const ctx = document.getElementById('programmeChart').getContext('2d');
  
  // Calculer la répartition par programme
  const programmes = {};
  inscritsData.forEach(inscrit => {
    programmes[inscrit.programme] = (programmes[inscrit.programme] || 0) + 1;
  });

  programmeChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: Object.keys(programmes),
      datasets: [{
        label: 'Nombre d\'inscrits',
        data: Object.values(programmes),
        backgroundColor: [
          '#FF6384',
          '#36A2EB',
          '#FFCE56',
          '#4BC0C0',
          '#9966FF'
        ],
        borderColor: [
          '#FF6384',
          '#36A2EB',
          '#FFCE56',
          '#4BC0C0',
          '#9966FF'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            font: {
              size: 11
            }
          }
        },
        x: {
          ticks: {
            font: {
              size: 11
            }
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
}

// Graphique du statut des paiements
function createPaiementChart() {
  const ctx = document.getElementById('paiementChart').getContext('2d');
  
  const paiements = {
    'Payé': inscritsData.filter(i => i.statutPaiement === 'paye').length,
    'Non payé': inscritsData.filter(i => i.statutPaiement === 'non-paye').length
  };

  paiementChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: Object.keys(paiements),
      datasets: [{
        data: Object.values(paiements),
        backgroundColor: [
          '#4BC0C0',
          '#FF6384'
        ],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 15,
            usePointStyle: true,
            font: {
              size: 12
            }
          }
        }
      }
    }
  });
}

// Graphique de répartition par classe
function createClasseChart() {
  const ctx = document.getElementById('classeChart').getContext('2d');
  
  // Calculer la répartition par classe
  const classes = {};
  inscritsData.forEach(inscrit => {
    classes[inscrit.classe] = (classes[inscrit.classe] || 0) + 1;
  });

  // Trier les classes
  const sortedClasses = Object.keys(classes).sort((a, b) => {
    const order = ['CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2', '6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale'];
    return order.indexOf(a) - order.indexOf(b);
  });

  classeChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: sortedClasses,
      datasets: [{
        label: 'Nombre d\'inscrits',
        data: sortedClasses.map(classe => classes[classe]),
        borderColor: '#36A2EB',
        backgroundColor: 'rgba(54, 162, 235, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#36A2EB',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            font: {
              size: 11
            }
          }
        },
        x: {
          ticks: {
            font: {
              size: 11
            }
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
}

// Graphique d'évolution des inscriptions
function createEvolutionChart() {
  const ctx = document.getElementById('evolutionChart').getContext('2d');
  
  // Calculer les inscriptions par mois
  const inscriptionsParMois = {
    'Jan 2024': 2,
    'Fév 2024': 3,
    'Mar 2024': 1,
    'Avr 2024': 0,
    'Mai 2024': 0,
    'Juin 2024': 0
  };

  evolutionChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: Object.keys(inscriptionsParMois),
      datasets: [{
        label: 'Nouvelles inscriptions',
        data: Object.values(inscriptionsParMois),
        borderColor: '#FF6384',
        backgroundColor: 'rgba(255, 99, 132, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#FF6384',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            font: {
              size: 11
            }
          }
        },
        x: {
          ticks: {
            font: {
              size: 11
            }
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
}

// Graphique des réservations par période
function createReservationChart() {
  const ctx = document.getElementById('reservationChart').getContext('2d');
  
  // Calculer les réservations par mois
  const reservationsParMois = {
    'Jan 2024': 0,
    'Fév 2024': 1,
    'Mar 2024': 1,
    'Avr 2024': 1,
    'Mai 2024': 1,
    'Juin 2024': 0
  };

  reservationChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: Object.keys(reservationsParMois),
      datasets: [{
        label: 'Réservations',
        data: Object.values(reservationsParMois),
        backgroundColor: 'rgba(75, 192, 192, 0.8)',
        borderColor: '#4BC0C0',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            font: {
              size: 11
            }
          }
        },
        x: {
          ticks: {
            font: {
              size: 11
            }
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
}

// Fonction pour mettre à jour les statistiques détaillées
function updateDetailedStats() {
  updateAgeStats();
  updateProgrammeStats();
  updateClasseStats();
}

// Statistiques détaillées par âge
function updateAgeStats() {
  const ageStats = document.getElementById('ageStats');
  const ageGroups = {
    '5-10 ans': inscritsData.filter(i => i.age >= 5 && i.age <= 10),
    '11-15 ans': inscritsData.filter(i => i.age >= 11 && i.age <= 15),
    '16-18 ans': inscritsData.filter(i => i.age >= 16 && i.age <= 18),
    '18+ ans': inscritsData.filter(i => i.age > 18)
  };

  ageStats.innerHTML = '';
  Object.entries(ageGroups).forEach(([group, inscrits]) => {
    const div = document.createElement('div');
    div.className = 'flex justify-between items-center text-sm';
    div.innerHTML = `
      <span class="text-gray-600">${group}</span>
      <span class="font-semibold text-gray-800">${inscrits.length}</span>
    `;
    ageStats.appendChild(div);
  });
}

// Statistiques détaillées par programme
function updateProgrammeStats() {
  const programmeStats = document.getElementById('programmeStats');
  const programmes = {};
  
  // Initialiser tous les programmes possibles
  const tousProgrammes = ['Football', 'Basketball', 'Natation', 'Autre'];
  tousProgrammes.forEach(programme => {
    programmes[programme] = 0;
  });
  
  // Compter les inscriptions par programme
  inscritsData.forEach(inscrit => {
    if (programmes.hasOwnProperty(inscrit.programme)) {
      programmes[inscrit.programme]++;
    }
  });

  programmeStats.innerHTML = '';
  Object.entries(programmes).forEach(([programme, count]) => {
    const div = document.createElement('div');
    div.className = 'flex justify-between items-center text-sm';
    div.innerHTML = `
      <span class="text-gray-600">${programme}</span>
      <span class="font-semibold text-gray-800">${count}</span>
    `;
    programmeStats.appendChild(div);
  });
}

// Statistiques détaillées par classe
function updateClasseStats() {
  const classeStats = document.getElementById('classeStats');
  const classes = {};
  inscritsData.forEach(inscrit => {
    classes[inscrit.classe] = (classes[inscrit.classe] || 0) + 1;
  });

  classeStats.innerHTML = '';
  Object.entries(classes).forEach(([classe, count]) => {
    const div = document.createElement('div');
    div.className = 'flex justify-between items-center text-sm';
    div.innerHTML = `
      <span class="text-gray-600">${classe}</span>
      <span class="font-semibold text-gray-800">${count}</span>
    `;
    classeStats.appendChild(div);
  });
}

// Fonction pour actualiser les statistiques
function refreshStats() {
  // Recalculer toutes les statistiques
  calculateStats();
  
  // Détruire les anciens graphiques
  if (ageChart) ageChart.destroy();
  if (programmeChart) programmeChart.destroy();
  if (paiementChart) paiementChart.destroy();
  if (classeChart) classeChart.destroy();
  if (evolutionChart) evolutionChart.destroy();
  if (reservationChart) reservationChart.destroy();
  
  // Recréer les graphiques
  createCharts();
  updateDetailedStats();
  
  // Afficher un message de confirmation
  showNotification('Statistiques actualisées avec succès!', 'success');
}

// Fonction pour exporter les statistiques
function exportStats() {
  const stats = {
    totalInscrits: inscritsData.length,
    totalPaiements: inscritsData.filter(i => i.statutPaiement === 'paye').length,
    totalReservations: inscritsData.filter(i => i.dateReservation).length,
    tauxPaiement: Math.round((inscritsData.filter(i => i.statutPaiement === 'paye').length / inscritsData.length) * 100),
    dateExport: new Date().toLocaleDateString('fr-FR'),
    details: inscritsData.map(inscrit => ({
      nom: inscrit.nom,
      prenom: inscrit.prenom,
      age: inscrit.age,
      classe: inscrit.classe,
      programme: inscrit.programme,
      statutPaiement: inscrit.statutPaiement,
      dateReservation: inscrit.dateReservation
    }))
  };
  
  const dataStr = JSON.stringify(stats, null, 2);
  const dataBlob = new Blob([dataStr], {type: 'application/json'});
  const url = URL.createObjectURL(dataBlob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `statistiques_academy_${new Date().toISOString().split('T')[0]}.json`;
  link.click();
  URL.revokeObjectURL(url);
  
  showNotification('Statistiques exportées avec succès!', 'success');
}

// Fonction pour afficher des notifications
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
    type === 'success' ? 'bg-green-500' : 
    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
  }`;
  notification.textContent = message;
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.remove();
  }, 3000);
}
