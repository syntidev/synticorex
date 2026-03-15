<!-- ── Footer SYNTIweb — fondo navy: SYNTI blue · web white ── -->
<footer style="background:var(--sw-navy);padding:48px 24px 28px;margin-top:0">
  <div style="max-width:1200px;margin:0 auto">

    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:48px;margin-bottom:36px">

      <!-- Brand col -->
      <div>
        <a href="index.php" style="display:inline-flex;align-items:center;gap:9px;margin-bottom:16px">
          <div style="width:28px;height:28px;border-radius:6px;background:var(--sw-blue);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
              <path d="M3 4h10M3 8h6M3 12h8" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </div>
          <!-- fondo oscuro: SYNTI #4A80E4 · web #FFFFFF -->
          <span style="font-size:17px;font-weight:700;letter-spacing:-.03em">
            <span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span>
          </span>
        </a>
        <p style="font-size:13px;color:rgba(255,255,255,.4);line-height:1.7;max-width:240px">
          Presencia digital para negocios venezolanos. Simple, rápido, sin complicaciones.
        </p>
        <div style="margin-top:20px;display:flex;gap:10px">
          <a href="https://syntiweb.com#precios" target="_blank"
             style="display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;background:var(--sw-blue);color:#fff;font-size:12px;font-weight:600;transition:background .15s"
             onmouseover="this.style.background='var(--sw-blue-h)'" onmouseout="this.style.background='var(--sw-blue)'">
            Ver productos →
          </a>
          <a href="https://wa.me/584120000000?text=Hola, vengo del blog de SYNTIweb" target="_blank"
             style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border-radius:8px;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.6);font-size:12px;font-weight:500;transition:all .15s"
             onmouseover="this.style.borderColor='rgba(255,255,255,.4)';this.style.color='#fff'"
             onmouseout="this.style.borderColor='rgba(255,255,255,.15)';this.style.color='rgba(255,255,255,.6)'">
            WhatsApp
          </a>
        </div>
      </div>

      <!-- Productos -->
      <div>
        <p style="font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.28);margin-bottom:14px">Productos</p>
        <?php foreach(['SYNTIstudio','SYNTIfood','SYNTIcat','Ver precios'] as $item): ?>
        <a href="https://syntiweb.com" target="_blank"
           style="display:block;font-size:13px;color:rgba(255,255,255,.5);padding:4px 0;transition:color .15s"
           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.5)'">
          <?= $item ?>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- Blog -->
      <div>
        <p style="font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.28);margin-bottom:14px">Blog</p>
        <?php
        $footerCats = ['Guía de productos','SEO & Visibilidad','Inteligencia Artificial','Funciones','Noticias'];
        foreach($footerCats as $cat): ?>
        <a href="index.php?cat=<?= urlencode($cat) ?>"
           style="display:block;font-size:13px;color:rgba(255,255,255,.5);padding:4px 0;transition:color .15s"
           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.5)'">
          <?= $cat ?>
        </a>
        <?php endforeach; ?>
        <a href="admin-posts.php"
           style="display:block;font-size:11px;color:rgba(255,255,255,.15);padding:4px 0;margin-top:16px;transition:color .15s"
           onmouseover="this.style.color='rgba(255,255,255,.4)'" onmouseout="this.style.color='rgba(255,255,255,.15)'">
          Admin ↗
        </a>
      </div>

    </div>

    <!-- Bottom bar -->
    <div style="padding-top:24px;border-top:1px solid rgba(255,255,255,.08);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
      <span style="font-size:12px;color:rgba(255,255,255,.28)">&copy; <?= date('Y') ?> SYNTIweb. Todos los derechos reservados.</span>
      <div style="display:flex;align-items:center;gap:16px">
        <a href="https://syntiweb.com" target="_blank" style="font-size:12px;color:rgba(255,255,255,.2);transition:color .15s"
           onmouseover="this.style.color='rgba(255,255,255,.5)'" onmouseout="this.style.color='rgba(255,255,255,.2)'">
          syntiweb.com
        </a>
      </div>
    </div>

  </div>
</footer>

</body>
</html>