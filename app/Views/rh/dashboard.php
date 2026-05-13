<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="shell">
  <aside class="sidebar">
    <div class="brand"><div class="title">TechMada RH</div><div class="sub">Espace RH</div></div>
    <div class="nav-section">Traitement</div>
    <ul class="nav">
      <li><a class="active" href="/rh/dashboard"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="#"><i class="bi bi-inbox"></i> Demandes à traiter <span class="badge-pill alert">4</span></a></li>
      <li><a href="#"><i class="bi bi-archive"></i> Historique</a></li>
      <li><a href="#"><i class="bi bi-people"></i> Soldes employés</a></li>
    </ul>
    <div class="userbox"><div class="userrow"><div class="avatar" style="background:#1a4f7a">MR</div><div><div class="fw-semibold">Marie Rabe</div><div class="small" style="color:rgba(255,255,255,.45)">Responsable RH</div></div></div></div>
  </aside>
  <main class="main">
    <div class="topbar"><div><div class="top-title">Demandes à traiter</div><div class="top-sub">Accueil</div></div><div class="top-actions"><span class="btnx" style="background:var(--warn-bg);color:var(--warn);border-color:var(--warn-br)"><i class="bi bi-hourglass-split"></i> 4 en attente</span></div></div>
    <div class="content">
      <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> Demande de Soa Rakoto approuvée. Son solde a été mis à jour.</div>
      <div class="section d-flex flex-wrap gap-2 mb-3">
        <button class="btnx btn-forest">Tous (8)</button><button class="btnx btn-secondary">En attente (4)</button><button class="btnx btn-secondary">Approuvées (3)</button><button class="btnx btn-secondary">Refusées (1)</button>
        <select class="form-select ms-auto" style="width:auto"><option>Tous les départements</option><option>IT</option><option>Finance</option><option>Marketing</option></select>
      </div>
      <div class="cardx">
        <div class="card-head"><h3>Demandes à valider</h3></div>
        <table class="tablex mb-0">
          <thead><tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr></thead>
          <tbody>
            <tr><td><strong>Soa Rakoto</strong><div class="small-muted">IT</div></td><td><span class="type-badge">Annuel</span></td><td>23/06 - 27/06</td><td>5 j</td><td><span style="color:var(--success);font-family:'DM Mono'">18 j</span></td><td><span class="badge-state s-attente">En attente</span></td><td><div class="d-flex gap-2 flex-wrap"><button class="btnx btn-approve"><i class="bi bi-check-lg"></i> Approuver</button><button class="btnx btn-refuse"><i class="bi bi-x-lg"></i> Refuser</button></div></td></tr>
            <tr><td><strong>Tsiry Fidy</strong><div class="small-muted">Finance</div></td><td><span class="type-badge t-maladie">Maladie</span></td><td>18/06 - 19/06</td><td>2 j</td><td><span style="color:var(--warn);font-family:'DM Mono'">1 j</span></td><td><span class="badge-state s-attente">En attente</span></td><td><div class="d-flex gap-2 flex-wrap"><button class="btnx btn-approve" disabled style="opacity:.4;cursor:not-allowed"><i class="bi bi-check-lg"></i> Approuver</button><button class="btnx btn-refuse"><i class="bi bi-x-lg"></i> Refuser</button></div></td></tr>
            <tr><td><strong>Haja Andria</strong><div class="small-muted">Marketing</div></td><td><span class="type-badge">Annuel</span></td><td>30/06 - 04/07</td><td>5 j</td><td><span style="color:var(--success);font-family:'DM Mono'">22 j</span></td><td><span class="badge-state s-attente">En attente</span></td><td><div class="d-flex gap-2 flex-wrap"><button class="btnx btn-approve"><i class="bi bi-check-lg"></i> Approuver</button><button class="btnx btn-refuse"><i class="bi bi-x-lg"></i> Refuser</button></div></td></tr>
          </tbody>
        </table>
      </div>
      <div class="grid-2 section">
        <div class="panel" style="border-color:var(--danger-br);background:var(--danger-bg)">
          <h3 class="mb-2" style="color:var(--danger);font-size:1rem"><i class="bi bi-x-circle"></i> Confirmer le refus</h3>
          <p class="small-muted mb-3">Demande de 2 jours du 18 au 19 juin 2025, type maladie.</p>
          <textarea class="form-control mb-3" rows="3" placeholder="Commentaire pour l'employé">Solde insuffisant. Solde maladie restant : 1 jour.</textarea>
          <div class="d-flex gap-2 flex-wrap"><button class="btnx btn-refuse">Confirmer le refus</button><button class="btnx btn-secondary">Annuler</button></div>
        </div>
        <div class="panel">
          <h3 class="mb-3" style="font-size:1rem">Indicateurs RH</h3>
          <div class="d-grid gap-3">
            <div><div class="d-flex justify-content-between"><span class="small-muted">Demandes en attente</span><strong>4</strong></div><div class="progress mt-2" style="height:8px"><div class="progress-bar" style="width:40%;background:var(--warn)"></div></div></div>
            <div><div class="d-flex justify-content-between"><span class="small-muted">Demandes approuvées</span><strong>31</strong></div><div class="progress mt-2" style="height:8px"><div class="progress-bar" style="width:75%;background:var(--success)"></div></div></div>
            <div><div class="d-flex justify-content-between"><span class="small-muted">Soldes critiques</span><strong>2</strong></div><div class="progress mt-2" style="height:8px"><div class="progress-bar" style="width:20%;background:var(--danger)"></div></div></div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer">2025 <span>TechMada RH</span></div>
  </main>
</div>
<?= $this->endSection() ?>
