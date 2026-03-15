<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($pageTitle ?? 'Blog — SYNTIweb') ?></title>
  <?php if (!empty($metaDesc)): ?>
  <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>"/>
  <?php endif; ?>

  <!-- Geist Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Quill Snow (solo cuando sea necesario, no afecta si no se usa) -->
  <style>
  /* ── Reset & Base ── */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --sw-blue:    #4A80E4;
    --sw-blue-h:  #3a6fd3;
    --sw-blue-lt: #EBF1FC;
    --sw-navy:    #1a1a1a;
    --sw-bg:      #F8FAFF;
    --sw-surface: #FFFFFF;
    --sw-border:  #E2E8F4;
    --sw-text:    #1a1a1a;
    --sw-muted:   #64748b;
    --sw-glow:    rgba(74,128,228,0.16);
    --sw-font:    'Inter', ui-sans-serif, system-ui, sans-serif;
  }
  html { scroll-behavior: smooth; }
  body {
    font-family: var(--sw-font);
    background: var(--sw-bg);
    color: var(--sw-text);
    -webkit-font-smoothing: antialiased;
  }
  a { text-decoration: none; color: inherit; }

  /* ── Navbar ── */
  .sw-nav {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--sw-border);
  }
  .sw-nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }
  /* Wordmark — fondo claro: SYNTI #1a1a1a · web #4A80E4 */
  .sw-wordmark {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
  }
  .sw-wordmark-icon {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: var(--sw-blue);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .sw-wordmark-text {
    font-size: 17px;
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1;
  }
  .sw-wordmark-text .synti { color: #1a1a1a; }
  .sw-wordmark-text .web   { color: var(--sw-blue); }

  .sw-nav-links {
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .sw-nav-link {
    font-size: 13px;
    font-weight: 500;
    color: var(--sw-muted);
    padding: 6px 12px;
    border-radius: 7px;
    transition: color .15s, background .15s;
  }
  .sw-nav-link:hover,
  .sw-nav-link.active { color: var(--sw-text); background: var(--sw-blue-lt); }
  .sw-nav-link.active { color: var(--sw-blue); font-weight: 600; }

  .sw-nav-actions { display: flex; align-items: center; gap: 8px; }

  .sw-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    border-radius: 8px;
    background: var(--sw-blue);
    color: #fff;
    font-family: var(--sw-font);
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background .15s;
    white-space: nowrap;
  }
  .sw-btn-primary:hover { background: var(--sw-blue-h); color: #fff; }

  .sw-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 14px;
    border-radius: 8px;
    background: transparent;
    color: var(--sw-muted);
    font-family: var(--sw-font);
    font-size: 13px;
    font-weight: 500;
    border: 1px solid var(--sw-border);
    cursor: pointer;
    transition: all .15s;
    white-space: nowrap;
  }
  .sw-btn-ghost:hover { border-color: var(--sw-blue); color: var(--sw-blue); }

  /* Mobile nav toggle */
  .sw-nav-mobile-btn {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--sw-muted);
    padding: 6px;
    border-radius: 7px;
  }
  .sw-nav-mobile-btn:hover { background: var(--sw-blue-lt); color: var(--sw-blue); }
  .sw-nav-mobile-menu {
    display: none;
    position: absolute;
    top: 60px;
    left: 0;
    right: 0;
    background: #fff;
    border-bottom: 1px solid var(--sw-border);
    padding: 12px 24px 20px;
    flex-direction: column;
    gap: 4px;
    z-index: 99;
    box-shadow: 0 8px 24px rgba(0,0,0,.06);
  }
  .sw-nav-mobile-menu.open { display: flex; }
  .sw-nav-mobile-menu .sw-nav-link { display: block; padding: 10px 12px; }

  /* Reveal animations */
  .rv { opacity: 0; transform: translateY(14px); animation: rv .5s ease forwards; }
  .d1{animation-delay:.05s}.d2{animation-delay:.10s}.d3{animation-delay:.15s}
  .d4{animation-delay:.20s}.d5{animation-delay:.25s}
  @keyframes rv { to { opacity:1; transform:translateY(0); } }

  @media(max-width:768px) {
    .sw-nav-links,.sw-nav-actions { display: none; }
    .sw-nav-mobile-btn { display: flex; }
  }
  </style>
</head>
<body>

<nav class="sw-nav">
  <div class="sw-nav-inner">
    <!-- Wordmark — fondo claro: SYNTI navy · web blue -->
    <a href="index.php" class="sw-wordmark">
      <div class="sw-wordmark-icon">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
          <path d="M3 4h10M3 8h6M3 12h8" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
      <span class="sw-wordmark-text"><span class="synti">SYNTI</span><span class="web">web</span></span>
    </a>

    <!-- Desktop links -->
    <div class="sw-nav-links">
      <a href="index.php" class="sw-nav-link <?= ($currentPage??'')==='blog' ? 'active' : '' ?>">Blog</a>
      <a href="https://syntiweb.com#precios" class="sw-nav-link" target="_blank">Productos</a>
      <a href="https://wa.me/584120000000?text=Hola, vengo del blog de SYNTIweb" class="sw-nav-link" target="_blank">Contacto</a>
      <?php if (($currentPage??'') === 'admin'): ?>
      <a href="admin-posts.php" class="sw-nav-link active">Admin</a>
      <?php endif; ?>
    </div>

    <!-- Desktop actions -->
    <div class="sw-nav-actions">
      <a href="https://syntiweb.com#precios" class="sw-btn-ghost" target="_blank">Ver productos</a>
      <a href="https://wa.me/584120000000?text=Hola, quiero saber más sobre SYNTIweb" class="sw-btn-primary" target="_blank">
        Contáctanos
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
        </svg>
      </a>
    </div>

    <!-- Mobile toggle -->
    <button class="sw-nav-mobile-btn" onclick="toggleMobileNav()" aria-label="Menú">
      <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

  <!-- Mobile menu -->
  <div class="sw-nav-mobile-menu" id="sw-mobile-menu">
    <a href="index.php" class="sw-nav-link">Blog</a>
    <a href="https://syntiweb.com#precios" class="sw-nav-link" target="_blank">Productos</a>
    <a href="https://wa.me/584120000000?text=Hola, vengo del blog de SYNTIweb" class="sw-nav-link" target="_blank">Contacto</a>
    <?php if (($currentPage??'') === 'admin'): ?>
    <a href="admin-posts.php" class="sw-nav-link">Admin</a>
    <?php endif; ?>
    <div style="margin-top:8px;padding-top:12px;border-top:1px solid var(--sw-border);display:flex;gap:8px;flex-wrap:wrap">
      <a href="https://syntiweb.com#precios" class="sw-btn-ghost" target="_blank" style="flex:1;justify-content:center">Ver productos</a>
      <a href="https://wa.me/584120000000" class="sw-btn-primary" target="_blank" style="flex:1;justify-content:center">Contáctanos</a>
    </div>
  </div>
</nav>

<script>
function toggleMobileNav() {
  document.getElementById('sw-mobile-menu').classList.toggle('open');
}
</script>