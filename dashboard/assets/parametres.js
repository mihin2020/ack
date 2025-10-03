// Mock data utilisateurs
let users = [
    { nom: 'Dupont', prenom: 'Jean', numero: '0601020304', email: 'jean.dupont@email.com', role: 'admin' },
    { nom: 'Martin', prenom: 'Sophie', numero: '0605060708', email: 'sophie.martin@email.com', role: 'gestion' },
    { nom: 'Durand', prenom: 'Paul', numero: '0609091011', email: 'paul.durand@email.com', role: 'secretaire' }
];

// Mock data autorisations
let permissions = [
    { action: 'Voir les inscrits', admin: true, gestion: true, secretaire: true },
    { action: 'Ajouter un inscrit', admin: true, gestion: true, secretaire: false },
    { action: 'Supprimer un inscrit', admin: true, gestion: false, secretaire: false },
    { action: 'Gérer les utilisateurs', admin: true, gestion: false, secretaire: false }
];

function renderUsers() {
    const tbody = document.getElementById('users-list');
    tbody.innerHTML = '';
    users.forEach((user, idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="py-3 px-4">${user.nom}</td>
            <td class="py-3 px-4">${user.prenom}</td>
            <td class="py-3 px-4">${user.email}</td>
            <td class="py-3 px-4">${user.numero}</td>
            <td class="py-3 px-4">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
            <td class="py-3 px-4">
                <button class="btn-primary px-3 py-1 rounded text-sm" onclick="deleteUser(${idx})">Supprimer</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function renderPermissions() {
    const tbody = document.getElementById('permissions-list');
    tbody.innerHTML = '';
    permissions.forEach((perm, permIdx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="py-3 px-4">${perm.action}</td>
            <td class="py-3 px-4"><input type="checkbox" data-role="admin" data-idx="${permIdx}" ${perm.admin ? 'checked' : ''}></td>
            <td class="py-3 px-4"><input type="checkbox" data-role="gestion" data-idx="${permIdx}" ${perm.gestion ? 'checked' : ''}></td>
            <td class="py-3 px-4"><input type="checkbox" data-role="secretaire" data-idx="${permIdx}" ${perm.secretaire ? 'checked' : ''}></td>
        `;
        tbody.appendChild(tr);
    });
    // Ajout des listeners sur les checkboxes
    tbody.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const idx = parseInt(this.getAttribute('data-idx'));
            const role = this.getAttribute('data-role');
            permissions[idx][role] = this.checked;
        });
    });
}

// Modal gestion
const btnAjouter = document.getElementById('btn-ajouter-user');
const modal = document.getElementById('modal-ajout-user');
const closeModal = document.getElementById('close-modal-ajout');

btnAjouter.addEventListener('click', () => {
    modal.classList.remove('hidden');
});
closeModal.addEventListener('click', () => {
    modal.classList.add('hidden');
});
window.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.add('hidden');
});

// Ajout utilisateur
const formAjout = document.getElementById('form-ajout-user');
formAjout.addEventListener('submit', function(e) {
    e.preventDefault();
    const nom = this.nom.value.trim();
    const prenom = this.prenom.value.trim();
    const numero = this.numero.value.trim();
    const email = this.email.value.trim();
    const password = this.password.value;
    const confirmPassword = this['confirm-password'].value;
    const role = this.role.value;
    if (password !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas.');
        return;
    }
    users.push({ nom, prenom, numero, email, role });
    renderUsers();
    modal.classList.add('hidden');
    this.reset();
});

// Suppression utilisateur
window.deleteUser = function(idx) {
    if (confirm('Supprimer cet utilisateur ?')) {
        users.splice(idx, 1);
        renderUsers();
    }
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    renderUsers();
    renderPermissions();
});
