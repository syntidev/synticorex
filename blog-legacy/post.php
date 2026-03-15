<?php
require_once __DIR__ . '/includes/tracker.php';
$jsonFile = __DIR__ . '/data/posts.json';
$all      = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
$slug     = $_GET['slug'] ?? '';
$post     = null;
foreach ($all as $p) {
  if ($p['slug'] === $slug && ($p['status'] ?? '') === 'published') { $post = $p; break; }
}
if (!$post) { header('Location: index.php'); exit; }
// Registrar visita a este post
sw_track('post', $slug);

$related = array_values(array_filter($all, fn($p) =>
  $p['slug'] !== $slug && ($p['status'] ?? '') === 'published' && $p['tag'] === $post['tag']
));
if (count($related) < 2) {
  $other   = array_values(array_filter($all, fn($p) => $p['slug'] !== $slug && ($p['status'] ?? '') === 'published'));
  $related = array_slice($other, 0, 3);
}
$related     = array_slice($related, 0, 3);

// ── Siguiente post (por fecha, publicados, excluyendo el actual) ──
$pubAll  = array_values(array_filter($all, fn($p) => ($p['status']??'') === 'published'));
usort($pubAll, fn($a,$b) => strcmp($b['date'], $a['date'])); // más reciente primero
$nextPost = null;
$currentIndex = null;
foreach ($pubAll as $i => $p) {
  if ($p['slug'] === $slug) { $currentIndex = $i; break; }
}
if ($currentIndex !== null) {
  // Siguiente = el post más antiguo inmediato (índice + 1)
  // Si estamos en el último, volvemos al primero (loop circular)
  $nextPost = $pubAll[($currentIndex + 1) % count($pubAll)] ?? null;
  // Asegurarse que no sea el mismo
  if ($nextPost && $nextPost['slug'] === $slug) $nextPost = null;
}
$pageTitle   = ($post['meta_title'] ?: $post['title']) . ' — SYNTIweb Blog';
$metaDesc    = $post['meta_description'] ?: $post['excerpt'];
$currentPage = 'blog';
include 'includes/header.php';
?>
<style>
.wrap{max-width:1200px;margin:0 auto;padding:40px 24px 80px}
.back-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--sw-muted);margin-bottom:32px;transition:color .15s}
.back-link:hover{color:var(--sw-blue)}
.layout{display:grid;grid-template-columns:1fr 300px;gap:48px;align-items:start}
.article-tag{display:inline-flex;font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;margin-bottom:16px}
.article-title{font-size:clamp(22px,3.5vw,36px);font-weight:700;letter-spacing:-.04em;line-height:1.15;color:var(--sw-text);margin-bottom:20px}
.article-meta{display:flex;align-items:center;gap:12px;padding-bottom:24px;border-bottom:1px solid var(--sw-border);margin-bottom:28px}
.article-avatar{width:40px;height:40px;border-radius:50%;object-fit:cover;box-shadow:0 0 0 2px var(--sw-blue-lt)}
.article-hero{border-radius:14px;overflow:hidden;aspect-ratio:16/7;margin-bottom:32px}
.article-hero img{width:100%;height:100%;object-fit:cover;display:block}
.prose{font-size:16px;color:var(--sw-text);line-height:1.8}
.prose h2{font-size:21px;font-weight:700;letter-spacing:-.03em;margin:36px 0 14px;padding-top:8px;border-top:1px solid var(--sw-border)}
.prose p{margin-bottom:18px}
.prose ul,.prose ol{margin:0 0 18px 20px}
.prose li{margin-bottom:8px}
.prose blockquote{border-left:3px solid var(--sw-blue);margin:28px 0;padding:16px 20px;background:var(--sw-blue-lt);border-radius:0 10px 10px 0;font-size:15px;font-style:italic}
.prose a{color:var(--sw-blue);text-decoration:underline}
.prose strong{font-weight:700}
.tag-chip{display:inline-flex;font-size:12px;font-weight:500;padding:5px 12px;border-radius:99px;border:1px solid var(--sw-border);color:var(--sw-muted);background:var(--sw-surface);transition:all .15s;margin:3px}
.tag-chip:hover{border-color:var(--sw-blue);color:var(--sw-blue)}

/* ── Seguir leyendo ── */
.next-post{display:grid;grid-template-columns:1fr 340px;border-radius:16px;overflow:hidden;border:1px solid var(--sw-border);background:var(--sw-surface);margin-top:40px;transition:box-shadow .25s,transform .2s;text-decoration:none}
.next-post:hover{box-shadow:0 12px 40px rgba(74,128,228,.13);transform:translateY(-2px)}
.next-post-body{padding:36px 36px;display:flex;flex-direction:column;justify-content:center}
.next-post-eyebrow{display:flex;align-items:center;gap:7px;font-size:11px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--sw-blue);margin-bottom:14px}
.next-post-eyebrow svg{opacity:.7}
.next-post-tag{display:inline-block;font-size:11px;font-weight:600;padding:2px 9px;border-radius:99px;margin-bottom:12px;align-self:flex-start}
.next-post-title{font-size:clamp(17px,2vw,22px);font-weight:700;letter-spacing:-.03em;line-height:1.25;color:var(--sw-text);margin-bottom:12px;transition:color .2s}
.next-post:hover .next-post-title{color:var(--sw-blue)}
.next-post-excerpt{font-size:13px;color:var(--sw-muted);line-height:1.65;margin-bottom:22px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.next-post-cta{display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:var(--sw-blue)}
.next-post-cta svg{transition:transform .2s}
.next-post:hover .next-post-cta svg{transform:translateX(4px)}
.next-post-img{overflow:hidden;position:relative}
.next-post-img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease}
.next-post:hover .next-post-img img{transform:scale(1.05)}
@media(max-width:720px){
  .next-post{grid-template-columns:1fr}
  .next-post-img{height:200px}
  .next-post-body{padding:24px}
}
@keyframes shine{0%{transform:translateX(-120%) skewX(-15deg)}100%{transform:translateX(400%) skewX(-15deg)}}
.ctx-cta{margin-top:40px;background:var(--sw-navy);border-radius:14px;padding:28px 32px;position:relative;overflow:hidden}
.ctx-cta::after{content:'';position:absolute;top:0;left:0;width:40%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.08),transparent);animation:shine 4s ease-in-out infinite;pointer-events:none}
.ctx-cta-label{font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--sw-blue);margin-bottom:8px;position:relative;z-index:1}
.ctx-cta-title{font-size:18px;font-weight:700;color:#fff;letter-spacing:-.02em;margin-bottom:6px;position:relative;z-index:1}
.ctx-cta-sub{font-size:13px;color:rgba(255,255,255,.5);margin-bottom:18px;line-height:1.5;position:relative;z-index:1}
.ctx-cta-btns{display:flex;gap:8px;flex-wrap:wrap;position:relative;z-index:1}
.cta-btn-p{display:inline-block;padding:10px 20px;border-radius:8px;background:var(--sw-blue);color:#fff;font-family:var(--sw-font);font-size:13px;font-weight:600;border:none;cursor:pointer;transition:background .15s;text-decoration:none}
.cta-btn-p:hover{background:var(--sw-blue-h)}
.cta-btn-s{display:inline-block;padding:10px 20px;border-radius:8px;background:transparent;color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.2);font-family:var(--sw-font);font-size:13px;cursor:pointer;transition:all .15s;text-decoration:none}
.cta-btn-s:hover{border-color:rgba(255,255,255,.5);color:#fff}
.sidebar{position:sticky;top:80px}
.sb-card{background:var(--sw-surface);border:1px solid var(--sw-border);border-radius:12px;overflow:hidden;margin-bottom:16px}
.sb-card-head{padding:14px 16px;border-bottom:1px solid var(--sw-border);background:#fafbff;font-size:11px;font-weight:600;color:var(--sw-muted);letter-spacing:.04em;text-transform:uppercase}
.sb-card-body{padding:16px}
.rel-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--sw-border);text-decoration:none}
.rel-item:last-child{border-bottom:none}
.rel-thumb{width:68px;height:52px;border-radius:8px;overflow:hidden;flex-shrink:0}
.rel-thumb img{width:100%;height:100%;object-fit:cover}
.rel-title{font-size:13px;font-weight:600;color:var(--sw-text);line-height:1.35;transition:color .15s}
.rel-item:hover .rel-title{color:var(--sw-blue)}
.share-btn{display:flex;align-items:center;gap:8px;width:100%;padding:9px 12px;border-radius:8px;border:1px solid var(--sw-border);background:var(--sw-surface);font-family:var(--sw-font);font-size:12px;font-weight:500;color:var(--sw-muted);cursor:pointer;transition:all .15s;margin-bottom:6px;text-decoration:none}
.share-btn:last-child{margin-bottom:0}
.share-btn:hover{border-color:var(--sw-blue);color:var(--sw-blue);background:var(--sw-blue-lt)}
@media(max-width:860px){.layout{grid-template-columns:1fr}.sidebar{position:static}}
</style>

<div class="wrap">
  <a href="index.php" class="back-link">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
    </svg>
    Volver al blog
  </a>

  <div class="layout">
    <article>
      <span class="article-tag" style="background:<?= $post['tag_color'] ?>18;color:<?= $post['tag_color'] ?>"><?= htmlspecialchars($post['tag']) ?></span>
      <h1 class="article-title"><?= htmlspecialchars($post['title']) ?></h1>

      <div class="article-meta">
        <img src="<?= htmlspecialchars($post['avatar']) ?>" alt="" class="article-avatar">
        <div>
          <div style="font-size:13px;font-weight:600;color:var(--sw-text)"><?= htmlspecialchars($post['author']) ?></div>
          <div style="font-size:11px;color:var(--sw-muted)"><?= $post['date_label'] ?> · <?= $post['read'] ?> de lectura</div>
        </div>
        <div style="margin-left:auto;display:flex;align-items:center;gap:14px">
          <button style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--sw-muted);background:none;border:none;cursor:pointer;font-family:var(--sw-font);transition:color .15s"
                  onmouseover="this.style.color='var(--sw-blue)'" onmouseout="this.style.color='var(--sw-muted)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
            </svg> Útil
          </button>
          <button onclick="copyLink()" style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--sw-muted);background:none;border:none;cursor:pointer;font-family:var(--sw-font);transition:color .15s"
                  onmouseover="this.style.color='var(--sw-blue)'" onmouseout="this.style.color='var(--sw-muted)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
              <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
            </svg>
            <span id="copy-label">Copiar link</span>
          </button>
        </div>
      </div>

      <div class="article-hero">
        <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
      </div>

      <div class="prose"><?= $post['content'] ?></div>

      <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--sw-border)">
        <?php foreach ($post['tags'] ?? [] as $t): ?>
        <span class="tag-chip">#<?= htmlspecialchars($t) ?></span>
        <?php endforeach; ?>
      </div>

      <!-- ── Seguir leyendo ── -->
      <?php if ($nextPost): ?>
      <a href="post.php?slug=<?= htmlspecialchars($nextPost['slug']) ?>" class="next-post">
        <div class="next-post-body">
          <div class="next-post-eyebrow">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12M6 12h12"/>
            </svg>
            Seguir leyendo
          </div>
          <span class="next-post-tag" style="background:<?= $nextPost['tag_color'] ?>18;color:<?= $nextPost['tag_color'] ?>">
            <?= htmlspecialchars($nextPost['tag']) ?>
          </span>
          <h3 class="next-post-title"><?= htmlspecialchars($nextPost['title']) ?></h3>
          <p class="next-post-excerpt"><?= htmlspecialchars($nextPost['excerpt']) ?></p>
          <span class="next-post-cta">
            Leer artículo
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4-4 4M3 12h18"/>
            </svg>
          </span>
        </div>
        <div class="next-post-img">
          <img src="<?= htmlspecialchars($nextPost['image']) ?>" alt="<?= htmlspecialchars($nextPost['title']) ?>">
        </div>
      </a>
      <?php endif; ?>

      <!-- Contextual CTA shimmer -->
      <div class="ctx-cta">
        <?php
        $ctaMap = [
          'Guía de productos'    => ['¿Cuál producto es para ti?',                        'Elige el plan correcto y empieza esta semana.',                    'Ver SYNTIstudio','Ver SYNTIfood'],
          'SEO & Visibilidad'    => ['¿Quieres aparecer en Google?',                       'SYNTIweb configura tu SEO automáticamente desde el día uno.',      'Empezar ahora','Hablar con alguien'],
          'Inteligencia Artificial'=>['Sé de los primeros en probarlo',                    'El asistente IA llega primero al Plan Visión. Anótate.',           'Quiero acceso beta','Ver Plan Visión'],
          'Funciones'            => ['¿Tu negocio ya tiene QR dinámico?',                  'Incluido en todos los planes. Listo en 48 horas.',                 'Ver planes','Hablar por WhatsApp'],
        ];
        $cta = $ctaMap[$post['tag']] ?? ['¿Listo para publicar tu negocio?','Empieza esta semana desde $99/año. Sin agencias.','Ver productos','Contáctanos'];
        ?>
        <p class="ctx-cta-label">Siguiente paso</p>
        <h3 class="ctx-cta-title"><?= $cta[0] ?></h3>
        <p class="ctx-cta-sub"><?= $cta[1] ?></p>
        <div class="ctx-cta-btns">
          <a href="https://syntiweb.com#precios" class="cta-btn-p" target="_blank"><?= $cta[2] ?> →</a>
          <a href="https://wa.me/584120000000?text=Hola, vengo del blog de SYNTIweb" class="cta-btn-s" target="_blank"><?= $cta[3] ?></a>
        </div>
      </div>
    </article>

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sb-card">
        <div class="sb-card-head">Sobre el autor</div>
        <div class="sb-card-body" style="display:flex;align-items:center;gap:12px">
          <img src="<?= htmlspecialchars($post['avatar']) ?>" style="width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0;box-shadow:0 0 0 2px var(--sw-blue-lt)" alt="">
          <div>
            <div style="font-size:13px;font-weight:600;color:var(--sw-text)"><?= htmlspecialchars($post['author']) ?></div>
            <div style="font-size:12px;color:var(--sw-muted);line-height:1.5;margin-top:2px">Equipo editorial de SYNTIweb. Escribimos para dueños de negocios, no para técnicos.</div>
          </div>
        </div>
      </div>

      <?php if (!empty($related)): ?>
      <div class="sb-card">
        <div class="sb-card-head">Más artículos</div>
        <div class="sb-card-body" style="padding:8px 16px">
          <?php foreach ($related as $r): ?>
          <a href="post.php?slug=<?= htmlspecialchars($r['slug']) ?>" class="rel-item">
            <div style="flex:1">
              <span class="rel-title"><?= htmlspecialchars($r['title']) ?></span>
              <div style="font-size:11px;color:var(--sw-muted);margin-top:4px"><?= $r['read'] ?> lectura</div>
            </div>
            <div class="rel-thumb"><img src="<?= htmlspecialchars($r['image']) ?>" alt=""></div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="sb-card">
        <div class="sb-card-head">Compartir</div>
        <div class="sb-card-body">
          <button onclick="copyLink()" class="share-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
              <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
            </svg>
            Copiar enlace
          </button>
          <a href="https://wa.me/?text=<?= urlencode($post['title'] . ' ' . ($_SERVER['REQUEST_URI'] ?? '')) ?>"
             target="_blank" class="share-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#25D366">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
            </svg>
            Compartir por WhatsApp
          </a>
        </div>
      </div>

      <div style="background:var(--sw-blue);border-radius:12px;padding:22px;text-align:center">
        <div style="font-size:24px;margin-bottom:10px">🚀</div>
        <p style="font-size:14px;font-weight:700;color:#fff;margin-bottom:6px;letter-spacing:-.02em">¿Tu negocio ya está online?</p>
        <p style="font-size:12px;color:rgba(255,255,255,.7);margin-bottom:16px;line-height:1.5">Desde $99/año. Listo en 48 horas.</p>
        <a href="https://syntiweb.com#precios" style="display:block;padding:9px;border-radius:8px;background:#fff;color:var(--sw-blue);font-family:var(--sw-font);font-size:13px;font-weight:600" target="_blank">Ver planes →</a>
      </div>
    </aside>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
function copyLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const el = document.getElementById('copy-label');
    if (el) { el.textContent = '¡Copiado!'; setTimeout(() => el.textContent = 'Copiar link', 2000); }
  });
}
</script>