<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$demandes = $demandes ?? [];
$employe = $employe ?? [];
$nbDemandes = count($demandes);
$nbEnAttente = count(array_filter($demandes, static fn ($d) => in_array(strtolower($d['statut'] ?? ''), ['en attente', 'en_attente'], true)));
$nbValidees = count(array_filter($demandes, static fn ($d) => in_array(strtolower($d['statut'] ?? ''), ['validé', 'valide', 'approuvée', 'approuvee'], true)));
$nbRefusees = count(array_filter($demandes, static fn ($d) => in_array(strtolower($d['statut'] ?? ''), ['refusé', 'refuse'], true)));

$formatDate = static function (?string $date): string {
    if (empty($date)) {
        return '-';
    }

    return date('d/m/Y', strtotime($date));
};

$initiales = static function (array $demande): string {
    return strtoupper(substr($demande['prenom'] ?? '', 0, 1) . substr($demande['nom'] ?? '', 0, 1));
};

$statutClass = static function (string $statut): string {
    $statut = strtolower($statut);

    if (in_array($statut, ['validé', 'valide', 'approuvée', 'approuvee'], true)) {
        return 's-approuvee';
    }

    if (in_array($statut, ['refusé', 'refuse'], true)) {
        return 's-refusee';
    }

    return 's-attente';
};

$statutLibelle = static function (string $statut): string {
    return str_replace('_', ' ', $statut);
};

$typeClass = static function (string $type): string {
    $type = strtolower($type);

    if (str_contains($type, 'maladie')) {
        return 't-maladie';
    }

    if (str_contains($type, 'sans')) {
        return 't-sans-solde';
    }

    return 't-annuel';
};
?>
<section id="page-liste-rh" style="margin-top:3rem">
<div class="app-wrap">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="#page-dashboard-rh"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li>
        <a href="#page-liste-rh" class="active">
          <i class="bi bi-inbox"></i> Demandes à traiter
          <span class="nav-badge alert"><?= esc($nbEnAttente) ?></span>
        </a>
      </li>
      <li><a href="#page-liste-rh"><i class="bi bi-archive"></i> Historique</a></li>
      <li><a href="#page-liste-rh"><i class="bi bi-people"></i> Soldes employés</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-blue"><?= esc(strtoupper(substr($employe['prenom'] ?? '', 0, 1) . substr($employe['nom'] ?? '', 0, 1))) ?></div>
        <div><div class="user-name"><?= esc(trim(($employe['prenom'] ?? '') . ' ' . ($employe['nom'] ?? ''))) ?></div><div class="user-role">Responsable RH</div></div>
        <a href="#page-login" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb"><a href="#page-dashboard-rh">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted);background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
          <i class="bi bi-hourglass-split"></i> <?= esc($nbEnAttente) ?> en attente
        </span>
      </div>
    </div>

    <div class="content">

      <!-- Flash -->
      <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        Demande de Soa Rakoto approuvée. Son solde a été mis à jour automatiquement.
      </div>

      <!-- Filtre -->
      <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--forest);background:var(--forest);color:var(--white);cursor:pointer">Tous (<?= esc($nbDemandes) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">En attente (<?= esc($nbEnAttente) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Approuvées (<?= esc($nbValidees) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Refusées (<?= esc($nbRefusees) ?>)</button>
        <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto;margin-left:auto">
          <option>Tous les départements</option>
          <option>IT</option>
          <option>Finance</option>
          <option>Marketing</option>
        </select>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php if (empty($demandes)): ?>
              <tr>
                <td colspan="7" class="td-muted" style="text-align:center;padding:1.5rem">Aucune demande trouvée.</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($demandes as $d): ?>
              <?php
                $soldeDisponible = $d['solde_disponible'];
                $soldeInsuffisant = $soldeDisponible !== null && $soldeDisponible < $d['nb_jours'];
                $estEnAttente = in_array(strtolower($d['statut'] ?? ''), ['en attente', 'en_attente'], true);
              ?>
              <tr>
                <td>
                  <div class="profile-row">
                    <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem"><?= esc($initiales($d)) ?></div>
                    <div class="profile-info">
                      <div class="pname"><?= esc($d['nom']) ?> <?= esc($d['prenom']) ?></div>
                      <div class="pdept"><?= esc($d['departement'] ?? '-') ?> · embauché le <?= esc($formatDate($d['date_embauche'] ?? null)) ?></div>
                    </div>
                  </div>
                </td>
                <td><span class="type-badge <?= esc($typeClass($d['type'] ?? '')) ?>"><?= esc($d['type'] ?? '-') ?></span></td>
                <td class="td-muted" style="font-size:.8rem"><?= esc($formatDate($d['date_debut'])) ?> - <?= esc($formatDate($d['date_fin'])) ?></td>
                <td class="td-mono"><?= esc($d['nb_jours']) ?> j</td>
                <td>
                  <?php if ($soldeDisponible === null): ?>
                    <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--muted)">-</span>
                  <?php else: ?>
                    <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:<?= $soldeInsuffisant ? 'var(--warn)' : 'var(--success)' ?>;font-weight:500"><?= esc($soldeDisponible) ?> j</span>
                    <span style="font-size:.72rem;color:<?= $soldeInsuffisant ? 'var(--danger)' : 'var(--muted)' ?>"><?= $soldeInsuffisant ? ' insuffisant' : ' dispo' ?></span>
                  <?php endif; ?>
                </td>
                <td><span class="statut <?= esc($statutClass($d['statut'] ?? '')) ?>"><?= esc($statutLibelle($d['statut'] ?? '-')) ?></span></td>
                <td>
                  <?php if ($estEnAttente): ?>
                    <div class="action-btns">
                      <button class="btn-sm btn-approve" <?= $soldeInsuffisant ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>><i class="bi bi-check-lg"></i> Approuver</button>
                      <button class="btn-sm btn-refuse"><i class="bi bi-x-lg"></i> Refuser</button>
                    </div>
                  <?php else: ?>
                    <span class="td-muted" style="font-size:.75rem">Déjà traité</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Modal refus (inline, visible ici pour le template) -->
      <div style="margin-top:1.5rem">
        <div class="form-section" style="border-color:var(--danger-br);background:var(--danger-bg)">
          <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Confirmer le refus — Tsiry Fidy</h3>
          <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
            Demande de <strong>2 jours</strong> du 18 au 19 juin 2025 · Type : Maladie<br>
            <span style="font-size:.8rem;color:var(--danger)"><i class="bi bi-exclamation-triangle"></i> Solde insuffisant : 1 jour disponible, 2 demandés.</span>
          </div>
          <div class="f-group">
            <label class="f-label">Commentaire pour l'employé (optionnel)</label>
            <textarea class="f-textarea" placeholder="Ex : Solde insuffisant, veuillez contacter les RH pour un congé sans solde.">Solde insuffisant. Solde maladie restant : 1 jour.</textarea>
          </div>
          <div class="form-actions">
            <button class="btn-sm btn-refuse" style="padding:9px 16px;font-size:.875rem"><i class="bi bi-x-lg"></i> Confirmer le refus</button>
            <button class="btn-secondary"><i class="bi bi-arrow-left"></i> Annuler</button>
          </div>
        </div>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
</section>
<?= $this->endSection() ?>
