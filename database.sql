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

-- Trigger 1 : approbation → déduire le solde
CREATE TRIGGER trg_approuver_conge
BEFORE UPDATE OF statut ON conges
FOR EACH ROW
WHEN NEW.statut = 'approuvee' AND OLD.statut = 'en_attente'
BEGIN
    -- Vérifier que le solde est suffisant
    SELECT CASE
        WHEN (
            SELECT (jours_attribues - jours_pris)
            FROM soldes
            WHERE employe_id    = NEW.employe_id
              AND type_conge_id  = NEW.type_conge_id
              AND annee          = CAST(strftime('%Y', NEW.date_debut) AS INTEGER)
        ) < NEW.nb_jours
        THEN RAISE(ABORT, 'Solde insuffisant pour approuver cette demande.')
    END;

    -- Déduire les jours
    UPDATE soldes
    SET jours_pris = jours_pris + NEW.nb_jours
    WHERE employe_id    = NEW.employe_id
      AND type_conge_id  = NEW.type_conge_id
      AND annee          = CAST(strftime('%Y', NEW.date_debut) AS INTEGER);
END;

-- Trigger 2 : refus ou annulation APRÈS approbation → recréditer
CREATE TRIGGER trg_annuler_conge
BEFORE UPDATE OF statut ON conges
FOR EACH ROW
WHEN OLD.statut = 'approuvee' AND NEW.statut IN ('refusee', 'annulee')
BEGIN
    UPDATE soldes
    SET jours_pris = MAX(0, jours_pris - OLD.nb_jours)
    WHERE employe_id    = OLD.employe_id
      AND type_conge_id  = OLD.type_conge_id
      AND annee          = CAST(strftime('%Y', OLD.date_debut) AS INTEGER);
END;

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
-- SELECT * FROM v_soldes;
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