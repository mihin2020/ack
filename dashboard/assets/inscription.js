document.addEventListener('DOMContentLoaded', function() {
  const ajouterBtn = document.getElementById('ajouter-enfant-btn');
  const enfantsContainer = document.getElementById('enfants-container');
  let enfantCount = 1;

  ajouterBtn.addEventListener('click', function() {
    enfantCount++;
    
    // Créer la nouvelle section enfant
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
      </div>
    `;
    
    // Insérer la nouvelle section avant le bouton
    enfantsContainer.insertBefore(nouvelleSection, ajouterBtn.parentElement);
  });
});
