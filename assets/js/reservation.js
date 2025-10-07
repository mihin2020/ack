// Données des inscrits (même structure que dans le dashboard)
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
        dateReservation: null,
        seances: {
            total: 10,
            reservees: ['2024-12-15', '2024-12-18', '2024-12-22', '2024-12-25', '2024-12-28'],
            effectuees: ['2024-11-15', '2024-11-18', '2024-11-22', '2024-11-25', '2024-11-28', '2024-12-01', '2024-12-05', '2024-12-08', '2023-12-15', '2023-12-18', '2023-12-22'],
            reportees: ['2024-12-10']
        }
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
        dateReservation: null,
        seances: {
            total: 8,
            reservees: ['2024-12-16', '2024-12-20', '2024-12-24', '2024-12-27'],
            effectuees: ['2024-11-16', '2024-11-20', '2024-11-24', '2024-11-27', '2024-12-02', '2024-12-06', '2023-11-16', '2023-11-20', '2023-11-24'],
            reportees: ['2024-12-10']
        }
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
        seances: {
            total: 12,
            reservees: ['2024-12-17', '2024-12-21', '2024-12-26', '2024-12-29'],
            effectuees: ['2024-11-17', '2024-11-21', '2024-11-26', '2024-11-29', '2024-12-03', '2024-12-07', '2024-12-11', '2023-11-17', '2023-11-21', '2023-11-26', '2023-11-29', '2023-12-03'],
            reportees: []
        }
    },
    {
        id: 4,
        nom: 'Moreau',
        prenom: 'Emma',
        dateNaissance: '12/05/2011',
        age: 13,
        classe: '4ème',
        parent: 'Marie Dupont',
        parentPrenom: 'Marie',
        telephone: '01 23 45 67 89', // Même numéro que Jean Dupont (même famille)
        email: 'marie.dupont@email.com',
        adresse: '123 Rue de la Paix, 75001 Paris',
        programme: 'Tennis',
        contactUrgence: 'Pierre Dupont - 01 98 76 54 32',
        infosMedicales: 'Néant',
        statutPaiement: 'paye',
        dateReservation: null,
        seances: {
            total: 6,
            reservees: ['2024-12-19', '2024-12-23', '2024-12-30'],
            effectuees: ['2024-11-19', '2024-11-23', '2024-12-04', '2023-11-19', '2023-11-23', '2023-12-04'],
            reportees: ['2024-12-09']
        }
    }
];

// Variables globales
let pensionnairesTrouves = [];

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    searchForm.addEventListener('submit', handleSearch);
});

// Fonction principale de recherche
function handleSearch(event) {
    event.preventDefault();
    
    const telephoneInput = document.getElementById('telephone');
    const telephone = telephoneInput.value.trim();
    
    if (!telephone) {
        alert('Veuillez saisir un numéro de téléphone');
        return;
    }
    
    // Normaliser le numéro de téléphone (enlever espaces, tirets, etc.)
    const telephoneNormalise = telephone.replace(/[\s\-\.\(\)]/g, '');
    
    // Rechercher les pensionnaires avec ce numéro
    pensionnairesTrouves = inscritsData.filter(inscrit => {
        const telephoneInscrit = inscrit.telephone.replace(/[\s\-\.\(\)]/g, '');
        return telephoneInscrit === telephoneNormalise;
    });
    
    // Afficher les résultats
    afficherResultats();
}

// Fonction pour afficher les résultats
function afficherResultats() {
    const resultsSection = document.getElementById('resultsSection');
    const noResultsSection = document.getElementById('noResultsSection');
    const pensionnairesList = document.getElementById('pensionnairesList');
    
    if (pensionnairesTrouves.length === 0) {
        // Aucun résultat trouvé
        resultsSection.style.display = 'none';
        noResultsSection.style.display = 'block';
    } else {
        // Des résultats trouvés
        noResultsSection.style.display = 'none';
        resultsSection.style.display = 'block';
        
        // Générer les cartes des pensionnaires
        pensionnairesList.innerHTML = '';
        pensionnairesTrouves.forEach(pensionnaire => {
            const carte = creerCartePensionnaire(pensionnaire);
            pensionnairesList.appendChild(carte);
        });
    }
}

// Fonction pour créer une carte de pensionnaire
function creerCartePensionnaire(pensionnaire) {
    const carte = document.createElement('div');
    carte.className = 'pensionnaire-card bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden';
    
    const statutPaiement = pensionnaire.statutPaiement === 'paye' ? 
        '<span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Payé</span>' :
        '<span class="inline-block px-2 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-full">Non payé</span>';
    
    carte.innerHTML = `
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                    ${pensionnaire.prenom} ${pensionnaire.nom}
                </h3>
                ${statutPaiement}
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-lg">cake</span>
                    <span class="text-slate-600 dark:text-slate-400">${pensionnaire.age} ans</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-lg">school</span>
                    <span class="text-slate-600 dark:text-slate-400">Classe ${pensionnaire.classe}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-lg">sports_soccer</span>
                    <span class="text-slate-600 dark:text-slate-400">${pensionnaire.programme}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-lg">event</span>
                    <span class="text-slate-600 dark:text-slate-400">
                        ${pensionnaire.seances.effectuees.length} séances effectuées
                    </span>
                </div>
            </div>
            
            <button onclick="afficherCalendrier(${pensionnaire.id})" 
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">calendar_month</span>
                Voir le calendrier
            </button>
        </div>
    `;
    
    return carte;
}

// Fonction pour afficher le calendrier d'un pensionnaire
function afficherCalendrier(pensionnaireId) {
    const pensionnaire = pensionnairesTrouves.find(p => p.id === pensionnaireId);
    if (!pensionnaire) return;
    
    // Créer et afficher le modal du calendrier
    const modal = creerModalCalendrier(pensionnaire);
    document.body.appendChild(modal);
    modal.style.display = 'block';
}

// Fonction pour créer le modal du calendrier
function creerModalCalendrier(pensionnaire) {
    const modal = document.createElement('div');
    modal.id = `calendrierModal-${pensionnaire.id}`;
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.style.display = 'none';
    
    // Initialiser le calendrier pour ce pensionnaire
    calendrierReservation.pensionnaireId = pensionnaire.id;
    calendrierReservation.moisActuel = new Date();
    
    const calendrier = genererCalendrier(pensionnaire);
    
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                            Calendrier - ${pensionnaire.prenom} ${pensionnaire.nom}
                        </h2>
                        <p class="text-slate-600 dark:text-slate-400">
                            Programme ${pensionnaire.programme} • Classe ${pensionnaire.classe}
                        </p>
                    </div>
                    <button onclick="fermerModal('${modal.id}')" 
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>
            </div>
            <div id="calendrier-content-${pensionnaire.id}" class="p-6">
                ${calendrier}
            </div>
        </div>
    `;
    
    // Fermer le modal en cliquant à l'extérieur
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            fermerModal(modal.id);
        }
    });
    
    return modal;
}

// Variables globales pour le calendrier de réservation
let calendrierReservation = {
    pensionnaireId: null,
    moisActuel: new Date(),
    anneeActuelle: new Date().getFullYear()
};

// Fonction pour générer le calendrier complet et interactif
function genererCalendrier(pensionnaire) {
    calendrierReservation.pensionnaireId = pensionnaire.id;
    calendrierReservation.moisActuel = new Date();
    
    const moisNoms = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    
    const joursSemaine = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    
    let html = `
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        ${moisNoms[calendrierReservation.moisActuel.getMonth()]} ${calendrierReservation.moisActuel.getFullYear()}
                    </h3>
                    <div class="flex items-center gap-2">
                        <button onclick="changerAnnee('${pensionnaire.id}', -1)" 
                                class="p-1 rounded border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs">
                            &lt;&lt;
                        </button>
                        <span class="text-sm text-slate-600 dark:text-slate-400 min-w-[60px] text-center">
                            ${calendrierReservation.moisActuel.getFullYear()}
                        </span>
                        <button onclick="changerAnnee('${pensionnaire.id}', 1)" 
                                class="p-1 rounded border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs">
                            &gt;&gt;
                        </button>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="changerMois('${pensionnaire.id}', -1)" 
                            class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button onclick="allerAujourdhui('${pensionnaire.id}')" 
                            class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm">
                        Aujourd'hui
                    </button>
                    <button onclick="changerMois('${pensionnaire.id}', 1)" 
                            class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
            
            <!-- Légende -->
            <div class="flex flex-wrap gap-4 mb-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Séance effectuée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Séance réservée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-orange-500 rounded"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Séance reportée</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-200 dark:bg-gray-600 rounded"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Aucune séance</span>
                </div>
            </div>
            
            <!-- Grille du calendrier -->
            <div class="grid grid-cols-7 gap-1 border border-slate-200 dark:border-slate-600 rounded-lg p-2">
                ${joursSemaine.map(jour => `
                    <div class="p-2 text-center text-sm font-medium text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-600">
                        ${jour}
                    </div>
                `).join('')}
                
                ${genererJoursCalendrier(pensionnaire, calendrierReservation.moisActuel)}
            </div>
        </div>
    `;
    
    return html;
}

// Fonction pour générer les jours du calendrier
function genererJoursCalendrier(pensionnaire, mois) {
    const premierJour = new Date(mois.getFullYear(), mois.getMonth(), 1);
    const dernierJour = new Date(mois.getFullYear(), mois.getMonth() + 1, 0);
    const premierLundi = new Date(premierJour);
    premierLundi.setDate(premierJour.getDate() - premierJour.getDay());
    
    let html = '';
    const aujourdhui = new Date();
    aujourdhui.setHours(0, 0, 0, 0);
    
    for (let i = 0; i < 42; i++) {
        const date = new Date(premierLundi);
        date.setDate(premierLundi.getDate() + i);
        
        const dateStr = date.toISOString().split('T')[0];
        const estMoisActuel = date.getMonth() === mois.getMonth();
        const estAujourdhui = date.getTime() === aujourdhui.getTime();
        
        let classeCouleur = '';
        let statut = '';
        let badgeHtml = '';
        
        // Déterminer le statut et la couleur
        if (pensionnaire.seances.effectuees.includes(dateStr)) {
            classeCouleur = 'bg-green-50 hover:bg-green-100 border-green-200 text-green-800';
            statut = 'Séance effectuée';
            badgeHtml = '<div class="w-2 h-2 bg-green-500 rounded-full mx-auto mb-1"></div>';
        } else if (pensionnaire.seances.reservees.includes(dateStr)) {
            classeCouleur = 'bg-blue-50 hover:bg-blue-100 border-blue-200 text-blue-800';
            statut = 'Séance réservée';
            badgeHtml = '<div class="w-2 h-2 bg-blue-500 rounded-full mx-auto mb-1"></div>';
        } else if (pensionnaire.seances.reportees.includes(dateStr)) {
            classeCouleur = 'bg-orange-50 hover:bg-orange-100 border-orange-200 text-orange-800';
            statut = 'Séance reportée';
            badgeHtml = '<div class="w-2 h-2 bg-orange-500 rounded-full mx-auto mb-1"></div>';
        } else {
            classeCouleur = estMoisActuel ? 
                'bg-white hover:bg-gray-50 border-gray-200 text-gray-700' : 
                'bg-gray-50 hover:bg-gray-100 border-gray-200 text-gray-400';
        }
        
        // Style pour aujourd'hui
        if (estAujourdhui && estMoisActuel) {
            classeCouleur += ' ring-2 ring-primary bg-primary/5';
        }
        
        // Style pour les jours du mois actuel
        const opacityClass = !estMoisActuel ? 'opacity-40' : '';
        
        html += `
            <div class="calendar-day min-h-[80px] p-2 text-center text-sm rounded-lg cursor-pointer border transition-all duration-200 ${classeCouleur} ${opacityClass}"
                 title="${statut || 'Aucune séance'} - ${new Date(dateStr).toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}"
                 onclick="afficherDetailsSeance('${pensionnaire.id}', '${dateStr}', '${statut}')">
                <div class="flex flex-col h-full">
                    <div class="text-xs font-medium mb-1">${date.getDate()}</div>
                    ${badgeHtml}
                    ${statut ? `<div class="text-xs mt-auto opacity-75">${statut.split(' ')[0]}</div>` : ''}
                </div>
            </div>
        `;
    }
    
    return html;
}

// Fonction pour afficher les détails d'une séance
function afficherDetailsSeance(pensionnaireId, date, statut) {
    const pensionnaire = pensionnairesTrouves.find(p => p.id == pensionnaireId);
    if (!pensionnaire) return;
    
    const dateFormatee = new Date(date).toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Créer un modal moderne pour les détails
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.style.display = 'block';
    
    let couleurStatut = '';
    let iconeStatut = '';
    
    if (statut === 'Séance effectuée') {
        couleurStatut = 'text-green-600 bg-green-50 border-green-200';
        iconeStatut = 'check_circle';
    } else if (statut === 'Séance réservée') {
        couleurStatut = 'text-blue-600 bg-blue-50 border-blue-200';
        iconeStatut = 'event';
    } else if (statut === 'Séance reportée') {
        couleurStatut = 'text-orange-600 bg-orange-50 border-orange-200';
        iconeStatut = 'schedule';
    } else {
        couleurStatut = 'text-gray-600 bg-gray-50 border-gray-200';
        iconeStatut = 'info';
    }
    
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        Détails de la séance
                    </h3>
                    <button onclick="this.closest('.fixed').remove()" 
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 rounded-lg border ${couleurStatut}">
                        <span class="material-symbols-outlined">${iconeStatut}</span>
                        <div>
                            <div class="font-medium">${statut || 'Aucune séance'}</div>
                            <div class="text-sm opacity-75">${dateFormatee}</div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg">
                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">Pensionnaire</div>
                        <div class="font-medium text-slate-900 dark:text-white">
                            ${pensionnaire.prenom} ${pensionnaire.nom}
                        </div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            ${pensionnaire.age} ans • Classe ${pensionnaire.classe} • ${pensionnaire.programme}
                        </div>
                    </div>
                    
                    ${statut && statut !== 'Aucune séance' ? `
                        <div class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg">
                            <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">Informations</div>
                            <div class="text-sm text-slate-900 dark:text-white">
                                ${getDetailsSeance(pensionnaire, date, statut)}
                            </div>
                        </div>
                    ` : ''}
                </div>
                
                <div class="flex justify-end mt-6">
                    <button onclick="this.closest('.fixed').remove()" 
                            class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Fermer le modal en cliquant à l'extérieur
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    document.body.appendChild(modal);
}

// Fonction pour obtenir les détails d'une séance
function getDetailsSeance(pensionnaire, date, statut) {
    if (statut === 'Séance effectuée') {
        return `Séance de ${pensionnaire.programme} terminée avec succès.`;
    } else if (statut === 'Séance réservée') {
        return `Séance de ${pensionnaire.programme} planifiée et confirmée.`;
    } else if (statut === 'Séance reportée') {
        return `Séance de ${pensionnaire.programme} reportée à une date ultérieure.`;
    }
    return 'Aucune séance prévue pour cette date.';
}

// Fonction pour fermer un modal
function fermerModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.remove();
    }
}

// Fonction pour changer de mois
function changerMois(pensionnaireId, direction) {
    calendrierReservation.moisActuel.setMonth(calendrierReservation.moisActuel.getMonth() + direction);
    actualiserCalendrier(pensionnaireId);
}

// Fonction pour changer d'année
function changerAnnee(pensionnaireId, direction) {
    calendrierReservation.moisActuel.setFullYear(calendrierReservation.moisActuel.getFullYear() + direction);
    actualiserCalendrier(pensionnaireId);
}

// Fonction pour aller à aujourd'hui
function allerAujourdhui(pensionnaireId) {
    calendrierReservation.moisActuel = new Date();
    actualiserCalendrier(pensionnaireId);
}

// Fonction pour actualiser le calendrier
function actualiserCalendrier(pensionnaireId) {
    const pensionnaire = pensionnairesTrouves.find(p => p.id == pensionnaireId);
    if (!pensionnaire) return;
    
    const contenuCalendrier = document.getElementById(`calendrier-content-${pensionnaireId}`);
    if (contenuCalendrier) {
        contenuCalendrier.innerHTML = genererCalendrier(pensionnaire);
    }
}
