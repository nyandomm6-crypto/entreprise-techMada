# 📋 TODO LIST — Système RH Interne TechMada (CodeIgniter)

> **Stack :** CodeIgniter 4 · MySQL · Bootstrap 5  
> **Durée cible :** 4h en binôme  
> **Conventions :** ligne en **gras** = fonctionnalité obligatoire · ligne normale = bonus si le temps le permet

---

## 🔧 SETUP & CONFIGURATION

- [ ] **Installer CodeIgniter 4 (via Composer)**
- [ ] **Configurer `.env` (base de données, baseURL)**
- [ ] **Créer la base de données MySQL**
- [ ] **Créer les migrations : `users`, `conges`, `departements`, `types_conge`**
- [ ] **Créer les seeders de base (admin, rôles, types de congé)**
- [ ] Configurer les sessions CodeIgniter (base de données ou fichier)
- [ ] Mettre en place le layout de base (navbar, sidebar selon rôle)

---

## 🔐 AUTHENTIFICATION (Tous rôles)

- [ ] **Page de connexion (`/login`)**
- [ ] **Page de déconnexion (`/logout`)**
- [ ] **Middleware/filtre de session : redirection si non connecté**
- [ ] **Middleware de rôle : `employe`, `rh`, `admin`**
- [ ] Affichage du rôle courant dans la navbar

---

## 👤 RÔLE : EMPLOYÉ (`role = employe`)

### Demandes de congé
- [ ] **Formulaire de soumission d'une demande (type, dates, motif)**
- [ ] **Validation CodeIgniter des champs du formulaire**
- [ ] **Enregistrement en base avec statut `en_attente`**
- [ ] **Liste de ses propres demandes avec statut (en attente / approuvé / refusé)**
- [ ] **Filtrer ses demandes par type de congé**
- [ ] **Annuler une demande encore en attente**

### Solde & Profil
- [ ] **Afficher le solde de congés restant (par type)**
- [ ] **Page de modification du profil (nom, mot de passe)**
- [ ] Hashage du mot de passe avec `password_hash()` / `password_verify()`

---

## 👔 RÔLE : RESPONSABLE RH (`role = rh`)

### Gestion des demandes
- [ ] **Liste de toutes les demandes en attente de son équipe**
- [ ] **Approuver une demande (avec commentaire optionnel)**
- [ ] **Refuser une demande (avec commentaire optionnel)**
- [ ] **Mise à jour automatique du solde à l'approbation**
- [ ] Filtrer les demandes par département
- [ ] Filtrer les demandes par statut

### Consultation
- [ ] **Voir le solde de congés de chaque employé**
- [ ] Voir l'historique complet des demandes traitées

---

## 🛠️ RÔLE : ADMINISTRATEUR (`role = admin`)

### Gestion des employés
- [ ] **Créer un employé (nom, email, mot de passe, rôle, département)**
- [ ] **Éditer un employé**
- [ ] **Désactiver/supprimer un employé**

### Gestion des référentiels
- [ ] **CRUD des départements**
- [ ] **CRUD des types de congé (nom, quota annuel)**

### Tableau de bord
- [ ] **Tableau de bord : liste des absences du mois en cours**
- [ ] **Initialiser / ajuster le solde annuel d'un employé**
- [ ] **Voir l'historique complet de toutes les demandes**
- [ ] Statistiques globales (nb demandes par statut, par mois)

---

## ⚙️ LOGIQUE MÉTIER

- [ ] **Calcul automatique du solde lors de l'approbation**
- [ ] **Vérification que le solde est suffisant avant soumission**
- [ ] **Blocage si dates invalides (date passée, chevauchement)**
- [ ] Envoi d'email de notification (approbation / refus)
- [ ] Calcul du nombre de jours ouvrables (exclure week-ends)

---

## 🗄️ MODÈLES CODEIGNITER (Models)

- [ ] **`UserModel`** — CRUD utilisateurs + filtre par rôle
- [ ] **`CongeModel`** — CRUD demandes + filtres par statut/type/département
- [ ] **`SoldeModel`** — lecture/mise à jour des soldes par employé et type
- [ ] **`DepartementModel`** — CRUD départements
- [ ] **`TypeCongeModel`** — CRUD types de congé

---

## 🎨 INTERFACE (Views)

- [ ] **Layout principal avec menu dynamique selon le rôle**
- [ ] **Page dashboard selon le rôle connecté**
- [ ] **Formulaire demande de congé (avec validation inline)**
- [ ] **Tableaux de données avec pagination**
- [ ] Badges colorés pour les statuts (en attente, approuvé, refusé)
- [ ] Messages flash (succès / erreur) après chaque action
- [ ] Design responsive (Bootstrap 5)

---

## ✅ TESTS & LIVRAISON

- [ ] Tester chaque rôle indépendamment (employé, RH, admin)
- [ ] Tester les cas limites (solde insuffisant, dates invalides)
- [ ] Vérifier les accès non autorisés (protection des routes)
- [ ] Nettoyage du code et commentaires
- [ ] Export SQL de la base finale
- [ ] README avec instructions d'installation

---

## 📁 STRUCTURE SUGGÉRÉE DES FICHIERS

```
app/
├── Controllers/
│   ├── Auth.php
│   ├── Dashboard.php
│   ├── Conges.php
│   ├── Admin/
│   │   ├── Employes.php
│   │   ├── Departements.php
│   │   └── TypesConge.php
│   └── RH/
│       └── Demandes.php
├── Models/
│   ├── UserModel.php
│   ├── CongeModel.php
│   ├── SoldeModel.php
│   ├── DepartementModel.php
│   └── TypeCongeModel.php
├── Views/
│   ├── layout/
│   │   ├── header.php
│   │   └── sidebar.php
│   ├── auth/
│   ├── employe/
│   ├── rh/
│   └── admin/
└── Filters/
    ├── AuthFilter.php
    └── RoleFilter.php
```

---

*Généré pour le projet TechMada — Système RH Interne*