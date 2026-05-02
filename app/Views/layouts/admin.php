<?php
/** @var string $content */
/** @var string|null $title */
$user = \App\Core\Auth::user();
$role = is_array($user) ? (string)($user['role'] ?? '') : '';
$isSuper = ($role === 'super_admin');
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH) ?: '/admin';
$isActive = static function (string $prefix) use ($uri): string {
    return str_starts_with($uri, $prefix) ? 'is-active' : '';
};
$isDashboardHome = (bool)preg_match('#/admin/?$#', rtrim($uri, '/'));
$adminSiteTheme = \App\Core\SiteTheme::normalize(\App\Models\Setting::get('site_theme', \App\Core\SiteTheme::DEFAULT) ?? \App\Core\SiteTheme::DEFAULT);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/assets/app.css', ENT_QUOTES, 'UTF-8') ?>">
</head>
<body class="admin-shell admin-light site-theme-<?= htmlspecialchars($adminSiteTheme, ENT_QUOTES, 'UTF-8') ?>">
  <div class="admin-layout">
    <!-- Sidebar (desktop) -->
    <aside class="admin-sidebar d-none d-lg-flex">
      <div class="admin-sidebar-inner">
        <a class="admin-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin', ENT_QUOTES, 'UTF-8') ?>">
          <span class="admin-brand-icon"><i class="bi bi-shield-lock"></i></span>
          <span class="admin-brand-text">Dolice Admin</span>
        </a>

        <?php require __DIR__ . '/partials/admin_sidebar_nav.php'; ?>

        <div class="admin-sidebar-footer">
          <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/logout', ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
          </form>
        </div>
      </div>
    </aside>

    <!-- Main area -->
    <div class="admin-main">
      <!-- Topbar -->
      <header class="admin-topbar">
        <div class="container-fluid px-3 px-lg-4">
          <div class="d-flex align-items-center justify-content-between gap-2 py-2">
            <div class="d-flex align-items-center gap-2">
              <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile" aria-controls="adminSidebarMobile">
                <i class="bi bi-list"></i>
              </button>
              <div class="admin-topbar-title">
                <div class="admin-topbar-kicker">Administration</div>
                <div class="admin-topbar-page"><?= htmlspecialchars($title ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <a class="btn btn-sm btn-light border" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">
                <i class="bi bi-globe2 me-1"></i>Site
              </a>
              <form class="d-none d-md-block" method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/logout', ENT_QUOTES, 'UTF-8') ?>">
                <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-box-arrow-right me-1"></i>Déconnexion</button>
              </form>
            </div>
          </div>
        </div>
      </header>

      <main class="admin-content container-fluid px-3 px-lg-4 py-4">
        <details class="admin-editor-guide card">
          <summary class="admin-editor-guide-summary"><i class="bi bi-journal-text me-2 admin-editor-guide-ic"></i>Guide pour les mises à jour du site</summary>
          <div class="admin-editor-guide-body">
            <p><strong>Ordre conseillé.</strong> Commencez par les <strong>demandes</strong> (devis et messages), puis mettez à jour le <strong>contenu public</strong> (services, réalisations, blog, etc.). Vérifiez enfin l’<strong>identité du site</strong> (accueil, fiche entreprise, pages légales) et les <strong>paramètres</strong> si vous changez d’adresse ou de thème.</p>
            <p><strong>Publication.</strong> Les listes utilisent souvent le statut <em>Publié</em> / <em>Brouillon</em> : seul le contenu publié est visible sur le site. Après enregistrement, ouvrez <strong>Voir le site</strong> (menu ou bouton en haut) pour contrôler le rendu.</p>
            <p><strong>Aide au survol.</strong> Dans le menu latéral, laissez le curseur sur un libellé pour afficher une brève explication de la section.</p>
            <p><strong>Pages statiques.</strong> L’entrée « Pages » n’apparaît plus dans le menu ; l’adresse <code>/admin/pages</code> reste utilisable si votre rôle inclut la permission <code>pages.view</code>.</p>
          </div>
        </details>
        <?= $content ?>
      </main>
    </div>
  </div>

  <!-- Sidebar (mobile) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebarMobile" aria-labelledby="adminSidebarMobileLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="adminSidebarMobileLabel"><i class="bi bi-shield-lock me-2"></i>Dolice Admin</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
      <?php require __DIR__ . '/partials/admin_sidebar_nav.php'; ?>

      <hr>
      <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/logout', ENT_QUOTES, 'UTF-8') ?>">
        <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (!window.bootstrap || typeof window.bootstrap.Tooltip !== 'function') return;
      document.querySelectorAll('.admin-nav .admin-nav-link[data-bs-title]').forEach(function (el) {
        try {
          window.bootstrap.Tooltip.getOrCreateInstance(el, { container: 'body', placement: 'right', trigger: 'hover focus' });
        } catch (e) {
          /* ignore */
        }
      });
    });
  </script>
  <script>
    document.querySelectorAll('[data-table-filter]').forEach(function (block) {
      const table = block.querySelector('tbody');
      if (!table) return;
      const rows = Array.from(table.querySelectorAll('tr[data-row]'));
      const textInput = block.querySelector('[data-filter-text]');
      const statusInput = block.querySelector('[data-filter-status]');
      const toolbar = block.querySelector('.crud-toolbar');

      const applyFilters = function () {
        const text = (textInput?.value || '').toLowerCase().trim();
        const status = (statusInput?.value || '').toLowerCase().trim();
        rows.forEach(function (row) {
          const haystack = (row.getAttribute('data-search') || row.textContent || '').toLowerCase();
          const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
          const textOk = text === '' || haystack.includes(text);
          const statusOk = status === '' || rowStatus === status;
          row.style.display = (textOk && statusOk) ? '' : 'none';
        });
      };

      textInput?.addEventListener('input', applyFilters);
      statusInput?.addEventListener('change', applyFilters);

      // Smart toolbar actions (reset + export CSV of visible rows)
      if (toolbar && !toolbar.querySelector('[data-toolbar-actions="1"]')) {
        const actions = document.createElement('div');
        actions.setAttribute('data-toolbar-actions', '1');
        actions.style.display = 'flex';
        actions.style.gap = '8px';
        actions.style.alignItems = 'center';
        actions.style.marginLeft = 'auto';

        const btnReset = document.createElement('button');
        btnReset.type = 'button';
        btnReset.className = 'btn btn-sm';
        btnReset.innerHTML = '<i class="bi bi-arrow-counterclockwise"></i>Réinitialiser';
        btnReset.addEventListener('click', function () {
          if (textInput) textInput.value = '';
          if (statusInput) statusInput.value = '';
          applyFilters();
        });

        const btnExport = document.createElement('button');
        btnExport.type = 'button';
        btnExport.className = 'btn btn-sm';
        btnExport.innerHTML = '<i class="bi bi-download"></i>Exporter CSV';
        btnExport.addEventListener('click', function () {
          const tableEl = block.querySelector('table');
          if (!tableEl) return;
          const headCells = Array.from(tableEl.querySelectorAll('thead th'));
          const header = headCells.map(th => (th.textContent || '').trim()).filter(Boolean);

          const visibleRows = rows.filter(r => r.style.display !== 'none');
          const body = visibleRows.map(function (tr) {
            const cells = Array.from(tr.querySelectorAll('td')).map(td => (td.textContent || '').trim().replace(/\s+/g, ' '));
            return cells;
          });

          const escapeCsv = (v) => {
            const s = String(v ?? '');
            if (/[",\n]/.test(s)) return '"' + s.replace(/"/g, '""') + '"';
            return s;
          };

          const lines = [];
          if (header.length > 0) lines.push(header.map(escapeCsv).join(','));
          body.forEach(r => lines.push(r.map(escapeCsv).join(',')));
          const csv = '\uFEFF' + lines.join('\n');

          const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
          const url = URL.createObjectURL(blob);
          const a = document.createElement('a');
          const title = (document.title || 'export').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
          a.href = url;
          a.download = (title || 'export') + '.csv';
          document.body.appendChild(a);
          a.click();
          a.remove();
          URL.revokeObjectURL(url);
        });

        actions.appendChild(btnReset);
        actions.appendChild(btnExport);
        toolbar.appendChild(actions);
      }
    });
  </script>
  <script>
    (function () {
      const dash = document.querySelector('[data-admin-dashboard="1"]');
      if (!dash) return;

      // Counters (lightweight)
      const els = Array.from(dash.querySelectorAll('[data-count]'));
      const animate = (el) => {
        const target = parseInt(el.getAttribute('data-count') || '0', 10) || 0;
        const start = 0;
        const dur = 650;
        const t0 = performance.now();
        const step = (t) => {
          const p = Math.min(1, (t - t0) / dur);
          const eased = 1 - Math.pow(1 - p, 3);
          const val = Math.round(start + (target - start) * eased);
          el.textContent = String(val);
          if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
      };
      els.forEach(animate);

      // Charts
      if (!window.Chart) return;
      Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
      Chart.defaults.color = '#334155';

      const k = {
        quotes: Number(dash.getAttribute('data-kpi-quotes') || 0),
        messages: Number(dash.getAttribute('data-kpi-messages') || 0),
        services: Number(dash.getAttribute('data-kpi-services') || 0),
        projects: Number(dash.getAttribute('data-kpi-projects') || 0),
        posts: Number(dash.getAttribute('data-kpi-posts') || 0),
        testimonials: Number(dash.getAttribute('data-kpi-testimonials') || 0),
      };

      const inbox = k.quotes + k.messages;
      const content = k.services + k.projects + k.posts;
      const feedback = k.testimonials;

      const activityCtx = document.getElementById('activityChart');
      if (activityCtx) {
        // eslint-disable-next-line no-new
        new Chart(activityCtx, {
          type: 'doughnut',
          data: {
            labels: ['Inbox (devis+messages)', 'Contenu publié', 'Avis en attente'],
            datasets: [
              {
                data: [inbox, content, feedback],
                backgroundColor: ['rgba(255,122,24,.85)', 'rgba(59,130,246,.85)', 'rgba(16,185,129,.85)'],
                borderColor: ['rgba(255,122,24,1)', 'rgba(59,130,246,1)', 'rgba(16,185,129,1)'],
                borderWidth: 1,
                hoverOffset: 6,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
              legend: { position: 'bottom' },
              tooltip: { enabled: true },
            },
          },
        });
      }

      const contentCtx = document.getElementById('contentChart');
      if (contentCtx) {
        // eslint-disable-next-line no-new
        new Chart(contentCtx, {
          type: 'bar',
          data: {
            labels: ['Services', 'Réalisations', 'Articles'],
            datasets: [
              {
                label: 'Publié',
                data: [k.services, k.projects, k.posts],
                backgroundColor: ['rgba(255,122,24,.75)', 'rgba(99,102,241,.75)', 'rgba(59,130,246,.75)'],
                borderColor: ['rgba(255,122,24,1)', 'rgba(99,102,241,1)', 'rgba(59,130,246,1)'],
                borderWidth: 1,
                borderRadius: 10,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true, ticks: { precision: 0 } },
              x: { grid: { display: false } },
            },
            plugins: { legend: { display: false } },
          },
        });
      }
    })();
  </script>
</body>
</html>
