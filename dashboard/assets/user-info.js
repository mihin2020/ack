// Informations de l'utilisateur connecté
const userInfo = {
    nom: 'Admin',
    prenom: 'User',
    role: 'Administrateur',
    email: 'admin@academy-ck.com',
    avatar: null // Peut être une URL d'image plus tard
};

// Fonction pour mettre à jour l'affichage de l'utilisateur dans toutes les pages
function updateUserDisplay() {
    const userSections = document.querySelectorAll('[data-user-section]');
    
    userSections.forEach(section => {
        const nameElement = section.querySelector('[data-user-name]');
        const roleElement = section.querySelector('[data-user-role]');
        const avatarElement = section.querySelector('[data-user-avatar]');
        
        if (nameElement) {
            nameElement.textContent = `${userInfo.prenom} ${userInfo.nom}`;
        }
        
        if (roleElement) {
            roleElement.textContent = userInfo.role;
        }
        
        if (avatarElement) {
            if (userInfo.avatar) {
                avatarElement.src = userInfo.avatar;
                avatarElement.style.display = 'block';
                avatarElement.nextElementSibling.style.display = 'none'; // Cache l'icône par défaut
            } else {
                avatarElement.style.display = 'none';
                avatarElement.nextElementSibling.style.display = 'flex'; // Affiche l'icône par défaut
            }
        }
    });
}

// Fonction pour récupérer les informations de l'utilisateur depuis le localStorage ou une API
function loadUserInfo() {
    // Essayer de récupérer depuis le localStorage
    const savedUser = localStorage.getItem('userInfo');
    if (savedUser) {
        const parsedUser = JSON.parse(savedUser);
        Object.assign(userInfo, parsedUser);
    }
    
    // Mettre à jour l'affichage
    updateUserDisplay();
}

// Fonction pour sauvegarder les informations de l'utilisateur
function saveUserInfo() {
    localStorage.setItem('userInfo', JSON.stringify(userInfo));
}

// Fonction pour changer le rôle de l'utilisateur (pour les tests)
function changeUserRole(newRole) {
    userInfo.role = newRole;
    saveUserInfo();
    updateUserDisplay();
}

// Fonction pour changer le nom de l'utilisateur
function changeUserName(nouveauPrenom, nouveauNom) {
    userInfo.prenom = nouveauPrenom;
    userInfo.nom = nouveauNom;
    saveUserInfo();
    updateUserDisplay();
}

// Initialiser les informations utilisateur au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadUserInfo();
    
    // Ajouter un event listener pour les clics sur la section utilisateur
    const userSections = document.querySelectorAll('[data-user-section]');
    userSections.forEach(section => {
        section.addEventListener('click', function() {
            // Optionnel : ouvrir un modal de profil utilisateur
            openUserProfileModal();
        });
    });
});

// Fonction pour ouvrir un modal de profil utilisateur
function openUserProfileModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.style.display = 'block';
    
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        Profil Utilisateur
                    </h3>
                    <button onclick="this.closest('.fixed').remove()" 
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-3xl">person</span>
                        </div>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h4 class="text-xl font-semibold text-slate-900 dark:text-white">
                            ${userInfo.prenom} ${userInfo.nom}
                        </h4>
                        <p class="text-slate-600 dark:text-slate-400">${userInfo.role}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-500">${userInfo.email}</p>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="bg-slate-50 dark:bg-slate-700 p-3 rounded-lg">
                            <div class="text-sm text-slate-600 dark:text-slate-400 mb-1">Nom complet</div>
                            <div class="font-medium text-slate-900 dark:text-white">
                                ${userInfo.prenom} ${userInfo.nom}
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 dark:bg-slate-700 p-3 rounded-lg">
                            <div class="text-sm text-slate-600 dark:text-slate-400 mb-1">Rôle</div>
                            <div class="font-medium text-slate-900 dark:text-white">
                                ${userInfo.role}
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 dark:bg-slate-700 p-3 rounded-lg">
                            <div class="text-sm text-slate-600 dark:text-slate-400 mb-1">Email</div>
                            <div class="font-medium text-slate-900 dark:text-white">
                                ${userInfo.email}
                            </div>
                        </div>
                    </div>
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

// Exporter les fonctions pour utilisation globale
window.updateUserDisplay = updateUserDisplay;
window.changeUserRole = changeUserRole;
window.changeUserName = changeUserName;
window.openUserProfileModal = openUserProfileModal;
