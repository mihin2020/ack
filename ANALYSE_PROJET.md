CAHIER DES CHARGES TECHNIQUE ET FONCTIONNEL
Projet : Système Intégré de Gestion de l’Académie Charles Kaboré (ACK)
Version 1.1 – Octobre 2025

1. Présentation Générale

L’Académie Charles Kaboré (ACK) souhaite mettre en place une plateforme numérique de gestion complète de ses activités sportives. 
Le système permettra aux parents d’inscrire leurs enfants, de suivre les séances et paiements, et aux administrateurs de gérer les inscriptions, réservations, statistiques et utilisateurs.

2. Objectifs du Projet

- Centraliser et automatiser la gestion des inscriptions et abonnements.
- Offrir une plateforme intuitive et interactive pour les parents et tuteurs.
- Mettre à disposition des administrateurs un tableau de bord dynamique et configurable.
- Faciliter le suivi des paiements, des séances et des performances.
- Améliorer la transparence et la fiabilité du traitement des données.

3. Technologies Utilisées

- Framework backend : Laravel , livewire
- Base de données : MySQL 
- Interface utilisateur : Tailwind CSS 3
- Graphiques : ApexCharts.js / Chart.js

4. Fonctionnalités Clés

✅ Interface publique (parents/tuteurs) :
- Inscription en ligne des enfants avec calcul automatique des tarifs.
- Réservation et suivi des séances.
- Visualisation de l’historique des paiements.

✅ Tableau de bord administrateur :
- Indicateurs et graphiques statistiques (inscriptions, paiements, réservations).
- Gestion des tarifs (inscription et abonnement) modifiables via l’interface.
- Gestion des paiements et génération de reçus.
- Gestion des utilisateurs, rôles et permissions.

5. Sécurité et Performance

- Authentification sécurisée avec Jetstream et rôles via Spatie.
- Protection CSRF et validation des formulaires.
- Optimisation des requêtes via Eloquent ORM.
- Sauvegarde automatique et gestion des logs.

7. Conclusion

Le système intégré de gestion de l’Académie Charles Kaboré permettra une administration complète, moderne et sécurisée des activités sportives, grâce à Laravel, Livewire, MySQL et Tailwind CSS.


# Analyse Complète du Projet - Academy Charles Kabore

## Vue d'ensemble
Système de gestion d'académie sportive permettant aux parents d'inscrire leurs enfants et aux administrateurs de gérer les inscriptions, paiements et réservations de séances.

---


## Fonctionnalités par Interface

### 1. INTERFACE PUBLIQUE (Client)

#### 1.1 Page d'Accueil (`index.html`)
**Fonctionnalités :**
- Présentation de l'académie
- Mot du promoteur (Charles Kaboré)
- Présentation des programmes sportifs
- Conditions d'adhésion et tarifs :
  - Inscription annuelle : 20 000 FCFA
  - Abonnement mensuel : 5 000 FCFA/mois
- Actualités de l'académie
- Galerie photo
- Formulaire de contact
- Navigation vers inscription et réservation

#### 1.2 Réservation de Séances (`reservation.html`)
**Fonctionnalités :**
- Recherche de pensionnaire par numéro de téléphone parent
- Affichage des pensionnaires trouvés avec ce numéro
- Visualisation du calendrier de séances pour chaque pensionnaire :
  - Séances effectuées (vert)
  - Séances réservées (bleu)
  - Séances reportées (orange)
- Navigation dans le calendrier (mois/année)
- Détails de chaque séance au clic
- Historique complet des séances

**Workflow :**
1. Parent saisit son numéro de téléphone
2. Système recherche tous les enfants inscrits avec ce numéro
3. Affichage des cartes des pensionnaires trouvés
4. Clic sur "Voir le calendrier" pour un pensionnaire
5. Affichage du calendrier interactif avec statuts des séances
6. Clic sur une date pour voir les détails de la séance

#### 1.3 Inscription Client (`inscription.html`)
**IMPORTANT :** Page publique accessible sans la sidebar admin.

**Fonctionnalités :**
- Formulaire d'inscription multi-enfants
- Informations enfant :
  - Nom, prénom
  - Date de naissance
  - Classe (CP1 à Terminale)
  - Nombre total de séances achetées
  - Planification calendrier des séances (max 7 par semaine)
- Informations parent/tuteur :
  - Nom, prénom
  - Téléphone, email
  - Adresse complète
- Informations complémentaires :
  - Contact d'urgence
  - Informations médicales/allergies
- Calcul automatique du montant total (5 000 FCFA/séance)
- Acceptation des CGU

**Workflow inscription client :**
1. Parent accède au formulaire depuis le site public
2. Remplit les informations pour un ou plusieurs enfants
3. Définit le nombre de séances à acheter pour chaque enfant
4. Planifie les séances sur le calendrier (max 7 par semaine)
5. Remplit les informations parent/tuteur
6. Ajoute les informations complémentaires
7. Accepte les CGU
8. Soumet la demande d'inscription
9. L'admin reçoit et valide l'inscription

---

### 2. INTERFACE ADMINISTRATEUR

#### 2.1 Connexion (`dashboard/login.html`)
**Fonctionnalités :**
- Formulaire de connexion (email + mot de passe)
- Redirection vers le tableau de bord après connexion
- Lien retour vers l'accueil public

#### 2.2 Tableau de Bord (`dashboard/index.html`)
**Fonctionnalités :**
- Statistiques principales :
  - Revenu total
  - Utilisateurs enregistrés
  - Réservations actives
- Graphiques :
  - Évolution des inscriptions (ligne)
  - Répartition des inscriptions (donut)
- Tableau des nouvelles inscriptions :
  - Filtrage par nom, prénom, classe, programme
  - Pagination
  - Colonnes : Nom, Prénom, Date naissance, Âge, Parent, Statut, Réservation
- Export des données
- Accès rapide aux autres sections via sidebar

#### 2.3 Inscription Manuelle (`dashboard/pages/inscription.html`)
**Fonctionnalités :**
- Formulaire complet pour inscription manuelle par l'admin
- Ajout de plusieurs enfants dans une même inscription
- Gestion du calendrier de planification des séances
- Calcul automatique des montants
- Suivi des séances restantes par enfant

**Différence avec la version client :**
- Accessible uniquement aux admins connectés
- Peut servir à créer/modifier des inscriptions
- Interface avec sidebar de navigation admin

#### 2.4 Liste des Inscrits (`dashboard/pages/liste-inscrits.html`)
**Fonctionnalités principales :**

**Filtrage avancé :**
- Par nom, prénom, date de naissance
- Par âge (tranches)
- Par parent/tuteur
- Par statut de paiement (payé/non payé)
- Par réservation :
  - Avec réservation
  - Sans réservation
  - Date spécifique
  - Période (du/au)

**Gestion des inscrits :**
- Visualisation en tableau paginé
- Actions sur chaque inscrit :
  - Voir les détails complets
  - Gérer le paiement (payé/non payé)
  - Gérer les réservations de séances
  - Modifier les informations
  - Imprimer les informations
  - Supprimer l'inscrit

**Modales de gestion :**

*Modal Paiement :*
- Changement du statut de paiement
- Enregistrement du statut

*Modal Réservation :*
- Définition du nombre total de séances
- Planification des séances sur calendrier
- Gestion des séances passées non effectuées (report possible)
- Suivi des séances restantes
- Maximum 7 séances par semaine

*Modal Détails :*
- Affichage complet des informations :
  - Informations enfant
  - Informations parent
  - Informations complémentaires
  - Statut et réservation
- Actions disponibles depuis les détails

*Modal Modification :*
- Édition complète des informations :
  - Informations enfant (nom, prénom, date, classe)
  - Informations parent (nom, prénom, téléphone, email, adresse)
  - Informations complémentaires (programme, contact urgence, médical)

**Workflow gestion inscrits :**
1. Visualisation de la liste avec filtres
2. Sélection d'un inscrit pour action
3. Gestion du paiement si nécessaire
4. Gestion des réservations et planification
5. Modification des informations si besoin
6. Impression des documents si nécessaire

#### 2.5 Statistiques (`dashboard/pages/statistiques.html`)
**Fonctionnalités :**
- Cartes statistiques principales :
  - Total inscrits
  - Paiements reçus
  - Réservations
  - Taux de paiement

**Graphiques :**
- Répartition par âge (barre)
- Programmes populaires (camembert)
- Statut des paiements (camembert)
- Répartition par classe (barre)
- Évolution des inscriptions (ligne)
- Réservations par période (barre)

**Statistiques détaillées :**
- Détails par tranche d'âge
- Détails par programme
- Détails par classe

**Actions :**
- Actualisation des statistiques
- Export des données statistiques

#### 2.6 Paramètres (`dashboard/pages/parametres.html`)
**Fonctionnalités :**
- Gestion des utilisateurs administrateurs :
  - Liste des utilisateurs admin
  - Ajout d'un nouvel utilisateur :
    - Nom, prénom
    - Numéro de téléphone
    - Email
    - Mot de passe (avec confirmation)
    - Rôle (Administrateur, Gestionnaire, Secrétaire)
  - Modification d'un utilisateur
  - Suppression d'un utilisateur

**Gestion des autorisations :**
- Tableau des autorisations par rôle
- Actions disponibles :
  - Gestion inscriptions
  - Gestion paiements
  - Gestion réservations
  - Visualisation statistiques
  - Gestion utilisateurs
  - Export données

**Rôles disponibles :**
- Administrateur : accès complet
- Gestionnaire : gestion inscriptions, paiements, réservations
- Secrétaire : visualisation et export limité

#### 2.7 Détails Inscrit (`dashboard/pages/details-inscrit.html`)
**Fonctionnalités :**
- Affichage détaillé de toutes les informations d'un inscrit
- Sections :
  - Informations enfant
  - Informations parent/tuteur
  - Informations complémentaires
  - Statut et réservation
- Actions disponibles :
  - Gérer le paiement
  - Modifier la réservation
  - Modifier les informations
  - Imprimer
- Bouton retour vers la liste

---

## Workflow Global du Système

### Workflow Inscription Nouvel Enfant

#### Côté Client (Parent)
1. Parent accède au site (`index.html`)
2. Clique sur "Inscription" dans le menu
3. Remplit le formulaire d'inscription :
   - Informations pour un ou plusieurs enfants
   - Définit le nombre de séances souhaitées
   - Planifie les séances sur le calendrier
   - Informations parent/tuteur
   - Informations complémentaires
4. Accepte les CGU
5. Soumet la demande
6. Reçoit une confirmation

#### Côté Admin
1. Admin se connecte au dashboard
2. Consulte les nouvelles inscriptions sur le tableau de bord
3. Accède à la liste des inscrits
4. Filtre ou recherche le nouvel inscrit
5. Vérifie les informations
6. Gère le paiement (marque comme payé si nécessaire)
7. Valide/modifie la planification des séances
8. L'inscription est active

### Workflow Réservation de Séances

#### Client (Parent)
1. Parent accède à `reservation.html`
2. Saisit son numéro de téléphone
3. Visualise tous ses enfants inscrits
4. Consulte le calendrier de chaque enfant :
   - Séances passées (effectuées)
   - Séances à venir (réservées)
   - Séances reportées
5. Peut voir les détails de chaque séance

#### Admin
1. Accède à la liste des inscrits
2. Sélectionne un inscrit
3. Ouvre la modal de gestion des réservations
4. Modifie le nombre de séances si nécessaire
5. Planifie ou modifie les séances sur le calendrier
6. Gère les séances passées non effectuées (report)
7. Enregistre les modifications

### Workflow Gestion Paiement

1. Admin consulte la liste des inscrits
2. Filtre par statut de paiement si nécessaire
3. Sélectionne un inscrit
4. Ouvre la modal de gestion du paiement
5. Change le statut (payé/non payé)
6. Enregistre le changement
7. Les statistiques sont mises à jour automatiquement

### Workflow Consultation Statistiques

1. Admin accède à la page statistiques
2. Visualise les statistiques principales (cartes)
3. Consulte les graphiques détaillés :
   - Répartition par âge, classe, programme
   - Évolution des inscriptions
   - Statut des paiements
4. Peut actualiser les données
5. Peut exporter les statistiques

---

## Points Techniques Importants

### Système de Calendrier
- Maximum 7 séances par semaine par enfant
- Validation des dates passées (désactivées)
- Calcul automatique des séances restantes
- Prix unitaire : 5 000 FCFA par séance
- Montant total calculé automatiquement

### Gestion Multi-Enfants
- Un parent peut inscrire plusieurs enfants
- Chaque enfant peut avoir son propre nombre de séances
- Planification indépendante par enfant
- Recherche par numéro de téléphone parent récupère tous les enfants

### Statuts de Séances
- **Effectuée** : Séance déjà passée et effectuée (vert)
- **Réservée** : Séance planifiée à venir (bleu)
- **Reportée** : Séance passée non effectuée, reportée (orange)
- **Aucune** : Pas de séance prévue (gris)

### Données Stockées par Inscrit
- Informations enfant (nom, prénom, date naissance, classe)
- Informations parent (nom, prénom, téléphone, email, adresse)
- Informations complémentaires (programme, contact urgence, médical)
- Statut de paiement
- Séances (total, réservées, effectuées, reportées)
- Dates de réservation

---

## Contraintes et Règles Métier

### Inscription
- Un parent peut inscrire plusieurs enfants
- Classe : CP1 à Terminale
- Prix par séance : 5 000 FCFA
- Maximum 7 séances par semaine par enfant
- Informations médicales obligatoires (peut être "Néant")

### Réservations
- Séances planifiées sur calendrier mensuel
- Impossible de planifier des dates passées
- Maximum 7 séances par semaine
- Séances restantes affichées en temps réel

### Paiements
- Statut : Payé / Non payé
- Montant calculé automatiquement selon nombre de séances
- Gestion indépendante par enfant

### Accès
- Client : Accès public aux pages d'inscription et réservation
- Admin : Accès sécurisé nécessitant connexion
- Rôles : Administrateur, Gestionnaire, Secrétaire avec permissions différentes

---

## Technologies Utilisées

- **Styling :** Tailwind CSS (CDN)
- **Icônes :** Material Symbols Outlined (Google Fonts)
- **Polices :** Inter, Plus Jakarta Sans

---

## Notes de Développement

### Fichiers JavaScript Clés
- `inscription.js` : Gestion du formulaire multi-enfants et calendrier
- `reservation.js` : Recherche et affichage des calendriers client
- `liste-inscrits.js` : Gestion complète des inscrits (filtres, modales)
- `statistiques.js` : Calcul et affichage des statistiques
- `parametres.js` : Gestion des utilisateurs admin
- `user-info.js` : Affichage des informations utilisateur connecté

### Points d'Attention
- Les données sont actuellement en dur dans les fichiers JS
- Nécessite intégration backend pour persistance
- Validation côté client présente mais validation serveur requise
- Sécurité : authentification admin à renforcer
- Responsive design présent mais à tester sur mobile



