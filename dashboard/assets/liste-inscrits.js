// Données des inscrits (simulation d'une base de données)
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
    dateReservation: null
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
    dateReservation: null
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
    dateReservation: null
  }
];

let currentInscritId = null;
let allInscritsData = [...inscritsData]; // Copie pour les filtres
let filteredInscritsData = [...inscritsData];

// Fonctions pour les modals
function openPaiementModal(nomEnfant) {
  const inscrit = inscritsData.find(i => `${i.prenom} ${i.nom}` === nomEnfant);
  if (inscrit) {
    currentInscritId = inscrit.id;
    document.getElementById('enfantNom').value = nomEnfant;
    document.getElementById('statutPaiement').value = inscrit.statutPaiement;
    document.getElementById('paiementModal').style.display = 'block';
  }
}

function closePaiementModal() {
  document.getElementById('paiementModal').style.display = 'none';
  currentInscritId = null;
}

function savePaiementStatus() {
  if (currentInscritId) {
    const statut = document.getElementById('statutPaiement').value;
    const inscrit = inscritsData.find(i => i.id === currentInscritId);
    if (inscrit) {
      inscrit.statutPaiement = statut;
      updateTableRow(currentInscritId);
    }
  }
  closePaiementModal();
}

function openReservationModal(nomEnfant) {
  const inscrit = inscritsData.find(i => `${i.prenom} ${i.nom}` === nomEnfant);
  if (inscrit) {
    currentInscritId = inscrit.id;
    document.getElementById('enfantNomReservation').value = nomEnfant;
    document.getElementById('dateReservation').value = inscrit.dateReservation || '';
    document.getElementById('reservationModal').style.display = 'block';
  }
}

function closeReservationModal() {
  document.getElementById('reservationModal').style.display = 'none';
  currentInscritId = null;
}

function saveReservation() {
  if (currentInscritId) {
    const date = document.getElementById('dateReservation').value;
    const inscrit = inscritsData.find(i => i.id === currentInscritId);
    if (inscrit) {
      inscrit.dateReservation = date;
      updateTableRow(currentInscritId);
    }
  }
  closeReservationModal();
}

function openDetails(nomEnfant) {
  const inscrit = inscritsData.find(i => `${i.prenom} ${i.nom}` === nomEnfant);
  if (inscrit) {
    currentInscritId = inscrit.id;
    document.getElementById('detailsNom').textContent = `${inscrit.prenom} ${inscrit.nom}`;
    document.getElementById('detailsDateNaissance').textContent = inscrit.dateNaissance;
    document.getElementById('detailsAge').textContent = `${inscrit.age} ans`;
    document.getElementById('detailsClasse').textContent = inscrit.classe;
    document.getElementById('detailsParent').textContent = inscrit.parent;
    document.getElementById('detailsTelephone').textContent = inscrit.telephone;
    document.getElementById('detailsEmail').textContent = inscrit.email;
    document.getElementById('detailsAdresse').textContent = inscrit.adresse;
    document.getElementById('detailsProgramme').textContent = inscrit.programme;
    document.getElementById('detailsContactUrgence').textContent = inscrit.contactUrgence;
    document.getElementById('detailsInfosMedicales').textContent = inscrit.infosMedicales;
    document.getElementById('detailsStatut').textContent = inscrit.statutPaiement === 'paye' ? 'Payé' : 'Non payé';
    document.getElementById('detailsReservation').textContent = inscrit.dateReservation || 'Non réservé';
    document.getElementById('detailsModal').style.display = 'block';
  }
}

function closeDetailsModal() {
  document.getElementById('detailsModal').style.display = 'none';
  currentInscritId = null;
}

function updateTableRow(inscritId) {
  // Mettre à jour les données
  allInscritsData = [...inscritsData];
  // Appliquer les filtres pour mettre à jour le tableau
  applyFilters();
}

// Actions du modal de détails
function openPaiementFromDetails() {
  if (currentInscritId) {
    const inscrit = inscritsData.find(i => i.id === currentInscritId);
    if (inscrit) {
      closeDetailsModal();
      openPaiementModal(`${inscrit.prenom} ${inscrit.nom}`);
    }
  }
}

function openReservationFromDetails() {
  if (currentInscritId) {
    const inscrit = inscritsData.find(i => i.id === currentInscritId);
    if (inscrit) {
      closeDetailsModal();
      openReservationModal(`${inscrit.prenom} ${inscrit.nom}`);
    }
  }
}

function openEditFromDetails() {
  if (currentInscritId) {
    const inscrit = inscritsData.find(i => i.id === currentInscritId);
    if (inscrit) {
      closeDetailsModal();
      openEditModal(inscrit);
    }
  }
}

function openEditModal(inscrit) {
  currentInscritId = inscrit.id;
  document.getElementById('editNom').value = inscrit.nom;
  document.getElementById('editPrenom').value = inscrit.prenom;
  document.getElementById('editDateNaissance').value = inscrit.dateNaissance;
  document.getElementById('editClasse').value = inscrit.classe;
  document.getElementById('editParentNom').value = inscrit.parent;
  document.getElementById('editParentPrenom').value = inscrit.parentPrenom;
  document.getElementById('editTelephone').value = inscrit.telephone;
  document.getElementById('editEmail').value = inscrit.email;
  document.getElementById('editAdresse').value = inscrit.adresse;
  document.getElementById('editProgramme').value = inscrit.programme;
  document.getElementById('editContactUrgence').value = inscrit.contactUrgence;
  document.getElementById('editInfosMedicales').value = inscrit.infosMedicales;
  document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
  currentInscritId = null;
}

function saveEdit() {
  if (currentInscritId) {
    const inscrit = inscritsData.find(i => i.id === currentInscritId);
    if (inscrit) {
      inscrit.nom = document.getElementById('editNom').value;
      inscrit.prenom = document.getElementById('editPrenom').value;
      inscrit.dateNaissance = document.getElementById('editDateNaissance').value;
      inscrit.classe = document.getElementById('editClasse').value;
      inscrit.parent = document.getElementById('editParentNom').value;
      inscrit.parentPrenom = document.getElementById('editParentPrenom').value;
      inscrit.telephone = document.getElementById('editTelephone').value;
      inscrit.email = document.getElementById('editEmail').value;
      inscrit.adresse = document.getElementById('editAdresse').value;
      inscrit.programme = document.getElementById('editProgramme').value;
      inscrit.contactUrgence = document.getElementById('editContactUrgence').value;
      inscrit.infosMedicales = document.getElementById('editInfosMedicales').value;
      
      // Recalculer l'âge
      const birthDate = new Date(inscrit.dateNaissance.split('/').reverse().join('-'));
      const today = new Date();
      inscrit.age = today.getFullYear() - birthDate.getFullYear();
      
      updateTableRow(currentInscritId);
      closeEditModal();
    }
  }
}

function deleteInscrit() {
  if (currentInscritId && confirm('Êtes-vous sûr de vouloir supprimer cet inscrit ?')) {
    const index = inscritsData.findIndex(i => i.id === currentInscritId);
    if (index !== -1) {
      inscritsData.splice(index, 1);
      location.reload(); // Recharger la page pour mettre à jour le tableau
    }
  }
}

function printDetails() {
  if (currentInscritId) {
    const inscrit = inscritsData.find(i => i.id === currentInscritId);
    if (inscrit) {
      const printWindow = window.open('', '_blank');
      printWindow.document.write(`
        <html>
          <head>
            <title>Détails de l'inscrit</title>
            <style>
              body { font-family: Arial, sans-serif; margin: 20px; }
              .header { text-align: center; margin-bottom: 30px; }
              .details { margin: 20px 0; }
              .detail-row { margin: 10px 0; }
              .label { font-weight: bold; }
            </style>
          </head>
          <body>
            <div class="header">
              <h1>ACADEMY CHARLES KABORE</h1>
              <h2>Détails de l'inscrit</h2>
            </div>
            <div class="details">
              <h3>Informations de l'enfant</h3>
              <div class="detail-row"><span class="label">Nom :</span> ${inscrit.nom}</div>
              <div class="detail-row"><span class="label">Prénom :</span> ${inscrit.prenom}</div>
              <div class="detail-row"><span class="label">Date de naissance :</span> ${inscrit.dateNaissance}</div>
              <div class="detail-row"><span class="label">Âge :</span> ${inscrit.age} ans</div>
              <div class="detail-row"><span class="label">Classe :</span> ${inscrit.classe}</div>
              <div class="detail-row"><span class="label">Programme :</span> ${inscrit.programme}</div>
              
              <h3>Informations du parent/tuteur</h3>
              <div class="detail-row"><span class="label">Nom complet :</span> ${inscrit.parent}</div>
              <div class="detail-row"><span class="label">Téléphone :</span> ${inscrit.telephone}</div>
              <div class="detail-row"><span class="label">Email :</span> ${inscrit.email}</div>
              <div class="detail-row"><span class="label">Adresse :</span> ${inscrit.adresse}</div>
              
              <h3>Informations complémentaires</h3>
              <div class="detail-row"><span class="label">Contact d'urgence :</span> ${inscrit.contactUrgence}</div>
              <div class="detail-row"><span class="label">Informations médicales :</span> ${inscrit.infosMedicales}</div>
              
              <h3>Statut</h3>
              <div class="detail-row"><span class="label">Statut du paiement :</span> ${inscrit.statutPaiement === 'paye' ? 'Payé' : 'Non payé'}</div>
              <div class="detail-row"><span class="label">Date de réservation :</span> ${inscrit.dateReservation || 'Non réservé'}</div>
            </div>
          </body>
        </html>
      `);
      printWindow.document.close();
      printWindow.print();
    }
  }
}

// Fonctions pour les actions du tableau
function editInscrit(nomEnfant) {
  const inscrit = inscritsData.find(i => `${i.prenom} ${i.nom}` === nomEnfant);
  if (inscrit) {
    openEditModal(inscrit);
  }
}

function deleteInscritFromTable(nomEnfant) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cet inscrit ?')) {
    const index = inscritsData.findIndex(i => `${i.prenom} ${i.nom}` === nomEnfant);
    if (index !== -1) {
      inscritsData.splice(index, 1);
      refreshData(); // Utiliser la nouvelle fonction de rafraîchissement
    }
  }
}

// ==================== FONCTIONS DE FILTRAGE ====================

// Fonction pour basculer l'affichage des filtres
function toggleFilters() {
  const filtersSection = document.getElementById('filtersSection');
  if (filtersSection.style.display === 'none') {
    filtersSection.style.display = 'block';
  } else {
    filtersSection.style.display = 'none';
  }
}

// Fonction pour basculer les filtres de réservation
function toggleReservationFilters() {
  const filterType = document.getElementById('reservationFilterType').value;
  const dateFilter = document.getElementById('dateFilter');
  const rangeFilter = document.getElementById('rangeFilter');
  
  dateFilter.style.display = 'none';
  rangeFilter.style.display = 'none';
  
  if (filterType === 'date') {
    dateFilter.style.display = 'block';
  } else if (filterType === 'range') {
    rangeFilter.style.display = 'block';
  }
  
  applyFilters();
}

// Fonction principale de filtrage
function applyFilters() {
  const nom = document.getElementById('filterNom').value.toLowerCase();
  const prenom = document.getElementById('filterPrenom').value.toLowerCase();
  const dateNaissance = document.getElementById('filterDateNaissance').value;
  const age = document.getElementById('filterAge').value;
  const parent = document.getElementById('filterParent').value.toLowerCase();
  const statut = document.getElementById('filterStatut').value;
  const reservationType = document.getElementById('reservationFilterType').value;
  const reservationDate = document.getElementById('filterReservationDate').value;
  const dateFrom = document.getElementById('filterDateFrom').value;
  const dateTo = document.getElementById('filterDateTo').value;

  filteredInscritsData = allInscritsData.filter(inscrit => {
    // Filtre par nom
    if (nom && !inscrit.nom.toLowerCase().includes(nom)) return false;
    
    // Filtre par prénom
    if (prenom && !inscrit.prenom.toLowerCase().includes(prenom)) return false;
    
    // Filtre par date de naissance
    if (dateNaissance && !inscrit.dateNaissance.includes(dateNaissance)) return false;
    
    // Filtre par âge
    if (age) {
      const ageRange = age.split('-');
      if (ageRange.length === 2) {
        const minAge = parseInt(ageRange[0]);
        const maxAge = parseInt(ageRange[1]);
        if (inscrit.age < minAge || inscrit.age > maxAge) return false;
      } else if (age === '18+') {
        if (inscrit.age < 18) return false;
      }
    }
    
    // Filtre par parent/tuteur
    if (parent && !inscrit.parent.toLowerCase().includes(parent)) return false;
    
    // Filtre par statut de paiement
    if (statut && inscrit.statutPaiement !== statut) return false;
    
    // Filtre par réservation
    if (reservationType === 'with' && !inscrit.dateReservation) return false;
    if (reservationType === 'without' && inscrit.dateReservation) return false;
    
    if (reservationType === 'date' && reservationDate) {
      if (!inscrit.dateReservation || inscrit.dateReservation !== reservationDate) return false;
    }
    
    if (reservationType === 'range' && dateFrom && dateTo) {
      if (!inscrit.dateReservation) return false;
      const inscritDate = new Date(inscrit.dateReservation);
      const fromDate = new Date(dateFrom);
      const toDate = new Date(dateTo);
      if (inscritDate < fromDate || inscritDate > toDate) return false;
    }
    
    return true;
  });

  updateTable();
  updateFilterResults();
}

// Fonction pour effacer tous les filtres
function clearAllFilters() {
  document.getElementById('filterNom').value = '';
  document.getElementById('filterPrenom').value = '';
  document.getElementById('filterDateNaissance').value = '';
  document.getElementById('filterAge').value = '';
  document.getElementById('filterParent').value = '';
  document.getElementById('filterStatut').value = '';
  document.getElementById('reservationFilterType').value = '';
  document.getElementById('filterReservationDate').value = '';
  document.getElementById('filterDateFrom').value = '';
  document.getElementById('filterDateTo').value = '';
  
  toggleReservationFilters();
  applyFilters();
}

// Fonction pour mettre à jour le tableau
function updateTable() {
  const tbody = document.querySelector('#inscritsTable tbody');
  if (!tbody) return;
  
  tbody.innerHTML = '';
  
  filteredInscritsData.forEach((inscrit, index) => {
    const row = document.createElement('tr');
    row.className = 'border-b border-gray-200 hover:bg-gray-50';
    
    // Statut de paiement
    const statutClass = inscrit.statutPaiement === 'paye' 
      ? 'text-green-700 bg-green-100 hover:bg-green-200'
      : 'text-orange-700 bg-orange-100 hover:bg-orange-200';
    const statutText = inscrit.statutPaiement === 'paye' ? 'Payé' : 'Non payé';
    
    // Réservation
    let reservationHtml;
    if (inscrit.dateReservation) {
      reservationHtml = `
        <div class="flex items-center space-x-2">
          <button onclick="openReservationModal('${inscrit.prenom} ${inscrit.nom}')" class="text-green-500 hover:text-green-600" title="Modifier la réservation">
            <span class="material-symbols-outlined">event_available</span>
          </button>
          <span class="text-sm text-gray-600">${inscrit.dateReservation}</span>
        </div>
      `;
    } else {
      reservationHtml = `
        <div class="flex items-center space-x-2">
          <button onclick="openReservationModal('${inscrit.prenom} ${inscrit.nom}')" class="text-gray-400 hover:text-gray-600" title="Réserver">
            <span class="material-symbols-outlined">event_available</span>
          </button>
          <span class="text-sm text-gray-400">Non réservé</span>
        </div>
      `;
    }
    
    row.innerHTML = `
      <td class="py-3 px-4 font-medium text-gray-800">${index + 1}</td>
      <td class="py-3 px-4">${inscrit.nom}</td>
      <td class="py-3 px-4">${inscrit.prenom}</td>
      <td class="py-3 px-4 text-gray-500">${inscrit.dateNaissance}</td>
      <td class="py-3 px-4 text-gray-500">${inscrit.age} ans</td>
      <td class="py-3 px-4">${inscrit.parent}</td>
      <td class="py-3 px-4">
        <button onclick="openPaiementModal('${inscrit.prenom} ${inscrit.nom}')" class="px-2 py-1 text-xs font-semibold ${statutClass} rounded-full">
          ${statutText}
        </button>
      </td>
      <td class="py-3 px-4">
        ${reservationHtml}
      </td>
      <td class="py-3 px-4 text-center">
        <div class="flex justify-center space-x-2">
          <button onclick="openDetails('${inscrit.prenom} ${inscrit.nom}')" class="text-blue-500 hover:text-blue-600 p-1" title="Détails">
            <span class="material-symbols-outlined">visibility</span>
          </button>
          <button onclick="editInscrit('${inscrit.prenom} ${inscrit.nom}')" class="text-gray-500 hover:text-yellow-500 p-1" title="Modifier">
            <span class="material-symbols-outlined">edit</span>
          </button>
          <button onclick="deleteInscritFromTable('${inscrit.prenom} ${inscrit.nom}')" class="text-gray-500 hover:text-red-500 p-1" title="Supprimer">
            <span class="material-symbols-outlined">delete</span>
          </button>
        </div>
      </td>
    `;
    
    tbody.appendChild(row);
  });
}

// Fonction pour mettre à jour les résultats du filtrage
function updateFilterResults() {
  const totalInscrits = allInscritsData.length;
  const filteredInscrits = filteredInscritsData.length;
  const filterResults = document.getElementById('filterResults');
  const filterResultsText = document.getElementById('filterResultsText');
  const tableHeader = document.getElementById('tableHeader');
  const totalCount = document.getElementById('totalCount');
  
  totalCount.textContent = `${filteredInscrits} inscrit${filteredInscrits > 1 ? 's' : ''}`;
  
  if (filteredInscrits < totalInscrits) {
    filterResults.style.display = 'block';
    filterResultsText.textContent = `Filtrage actif : ${filteredInscrits} sur ${totalInscrits} inscrits affichés`;
    tableHeader.innerHTML = `
      <div class="flex items-center justify-between">
        <span class="text-sm text-blue-600 font-medium">🔍 Résultats filtrés</span>
        <span id="totalCount" class="text-sm font-medium text-gray-800">${filteredInscrits} inscrit${filteredInscrits > 1 ? 's' : ''}</span>
      </div>
    `;
  } else {
    filterResults.style.display = 'none';
    tableHeader.innerHTML = `
      <div class="flex items-center justify-between">
        <span class="text-sm text-gray-600">Affichage de tous les inscrits</span>
        <span id="totalCount" class="text-sm font-medium text-gray-800">${filteredInscrits} inscrit${filteredInscrits > 1 ? 's' : ''}</span>
      </div>
    `;
  }
}

// Fonction pour exporter les données
function exportData() {
  const dataToExport = filteredInscritsData.length > 0 ? filteredInscritsData : allInscritsData;
  const csvContent = generateCSV(dataToExport);
  downloadCSV(csvContent, 'inscrits_academy.csv');
}

// Fonction pour générer le CSV
function generateCSV(data) {
  const headers = ['Nom', 'Prénom', 'Date de naissance', 'Âge', 'Classe', 'Parent/Tuteur', 'Téléphone', 'Email', 'Adresse', 'Programme', 'Contact urgence', 'Infos médicales', 'Statut paiement', 'Date réservation'];
  const csvRows = [headers.join(',')];
  
  data.forEach(inscrit => {
    const row = [
      inscrit.nom,
      inscrit.prenom,
      inscrit.dateNaissance,
      inscrit.age,
      inscrit.classe,
      inscrit.parent,
      inscrit.telephone,
      inscrit.email,
      inscrit.adresse,
      inscrit.programme,
      inscrit.contactUrgence,
      inscrit.infosMedicales,
      inscrit.statutPaiement === 'paye' ? 'Payé' : 'Non payé',
      inscrit.dateReservation || 'Non réservé'
    ];
    csvRows.push(row.join(','));
  });
  
  return csvRows.join('\n');
}

// Fonction pour télécharger le CSV
function downloadCSV(csvContent, filename) {
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  link.setAttribute('href', url);
  link.setAttribute('download', filename);
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// Fonction pour mettre à jour les données quand on modifie un inscrit
function refreshData() {
  allInscritsData = [...inscritsData];
  applyFilters();
}

// Initialiser le tableau au chargement
document.addEventListener('DOMContentLoaded', function() {
  updateTable();
  updateFilterResults();
});

// Fermer les modals en cliquant à l'extérieur
window.onclick = function(event) {
  const paiementModal = document.getElementById('paiementModal');
  const reservationModal = document.getElementById('reservationModal');
  const detailsModal = document.getElementById('detailsModal');
  const editModal = document.getElementById('editModal');
  
  if (event.target === paiementModal) {
    closePaiementModal();
  }
  if (event.target === reservationModal) {
    closeReservationModal();
  }
  if (event.target === detailsModal) {
    closeDetailsModal();
  }
  if (event.target === editModal) {
    closeEditModal();
  }
}

