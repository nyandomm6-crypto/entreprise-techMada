<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="shell">
  <aside class="sidebar">
    <div class="brand">
      <div class="title">TechMada RH</div>
      <div class="sub">Espace employe</div>
    </div>
    <div class="nav-section">Menu</div>
    <ul class="nav">
      <li><a class="active" href="/employe/dashboard"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="#"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="#"><i class="bi bi-calendar3"></i> Mes demandes <span class="badge-pill alert">2</span></a></li>
      <li><a href="#"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="userbox">
      <div class="userrow">
        <div class="avatar">SR</div>
        <div>
          <div class="fw-semibold">Soa Rakoto</div>
          <div class="small" style="color:rgba(255,255,255,.45)">Employe · IT</div>
        </div>
      </div>
    </div>
  </aside>
  <main class="main">
    <div class="topbar">
      <div>
        <div class="top-title">Tableau de bord</div>
        <div class="top-sub">Accueil</div>
      </div>
      <div class="top-actions">
        <a href="#" class="btnx btn-forest"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>
    <div class="content">
      <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i> Votre demande de congé a bien été soumise.</div>
      <div class="metrics">
        <div class="metric"><div class="icon mi-amber"><i class="bi bi-hourglass-split"></i></div><div class="val">2</div><div class="lbl">En attente</div></div>
        <div class="metric"><div class="icon mi-green"><i class="bi bi-check-circle"></i></div><div class="val">5</div><div class="lbl">Approuvées</div></div>
        <div class="metric"><div class="icon mi-forest"><i class="bi bi-calendar-check"></i></div><div class="val">18</div><div class="lbl">Jours restants</div><div class="sub">sur 30 cette année</div></div>
        <div class="metric"><div class="icon mi-red"><i class="bi bi-x-circle"></i></div><div class="val">1</div><div class="lbl">Refusée</div></div>
      </div>
      <div class="grid-2 section">
        <div class="cardx">
          <div class="card-head"><h3>Mes soldes de congé</h3><div class="small-muted">2025</div></div>
          <div class="p-3 d-grid gap-3">
            <div class="panel"><div class="d-flex justify-content-between"><strong>Congé annuel</strong><span class="small-muted"><strong>18</strong> / 30 j</span></div><div class="progress mt-2" style="height:8px"><div class="progress-bar" style="width:60%;background:var(--forest)"></div></div></div>
            <div class="panel"><div class="d-flex justify-content-between"><strong>Congé maladie</strong><span class="small-muted"><strong>8</strong> / 10 j</span></div><div class="progress mt-2" style="height:8px"><div class="progress-bar" style="width:80%;background:var(--info)"></div></div></div>
            <div class="panel"><div class="d-flex justify-content-between"><strong>Congé spécial</strong><span class="small-muted"><strong>1</strong> / 5 j</span></div><div class="progress mt-2" style="height:8px"><div class="progress-bar" style="width:20%;background:var(--warn)"></div></div></div>
          </div>
        </div>
        <div class="cardx">
          <div class="card-head"><h3>Dernières demandes</h3></div>
          <table class="tablex mb-0">
            <thead><tr><th>Type</th><th>Période</th><th>Durée</th><th>Statut</th></tr></thead>
            <tbody>
              <tr><td><span class="type-badge">Annuel</span></td><td>16-20 juin</td><td>5 j</td><td><span class="badge-state s-attente">En attente</span></td></tr>
              <tr><td><span class="type-badge t-maladie">Maladie</span></td><td>2-3 juin</td><td>2 j</td><td><span class="badge-state s-approuvee">Approuvée</span></td></tr>
              <tr><td><span class="type-badge">Annuel</span></td><td>12-16 mai</td><td>5 j</td><td><span class="badge-state s-approuvee">Approuvée</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="footer">2025 <span>TechMada RH</span> — Projet CodeIgniter 4</div>
  </main>
</div>
<?= $this->endSection() ?>
