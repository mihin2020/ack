let calendrierInstances = {};

function initCalendrier(index, totalSeances) {
    const container = document.getElementById(`calendrier-container-${index}`);
    if (!container) return;
    
    const restantesSpan = document.getElementById(`seances-restantes-${index}`);
    let datesSelectionnees = [];
    let moisActuel = new Date();
    moisActuel.setDate(1);
    
    function renderCalendrier() {
        container.innerHTML = '';
        
        // Navigation
        const nav = document.createElement('div');
        nav.className = 'calendrier-nav';
        const moisNom = moisActuel.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
        nav.innerHTML = `
            <button type="button" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm" onclick="calendrierInstances[${index}].prevMois()">← Précédent</button>
            <span class="font-semibold text-gray-800 capitalize">${moisNom}</span>
            <button type="button" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm" onclick="calendrierInstances[${index}].nextMois()">Suivant →</button>
        `;
        container.appendChild(nav);
        
        // Grille
        const grid = document.createElement('div');
        grid.className = 'calendrier-grid';
        
        // En-têtes
        ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'].forEach(jour => {
            const jourDiv = document.createElement('div');
            jourDiv.className = 'calendrier-jour';
            jourDiv.textContent = jour;
            grid.appendChild(jourDiv);
        });
        
        // Dates
        const premierJour = new Date(moisActuel.getFullYear(), moisActuel.getMonth(), 1);
        const dernierJour = new Date(moisActuel.getFullYear(), moisActuel.getMonth() + 1, 0);
        const premierJourSemaine = premierJour.getDay() || 7;
        
        // Jours du mois précédent
        for (let i = premierJourSemaine - 1; i > 0; i--) {
            const date = new Date(premierJour);
            date.setDate(date.getDate() - i);
            grid.appendChild(createDateElement(date, true));
        }
        
        // Jours du mois actuel
        for (let jour = 1; jour <= dernierJour.getDate(); jour++) {
            const date = new Date(moisActuel.getFullYear(), moisActuel.getMonth(), jour);
            grid.appendChild(createDateElement(date, false));
        }
        
        // Jours du mois suivant
        const joursApres = 42 - (premierJourSemaine - 1 + dernierJour.getDate());
        for (let i = 1; i <= joursApres; i++) {
            const date = new Date(moisActuel.getFullYear(), moisActuel.getMonth() + 1, i);
            grid.appendChild(createDateElement(date, true));
        }
        
        container.appendChild(grid);
        updateRestantes();
    }
    
    function createDateElement(date, autreMois) {
        const div = document.createElement('div');
        div.className = 'calendrier-date';
        div.textContent = date.getDate();
        
        const dateStr = date.toISOString().split('T')[0];
        const aujourdhui = new Date();
        aujourdhui.setHours(0, 0, 0, 0);
        const estPasse = date < aujourdhui;
        
        if (autreMois) div.classList.add('autre-mois');
        if (datesSelectionnees.includes(dateStr)) div.classList.add('selected');
        if (estPasse || autreMois || (datesSelectionnees.length >= totalSeances && !datesSelectionnees.includes(dateStr))) {
            div.classList.add('disabled');
        } else {
            div.onclick = () => toggleDate(dateStr);
        }
        
        return div;
    }
    
    function toggleDate(dateStr) {
        const indexDate = datesSelectionnees.indexOf(dateStr);
        if (indexDate > -1) {
            datesSelectionnees.splice(indexDate, 1);
        } else if (datesSelectionnees.length < totalSeances) {
            datesSelectionnees.push(dateStr);
        }
        renderCalendrier();
    }
    
    function updateRestantes() {
        if (restantesSpan) {
            restantesSpan.textContent = totalSeances - datesSelectionnees.length;
        }
    }
    
    const instance = {
        prevMois: () => {
            moisActuel.setMonth(moisActuel.getMonth() - 1);
            renderCalendrier();
        },
        nextMois: () => {
            moisActuel.setMonth(moisActuel.getMonth() + 1);
            renderCalendrier();
        },
        render: renderCalendrier
    };
    
    calendrierInstances[index] = instance;
    renderCalendrier();
}

// Fonction pour supprimer une instance de calendrier
function removeCalendrier(index) {
    delete calendrierInstances[index];
}









