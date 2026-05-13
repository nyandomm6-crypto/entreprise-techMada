<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="shell">
  <aside class="sidebar">
    <div class="brand"><div class="title">TechMada RH</div><div class="sub">Administration</div></div>
    <div class="nav-section">Gestion</div>
    <ul class="nav">
      <li><a class="active" href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li><a href="#"><i class="bi bi-inbox"></i> Toutes les demandes <span class="badge-pill alert">4</span></a></li>
      <li><a href="#"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="#"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="#"><i class="bi bi-tags"></i> Types de congé</a></li>
      <li><a href="#"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
    </ul>
    <div class="userbox"><div class="userrow"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="fw-semibold">Administrateur</div><div class="small" style="color:rgba(255,255,255,.45)">Admin système</div></div></div></div>
  </aside>
  <main class="main">
    <div class="topbar"><div><div class="top-title">Vue d'ensemble</div><div class="top-sub">Administration</div></div><div class="top-actions"><a class="btnx btn-forest" href="#"><i class="bi bi-person-plus"></i> Ajouter un employé</a></div></div>
    <div class="content">
      <div class="metrics">
        <div class="metric"><div class="icon mi-forest"><i class="bi bi-people"></i></div><div class="val">24</div><div class="lbl">Employés actifs</div><div class="sub">+2 ce mois</div></div>
        <div class="metric"><div class="icon mi-amber"><i class="bi bi-hourglass-split"></i></div><div class="val">4</div><div class="lbl">Demandes en attente</div></div>
        <div class="metric"><div class="icon mi-green"><i class="bi bi-calendar-check"></i></div><div class="val">31</div><div class="lbl">Approuvées ce mois</div><div class="sub">+6 vs mois dernier</div></div>
        <div class="metric"><div class="icon mi-blue"><i class="bi bi-building"></i></div><div class="val">4</div><div class="lbl">Départements</div></div>
        <div class="metric"><div class="icon mi-red"><i class="bi bi-person-slash"></i></div><div class="val">3</div><div class="lbl">Absents aujourd'hui</div></div>
      </div>
      <div class="grid-2 section">
        <div class="cardx">
          <div class="card-head"><h3>Demandes récentes</h3></div>
          <table class="tablex mb-0">
            <thead><tr><th>Employé</th><th>Type</th><th>Durée</th><th>Statut</th></tr></thead>
            <tbody>
              <tr><td><strong>Soa Rakoto</strong></td><td><span class="type-badge">Annuel</span></td><td>5 j</td><td><span class="badge-state s-attente">En attente</span></td></tr>
              <tr><td><strong>Tsiry Fidy</strong></td><td><span class="type-badge t-maladie">Maladie</span></td><td>2 j</td><td><span class="badge-state s-attente">En attente</span></td></tr>
              <tr><td><strong>Haja Andria</strong></td><td><span class="type-badge">Annuel</span></td><td>5 j</td><td><span class="badge-state s-approuvee">Approuvée</span></td></tr>
            </tbody>
          </table>
        </div>
        <div class="d-grid gap-3">
          <div class="panel">
            <h3 class="mb-3" style="font-size:1rem">Absents aujourd'hui</h3>
            <div class="d-grid gap-2">
              <div class="d-flex align-items-center gap-2"><div class="avatar" style="width:30px;height:30px">SR</div><div><div class="fw-semibold">Soa Rakoto</div><div class="small-muted">Congé annuel · retour 28/06</div></div></div>
              <div class="d-flex align-items-center gap-2"><div class="avatar" style="width:30px;height:30px;background:#993556">NR</div><div><div class="fw-semibold">Noro Ramarao</div><div class="small-muted">Maladie · retour 17/06</div></div></div>
              <div class="d-flex align-items-center gap-2"><div class="avatar" style="width:30px;height:30px;background:#b8750a">KF</div><div><div class="fw-semibold">Ketaka Feno</div><div class="small-muted">Congé spécial · retour 16/06</div></div></div>
            </div>
          </div>
          <div class="flash flash-warn mb-0"><i class="bi bi-exclamation-triangle-fill"></i> 2 employés ont un solde critique (≤ 2 jours).</div>
        </div>
      </div>
    </div>
    <div class="footer">2025 <span>TechMada RH</span></div>
  </main>
</div>
<?= $this->endSection() ?>
