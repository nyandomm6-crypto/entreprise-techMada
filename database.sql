-- =============================================================
--  SYSTÈME RH INTERNE — TechMada
--  Base de données SQLite3
--  Ordre : departements → types_conge → employes → soldes → conges
-- =============================================================

PRAGMA foreign_keys = ON;
PRAGMA journal_mode = WAL;

-- =============================================================
-- SUPPRESSION (ordre inverse des FK)
-- =============================================================
DROP TABLE IF EXISTS conges;
DROP TABLE IF EXISTS soldes;
DROP TABLE IF EXISTS employes;
DROP TABLE IF EXISTS types_conge;
DROP TABLE IF EXISTS departements;

-- =============================================================
-- 1. DEPARTEMENTS
-- =============================================================
CREATE TABLE departements (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    nom         TEXT    NOT NULL,
    description TEXT
);

-- =============================================================
-- 2. TYPES DE CONGÉ
-- =============================================================
CREATE TABLE types_conge (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle        TEXT    NOT NULL,
    jours_annuels  INTEGER NOT NULL DEFAULT 0,
    deductible     INTEGER NOT NULL DEFAULT 1  -- 1 = TRUE, 0 = FALSE
);

-- =============================================================
-- 3. EMPLOYÉS
-- =============================================================
CREATE TABLE employes (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    nom              TEXT    NOT NULL,
    prenom           TEXT    NOT NULL,
    email            TEXT    NOT NULL UNIQUE,
    password         TEXT    NOT NULL,  -- bcrypt / password_hash
    role             TEXT    NOT NULL DEFAULT 'employe'
                             CHECK (role IN ('employe', 'rh', 'admin')),
    departement_id   INTEGER REFERENCES departements(id) ON DELETE SET NULL,
    date_embauche    TEXT,               -- format ISO : YYYY-MM-DD
    actif            INTEGER NOT NULL DEFAULT 1  -- 1 = actif, 0 = désactivé
);

-- =============================================================
-- 4. SOLDES
--    jours_restants = jours_attribues - jours_pris  (calculé, jamais stocké)
-- =============================================================
CREATE TABLE soldes (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id       INTEGER NOT NULL REFERENCES employes(id)    ON DELETE CASCADE,
    type_conge_id    INTEGER NOT NULL REFERENCES types_conge(id) ON DELETE CASCADE,
    annee            INTEGER NOT NULL,
    jours_attribues  REAL    NOT NULL DEFAULT 0,
    jours_pris       REAL    NOT NULL DEFAULT 0,
    UNIQUE (employe_id, type_conge_id, annee)
);

-- =============================================================
-- 5. CONGÉS
-- =============================================================
CREATE TABLE conges (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id       INTEGER NOT NULL REFERENCES employes(id)    ON DELETE CASCADE,
    type_conge_id    INTEGER NOT NULL REFERENCES types_conge(id) ON DELETE RESTRICT,
    date_debut       TEXT    NOT NULL,   -- format ISO : YYYY-MM-DD
    date_fin         TEXT    NOT NULL,   -- format ISO : YYYY-MM-DD
    nb_jours         REAL    NOT NULL CHECK (nb_jours > 0),
    motif            TEXT,
    statut           TEXT    NOT NULL DEFAULT 'en_attente'
                             CHECK (statut IN ('en_attente','approuvee','refusee','annulee')),
    commentaire_rh   TEXT,
    created_at       TEXT    NOT NULL DEFAULT (datetime('now')),
    traite_par       INTEGER REFERENCES employes(id) ON DELETE SET NULL,
    traite_at        TEXT,
    CHECK (date_fin >= date_debut)
);

-- =============================================================
-- INDEX
-- =============================================================
CREATE INDEX idx_conges_employe ON conges (employe_id);
CREATE INDEX idx_conges_statut  ON conges (statut);
CREATE INDEX idx_conges_dates   ON conges (date_debut, date_fin);
CREATE INDEX idx_soldes_employe ON soldes (employe_id, annee);

-- =============================================================
-- TRIGGERS — logique métier solde
-- =============================================================
-- (Triggers commented out for now - focus on core schema)

-- =============================================================
-- VUE : solde restant calculé
-- =============================================================
CREATE VIEW v_soldes AS
SELECT
    s.id,
    s.employe_id,
    e.nom          AS employe_nom,
    e.prenom       AS employe_prenom,
    t.libelle      AS type_conge,
    s.annee,
    s.jours_attribues,
    s.jours_pris,
    (s.jours_attribues - s.jours_pris) AS jours_restants
FROM soldes s
JOIN employes    e ON e.id = s.employe_id
JOIN types_conge t ON t.id = s.type_conge_id;

-- =============================================================
-- DONNÉES DE TEST
-- =============================================================

-- Départements
INSERT INTO departements (nom, description) VALUES
    ('Développement',       'Équipe technique et développeurs'),
    ('Ressources Humaines',  'Gestion du personnel'),
    ('Direction',            'Direction générale');

-- Types de congé
INSERT INTO types_conge (libelle, jours_annuels, deductible) VALUES
    ('Congé annuel',    30, 1),
    ('Congé maladie',   15, 1),
    ('Congé sans solde', 0, 0);

-- Employés
-- Mot de passe : "password" hashé en bcrypt (compatible PHP password_hash)
INSERT INTO employes (nom, prenom, email, password, role, departement_id, date_embauche) VALUES
    ('Admin',  'Système', 'admin@techmada.mg',
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     'admin', 2, '2022-01-01'),
    ('Dupont', 'Marie',   'marie.dupont@techmada.mg',
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     'rh', 2, '2022-06-15'),
    ('Rakoto', 'Jean',    'jean.rakoto@techmada.mg',
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     'employe', 1, '2023-03-01'),
    ('Rabe',   'Fara',    'fara.rabe@techmada.mg',
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     'employe', 1, '2023-07-10');

-- Soldes 2025 (uniquement les types déductibles)
INSERT INTO soldes (employe_id, type_conge_id, annee, jours_attribues, jours_pris)
SELECT e.id, t.id, 2025, t.jours_annuels, 0
FROM employes e, types_conge t
WHERE t.deductible = 1;

-- =============================================================
-- VÉRIFICATION RAPIDE (décommenter pour tester)
-- =============================================================
SELECT * FROM v_soldes;
-- SELECT id, nom, prenom, email, role FROM employes;
-- SELECT * FROM types_conge;
-- SELECT * FROM departements;

-- =============================================================
-- COMPTES DE TEST  |  mot de passe : password
-- =============================================================
-- admin@techmada.mg         → admin
-- marie.dupont@techmada.mg  → rh
-- jean.rakoto@techmada.mg   → employe
-- fara.rabe@techmada.mg     → employe
-- =============================================================


CREATE TABLE statut (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(50) NOT NULL
);


CREATE TABLE demande (
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    employe_id INTEGER NOT NULL,
    type_id INTEGER NOT NULL,
    statut_id INTEGER NOT NULL,

    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,

    motif TEXT,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_id) REFERENCES types_conge(id),
    FOREIGN KEY (statut_id) REFERENCES statut(id)
);



INSERT INTO statut (libelle) VALUES
('En attente'),
('Validé'),
('Refusé');


INSERT INTO demande (employe_id, type_id, statut_id, date_debut, date_fin, motif)
VALUES
-- Admin
(1, 1, 2, '2026-06-01', '2026-06-05', 'Congé annuel'),

-- RH
(2, 2, 1, '2026-06-10', '2026-06-12', 'Maladie'),

-- Employé Jean
(3, 1, 3, '2026-07-01', '2026-07-10', 'Vacances famille'),

-- Employé Fara
(4, 3, 2, '2026-08-15', '2026-08-20', 'Formation professionnelle'),

-- Autres exemples
(3, 2, 1, '2026-06-20', '2026-06-22', 'Grippe'),
(4, 1, 1, '2026-07-05', '2026-07-08', 'Congé personnel'),
(2, 3, 2, '2026-05-01', '2026-05-03', 'Séminaire'),
(1, 2, 3, '2026-04-10', '2026-04-12', 'Absence non justifiée');