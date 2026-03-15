<?php
// ── Data ──────────────────────────────────────────────
$jsonFile  = __DIR__ . '/data/posts.json';
$statsFile = __DIR__ . '/data/stats.json';

$all    = file_exists($jsonFile)  ? json_decode(file_get_contents($jsonFile),  true) : [];
$stats  = file_exists($statsFile) ? json_decode(file_get_contents($statsFile), true) : [];
$catsFile = __DIR__ . '/data/categories.json';
$catsData = file_exists($catsFile) ? json_decode(file_get_contents($catsFile), true) : [];

usort($all, fn($a,$b) => strcmp($b['date'], $a['date']));

$published   = array_values(array_filter($all, fn($p) => ($p['status']??'') === 'published'));
$drafts      = array_values(array_filter($all, fn($p) => ($p['status']??'') === 'draft'));
$totalVisits = $stats['total_visits']   ?? 0;
$todayVisits = $stats['today_visits']   ?? 0;
$yesterdayV  = $stats['yesterday_visits'] ?? 0;
$hours       = $stats['hours']          ?? array_fill(0, 24, 0);

// Top post por vistas
$topPost = null; $topViews = 0;
foreach ($all as $p) {
    $v = $stats['posts'][$p['slug']]['views'] ?? 0;
    if ($v > $topViews) { $topViews = $v; $topPost = $p; }
}

// Vista activa
$view = $_GET['view'] ?? 'dashboard';

// Editar post
$editId = $_GET['id'] ?? null;
$editPost = null;
if ($editId) {
    foreach ($all as $p) { if ($p['id'] === $editId) { $editPost = $p; break; } }
}
if ($editId) $view = 'editor';

$catsFile   = __DIR__ . '/data/categories.json';
$catsData   = file_exists($catsFile) ? json_decode(file_get_contents($catsFile), true) : [];
$tagOptions = array_column($catsData, 'name');
$tagColors  = array_column($catsData, 'color', 'name');
if (empty($tagOptions)) {
    $tagOptions = ['Guía de productos','SEO & Visibilidad','Inteligencia Artificial','Funciones'];
    $tagColors  = ['Guía de productos'=>'#4A80E4','SEO & Visibilidad'=>'#10b981','Inteligencia Artificial'=>'#f59e0b','Funciones'=>'#8b5cf6'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Admin — Blog SYNTIweb</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Quill Snow (para el editor) -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet"/>
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --sw-blue:#4A80E4;--sw-blue-h:#3a6fd3;--sw-blue-lt:#EBF1FC;
  --sw-navy:#1a1a1a;--sw-bg:#F8FAFF;--sw-surface:#FFFFFF;
  --sw-border:#E2E8F4;--sw-text:#1a1a1a;--sw-muted:#64748b;
  --sw-glow:rgba(74,128,228,.16);
  --sw-font:'Inter',ui-sans-serif,system-ui,sans-serif;
  --sidebar-w:240px;
}
html,body{height:100%;font-family:var(--sw-font);background:var(--sw-bg);color:var(--sw-text);-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}

/* ── Layout ── */
.app{display:flex;height:100vh;overflow:hidden}

/* ── Sidebar ── */
.sidebar{width:var(--sidebar-w);flex-shrink:0;background:#fff;border-right:1px solid var(--sw-border);display:flex;flex-direction:column;height:100vh;overflow-y:auto}
.sb-brand{padding:18px 20px 16px;border-bottom:1px solid var(--sw-border);display:flex;align-items:center;gap:9px;flex-shrink:0}
.sb-brand-icon{width:30px;height:30px;border-radius:7px;background:var(--sw-blue);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sb-brand-text{font-size:17px;font-weight:700;letter-spacing:-.03em}
.sb-brand-text .synti{color:#1a1a1a}
.sb-brand-text .web{color:var(--sw-blue)}
.sb-nav{padding:16px 12px;flex:1}
.sb-section{margin-bottom:24px}
.sb-section-label{font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--sw-muted);padding:0 8px;margin-bottom:6px}
.sb-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:8px;font-size:13px;font-weight:500;color:var(--sw-muted);cursor:pointer;transition:all .15s;border:none;background:none;width:100%;text-align:left;font-family:var(--sw-font)}
.sb-link svg{flex-shrink:0;opacity:.7}
.sb-link:hover{background:var(--sw-bg);color:var(--sw-text)}
.sb-link.active{background:var(--sw-blue-lt);color:var(--sw-blue);font-weight:600}
.sb-link.active svg{opacity:1}
.sb-footer{padding:16px 12px;border-top:1px solid var(--sw-border);flex-shrink:0}
.sb-blog-link{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--sw-muted);padding:7px 10px;border-radius:7px;transition:all .15s}
.sb-blog-link:hover{background:var(--sw-bg);color:var(--sw-blue)}

/* ── Main ── */
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.topbar{height:56px;flex-shrink:0;background:#fff;border-bottom:1px solid var(--sw-border);display:flex;align-items:center;justify-content:space-between;padding:0 28px}
.topbar-title{font-size:16px;font-weight:600;color:var(--sw-text)}
.topbar-actions{display:flex;align-items:center;gap:10px}
.content{flex:1;overflow-y:auto;padding:28px}

/* ── Buttons ── */
.btn-primary{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;background:var(--sw-blue);color:#fff;font-family:var(--sw-font);font-size:13px;font-weight:600;border:none;cursor:pointer;transition:background .15s}
.btn-primary:hover{background:var(--sw-blue-h)}
.btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:transparent;color:var(--sw-muted);font-family:var(--sw-font);font-size:13px;font-weight:500;border:1px solid var(--sw-border);cursor:pointer;transition:all .15s}
.btn-ghost:hover{border-color:var(--sw-blue);color:var(--sw-blue)}
.btn-danger{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:7px;background:#fff2f2;color:#dc2626;border:none;font-family:var(--sw-font);font-size:12px;font-weight:500;cursor:pointer;transition:all .15s}
.btn-danger:hover{background:#dc2626;color:#fff}
.btn-edit{display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border-radius:7px;background:var(--sw-blue-lt);color:var(--sw-blue);border:none;font-family:var(--sw-font);font-size:12px;font-weight:500;cursor:pointer;transition:all .15s;text-decoration:none}
.btn-edit:hover{background:var(--sw-blue);color:#fff}

/* ── Dashboard stats ── */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.stat-card{background:#fff;border:1px solid var(--sw-border);border-radius:14px;padding:20px 22px;position:relative;overflow:hidden}
.stat-card::before{content:'';position:absolute;top:0;right:0;width:60px;height:60px;border-radius:0 14px 0 60px;background:var(--sw-blue-lt);opacity:.5}
.stat-icon{width:36px;height:36px;border-radius:9px;background:var(--sw-blue-lt);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.stat-icon svg{color:var(--sw-blue)}
.stat-n{font-size:28px;font-weight:700;letter-spacing:-.04em;color:var(--sw-text);line-height:1}
.stat-label{font-size:12px;color:var(--sw-muted);margin-top:4px}
.stat-delta{font-size:11px;font-weight:600;margin-top:8px;display:inline-flex;align-items:center;gap:3px}
.delta-up{color:#16a34a}.delta-dn{color:#dc2626}.delta-eq{color:var(--sw-muted)}

/* ── Chart bar ── */
.chart-wrap{background:#fff;border:1px solid var(--sw-border);border-radius:14px;padding:20px 22px;margin-bottom:28px}
.chart-title{font-size:13px;font-weight:600;color:var(--sw-text);margin-bottom:16px;display:flex;align-items:center;justify-content:space-between}
.chart-title span{font-size:11px;font-weight:400;color:var(--sw-muted)}
.bars{display:flex;align-items:flex-end;gap:6px;height:80px}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px}
.bar{width:100%;border-radius:4px 4px 0 0;background:var(--sw-blue-lt);min-height:4px;transition:height .3s ease}
.bar.peak{background:var(--sw-blue)}
.bar-label{font-size:9px;color:var(--sw-muted)}

/* ── Posts table ── */
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.section-title{font-size:15px;font-weight:600;color:var(--sw-text)}
.section-sub{font-size:12px;color:var(--sw-muted);margin-top:2px}
.posts-table{background:#fff;border:1px solid var(--sw-border);border-radius:14px;overflow:hidden}
.table-head{display:grid;grid-template-columns:1fr 130px 90px 80px 110px;gap:12px;padding:11px 18px;background:#fafbff;border-bottom:1px solid var(--sw-border);font-size:10px;font-weight:600;color:var(--sw-muted);letter-spacing:.06em;text-transform:uppercase}
.table-row{display:grid;grid-template-columns:1fr 130px 90px 80px 110px;gap:12px;padding:13px 18px;border-bottom:1px solid var(--sw-border);align-items:center;transition:background .15s}
.table-row:last-child{border-bottom:none}
.table-row:hover{background:var(--sw-bg)}
.post-thumb{width:44px;height:33px;border-radius:5px;overflow:hidden;flex-shrink:0}
.post-thumb img{width:100%;height:100%;object-fit:cover}
.post-info{display:flex;align-items:center;gap:10px;min-width:0}
.post-title-t{font-size:13px;font-weight:600;color:var(--sw-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;transition:color .15s}
.table-row:hover .post-title-t{color:var(--sw-blue)}
.post-sub{font-size:11px;color:var(--sw-muted);margin-top:1px}
.tag-chip{font-size:10px;font-weight:600;padding:2px 8px;border-radius:99px;display:inline-block;white-space:nowrap}
.status-badge{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:3px 9px;border-radius:99px}
.s-pub{background:#dcfce7;color:#16a34a}
.s-drf{background:#fef9c3;color:#ca8a04}
.action-btns{display:flex;gap:5px}
.empty-state{text-align:center;padding:48px 20px;color:var(--sw-muted);font-size:13px}

/* ── Top posts ── */
.top-posts-list{background:#fff;border:1px solid var(--sw-border);border-radius:14px;overflow:hidden}
.top-post-row{display:flex;align-items:center;gap:12px;padding:13px 18px;border-bottom:1px solid var(--sw-border);transition:background .15s}
.top-post-row:last-child{border-bottom:none}
.top-post-row:hover{background:var(--sw-bg)}
.top-post-rank{width:22px;height:22px;border-radius:50%;background:var(--sw-blue-lt);color:var(--sw-blue);font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.top-post-rank.gold{background:#fef3c7;color:#d97706}
.top-post-title{font-size:13px;font-weight:500;color:var(--sw-text);flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.top-post-views{font-size:12px;font-weight:600;color:var(--sw-blue);flex-shrink:0}

/* ── Dashboard 2-col ── */
.dash-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:28px}

/* ── Alerts ── */
.alert{padding:11px 16px;border-radius:9px;font-size:13px;font-weight:500;margin-bottom:18px;display:none}
.alert-ok{background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0}
.alert-err{background:#fef2f2;color:#dc2626;border:1px solid #fecaca}

/* ── Editor ── */
.editor-grid{display:grid;grid-template-columns:1fr 270px;gap:20px;align-items:start}
.field-label{font-size:12px;font-weight:600;color:var(--sw-text);margin-bottom:6px;display:block}
.field-input{width:100%;padding:9px 13px;border:1px solid var(--sw-border);border-radius:8px;background:#fff;color:var(--sw-text);font-family:var(--sw-font);font-size:13px;outline:none;transition:border-color .15s,box-shadow .15s}
.field-input:focus{border-color:var(--sw-blue);box-shadow:0 0 0 3px var(--sw-glow)}
.field-group{margin-bottom:16px}
.field-select{width:100%;padding:9px 13px;border:1px solid var(--sw-border);border-radius:8px;background:#fff;color:var(--sw-text);font-family:var(--sw-font);font-size:13px;outline:none;cursor:pointer}
.sb-card-ed{background:#fff;border:1px solid var(--sw-border);border-radius:12px;overflow:hidden;margin-bottom:14px}
.sb-card-ed-head{padding:11px 14px;border-bottom:1px solid var(--sw-border);background:#fafbff;font-size:11px;font-weight:600;color:var(--sw-muted);letter-spacing:.04em;text-transform:uppercase}
.sb-card-ed-body{padding:14px}
.status-opts{display:grid;grid-template-columns:1fr 1fr;gap:6px}
.status-opt{padding:7px 10px;border:1.5px solid var(--sw-border);border-radius:8px;font-size:12px;font-weight:500;color:var(--sw-muted);cursor:pointer;text-align:center;transition:all .15s;background:#fff}
.status-opt.active{border-color:var(--sw-blue);color:var(--sw-blue);background:var(--sw-blue-lt)}
.seo-preview{background:var(--sw-bg);border:1px solid var(--sw-border);border-radius:8px;padding:12px 14px}
.seo-url{font-size:11px;color:#188038;margin-bottom:3px}
.seo-title{font-size:14px;color:#1a0dab;font-weight:500;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.seo-desc{font-size:12px;color:#4d5156;line-height:1.5}
.char-bar{height:3px;border-radius:99px;background:var(--sw-border);margin-top:5px}
.char-fill{height:100%;border-radius:99px;background:var(--sw-blue);transition:width .2s}
.tag-chips-wrap{display:flex;flex-wrap:wrap;gap:5px;min-height:28px;margin-bottom:6px}
.tag-chip-ed{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:99px;background:var(--sw-blue-lt);color:var(--sw-blue);font-size:11px;font-weight:500}
.tag-chip-ed button{background:none;border:none;cursor:pointer;color:inherit;padding:0;line-height:1;font-size:13px}
#editor-quill{min-height:340px;font-family:var(--sw-font);font-size:15px;line-height:1.75}
.ql-toolbar{border:1px solid var(--sw-border)!important;border-radius:8px 8px 0 0!important;background:#fafbff}
.ql-container{border:1px solid var(--sw-border)!important;border-top:none!important;border-radius:0 0 8px 8px!important;font-family:var(--sw-font)!important}
.ql-editor{min-height:340px;font-family:var(--sw-font);font-size:15px;line-height:1.75}
.slug-preview{display:flex;align-items:center;gap:8px;padding:8px 12px;background:var(--sw-bg);border:1px solid var(--sw-border);border-radius:8px;font-size:11px;color:var(--sw-muted)}
.slug-preview em{color:var(--sw-blue);font-style:normal;font-weight:600}

/* ── Modal ── */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;display:none;align-items:center;justify-content:center}
.overlay.show{display:flex}
.modal{background:#fff;border-radius:14px;padding:32px;max-width:380px;width:90%;box-shadow:0 24px 64px rgba(0,0,0,.18)}
.modal-title{font-size:17px;font-weight:700;color:var(--sw-text);margin-bottom:8px}
.modal-sub{font-size:13px;color:var(--sw-muted);margin-bottom:24px;line-height:1.55}
.modal-btns{display:flex;gap:8px;justify-content:flex-end}
.btn-cancel{padding:9px 18px;border-radius:8px;border:1px solid var(--sw-border);background:#fff;color:var(--sw-muted);font-family:var(--sw-font);font-size:13px;cursor:pointer;transition:all .15s}
.btn-cancel:hover{border-color:var(--sw-blue);color:var(--sw-blue)}
.btn-confirm-del{padding:9px 18px;border-radius:8px;background:#dc2626;color:#fff;border:none;font-family:var(--sw-font);font-size:13px;font-weight:600;cursor:pointer;transition:background .15s}
.btn-confirm-del:hover{background:#b91c1c}

@media(max-width:1024px){
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  .dash-grid{grid-template-columns:1fr}
  .table-head,.table-row{grid-template-columns:1fr 100px 80px 90px}
  .table-head div:nth-child(4),.table-row div:nth-child(4){display:none}
  .editor-grid{grid-template-columns:1fr}
}
@media(max-width:700px){
  .sidebar{position:fixed;left:-240px;z-index:100;transition:left .25s}
  .sidebar.open{left:0}
  .stats-grid{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>
<div class="app">

  <!-- ══ SIDEBAR ══ -->
  <aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sb-brand">
      <div class="sb-brand-icon">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
          <path d="M3 4h10M3 8h6M3 12h8" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
      <span class="sb-brand-text"><span class="synti">SYNTI</span><span class="web">web</span></span>
    </div>

    <nav class="sb-nav">
      <!-- Overview -->
      <div class="sb-section">
        <div class="sb-section-label">Visión general</div>
        <button class="sb-link <?= $view==='dashboard'?'active':'' ?>" onclick="setView('dashboard')">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
          </svg>
          Dashboard
        </button>
      </div>

      <!-- Contenido -->
      <div class="sb-section">
        <div class="sb-section-label">Contenido</div>
        <button class="sb-link <?= $view==='posts'?'active':'' ?>" onclick="setView('posts')">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
          </svg>
          Posts
          <span style="margin-left:auto;font-size:10px;background:var(--sw-blue-lt);color:var(--sw-blue);padding:1px 7px;border-radius:99px;font-weight:600"><?= count($all) ?></span>
        </button>
        <button class="sb-link <?= $view==='editor'?'active':'' ?>" onclick="setView('editor')">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" d="M12 4v16m8-8H4"/>
          </svg>
          Nuevo artículo
        </button>
        <button class="sb-link <?= $view==='cats'?'active':'' ?>" onclick="setView('cats')">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" d="M7 7h.01M7 3h5l9 9a2 2 0 0 1 0 2.83L16.83 19a2 2 0 0 1-2.83 0L5 10V3z"/>
          </svg>
          Categorías
          <span style="margin-left:auto;font-size:10px;background:var(--sw-blue-lt);color:var(--sw-blue);padding:1px 7px;border-radius:99px;font-weight:600"><?= count($catsData) ?></span>
        </button>
      </div>

      <!-- Estadísticas rápidas en sidebar -->
      <div class="sb-section">
        <div class="sb-section-label">Hoy</div>
        <div style="padding:10px 10px;background:var(--sw-bg);border-radius:9px;border:1px solid var(--sw-border)">
          <div style="font-size:22px;font-weight:700;letter-spacing:-.04em;color:var(--sw-text)"><?= $todayVisits ?></div>
          <div style="font-size:11px;color:var(--sw-muted)">visitas hoy</div>
          <?php $delta = $todayVisits - $yesterdayV; ?>
          <div style="font-size:11px;margin-top:6px;font-weight:600;color:<?= $delta>=0?'#16a34a':'#dc2626' ?>">
            <?= $delta>=0?'↑':'↓' ?> <?= abs($delta) ?> vs ayer
          </div>
        </div>
      </div>
    </nav>

    <div class="sb-footer">
      <a href="index.php" class="sb-blog-link" target="_blank">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        Ver blog público
      </a>
    </div>
  </aside>

  <!-- ══ MAIN ══ -->
  <div class="main">

    <!-- Topbar -->
    <header class="topbar">
      <div style="display:flex;align-items:center;gap:12px">
        <button class="btn-ghost" style="display:none;padding:6px 10px" id="menu-btn" onclick="document.getElementById('sidebar').classList.toggle('open')">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <span class="topbar-title" id="topbar-title">
          <?= $view==='dashboard'?'Dashboard':($view==='posts'?'Posts':'Nuevo artículo') ?>
        </span>
      </div>
      <div class="topbar-actions">
        <?php if ($view==='posts'): ?>
        <button class="btn-primary" onclick="setView('editor')">
          <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" d="M12 4v16m8-8H4"/>
          </svg>
          Nuevo artículo
        </button>
        <?php elseif ($view==='editor'): ?>
        <button class="btn-ghost" onclick="setView('posts')">← Volver a posts</button>
        <?php endif; ?>
      </div>
    </header>

    <!-- Content -->
    <div class="content">
      <div class="alert alert-ok" id="alert-ok"></div>
      <div class="alert alert-err" id="alert-err"></div>

      <!-- ══ VIEW: DASHBOARD ══ -->
      <div id="view-dashboard" style="display:<?= $view==='dashboard'?'block':'none' ?>">

        <!-- Stats grid -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon">
              <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            <div class="stat-n"><?= number_format($totalVisits) ?></div>
            <div class="stat-label">Visitas totales al blog</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">
              <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
              </svg>
            </div>
            <div class="stat-n"><?= $todayVisits ?></div>
            <div class="stat-label">Visitas hoy</div>
            <?php $d = $todayVisits - $yesterdayV; ?>
            <div class="stat-delta <?= $d>=0?'delta-up':'delta-dn' ?>">
              <?= $d>=0?'↑':'↓' ?> <?= abs($d) ?> vs ayer
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">
              <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
              </svg>
            </div>
            <div class="stat-n"><?= count($published) ?></div>
            <div class="stat-label">Posts publicados</div>
            <div class="stat-delta delta-eq"><?= count($drafts) ?> borradores</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">
              <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
            </div>
            <div class="stat-n" style="font-size:18px;margin-top:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
              <?= $topPost ? htmlspecialchars(mb_substr($topPost['title'],0,30).'…') : '—' ?>
            </div>
            <div class="stat-label">Post más leído (<?= $topViews ?> visitas)</div>
          </div>
        </div>

        <!-- Hora pico chart + top posts -->
        <div class="dash-grid">
          <!-- Barras por hora -->
          <div class="chart-wrap">
            <div class="chart-title">
              Visitas por hora (hoy)
              <?php $peakHour = array_search(max($hours), $hours); ?>
              <span>Hora pico: <?= str_pad($peakHour,2,'0',STR_PAD_LEFT) ?>:00</span>
            </div>
            <?php $maxH = max(max($hours), 1); ?>
            <div class="bars">
              <?php for ($h=0; $h<24; $h++):
                $pct = round(($hours[$h]/$maxH)*80);
              ?>
              <div class="bar-col">
                <div class="bar <?= $h===$peakHour?'peak':'' ?>" style="height:<?= max($pct,4) ?>px" title="<?= $hours[$h] ?> visitas"></div>
                <?php if ($h % 4 === 0): ?>
                <div class="bar-label"><?= str_pad($h,2,'0',STR_PAD_LEFT) ?></div>
                <?php else: ?>
                <div class="bar-label"> </div>
                <?php endif; ?>
              </div>
              <?php endfor; ?>
            </div>
          </div>

          <!-- Top posts -->
          <div>
            <div class="section-header" style="margin-bottom:12px">
              <div class="section-title">Top artículos</div>
            </div>
            <div class="top-posts-list">
              <?php
              $ranked = [];
              foreach ($all as $p) {
                  $v = $stats['posts'][$p['slug']]['views'] ?? 0;
                  $ranked[] = ['post'=>$p,'views'=>$v];
              }
              usort($ranked, fn($a,$b) => $b['views'] <=> $a['views']);
              $ranked = array_slice($ranked, 0, 6);
              if (empty($ranked)):
              ?>
              <div class="empty-state">Sin visitas registradas aún</div>
              <?php else: foreach ($ranked as $i => $r): ?>
              <div class="top-post-row">
                <div class="top-post-rank <?= $i===0?'gold':'' ?>"><?= $i+1 ?></div>
                <div class="top-post-title" title="<?= htmlspecialchars($r['post']['title']) ?>">
                  <?= htmlspecialchars(mb_substr($r['post']['title'],0,45)) ?>
                </div>
                <div class="top-post-views"><?= $r['views'] ?> visitas</div>
              </div>
              <?php endforeach; endif; ?>
            </div>
          </div>
        </div>

        <!-- Posts recientes en dashboard -->
        <div class="section-header">
          <div>
            <div class="section-title">Posts recientes</div>
            <div class="section-sub">Últimos 5 artículos</div>
          </div>
          <button class="btn-ghost" onclick="setView('posts')" style="font-size:12px">Ver todos →</button>
        </div>
        <div class="posts-table">
          <div class="table-head">
            <div>Artículo</div><div>Categoría</div><div>Estado</div><div>Visitas</div><div>Acciones</div>
          </div>
          <?php foreach (array_slice($all,0,5) as $p): ?>
          <?php renderRow($p, $stats); ?>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- /dashboard -->

      <!-- ══ VIEW: POSTS ══ -->
      <div id="view-posts" style="display:<?= $view==='posts'?'block':'none' ?>">
        <div class="section-header" style="margin-bottom:20px">
          <div>
            <div class="section-title">Todos los artículos</div>
            <div class="section-sub"><?= count($published) ?> publicados · <?= count($drafts) ?> borradores</div>
          </div>
        </div>
        <div class="posts-table" id="posts-table-full">
          <div class="table-head">
            <div>Artículo</div><div>Categoría</div><div>Estado</div><div>Visitas</div><div>Acciones</div>
          </div>
          <?php if (empty($all)): ?>
          <div class="empty-state">No hay artículos todavía. ¡Crea el primero!</div>
          <?php else: foreach ($all as $p): renderRow($p, $stats); endforeach; endif; ?>
        </div>
      </div>
      <!-- /posts -->

      <!-- ══ VIEW: EDITOR ══ -->
      <div id="view-editor" style="display:<?= $view==='editor'?'block':'none' ?>">
        <?php
        $isEdit = $editPost !== null;
        $ep     = $editPost ?? [];
        ?>
        <div class="section-header" style="margin-bottom:20px">
          <div>
            <div class="section-title"><?= $isEdit ? 'Editar artículo' : 'Nuevo artículo' ?></div>
            <?php if ($isEdit): ?>
            <div class="section-sub">Editando: <?= htmlspecialchars($ep['title']) ?></div>
            <?php endif; ?>
          </div>
          <div style="display:flex;gap:8px">
            <button class="btn-ghost" onclick="savePost('draft')">Guardar borrador</button>
            <button class="btn-primary" onclick="savePost('published')">
              <?= $isEdit ? 'Actualizar' : 'Publicar' ?>
            </button>
          </div>
        </div>

        <div class="editor-grid">
          <!-- Main -->
          <div>
            <div class="field-group">
              <label class="field-label">Título del artículo *</label>
              <input type="text" id="post-title" class="field-input" placeholder="Escribe un título claro y atractivo…"
                     value="<?= htmlspecialchars($ep['title'] ?? '') ?>" oninput="onTitleInput(this.value)">
            </div>
            <div class="field-group">
              <label class="field-label">URL (slug)</label>
              <div class="slug-preview">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                  <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
                blog/<em id="slug-display"><?= htmlspecialchars($ep['slug'] ?? 'tu-articulo-aqui') ?></em>
              </div>
              <input type="hidden" id="post-slug" value="<?= htmlspecialchars($ep['slug'] ?? '') ?>">
            </div>
            <div class="field-group">
              <label class="field-label">Extracto / descripción corta</label>
              <textarea id="post-excerpt" class="field-input" rows="2" placeholder="1-2 frases que resuman el artículo…" style="resize:vertical"><?= htmlspecialchars($ep['excerpt'] ?? '') ?></textarea>
            </div>
            <div class="field-group">
              <label class="field-label">Contenido</label>
              <div id="editor-quill"><?= $ep['content'] ?? '' ?></div>
              <input type="hidden" id="post-content">
            </div>
          </div>

          <!-- Sidebar del editor -->
          <div>
            <!-- Estado -->
            <div class="sb-card-ed">
              <div class="sb-card-ed-head">Estado</div>
              <div class="sb-card-ed-body">
                <div class="status-opts" id="status-opts">
                  <?php $st = $ep['status'] ?? 'published'; ?>
                  <div class="status-opt <?= $st==='published'?'active':'' ?>" onclick="setStatus('published',this)">✓ Publicado</div>
                  <div class="status-opt <?= $st==='draft'?'active':'' ?>" onclick="setStatus('draft',this)">✎ Borrador</div>
                </div>
                <input type="hidden" id="post-status" value="<?= $st ?>">
                <div style="margin-top:12px">
                  <label class="field-label">Fecha de publicación</label>
                  <input type="date" id="post-date" class="field-input" value="<?= $ep['date'] ?? date('Y-m-d') ?>">
                </div>
                <div style="margin-top:10px;display:flex;align-items:center;gap:8px">
                  <input type="checkbox" id="post-featured" <?= !empty($ep['featured'])?'checked':'' ?> style="cursor:pointer">
                  <label for="post-featured" style="font-size:12px;color:var(--sw-muted);cursor:pointer">Artículo destacado</label>
                </div>
              </div>
            </div>

            <!-- Categoría -->
            <div class="sb-card-ed">
              <div class="sb-card-ed-head">Categoría</div>
              <div class="sb-card-ed-body">
                <select id="post-tag" class="field-select" onchange="updateTagColor()">
                  <?php foreach ($tagOptions as $t): ?>
                  <option value="<?= $t ?>" <?= ($ep['tag']??'')===$t?'selected':'' ?>><?= $t ?></option>
                  <?php endforeach; ?>
                </select>
                <input type="hidden" id="post-tag-color" value="<?= $ep['tag_color'] ?? '#4A80E4' ?>">
                <div id="tag-color-preview" style="margin-top:8px;height:4px;border-radius:99px;background:<?= $ep['tag_color'] ?? '#4A80E4' ?>"></div>
              </div>
            </div>

            <!-- Imagen -->
            <div class="sb-card-ed">
              <div class="sb-card-ed-head">Imagen destacada</div>
              <div class="sb-card-ed-body">
                <input type="text" id="post-image" class="field-input" placeholder="URL de imagen…"
                       value="<?= htmlspecialchars($ep['image'] ?? '') ?>" oninput="previewImg(this.value)">
                <div id="img-preview" style="margin-top:8px;border-radius:7px;overflow:hidden;display:<?= !empty($ep['image'])?'block':'none' ?>">
                  <img id="img-preview-el" src="<?= htmlspecialchars($ep['image'] ?? '') ?>" style="width:100%;height:100px;object-fit:cover;border-radius:7px">
                </div>
              </div>
            </div>

            <!-- Tags -->
            <div class="sb-card-ed">
              <div class="sb-card-ed-head">Etiquetas</div>
              <div class="sb-card-ed-body">
                <div class="tag-chips-wrap" id="tags-chips"></div>
                <input type="text" class="field-input" placeholder="Escribe y presiona Enter…" onkeydown="handleTagKey(event)" style="font-size:12px;padding:7px 10px">
                <input type="hidden" id="post-tags" value="<?= htmlspecialchars(implode(',', $ep['tags'] ?? [])) ?>">
              </div>
            </div>

            <!-- SEO -->
            <div class="sb-card-ed">
              <div class="sb-card-ed-head">SEO</div>
              <div class="sb-card-ed-body">
                <div class="field-group">
                  <label class="field-label" style="font-size:11px">Meta título</label>
                  <input type="text" id="meta-title" class="field-input" style="font-size:12px"
                         placeholder="Título para Google…" value="<?= htmlspecialchars($ep['meta_title'] ?? '') ?>" oninput="updateSeoPreview()">
                  <div class="char-bar"><div class="char-fill" id="mt-bar" style="width:0%"></div></div>
                </div>
                <div class="field-group">
                  <label class="field-label" style="font-size:11px">Meta descripción</label>
                  <textarea id="meta-desc" class="field-input" rows="2" style="font-size:12px;resize:none"
                            placeholder="Descripción para Google…" oninput="updateSeoPreview()"><?= htmlspecialchars($ep['meta_description'] ?? '') ?></textarea>
                  <div class="char-bar"><div class="char-fill" id="md-bar" style="width:0%"></div></div>
                </div>
                <div class="seo-preview">
                  <div class="seo-url">syntiweb.com/blog/<span id="seo-slug"><?= $ep['slug'] ?? '' ?></span></div>
                  <div class="seo-title" id="seo-title"><?= htmlspecialchars($ep['meta_title'] ?? $ep['title'] ?? 'Título del artículo') ?></div>
                  <div class="seo-desc" id="seo-desc"><?= htmlspecialchars($ep['meta_description'] ?? $ep['excerpt'] ?? 'Descripción del artículo…') ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <input type="hidden" id="edit-id" value="<?= $isEdit ? htmlspecialchars($ep['id']) : '' ?>">
      </div>
      <!-- /editor -->

      <!-- ══ VIEW: CATEGORÍAS ══ -->
      <div id="view-cats" style="display:<?= $view==='cats'?'block':'none' ?>">
        <div class="section-header" style="margin-bottom:20px">
          <div>
            <div class="section-title">Categorías</div>
            <div class="section-sub">Gestiona las categorías del blog — se aplican en tiempo real</div>
          </div>
          <button class="btn-primary" onclick="addCat()">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva categoría
          </button>
        </div>

        <div style="background:#fff;border:1px solid var(--sw-border);border-radius:14px;overflow:hidden;max-width:640px">
          <div style="padding:11px 18px;background:#fafbff;border-bottom:1px solid var(--sw-border);display:grid;grid-template-columns:1fr 120px 80px;gap:12px;font-size:10px;font-weight:600;color:var(--sw-muted);letter-spacing:.06em;text-transform:uppercase">
            <div>Nombre</div><div>Color</div><div>Acción</div>
          </div>
          <div id="cats-list">
            <?php foreach ($catsData as $i => $cat): ?>
            <?php renderCatRow($cat, $i); ?>
            <?php endforeach; ?>
          </div>
          <?php if (empty($catsData)): ?>
          <div class="empty-state">No hay categorías. Crea la primera.</div>
          <?php endif; ?>
        </div>

        <div style="margin-top:20px;display:flex;gap:10px">
          <button class="btn-primary" onclick="saveCats()">Guardar cambios</button>
          <span style="font-size:12px;color:var(--sw-muted);align-self:center" id="cats-status"></span>
        </div>

        <div style="margin-top:24px;padding:16px 18px;background:var(--sw-blue-lt);border:1px solid rgba(74,128,228,.2);border-radius:12px;max-width:640px">
          <div style="font-size:12px;font-weight:600;color:var(--sw-blue);margin-bottom:4px">¿Cómo funciona?</div>
          <div style="font-size:12px;color:var(--sw-muted);line-height:1.7">
            Las categorías que crees aquí aparecerán automáticamente en el blog público y en el editor de artículos.
            Al eliminar una categoría, los posts que la tenían asignada la conservan — solo dejan de aparecer en el filtro del blog.
          </div>
        </div>
      </div>
      <!-- /cats -->

    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /app -->

<!-- Delete modal -->
<div class="overlay" id="del-overlay">
  <div class="modal">
    <h3 class="modal-title">¿Eliminar este artículo?</h3>
    <p class="modal-sub" id="del-modal-sub">Esta acción no se puede deshacer.</p>
    <div class="modal-btns">
      <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
      <button class="btn-confirm-del" id="btn-confirm-del" onclick="doDelete()">Sí, eliminar</button>
    </div>
  </div>
</div>

<script>
// ── View switching ──────────────────────────────────────
const TITLES = {dashboard:'Dashboard', posts:'Posts', editor:'Nuevo artículo', cats:'Categorías'};
function setView(v) {
  ['dashboard','posts','editor','cats'].forEach(x => {
    document.getElementById('view-'+x).style.display = x===v ? 'block' : 'none';
    document.querySelectorAll('.sb-link').forEach(el => {
      if (el.getAttribute('onclick') === `setView('${x}')`) {
        el.classList.toggle('active', x===v);
      }
    });
  });
  document.getElementById('topbar-title').textContent = TITLES[v] || v;
  const actions = document.querySelector('.topbar-actions');
  if (v==='posts') {
    actions.innerHTML = `<button class="btn-primary" onclick="setView('editor')">
      <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Nuevo artículo</button>`;
  } else if (v==='editor') {
    actions.innerHTML = `<button class="btn-ghost" onclick="setView('posts')">← Volver a posts</button>`;
  } else {
    actions.innerHTML = '';
  }
}

// ── Quill editor ────────────────────────────────────────
const quill = new Quill('#editor-quill', {
  theme: 'snow',
  modules: { toolbar: [
    [{ header: [2, 3, false] }],
    ['bold','italic','underline'],
    [{ list:'ordered' },{ list:'bullet' }],
    ['blockquote','link'],
    ['clean']
  ]}
});

// ── Slug ────────────────────────────────────────────────
function slugify(s) {
  return s.toLowerCase()
    .normalize('NFD').replace(/[\u0300-\u036f]/g,'')
    .replace(/[^a-z0-9\s-]/g,'')
    .trim().replace(/\s+/g,'-').replace(/-+/g,'-');
}
let slugLocked = <?= $isEdit ? 'true' : 'false' ?>;
function onTitleInput(v) {
  if (!slugLocked) {
    const s = slugify(v);
    document.getElementById('post-slug').value = s;
    document.getElementById('slug-display').textContent = s || 'tu-articulo-aqui';
    document.getElementById('seo-slug').textContent = s;
  }
  updateSeoPreview();
}

// ── Status ──────────────────────────────────────────────
function setStatus(val, el) {
  document.getElementById('post-status').value = val;
  document.querySelectorAll('.status-opt').forEach(o => o.classList.remove('active'));
  el.classList.add('active');
}

// ── Tag color ───────────────────────────────────────────
const TAG_COLORS = <?= json_encode($tagColors) ?>;
function updateTagColor() {
  const tag = document.getElementById('post-tag').value;
  const col = TAG_COLORS[tag] || '#4A80E4';
  document.getElementById('post-tag-color').value = col;
  document.getElementById('tag-color-preview').style.background = col;
}

// ── Image preview ───────────────────────────────────────
function previewImg(url) {
  const wrap = document.getElementById('img-preview');
  const el   = document.getElementById('img-preview-el');
  if (url) { el.src = url; wrap.style.display = 'block'; }
  else { wrap.style.display = 'none'; }
}

// ── Tags ────────────────────────────────────────────────
let tags = (document.getElementById('post-tags').value || '').split(',').filter(Boolean);
function renderTags() {
  const wrap = document.getElementById('tags-chips');
  wrap.innerHTML = '';
  tags.forEach(t => {
    const span = document.createElement('span');
    span.className = 'tag-chip-ed';
    span.innerHTML = `${t}<button onclick="removeTag('${t}')">×</button>`;
    wrap.appendChild(span);
  });
  document.getElementById('post-tags').value = tags.join(',');
}
function addTag(t) {
  t = t.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'');
  if (t && !tags.includes(t) && tags.length < 8) { tags.push(t); renderTags(); }
}
function removeTag(t) { tags = tags.filter(x => x!==t); renderTags(); }
function handleTagKey(e) {
  if (e.key==='Enter' || e.key===',') {
    e.preventDefault();
    const val = e.target.value.trim().replace(/,$/,'');
    if (val) addTag(val);
    e.target.value = '';
  }
}
renderTags();

// ── SEO preview ─────────────────────────────────────────
function updateSeoPreview() {
  const mt = document.getElementById('meta-title').value;
  const md = document.getElementById('meta-desc').value;
  const ti = document.getElementById('post-title').value;
  const ex = document.getElementById('post-excerpt').value;
  document.getElementById('seo-title').textContent = mt || ti || 'Título del artículo';
  document.getElementById('seo-desc').textContent  = md || ex || 'Descripción…';
  document.getElementById('mt-bar').style.width = Math.min((mt.length/60)*100,100)+'%';
  document.getElementById('mt-bar').style.background = mt.length>60?'#dc2626':'var(--sw-blue)';
  document.getElementById('md-bar').style.width = Math.min((md.length/160)*100,100)+'%';
  document.getElementById('md-bar').style.background = md.length>160?'#dc2626':'var(--sw-blue)';
}
updateSeoPreview();
updateTagColor();

// ── Save ────────────────────────────────────────────────
async function savePost(forceStatus) {
  document.getElementById('post-content').value = quill.root.innerHTML;
  const title = document.getElementById('post-title').value.trim();
  if (!title) { showAlert('err','El título es obligatorio.'); return; }
  if (quill.getText().trim().length < 10) { showAlert('err','El contenido está vacío.'); return; }

  const dateRaw   = document.getElementById('post-date').value;
  const dateLabel = dateRaw ? new Date(dateRaw+'T12:00:00').toLocaleDateString('es-VE',{day:'numeric',month:'short',year:'numeric'}) : '';
  const words     = quill.getText().trim().split(/\s+/).length;
  const readMins  = Math.max(1, Math.round(words/200));
  const editId    = document.getElementById('edit-id').value;

  const payload = {
    id:               editId || null,
    title,
    slug:             document.getElementById('post-slug').value || slugify(title),
    excerpt:          document.getElementById('post-excerpt').value,
    content:          quill.root.innerHTML,
    image:            document.getElementById('post-image').value,
    tag:              document.getElementById('post-tag').value,
    tag_color:        document.getElementById('post-tag-color').value,
    author:           'Equipo SYNTIweb',
    avatar:           'https://i.pravatar.cc/80?img=12',
    date:             dateRaw,
    date_label:       dateLabel,
    read:             readMins+' min',
    featured:         document.getElementById('post-featured').checked,
    status:           forceStatus || document.getElementById('post-status').value,
    meta_title:       document.getElementById('meta-title').value,
    meta_description: document.getElementById('meta-desc').value,
    tags:             document.getElementById('post-tags').value.split(',').filter(Boolean),
  };

  try {
    const res  = await fetch('actions/save-post.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
    const data = await res.json();
    if (data.ok) {
      showAlert('ok','✓ Artículo guardado. Recargando…');
      setTimeout(()=>location.reload(),1200);
    } else { showAlert('err','✗ '+(data.error||'Error al guardar.')); }
  } catch(e) { showAlert('err','✗ Error de red.'); }
}

// ── Delete ──────────────────────────────────────────────
let pendingDeleteId = null;
function confirmDelete(id, title) {
  pendingDeleteId = id;
  document.getElementById('del-modal-sub').textContent = '"'+title+'" se eliminará permanentemente.';
  document.getElementById('del-overlay').classList.add('show');
}
function closeModal() {
  document.getElementById('del-overlay').classList.remove('show');
  pendingDeleteId = null;
}
async function doDelete() {
  if (!pendingDeleteId) return;
  const btn = document.getElementById('btn-confirm-del');
  btn.textContent = 'Eliminando…'; btn.disabled = true;
  try {
    const res  = await fetch('actions/delete-post.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id:pendingDeleteId})});
    const data = await res.json();
    closeModal(); btn.textContent='Sí, eliminar'; btn.disabled=false;
    if (data.ok) { showAlert('ok','✓ Eliminado.'); setTimeout(()=>location.reload(),1000); }
    else showAlert('err','✗ '+(data.error||'Error.'));
  } catch(e) { closeModal(); showAlert('err','✗ Error de red.'); btn.textContent='Sí, eliminar'; btn.disabled=false; }
  pendingDeleteId = null;
}

// ── Alert ───────────────────────────────────────────────
function showAlert(type, msg) {
  const ok=document.getElementById('alert-ok'), err=document.getElementById('alert-err');
  ok.style.display='none'; err.style.display='none';
  const el = type==='ok'?ok:err;
  el.textContent=msg; el.style.display='block';
  if (type==='ok') setTimeout(()=>el.style.display='none',3500);
}
const p = new URLSearchParams(window.location.search);
if (p.get('saved')==='1') showAlert('ok','✓ Artículo guardado correctamente.');
</script>
</body>
</html>

<?php
// ── Helper: render category row ──────────────────────────
function renderCatRow(array $cat, int $i): void {
    $name  = htmlspecialchars($cat['name']);
    $color = htmlspecialchars($cat['color'] ?? '#4A80E4');
    echo "<div class='table-row' id='cat-row-{$i}' style='grid-template-columns:1fr 120px 80px'>
      <div>
        <input type='text' class='field-input cat-name' value='{$name}'
               style='font-size:13px;padding:6px 10px' placeholder='Nombre de categoría'>
      </div>
      <div style='display:flex;align-items:center;gap:8px'>
        <input type='color' class='cat-color' value='{$color}'
               style='width:32px;height:32px;border:1px solid var(--sw-border);border-radius:6px;cursor:pointer;padding:2px'>
        <span class='cat-color-preview' style='font-size:11px;font-weight:600;padding:2px 8px;border-radius:99px;background:{$color}18;color:{$color}'>{$name}</span>
      </div>
      <div>
        <button class='btn-danger' onclick='removeCatRow(this)' title='Eliminar'>
          <svg width='12' height='12' fill='none' stroke='currentColor' viewBox='0 0 24 24' stroke-width='2'>
            <polyline points='3 6 5 6 21 6'/><path stroke-linecap='round' d='M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6'/>
          </svg>
          Quitar
        </button>
      </div>
    </div>";
}
?>
<script>
function editPost(id) {
  window.location.href = 'admin.php?view=editor&id=' + id;
}

// ── Categorías ──────────────────────────────────────────
let catIndex = <?= count($catsData) ?>;

function addCat() {
  const list = document.getElementById('cats-list');
  const idx  = catIndex++;
  const div  = document.createElement('div');
  div.className = 'table-row';
  div.id = 'cat-row-' + idx;
  div.style.gridTemplateColumns = '1fr 120px 80px';
  div.innerHTML = `
    <div>
      <input type="text" class="field-input cat-name" value=""
             style="font-size:13px;padding:6px 10px" placeholder="Nueva categoría…"
             oninput="syncCatPreview(this)">
    </div>
    <div style="display:flex;align-items:center;gap:8px">
      <input type="color" class="cat-color" value="#4A80E4"
             style="width:32px;height:32px;border:1px solid var(--sw-border);border-radius:6px;cursor:pointer;padding:2px"
             oninput="syncCatColor(this)">
      <span class="cat-color-preview" style="font-size:11px;font-weight:600;padding:2px 8px;border-radius:99px;background:#4A80E418;color:#4A80E4">Vista previa</span>
    </div>
    <div>
      <button class="btn-danger" onclick="removeCatRow(this)" title="Eliminar">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <polyline points="3 6 5 6 21 6"/><path stroke-linecap="round" d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        </svg>
        Quitar
      </button>
    </div>`;
  list.appendChild(div);
  div.querySelector('.cat-name').focus();
}

function removeCatRow(btn) {
  btn.closest('.table-row').remove();
}

function syncCatPreview(input) {
  const row     = input.closest('.table-row');
  const preview = row.querySelector('.cat-color-preview');
  preview.textContent = input.value || 'Vista previa';
}

function syncCatColor(input) {
  const row     = input.closest('.table-row');
  const preview = row.querySelector('.cat-color-preview');
  const hex     = input.value;
  preview.style.color      = hex;
  preview.style.background = hex + '18';
}

// Sync live en filas existentes
document.querySelectorAll('.cat-name').forEach(el => {
  el.addEventListener('input', () => syncCatPreview(el));
});
document.querySelectorAll('.cat-color').forEach(el => {
  el.addEventListener('input', () => syncCatColor(el));
});

async function saveCats() {
  const rows = document.querySelectorAll('#cats-list .table-row');
  const categories = [];
  rows.forEach(row => {
    const name  = row.querySelector('.cat-name')?.value.trim();
    const color = row.querySelector('.cat-color')?.value || '#4A80E4';
    if (name) categories.push({ name, color });
  });
  if (!categories.length) { showAlert('err','Agrega al menos una categoría.'); return; }
  const status = document.getElementById('cats-status');
  status.textContent = 'Guardando…';
  try {
    const res  = await fetch('actions/save-cats.php', {
      method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ categories })
    });
    const data = await res.json();
    if (data.ok) {
      status.textContent = '✓ Guardado';
      status.style.color = '#16a34a';
      showAlert('ok', '✓ Categorías guardadas. Se aplican en el blog inmediatamente.');
      setTimeout(() => { status.textContent = ''; }, 3000);
    } else {
      status.textContent = '✗ Error';
      status.style.color = '#dc2626';
      showAlert('err', '✗ ' + (data.error || 'Error al guardar.'));
    }
  } catch(e) { showAlert('err','✗ Error de red.'); }
}
</script>
    $views = $stats['posts'][$p['slug']]['views'] ?? 0;
    $id    = htmlspecialchars($p['id']);
    $title = htmlspecialchars($p['title']);
    $tag   = htmlspecialchars($p['tag'] ?? '');
    $col   = $p['tag_color'] ?? '#4A80E4';
    $img   = htmlspecialchars($p['image'] ?? '');
    $st    = ($p['status'] ?? '') === 'published';
    echo "<div class='table-row' id='row-{$id}'>
      <div class='post-info'>
        <div class='post-thumb'><img src='{$img}' alt='' onerror=\"this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2244%22 height=%2233%22><rect width=%2244%22 height=%2233%22 fill=%22%23EBF1FC%22/></svg>'\"></div>
        <div>
          <div class='post-title-t' title='{$title}'>{$title}</div>
          <div class='post-sub'>{$p['date_label']} · {$p['read']}</div>
        </div>
      </div>
      <div><span class='tag-chip' style='background:{$col}18;color:{$col}'>{$tag}</span></div>
      <div><span class='status-badge ".($st?'s-pub':'s-drf')."'>
        <span style='width:5px;height:5px;border-radius:50%;background:currentColor'></span>
        ".($st?'Publicado':'Borrador')."
      </span></div>
      <div style='font-size:13px;font-weight:600;color:var(--sw-blue)'>{$views}</div>
      <div class='action-btns'>
        <button class='btn-edit' onclick=\"editPost('{$id}')\">Editar</button>
        <button class='btn-danger' onclick=\"confirmDelete('{$id}','{$title}')\">
          <svg width='11' height='11' fill='none' stroke='currentColor' viewBox='0 0 24 24' stroke-width='2'>
            <polyline points='3 6 5 6 21 6'/><path stroke-linecap='round' d='M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6'/>
          </svg>
        </button>
      </div>
    </div>";
}
?>
<script>
function editPost(id) {
  window.location.href = 'admin.php?view=editor&id=' + id;
}
</script>