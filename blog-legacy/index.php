<?php
require_once __DIR__ . '/includes/tracker.php';
sw_track('index');
$jsonFile = __DIR__ . '/data/posts.json';
$all      = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
$published = array_filter($all, fn($p) => ($p['status'] ?? '') === 'published');
usort($published, fn($a,$b) => strcmp($b['date'], $a['date']));
$published = array_values($published);
$featured  = null;
foreach ($published as $p) { if ($p['featured'] ?? false) { $featured = $p; break; } }
$rest = array_values(array_filter($published, fn($p) => !($p['featured'] ?? false)));
$catsFile  = __DIR__ . '/data/categories.json';
$catsData  = file_exists($catsFile) ? json_decode(file_get_contents($catsFile), true) : [];
$cats      = array_merge(['Todos'], array_column($catsData, 'name'));
$pageTitle   = 'Blog — SYNTIweb';
$metaDesc    = 'Guías, tips y novedades para hacer crecer tu negocio en internet con SYNTIweb.';
$currentPage = 'blog';
include 'includes/header.php';
?>
<style>
/* ── Page-level styles ── */
.hero{
  background:
    radial-gradient(ellipse 65% 55% at 10% 50%,rgba(74,128,228,.09) 0%,transparent 65%),
    radial-gradient(ellipse 45% 60% at 90% 15%,rgba(74,128,228,.06) 0%,transparent 60%),
    var(--sw-bg);
  padding:64px 24px 48px;
  position:relative;
  overflow:hidden;
}
/* Decorative mesh — behind all content */
.hero-mesh{
  position:absolute;
  inset:0;
  pointer-events:none;
  z-index:0;
}
.hero-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1}
.hero-top{text-align:center;margin-bottom:56px}
.hero-badge{display:inline-flex;align-items:center;gap:7px;background:var(--sw-blue-lt);border:1px solid rgba(74,128,228,.22);border-radius:99px;padding:5px 14px;margin-bottom:20px}
.hero-title{font-size:clamp(26px,4vw,44px);font-weight:700;letter-spacing:-.04em;line-height:1.1;color:var(--sw-text);margin-bottom:12px}
.hero-sub{font-size:15px;color:var(--sw-muted);max-width:440px;margin:0 auto 28px;line-height:1.65}
.hero-form{display:flex;gap:8px;max-width:360px;margin:0 auto;flex-wrap:wrap;justify-content:center}
.hero-input{flex:1;min-width:180px;padding:10px 15px;border:1px solid var(--sw-border);border-radius:8px;background:var(--sw-surface);color:var(--sw-text);font-family:var(--sw-font);font-size:13px;outline:none;transition:border-color .15s,box-shadow .15s}
.hero-input:focus{border-color:var(--sw-blue);box-shadow:0 0 0 3px var(--sw-glow)}

/* ── Preline Featured: grid 2 cols, image right, content left ── */
.feat-grid{display:grid;grid-template-columns:1fr 1fr;align-items:center;gap:32px;background:var(--sw-surface);border:1px solid var(--sw-border);border-radius:16px;overflow:hidden;transition:box-shadow .25s,transform .25s}
.feat-grid:hover{box-shadow:0 16px 48px rgba(74,128,228,.12);transform:translateY(-2px)}
.feat-img-col{order:2;position:relative;padding-top:50%;overflow:hidden}
@media(max-width:640px){.feat-img-col{padding-top:100%}}
.feat-img-col img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
.feat-grid:hover .feat-img-col img{transform:scale(1.04)}
.feat-body-col{order:1;padding:48px 44px;display:flex;flex-direction:column;justify-content:center}
.feat-tag{display:inline-flex;align-items:center;font-size:11px;font-weight:600;padding:4px 12px;border-radius:99px;margin-bottom:20px;align-self:flex-start}
.feat-title{font-size:clamp(20px,2.5vw,30px);font-weight:700;letter-spacing:-.03em;line-height:1.2;color:var(--sw-text);margin-bottom:14px;transition:color .15s}
.feat-grid:hover .feat-title{color:var(--sw-blue)}
.feat-excerpt{font-size:14px;color:var(--sw-muted);line-height:1.7;margin-bottom:28px}
.feat-avatar{width:36px;height:36px;border-radius:50%;object-fit:cover;box-shadow:0 0 0 2px var(--sw-blue-lt);flex-shrink:0}
.feat-cta{margin-left:auto;display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--sw-blue);text-decoration:none}
.feat-cta:hover{text-decoration:underline}

/* ── Section divider ── */
.sec-div{display:flex;align-items:center;gap:12px;margin:40px 0 20px}
.sec-div-line{height:1px;flex:1;background:var(--sw-border)}
.sec-div-label{font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--sw-blue)}

/* ── 2-col preline cards ── */
.preline-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px}
.pl-card{display:flex;flex-direction:column;text-decoration:none}
.pl-card-img{position:relative;padding-top:65%;border-radius:12px;overflow:hidden;margin-bottom:18px}
.pl-card-img img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
.pl-card:hover .pl-card-img img{transform:scale(1.05)}
.pl-card-tag{display:inline-block;font-size:11px;font-weight:600;padding:2px 9px;border-radius:99px;margin-bottom:8px}
.pl-card-title{font-size:18px;font-weight:700;letter-spacing:-.02em;color:var(--sw-text);margin-bottom:8px;line-height:1.3;transition:color .15s}
.pl-card:hover .pl-card-title{color:var(--sw-blue)}
.pl-card-excerpt{font-size:13px;color:var(--sw-muted);line-height:1.6;margin-bottom:14px}
.pl-card-cta{display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:500;color:var(--sw-blue);margin-top:auto}
.pl-card-cta:hover{text-decoration:underline}

/* ── Banner shimmer ── */
@keyframes shine{0%{transform:translateX(-120%) skewX(-15deg)}100%{transform:translateX(400%) skewX(-15deg)}}
.banner{background:var(--sw-navy);border-radius:16px;overflow:hidden;position:relative;padding:40px 48px;margin:8px 0 24px;display:grid;grid-template-columns:1fr auto;gap:32px;align-items:center}
.banner::before{content:'';position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse 50% 120% at 80% 50%,rgba(74,128,228,.28) 0%,transparent 65%)}
.banner::after{content:'';position:absolute;top:0;left:0;width:35%;height:100%;background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.10) 50%,transparent 100%);animation:shine 4s ease-in-out infinite;pointer-events:none}
.banner-eyebrow{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--sw-blue);margin-bottom:8px;position:relative;z-index:1}
.banner-title{font-size:22px;font-weight:700;letter-spacing:-.03em;color:#fff;line-height:1.2;margin-bottom:6px;position:relative;z-index:1}
.banner-sub{font-size:13px;color:rgba(255,255,255,.5);line-height:1.55;position:relative;z-index:1}
.banner-actions{display:flex;flex-direction:column;gap:8px;position:relative;z-index:1;flex-shrink:0}
.banner-btn-main{display:block;padding:11px 22px;border-radius:9px;background:var(--sw-blue);color:#fff;border:none;cursor:pointer;font-family:var(--sw-font);font-size:13px;font-weight:600;transition:background .15s;white-space:nowrap;text-align:center}
.banner-btn-main:hover{background:var(--sw-blue-h)}
.banner-btn-sec{display:block;padding:9px 22px;border-radius:9px;background:transparent;color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.2);cursor:pointer;font-family:var(--sw-font);font-size:12px;font-weight:500;transition:border-color .15s,color .15s;white-space:nowrap;text-align:center}
.banner-btn-sec:hover{border-color:rgba(255,255,255,.5);color:#fff}

/* ── 3-col small cards ── */
.sm-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.sm-card{border-radius:12px;overflow:hidden;background:var(--sw-surface);border:1px solid var(--sw-border);transition:box-shadow .2s,transform .2s;text-decoration:none;display:block}
.sm-card:hover{box-shadow:0 6px 24px rgba(74,128,228,.10);transform:translateY(-2px)}
.sm-card-img{position:relative;padding-top:55%;overflow:hidden}
.sm-card-img img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
.sm-card:hover .sm-card-img img{transform:scale(1.05)}
.sm-card-body{padding:16px}
.sm-card-tag{font-size:10px;font-weight:600;padding:2px 8px;border-radius:99px;display:inline-block;margin-bottom:7px}
.sm-card-title{font-size:14px;font-weight:700;letter-spacing:-.01em;color:var(--sw-text);line-height:1.35;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color .15s}
.sm-card:hover .sm-card-title{color:var(--sw-blue)}
.sm-card-meta{font-size:11px;color:var(--sw-muted);margin-top:10px;padding-top:10px;border-top:1px solid var(--sw-border);display:flex;gap:6px;align-items:center}

/* ── Filters ── */
.filters{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:28px}
.filter-group{display:flex;gap:6px;flex-wrap:wrap}
.filter-btn{font-family:var(--sw-font);font-size:12px;font-weight:500;padding:6px 14px;border-radius:99px;border:1px solid var(--sw-border);background:var(--sw-surface);color:var(--sw-muted);cursor:pointer;transition:all .15s}
.filter-btn:hover{border-color:var(--sw-blue);color:var(--sw-blue)}
.filter-btn.active{background:var(--sw-blue);border-color:var(--sw-blue);color:#fff}

/* ── Products grid ── */
.products-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.prod-card{border-radius:14px;padding:24px;border:1px solid var(--sw-border);background:var(--sw-surface);transition:box-shadow .2s,border-color .2s;text-decoration:none;display:block}
.prod-card:hover{box-shadow:0 8px 28px rgba(74,128,228,.10);border-color:rgba(74,128,228,.3)}
.prod-icon{width:40px;height:40px;border-radius:10px;background:var(--sw-blue-lt);display:flex;align-items:center;justify-content:center;margin-bottom:14px;font-size:20px}
.prod-name{font-size:15px;font-weight:700;letter-spacing:-.02em;color:var(--sw-text);margin-bottom:6px}
.prod-desc{font-size:13px;color:var(--sw-muted);line-height:1.6;margin-bottom:16px}
.prod-link{font-size:12px;font-weight:600;color:var(--sw-blue);display:inline-flex;align-items:center;gap:4px}
.prod-link:hover{text-decoration:underline}

/* ── Newsletter block ── */
.newsletter-block{margin-top:48px;background:var(--sw-blue-lt);border:1px solid rgba(74,128,228,.2);border-radius:16px;padding:40px 48px;display:grid;grid-template-columns:1fr auto;gap:32px;align-items:center}

/* ── Responsive ── */
@media(max-width:900px){
  .feat-grid{grid-template-columns:1fr}
  .feat-img-col{order:1;padding-top:55%}
  .feat-body-col{order:2;padding:28px 24px}
  .preline-grid{grid-template-columns:1fr}
  .sm-grid{grid-template-columns:1fr 1fr}
  .banner{grid-template-columns:1fr;padding:32px 24px}
  .products-grid{grid-template-columns:1fr}
  .newsletter-block{grid-template-columns:1fr;padding:28px 24px}
}
@media(max-width:560px){
  .sm-grid{grid-template-columns:1fr}
  .hero{padding:48px 24px 36px}
}
</style>

<!-- ── Hero section ── -->
<section class="hero">

  <!-- ── Decorative mesh: hexágonos flotantes ── -->
  <svg class="hero-mesh" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"
       preserveAspectRatio="xMidYMid slice" aria-hidden="true">

    <g fill="none" stroke="#4A80E4" stroke-width="1.2">
      <!-- Top-left cluster — grande y mediano -->
      <polygon points="72,18  118,18  141,58  118,98  72,98  49,58"   opacity="0.25"/>
      <polygon points="18,90  48,90   63,116  48,142  18,142  3,116"   opacity="0.18"/>

      <!-- Left-center — mediano -->
      <polygon points="55,210  95,210  115,244  95,278  55,278  35,244" opacity="0.20"/>

      <!-- Top-center — pequeño discreto -->
      <polygon points="520,8  548,8  562,32  548,56  520,56  506,32"   opacity="0.18"/>

      <!-- Top-right — grande -->
      <polygon points="1180,12  1240,12  1270,64  1240,116 1180,116 1150,64" opacity="0.22"/>

      <!-- Right-center — mediano -->
      <polygon points="1340,80  1390,80  1415,122  1390,164  1340,164  1315,122" opacity="0.20"/>

      <!-- Far right — pequeño -->
      <polygon points="1480,180 1510,180 1525,206 1510,232 1480,232 1465,206" opacity="0.16"/>

      <!-- Bottom-left — grande -->
      <polygon points="100,330  160,330  190,384  160,438  100,438  70,384" opacity="0.18"/>

      <!-- Bottom-center-right — mediano -->
      <polygon points="820,360  865,360  888,398  865,436  820,436  797,398" opacity="0.16"/>

      <!-- Center ghost — muy grande, apenas visible -->
      <polygon points="560,140  660,140  710,224  660,308  560,308  510,224" opacity="0.06" stroke-width="1.4"/>
    </g>
  </svg>
  <!-- ── /mesh ── -->

  <div class="hero-inner">

    <!-- Top copy -->
    <div class="hero-top rv d1">
      <div class="hero-badge">
        <span style="width:6px;height:6px;border-radius:50%;background:var(--sw-blue);display:inline-block"></span>
        <span style="font-size:11px;font-weight:600;color:var(--sw-blue);letter-spacing:.05em;text-transform:uppercase">Blog de SYNTIweb</span>
      </div>
      <h1 class="hero-title">Aprende a sacarle más partido<br>a tu presencia digital</h1>
      <p class="hero-sub">Guías, tips y novedades sobre los productos SYNTIweb.<br>Escrito para dueños de negocios, no para técnicos.</p>
      <div class="hero-form">
        <input type="email" class="hero-input" placeholder="tu@email.com">
        <button class="sw-btn-primary">Suscribirme →</button>
      </div>
    </div>

    <!-- ── Featured post — Preline grid layout ── -->
    <?php if ($featured): ?>
    <div class="rv d2" id="featured-wrap">
      <div class="sec-div"><div class="sec-div-line"></div><span class="sec-div-label">Artículo destacado</span><div class="sec-div-line"></div></div>
      <a href="post.php?slug=<?= htmlspecialchars($featured['slug']) ?>" class="feat-grid" data-cat="<?= htmlspecialchars($featured['tag']) ?>">
        <!-- Image col — order:2 (right) -->
        <div class="feat-img-col">
          <img src="<?= htmlspecialchars($featured['image']) ?>" alt="<?= htmlspecialchars($featured['title']) ?>">
        </div>
        <!-- Content col — order:1 (left) -->
        <div class="feat-body-col">
          <span class="feat-tag" style="background:<?= $featured['tag_color'] ?>18;color:<?= $featured['tag_color'] ?>">
            <?= htmlspecialchars($featured['tag']) ?>
          </span>
          <h2 class="feat-title"><?= htmlspecialchars($featured['title']) ?></h2>
          <p class="feat-excerpt"><?= htmlspecialchars($featured['excerpt']) ?></p>
          <div style="display:flex;align-items:center;gap:10px">
            <img src="<?= htmlspecialchars($featured['avatar']) ?>" alt="" class="feat-avatar">
            <div>
              <div style="font-size:13px;font-weight:600;color:var(--sw-text)"><?= htmlspecialchars($featured['author']) ?></div>
              <div style="font-size:11px;color:var(--sw-muted)"><?= $featured['date_label'] ?> · <?= $featured['read'] ?> lectura</div>
            </div>
            <span class="feat-cta">
              Leer artículo
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4-4 4M3 12h18"/>
              </svg>
            </span>
          </div>
        </div>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- ── Main content ── -->
<main style="max-width:1200px;margin:0 auto;padding:0 24px 80px" id="cats">

  <!-- Filters -->
  <div class="filters rv d1" style="margin-top:40px">
    <div class="filter-group" id="filter-group">
      <?php foreach ($cats as $i => $cat): ?>
      <button class="filter-btn <?= $i === 0 ? 'active' : '' ?>" onclick="filterAll(this,'<?= $cat ?>')" data-cat="<?= $cat ?>"><?= $cat ?></button>
      <?php endforeach; ?>
    </div>
    <span style="font-size:12px;color:var(--sw-muted)" id="post-count"><?= count($published) ?> artículos</span>
  </div>

  <?php
  $top2   = array_slice($rest, 0, 2);
  $bottom = array_slice($rest, 2);
  ?>

  <!-- 2-col cards -->
  <?php if (!empty($top2)): ?>
  <div class="preline-grid rv d2" id="top2-grid">
    <?php foreach ($top2 as $post): ?>
    <a href="post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="pl-card" data-cat="<?= htmlspecialchars($post['tag']) ?>">
      <div class="pl-card-img">
        <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
      </div>
      <span class="pl-card-tag" style="background:<?= $post['tag_color'] ?>18;color:<?= $post['tag_color'] ?>"><?= htmlspecialchars($post['tag']) ?></span>
      <h3 class="pl-card-title"><?= htmlspecialchars($post['title']) ?></h3>
      <p class="pl-card-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
      <span class="pl-card-cta">
        Leer más
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
      </span>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Banner shimmer -->
  <div class="banner rv d3">
    <div>
      <p class="banner-eyebrow">¿Todavía sin página?</p>
      <h3 class="banner-title">Tu negocio puede estar online<br>esta semana. Desde $99/año.</h3>
      <p class="banner-sub">Sin agencias. Sin complicaciones. Tú nos dices qué vendes,<br>nosotros hacemos todo lo demás en 48 horas.</p>
    </div>
    <div class="banner-actions">
      <a href="https://syntiweb.com#precios" class="banner-btn-main" target="_blank">Ver productos →</a>
      <a href="https://wa.me/584120000000?text=Quiero recomendar SYNTIweb" class="banner-btn-sec" target="_blank">Recomienda a un amigo</a>
      <a href="https://syntiweb.com#precios" class="banner-btn-sec" target="_blank">Actualiza tu plan</a>
    </div>
  </div>

  <!-- 3-col small cards -->
  <?php if (!empty($bottom)): ?>
  <div class="sm-grid" id="bottom-grid">
    <?php foreach ($bottom as $i => $post): ?>
    <a href="post.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="sm-card rv d<?= ($i % 3) + 1 ?>" data-cat="<?= htmlspecialchars($post['tag']) ?>">
      <div class="sm-card-img">
        <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
      </div>
      <div class="sm-card-body">
        <span class="sm-card-tag" style="background:<?= $post['tag_color'] ?>18;color:<?= $post['tag_color'] ?>"><?= htmlspecialchars($post['tag']) ?></span>
        <h3 class="sm-card-title"><?= htmlspecialchars($post['title']) ?></h3>
        <div class="sm-card-meta">
          <img src="<?= htmlspecialchars($post['avatar']) ?>" style="width:20px;height:20px;border-radius:50%;object-fit:cover" alt="">
          <span style="flex:1"><?= htmlspecialchars($post['author']) ?></span>
          <span><?= $post['read'] ?></span>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Products section -->
  <div style="margin-top:72px">
    <div class="sec-div rv"><div class="sec-div-line"></div><span class="sec-div-label">Nuestros productos</span><div class="sec-div-line"></div></div>
    <p style="text-align:center;font-size:15px;color:var(--sw-muted);margin-bottom:28px;max-width:480px;margin-left:auto;margin-right:auto;line-height:1.6">Tres herramientas. Un solo objetivo: que vendas más sin volverte loco.</p>
    <div class="products-grid rv">
      <?php foreach([
        ['🎨','SYNTIstudio','studio','Marcas, servicios y freelancers. Tu presencia profesional lista en 48 horas.'],
        ['🍕','SYNTIfood',  'food',  'Restaurantes y negocios de comida. Tu menú digital con tasa BCV automática.'],
        ['🛍','SYNTIcat',   'cat',   'Comercios y tiendas. Tu catálogo visual con botón directo a WhatsApp.'],
      ] as [$ico, $name, $slug, $desc]): ?>
      <a href="https://syntiweb.com/<?= $slug ?>" class="prod-card" target="_blank">
        <div class="prod-icon"><?= $ico ?></div>
        <div class="prod-name"><?= $name ?></div>
        <p class="prod-desc"><?= $desc ?></p>
        <span class="prod-link">Ver producto <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Newsletter block -->
  <div class="newsletter-block rv">
    <div>
      <p style="font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--sw-blue);margin-bottom:8px">Newsletter</p>
      <h3 style="font-size:22px;font-weight:700;letter-spacing:-.03em;color:var(--sw-text);margin-bottom:6px">Nuevos artículos, directo a tu correo.</h3>
      <p style="font-size:13px;color:var(--sw-muted)">Sin spam. Solo cuando publicamos algo que vale la pena leer.</p>
    </div>
    <div style="display:flex;flex-direction:column;gap:8px;min-width:260px">
      <input type="email" class="hero-input" placeholder="tu@email.com">
      <button class="sw-btn-primary" style="justify-content:center;padding:10px 20px">Suscribirme →</button>
    </div>
  </div>

</main>

<?php include 'includes/footer.php'; ?>

<script>
function filterAll(btn, cat) {
  // Botones activos
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  // ── Featured wrap ──
  const featWrap = document.getElementById('featured-wrap');
  if (featWrap) {
    const featCard = featWrap.querySelector('[data-cat]');
    const show = cat === 'Todos' || (featCard && featCard.dataset.cat === cat);
    featWrap.style.display = show ? '' : 'none';
  }

  // ── Todas las cards con data-cat ──
  const cards = document.querySelectorAll('#top2-grid [data-cat], #bottom-grid [data-cat]');
  cards.forEach(el => {
    el.style.display = (cat === 'Todos' || el.dataset.cat === cat) ? '' : 'none';
  });

  // ── Colapsar grids vacíos ──
  ['top2-grid','bottom-grid'].forEach(id => {
    const grid = document.getElementById(id);
    if (!grid) return;
    const visible = grid.querySelectorAll('[data-cat]:not([style*="display: none"])').length;
    grid.style.display = visible > 0 ? '' : 'none';
  });

  // ── Contador ──
  const allCards = document.querySelectorAll('[data-cat]');
  let v = 0;
  allCards.forEach(el => { if (el.style.display !== 'none') v++; });
  document.getElementById('post-count').textContent = v + ' artículos';
}
</script>