document.addEventListener('DOMContentLoaded', function() {
  const ajouterBtn = document.getElementById('ajouter-enfant-btn');
  const enfantsContainer = document.getElementById('enfants-container');
  let enfantCount = 1;

  // Fonction pour initialiser la planification sur un bloc enfant donné
  function initPlanificationEnfant(enfantSection, enfantIndex) {
    // Sélecteurs dynamiques
    const nbSeancesInput = enfantSection.querySelector(`#nb-seances-total-${enfantIndex}`);
    const seancesRestantesSpan = enfantSection.querySelector(`#seances-restantes-${enfantIndex}`);
    const montantTotalSpan = enfantSection.querySelector(`#montant-total-${enfantIndex}`);
    const calendrierContainer = enfantSection.querySelector(`#calendrier-container-${enfantIndex}`);

    let totalSeances = 0;
    let seancesRestantes = 0;
    let datesSelectionnees = []; // Array de dates au format 'YYYY-MM-DD'
    let moisActuel = new Date();
    moisActuel.setDate(1); // Premier jour du mois
    const prixParSeance = 5000; // Prix unitaire d'une séance

    function updateSeancesRestantes() {
      seancesRestantes = totalSeances - datesSelectionnees.length;
      seancesRestantesSpan.textContent = seancesRestantes;
    }

    function updateMontantTotal() {
      const montant = totalSeances * prixParSeance;
      montantTotalSpan.textContent = `Montant total : ${montant.toLocaleString('fr-FR')} FCFA`;
    }

    function getNumeroSemaine(date) {
      const d = new Date(date);
      const jour = d.getDay() || 7; // Dimanche = 7
      d.setDate(d.getDate() + 4 - jour);
      const anneeDebut = new Date(d.getFullYear(), 0, 1);
      return Math.ceil((((d - anneeDebut) / 86400000) + 1) / 7);
    }

    function getDatesSemaine(date) {
      const d = new Date(date);
      const jour = d.getDay() || 7; // Dimanche = 7
      const lundi = new Date(d);
      lundi.setDate(d.getDate() - jour + 1);
      const dates = [];
      for (let i = 0; i < 7; i++) {
        const date = new Date(lundi);
        date.setDate(lundi.getDate() + i);
        dates.push(date.toISOString().split('T')[0]);
      }
      return dates;
    }

    function countSeancesSemaine(date) {
      const datesSemaine = getDatesSemaine(date);
      return datesSelectionnees.filter(d => datesSemaine.includes(d)).length;
    }

    function toggleDate(dateStr) {
      const index = datesSelectionnees.indexOf(dateStr);
      if (index > -1) {
        // Retirer la date
        datesSelectionnees.splice(index, 1);
      } else {
        // Vérifier si on peut ajouter
        if (seancesRestantes > 0 && countSeancesSemaine(dateStr) < 7) {
          datesSelectionnees.push(dateStr);
        }
      }
      renderCalendrier();
    }

    function renderCalendrier() {
      calendrierContainer.innerHTML = '';

      // Navigation
      const nav = document.createElement('div');
      nav.className = 'calendrier-nav';
      const moisNom = moisActuel.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
      nav.innerHTML = `
        <button type="button" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm" id="prev-mois-${enfantIndex}">← Précédent</button>
        <span class="font-semibold text-gray-800 capitalize">${moisNom}</span>
        <button type="button" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm" id="next-mois-${enfantIndex}">Suivant →</button>
      `;
      calendrierContainer.appendChild(nav);

      // Grille du calendrier
      const grid = document.createElement('div');
      grid.className = 'calendrier-grid';

      // En-têtes des jours
      const jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
      jours.forEach(jour => {
        const jourDiv = document.createElement('div');
        jourDiv.className = 'calendrier-jour';
        jourDiv.textContent = jour;
        grid.appendChild(jourDiv);
      });

      // Calculer les dates du calendrier
      const premierJour = new Date(moisActuel.getFullYear(), moisActuel.getMonth(), 1);
      const dernierJour = new Date(moisActuel.getFullYear(), moisActuel.getMonth() + 1, 0);
      const premierJourSemaine = premierJour.getDay() || 7; // 1 = lundi, 7 = dimanche

      // Dates du mois précédent
      const joursAvant = premierJourSemaine - 1;
      for (let i = joursAvant; i > 0; i--) {
        const date = new Date(premierJour);
        date.setDate(date.getDate() - i);
        const dateDiv = createDateElement(date, true);
        grid.appendChild(dateDiv);
      }

      // Dates du mois actuel
      for (let jour = 1; jour <= dernierJour.getDate(); jour++) {
        const date = new Date(moisActuel.getFullYear(), moisActuel.getMonth(), jour);
        const dateDiv = createDateElement(date, false);
        grid.appendChild(dateDiv);
      }

      // Dates du mois suivant pour compléter la grille
      const totalCases = Math.ceil((joursAvant + dernierJour.getDate()) / 7) * 7;
      const joursApres = totalCases - (joursAvant + dernierJour.getDate());
      for (let i = 1; i <= joursApres; i++) {
        const date = new Date(moisActuel.getFullYear(), moisActuel.getMonth() + 1, i);
        const dateDiv = createDateElement(date, true);
        grid.appendChild(dateDiv);
      }

      calendrierContainer.appendChild(grid);

      // Events de navigation
      document.getElementById(`prev-mois-${enfantIndex}`).addEventListener('click', () => {
        moisActuel.setMonth(moisActuel.getMonth() - 1);
        renderCalendrier();
      });
      document.getElementById(`next-mois-${enfantIndex}`).addEventListener('click', () => {
        moisActuel.setMonth(moisActuel.getMonth() + 1);
        renderCalendrier();
      });

      updateSeancesRestantes();
    }

    function createDateElement(date, autreMois) {
      const dateDiv = document.createElement('div');
      dateDiv.className = 'calendrier-date';
      dateDiv.textContent = date.getDate();

      const dateStr = date.toISOString().split('T')[0];
      dateDiv.setAttribute('data-date', dateStr);
      
      const aujourdhui = new Date();
      aujourdhui.setHours(0, 0, 0, 0);
      const estPasse = date < aujourdhui;

      if (autreMois) {
        dateDiv.classList.add('autre-mois');
      }

      if (datesSelectionnees.includes(dateStr)) {
        dateDiv.classList.add('selected');
      }

      // Vérifier si le calendrier est actif (totalSeances >= 1)
      const calendrierActif = totalSeances >= 1;
      
      // Vérifier si la date peut être sélectionnée
      const seancesSemaine = countSeancesSemaine(dateStr);
      const peutSelectionner = calendrierActif && !estPasse && !autreMois && (datesSelectionnees.includes(dateStr) || (seancesRestantes > 0 && seancesSemaine < 7));

      if (!peutSelectionner) {
        dateDiv.classList.add('disabled');
      } else {
        dateDiv.addEventListener('click', () => {
          toggleDate(dateStr);
        });
      }

      return dateDiv;
    }

    // Gestion du nombre total de séances
    nbSeancesInput.addEventListener('input', function() {
      totalSeances = parseInt(this.value) || 0;
      datesSelectionnees = [];
      updateMontantTotal();
      renderCalendrier();
    });

    // Initialisation
    renderCalendrier();
  }

  // Initialisation pour l'enfant 1 (déjà présent dans le HTML)
  const enfantSection0 = enfantsContainer.querySelector('.enfant-section');
  // On modifie les IDs pour l'enfant 1
  enfantSection0.querySelector('#nb-seances-total').id = 'nb-seances-total-0';
  enfantSection0.querySelector('#seances-restantes').id = 'seances-restantes-0';
  enfantSection0.querySelector('#montant-total').id = 'montant-total-0';
  enfantSection0.querySelector('#calendrier-container').id = 'calendrier-container-0';
  initPlanificationEnfant(enfantSection0, 0);

  // Ajout dynamique d'enfants
  ajouterBtn.addEventListener('click', function() {
    enfantCount++;
    const enfantIndex = enfantCount - 1;
    // Créer la nouvelle section enfant avec IDs uniques
    const nouvelleSection = document.createElement('div');
    nouvelleSection.className = 'enfant-section';
    nouvelleSection.innerHTML = `
      <h4>Enfant ${enfantCount}</h4>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
          <input type="text" class="w-full border border-red-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Entrez le nom de l'enfant"/>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
          <input type="text" class="w-full border border-red-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Entrez le prénom de l'enfant"/>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
          <input type="date" class="w-full border border-red-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"/>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Classe</label>
          <select class="w-full border border-red-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
            <option>Sélectionner la classe</option>
            <option>CP1</option>
            <option>CP2</option>
            <option>CE1</option>
            <option>CE2</option>
            <option>CM1</option>
            <option>CM2</option>
            <option>6ème</option>
            <option>5ème</option>
            <option>4ème</option>
            <option>3ème</option>
            <option>2nde</option>
            <option>1ère</option>
            <option>Terminale</option>
          </select>
        </div>
        <div class="md:col-span-2">
          <div class="flex items-center justify-between mb-1">
            <label class="block text-sm font-medium text-gray-700">Nombre total de séances achetées</label>
            <span id="montant-total-${enfantIndex}" class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Montant total : 0 FCFA</span>
          </div>
          <input type="number" min="1" class="w-full border border-orange-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Ex : 20" id="nb-seances-total-${enfantIndex}" />
        </div>
        <div class="md:col-span-2 mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Planification des séances sur calendrier</label>
          <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-600">Séances restantes : <span id="seances-restantes-${enfantIndex}" class="font-bold text-orange-500">0</span></span>
            <span class="text-xs text-gray-500">Max 7 séances par semaine</span>
          </div>
          <div id="calendrier-container-${enfantIndex}" class="border border-orange-300 rounded-lg p-4 bg-white"></div>
        </div>
      </div>
    `;
    enfantsContainer.insertBefore(nouvelleSection, ajouterBtn.parentElement);
    initPlanificationEnfant(nouvelleSection, enfantIndex);
  });

});
