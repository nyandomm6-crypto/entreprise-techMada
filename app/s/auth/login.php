<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-center min-vh-100 p-3" style="background:linear-gradient(135deg,var(--ink),#0f1710);">
  <div class="cardx w-100" style="max-width:980px;overflow:hidden;">
    <div class="row g-0">
      <div class="col-lg-6 p-4 p-lg-5" style="background:var(--forest);color:#fff;min-height:100%;">
        <div class="d-flex flex-column justify-content-between h-100">
          <div>
            <div class="brand-name fs-3 mb-2">TechMada RH</div>
            <div class="opacity-75">Gestion des congés</div>
            <h1 class="mt-5 mb-3" style="font-size:2rem;line-height:1.05">Accédez à votre espace selon votre profil.</h1>
            <p class="mb-0" style="max-width:32rem;color:rgba(255,255,255,.72)">Un seul point d'entrée pour les comptes <strong>employe</strong>, <strong>rh</strong> et <strong>admin</strong>.</p>
          </div>
          <div class="mt-4">
            <div class="small text-uppercase mb-2" style="letter-spacing:.12em;color:rgba(255,255,255,.35)">Comptes de démonstration</div>
            <div class="d-grid gap-2">
              <div class="panel" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.12);color:#fff">
                <div class="fw-bold">Employé</div>
                <div class="small" style="color:rgba(255,255,255,.6)">employe@techmada.mg · emp123</div>
              </div>
              <div class="panel" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.12);color:#fff">
                <div class="fw-bold">Responsable RH</div>
                <div class="small" style="color:rgba(255,255,255,.6)">rh@techmada.mg · rh123</div>
              </div>
              <div class="panel" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.12);color:#fff">
                <div class="fw-bold">Administrateur</div>
                <div class="small" style="color:rgba(255,255,255,.6)">admin@techmada.mg · admin123</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 p-4 p-lg-5 bg-white">
        <div class="mb-4">
          <div class="top-title">Connexion</div>
          <div class="small-muted">Entrez vos identifiants pour accéder à votre espace.</div>
        </div>
        <div class="flash flash-error">
          <i class="bi bi-exclamation-circle-fill"></i>
          Identifiants incorrects. Veuillez réessayer.
        </div>
        <form>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Adresse email</label>
            <input type="email" class="form-control form-control-lg" placeholder="vous@techmada.mg" value="employe@techmada.mg">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small">Mot de passe</label>
            <input type="password" class="form-control form-control-lg" placeholder="••••••••" value="emp123">
          </div>
          <button type="submit" class="btnx btn-forest w-100 justify-content-center">Se connecter <i class="bi bi-arrow-right-short"></i></button>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
