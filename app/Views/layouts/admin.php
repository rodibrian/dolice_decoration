<?php
/** @var string $content */
/** @var string|null $title */
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH) ?: '/admin';
$isActive = static function (string $prefix) use ($uri): string {
    return str_starts_with($uri, $prefix) ? 'is-active' : '';
};
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
<body class="admin-shell admin-light">
  <div class="admin-layout">
    <!-- Sidebar (desktop) -->
    <aside class="admin-sidebar d-none d-lg-flex">
      <div class="admin-sidebar-inner">
        <a class="admin-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin', ENT_QUOTES, 'UTF-8') ?>">
          <span class="admin-brand-icon"><i class="bi bi-shield-lock"></i></span>
          <span class="admin-brand-text">Dolice Admin</span>
        </a>

        <nav class="admin-nav">
          <div class="admin-nav-title">Gestion</div>
          <a class="admin-nav-link <?= $isActive('/admin/services') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-tools"></i><span>Services</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/projects') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-building-gear"></i><span>Réalisations</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/posts') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-newspaper"></i><span>Blog</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/testimonials') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-chat-quote"></i><span>Témoignages</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/quotes') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-file-earmark-text"></i><span>Devis</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/messages') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-envelope"></i><span>Messages</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/partners') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-people"></i><span>Partenaires</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/hero-slides') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/hero-slides', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-images"></i><span>Slides accueil</span>
          </a>

          <div class="admin-nav-title mt-3">Configuration</div>
          <a class="admin-nav-link <?= $isActive('/admin/pages') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-file-text"></i><span>Pages</span>
          </a>
          <a class="admin-nav-link <?= $isActive('/admin/settings') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/settings', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-gear"></i><span>Paramètres</span>
          </a>

          <div class="admin-nav-title mt-3">Raccourcis</div>
          <a class="admin-nav-link" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-globe2"></i><span>Voir le site</span>
          </a>
        </nav>

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
      <nav class="admin-nav">
        <div class="admin-nav-title">Gestion</div>
        <a class="admin-nav-link <?= $isActive('/admin/services') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-tools"></i><span>Services</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/projects') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-building-gear"></i><span>Réalisations</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/posts') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-newspaper"></i><span>Blog</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/testimonials') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-chat-quote"></i><span>Témoignages</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/quotes') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-file-earmark-text"></i><span>Devis</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/messages') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope"></i><span>Messages</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/partners') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-people"></i><span>Partenaires</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/hero-slides') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/hero-slides', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images"></i><span>Slides accueil</span></a>

        <div class="admin-nav-title mt-3">Configuration</div>
        <a class="admin-nav-link <?= $isActive('/admin/pages') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-file-text"></i><span>Pages</span></a>
        <a class="admin-nav-link <?= $isActive('/admin/settings') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/settings', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-gear"></i><span>Paramètres</span></a>

        <div class="admin-nav-title mt-3">Raccourcis</div>
        <a class="admin-nav-link" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-globe2"></i><span>Voir le site</span></a>
      </nav>

      <hr>
      <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/logout', ENT_QUOTES, 'UTF-8') ?>">
        <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    document.querySelectorAll('[data-table-filter]').forEach(function (block) {
      const table = block.querySelector('tbody');
      if (!table) return;
      const rows = Array.from(table.querySelectorAll('tr[data-row]'));
      const textInput = block.querySelector('[data-filter-text]');
      const statusInput = block.querySelector('[data-filter-status]');

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
    });
  </script>
</body>
</html>
