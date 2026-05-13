<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'TechMada RH') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{--ink:#1c2b1e;--forest:#2d5a3d;--forest2:#3d7a52;--leaf:#5fa876;--mint:#d4ede0;--cream:#f8f6f1;--white:#fff;--border:#dde8e1;--muted:#7a8f80;--danger:#c0392b;--danger-bg:#fdf0ee;--danger-br:#f0b8b2;--warn:#b8750a;--warn-bg:#fef9ee;--warn-br:#f5d98a;--success:#1e6b3f;--success-bg:#edf7f2;--success-br:#8fd4aa;--info:#1a4f7a;--info-bg:#eaf2fb;--info-br:#8fbde8;--sidebar-w:255px;--topbar-h:64px}
*{box-sizing:border-box}
body{margin:0;font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink)}
h1,h2,h3,.brand-name{font-family:'Playfair Display',serif}
.shell{min-height:100vh;display:flex}
.sidebar{width:var(--sidebar-w);background:var(--ink);color:#fff;display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow:auto}
.brand{padding:1.25rem 1.1rem;border-bottom:1px solid rgba(255,255,255,.06)}
.brand .title{font-size:1rem;font-weight:700;line-height:1.1}
.brand .sub{font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.35)}
.nav-section{padding:.8rem 1.1rem .35rem;font-size:.62rem;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.28)}
.nav{list-style:none;padding:0 .75rem;margin:0}
.nav a{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:8px;color:rgba(255,255,255,.6);text-decoration:none;font-size:.88rem;transition:all .15s}
.nav a:hover,.nav a.active{background:rgba(255,255,255,.08);color:#fff}
.badge-pill{margin-left:auto;font-size:.65rem;padding:2px 7px;border-radius:999px;background:rgba(255,255,255,.12)}
.badge-pill.alert{background:var(--danger)}
.userbox{margin-top:auto;padding:1rem .75rem;border-top:1px solid rgba(255,255,255,.06)}
.userrow{display:flex;align-items:center;gap:10px;padding:9px 11px;border-radius:8px}
.avatar{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'DM Mono',monospace;font-size:.72rem;font-weight:700;color:#fff;background:var(--forest2);flex-shrink:0}
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{height:var(--topbar-h);background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:1rem;padding:0 1.5rem;position:sticky;top:0;z-index:10}
.top-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700}
.top-sub{font-size:.8rem;color:var(--muted);display:flex;align-items:center;gap:5px}
.top-actions{margin-left:auto;display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.content{padding:1.5rem;flex:1}
.cardx{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden}
.card-head{padding:.9rem 1.1rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.card-head h3{margin:0;font-size:.98rem;font-weight:700}
.section{margin-top:1.25rem}
.metrics{display:grid;grid-template-columns:repeat(auto-fit,minmax(165px,1fr));gap:1rem;margin-bottom:1.25rem}
.metric{background:#fff;border:1px solid var(--border);border-radius:14px;padding:1rem 1.1rem}
.metric .icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:.9rem}
.mi-forest{background:var(--mint);color:var(--forest)}
.mi-green{background:var(--success-bg);color:var(--success)}
.mi-amber{background:var(--warn-bg);color:var(--warn)}
.mi-blue{background:var(--info-bg);color:var(--info)}
.mi-red{background:var(--danger-bg);color:var(--danger)}
.metric .val{font-family:'DM Mono',monospace;font-size:1.7rem;line-height:1;font-weight:700}
.metric .lbl{font-size:.8rem;color:var(--muted);margin-top:4px}
.metric .sub{font-size:.72rem;color:var(--muted);margin-top:5px}
.flash{padding:11px 14px;border-radius:10px;font-size:.86rem;font-weight:500;display:flex;align-items:center;gap:9px;border:1px solid transparent;margin-bottom:1rem}
.flash-success{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.flash-error{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.flash-warn{background:var(--warn-bg);color:var(--warn);border-color:var(--warn-br)}
.flash-info{background:var(--info-bg);color:var(--info);border-color:var(--info-br)}
.badge-state{display:inline-flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;padding:4px 9px;border-radius:999px}
.badge-state::before{content:'';width:6px;height:6px;border-radius:50%}
.s-attente{background:var(--warn-bg);color:var(--warn)}
.s-attente::before{background:var(--warn)}
.s-approuvee{background:var(--success-bg);color:var(--success)}
.s-approuvee::before{background:var(--success)}
.s-refusee{background:var(--danger-bg);color:var(--danger)}
.s-refusee::before{background:var(--danger)}
.s-annulee{background:#f1efe8;color:#7a8f80}
.s-annulee::before{background:#b4b2a9}
.type-badge{display:inline-block;font-size:.68rem;font-weight:700;padding:3px 8px;border-radius:6px;background:var(--mint);color:var(--forest)}
.t-maladie{background:var(--info-bg);color:var(--info)}
.t-special{background:#f0e8fb;color:#5a2d82}
.t-sans-solde{background:#f1efe8;color:#7a8f80}
.tablex{width:100%;border-collapse:collapse;font-size:.87rem}
.tablex th,.tablex td{padding:12px 14px;border-bottom:1px solid var(--border);text-align:left;vertical-align:middle}
.tablex thead th{font-size:.68rem;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);background:var(--cream)}
.tablex tbody tr:hover{background:var(--cream)}
.btnx{display:inline-flex;align-items:center;gap:6px;border:1px solid transparent;border-radius:10px;padding:9px 14px;font-size:.85rem;font-weight:700;text-decoration:none;cursor:pointer;transition:all .15s}
.btn-forest{background:var(--forest);color:#fff}
.btn-forest:hover{background:var(--forest2);color:#fff}
.btn-secondary{background:#fff;color:var(--muted);border-color:var(--border)}
.btn-secondary:hover{border-color:var(--muted);color:var(--ink)}
.btn-approve{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.btn-refuse{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.btn-cancel{background:var(--cream);color:var(--muted);border-color:var(--border)}
.grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem}
.grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}
.form-control,.form-select,.form-control:focus,.form-select:focus{border-radius:10px;border-color:var(--border);box-shadow:none}
.small-muted{font-size:.78rem;color:var(--muted)}
.footer{padding:.8rem 1.5rem;border-top:1px solid var(--border);font-size:.76rem;color:var(--muted);background:#fff}
.footer span{color:var(--forest);font-weight:700}
.panel{background:#fff;border:1px solid var(--border);border-radius:14px;padding:1rem}
@media (max-width: 992px){.shell{flex-direction:column}.sidebar{width:100%;height:auto;position:relative}.topbar{height:auto;min-height:var(--topbar-h);padding:.9rem 1rem;align-items:flex-start}.top-actions{margin-left:0;width:100%}.content{padding:1rem}.grid-2,.grid-3{grid-template-columns:1fr}}
</style>
</head>
<body>
<?= $this->renderSection('content') ?>
</body>
</html>
