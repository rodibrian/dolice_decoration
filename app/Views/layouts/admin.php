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
  <header class="site-header shadow-sm">
    <nav class="navbar navbar-expand-xl navbar-dark">
      <div class="container">
        <a class="brand navbar-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-shield-lock me-2"></i>Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Menu admin">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
          <nav class="admin-menu nav ms-xl-3">
            <a class="<?= $isActive('/admin/services') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-tools me-1"></i>Services</a>
            <a class="<?= $isActive('/admin/projects') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-building-gear me-1"></i>Réalisations</a>
            <a class="<?= $isActive('/admin/posts') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-newspaper me-1"></i>Blog</a>
            <a class="<?= $isActive('/admin/testimonials') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-chat-quote me-1"></i>Témoignages</a>
            <a class="<?= $isActive('/admin/quotes') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-file-earmark-text me-1"></i>Devis</a>
            <a class="<?= $isActive('/admin/messages') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-1"></i>Messages</a>
            <a class="<?= $isActive('/admin/partners') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-people me-1"></i>Partenaires</a>
            <a class="<?= $isActive('/admin/pages') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-file-text me-1"></i>Pages</a>
            <a class="<?= $isActive('/admin/settings') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/settings', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-gear me-1"></i>Paramètres</a>
            <a class="menu-external" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-globe2 me-1"></i>Site</a>
          </nav>
        </div>
      </div>
    </nav>
  </header>

  <main class="container py-4">
    <div class="row">
      <div class="col-12">
        <?= $content ?>
      </div>
    </div>
  </main>
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
