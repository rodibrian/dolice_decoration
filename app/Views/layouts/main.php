<?php
/** @var string $content */
/** @var string|null $title */

$companyName = \App\Models\Setting::get('company_name', 'Dolice Decoration') ?? 'Dolice Decoration';
$companySlogan = \App\Models\Setting::get('company_slogan', 'Finition & décoration de bâtiment.') ?? 'Finition & décoration de bâtiment.';
$companyLogo = \App\Models\Setting::get('company_logo', null);
$companyLogoUrl = '';
if (!empty($companyLogo)) {
  $isAbsLogo = preg_match('#^https?://#i', (string)$companyLogo) === 1;
  $companyLogoUrl = $isAbsLogo
    ? (string)$companyLogo
    : ((env('APP_URL', '') ?: '') . (string)$companyLogo);
}

$theme = \App\Core\SiteTheme::normalize(\App\Models\Setting::get('site_theme', \App\Core\SiteTheme::DEFAULT) ?? \App\Core\SiteTheme::DEFAULT);
$themeTone = in_array($theme, ['midnight', 'obsidian'], true) ? 'dark' : 'light';

$heroCoverRaw = trim((string)(\App\Models\Setting::get('hero_cover_image', '') ?? ''));
$heroCoverUrl = '';
if ($heroCoverRaw !== '') {
  $isAbsoluteCover = preg_match('#^https?://#i', $heroCoverRaw) === 1;
  $heroCoverUrl = $isAbsoluteCover
    ? $heroCoverRaw
    : (rtrim((string)(env('APP_URL', '') ?: ''), '/') . '/' . ltrim($heroCoverRaw, '/'));
}

$isOn = static function (string $k, bool $defaultOn = true): bool {
  $v = \App\Models\Setting::get($k, null);
  if ($v === null || $v === '') {
    return $defaultOn;
  }
  if ((string)$v === '0') {
    return false;
  }
  return (string)$v === '1';
};

$showMainNav = $isOn('layout_show_main_nav', true);
$showMainFooter = $isOn('layout_show_footer', true);

$footerPartners = [];
try {
  $footerPartners = \App\Models\Partner::published(18);
} catch (\Throwable $e) {
  $footerPartners = [];
}

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptName = (string)($_SERVER['SCRIPT_NAME'] ?? '');
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath !== '' && $basePath !== '/' && str_starts_with($uri, $basePath)) {
  $uri = substr($uri, strlen($basePath)) ?: '/';
}
$uri = rtrim($uri, '/') ?: '/';
$isActive = static function (string $path) use ($uri): string {
  $path = rtrim($path, '/') ?: '/';
  return $uri === $path ? 'is-active' : '';
};

// Base URL used by public JS (modals / rewrite fallback).
// Prefer APP_URL when set; otherwise derive from current request (works on XAMPP subfolders).
$appBase = trim((string)(env('APP_URL', '') ?: ''));
if ($appBase === '') {
  $https = (isset($_SERVER['HTTPS']) && (string)$_SERVER['HTTPS'] !== '' && (string)$_SERVER['HTTPS'] !== 'off');
  $scheme = $https ? 'https' : 'http';
  $host = (string)($_SERVER['HTTP_HOST'] ?? '');
  if ($host !== '') {
    // When served from a subfolder (e.g. /dolice_decoration/public), keep that basePath.
    // The router will strip it when dispatching.
    $appBase = $scheme . '://' . $host . ($basePath !== '' ? $basePath : '');
  } else {
    $appBase = $basePath !== '' ? $basePath : '';
  }
}
$appBase = rtrim($appBase, '/');
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'Dolice Decoration', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap (latest) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    /* Ensure navbar toggler icon visible with custom background */
    .navbar-toggler{border-color:rgba(0,0,0,.15)}
    .navbar-toggler-icon{filter: none}
  </style>
  <!-- AOS (scroll animations) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
  <!-- Glide.js (sliders) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.core.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.theme.min.css">
  <!-- Site theme -->
  <link rel="stylesheet" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/assets/site.css', ENT_QUOTES, 'UTF-8') ?>">
  <meta name="app-base" content="<?= htmlspecialchars($appBase, ENT_QUOTES, 'UTF-8') ?>">
</head>
<body class="theme-<?= htmlspecialchars($theme, ENT_QUOTES, 'UTF-8') ?> theme-tone-<?= htmlspecialchars($themeTone, ENT_QUOTES, 'UTF-8') ?>" <?= $heroCoverUrl !== '' ? 'style="--hero-cover:url(\'' . htmlspecialchars($heroCoverUrl, ENT_QUOTES, 'UTF-8') . '\')"' : '' ?>>
  <?php if ($showMainNav): ?>
  <nav class="navbar navbar-expand-lg navbar-blur">
    <div class="container py-2">
      <a class="navbar-brand fw-bold" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">
        <?php if ($companyLogoUrl !== ''): ?>
          <img src="<?= htmlspecialchars($companyLogoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="" style="height:28px;width:auto;object-fit:contain" class="me-2 align-text-bottom">
        <?php else: ?>
          <i class="bi bi-buildings text-brand me-2"></i>
        <?php endif; ?>
        <?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainOffcanvas" aria-controls="mainOffcanvas" aria-label="Menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="d-none d-lg-flex align-items-center gap-3 ms-auto">
        <?php if ($isOn('nav_show_services', true)): ?><a class="nav-link nav-link-pro <?= $isActive('/services') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a><?php endif; ?>
        <?php if ($isOn('nav_show_projects', true)): ?><a class="nav-link nav-link-pro <?= $isActive('/realisations') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a><?php endif; ?>
        <?php if ($isOn('nav_show_blog', true)): ?><a class="nav-link nav-link-pro <?= $isActive('/blog') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">Blog</a><?php endif; ?>
        <?php if ($isOn('nav_show_faq', true)): ?><a class="nav-link nav-link-pro <?= $isActive('/faq') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/faq', ENT_QUOTES, 'UTF-8') ?>">FAQ</a><?php endif; ?>
        <?php if ($isOn('nav_show_contact', true)): ?><a class="nav-link nav-link-pro <?= $isActive('/contact') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">Contact</a><?php endif; ?>
        <?php if ($isOn('nav_show_quote', true)): ?>
          <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-clipboard-check me-2"></i>Demander un devis
          </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- Offcanvas mobile menu -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="mainOffcanvas" aria-labelledby="mainOffcanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="mainOffcanvasLabel">Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
      <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action <?= $isActive('/') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a>
        <?php if ($isOn('nav_show_history', true)): ?><a class="list-group-item list-group-item-action <?= $isActive('/notre-histoire') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/notre-histoire', ENT_QUOTES, 'UTF-8') ?>">Notre histoire</a><?php endif; ?>
        <?php if ($isOn('nav_show_services', true)): ?><a class="list-group-item list-group-item-action <?= $isActive('/services') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a><?php endif; ?>
        <?php if ($isOn('nav_show_projects', true)): ?><a class="list-group-item list-group-item-action <?= $isActive('/realisations') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a><?php endif; ?>
        <?php if ($isOn('nav_show_blog', true)): ?><a class="list-group-item list-group-item-action <?= $isActive('/blog') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">Blog</a><?php endif; ?>
        <?php if ($isOn('nav_show_faq', true)): ?><a class="list-group-item list-group-item-action <?= $isActive('/faq') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/faq', ENT_QUOTES, 'UTF-8') ?>">FAQ</a><?php endif; ?>
        <?php if ($isOn('nav_show_contact', true)): ?><a class="list-group-item list-group-item-action <?= $isActive('/contact') ?>" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">Contact</a><?php endif; ?>
      </div>

      <div class="d-grid gap-2 mt-4">
        <?php if ($isOn('nav_show_quote', true)): ?>
          <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-clipboard-check me-2"></i>Demander un devis
          </a>
        <?php endif; ?>
        <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-shield-lock me-2"></i>Admin
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <main class="site-main">
    <?= $content ?>
  </main>

  <!-- Project details modal -->
  <div class="modal fade" id="projectDetailsModal" tabindex="-1" aria-labelledby="projectDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
      <div class="modal-content rounded-4 overflow-hidden">
        <div class="modal-header">
          <h5 class="modal-title" id="projectDetailsModalLabel">Réalisation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-lg-7">
              <div class="ratio ratio-16x9 bg-light rounded-4 overflow-hidden border">
                <div class="d-flex align-items-center justify-content-center w-100 h-100 skeleton" data-project-modal-skeleton></div>
                <div id="projectModalCarouselWrap" class="w-100 h-100 d-none" data-project-modal-carousel-wrap></div>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                  <div class="text-secondary small mb-1" data-project-modal-meta></div>
                  <h3 class="h4 mb-2" data-project-modal-title></h3>
                </div>
              </div>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge text-bg-light border" data-project-modal-badge-category></span>
                <span class="badge text-bg-light border" data-project-modal-badge-type></span>
                <span class="badge text-bg-light border" data-project-modal-badge-date></span>
              </div>
              <div class="text-secondary" style="white-space:pre-wrap" data-project-modal-description></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i>Voir tout</a>
          <button type="button" class="btn btn-brand" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Service details modal -->
  <div class="modal fade" id="serviceDetailsModal" tabindex="-1" aria-labelledby="serviceDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
      <div class="modal-content rounded-4 overflow-hidden">
        <div class="modal-header">
          <h5 class="modal-title" id="serviceDetailsModalLabel">Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-lg-7">
              <div class="ratio ratio-16x9 bg-light rounded-4 overflow-hidden border">
                <div class="d-flex align-items-center justify-content-center w-100 h-100 skeleton" data-service-modal-skeleton></div>
                <img class="w-100 h-100 d-none" style="object-fit:cover" alt="" data-service-modal-image>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                  <div class="text-secondary small mb-1" data-service-modal-meta></div>
                  <h3 class="h4 mb-2" data-service-modal-title></h3>
                </div>
              </div>
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge text-bg-light border d-none" data-service-modal-badge-category></span>
                <span class="badge text-bg-light border d-none" data-service-modal-badge-price></span>
              </div>
              <div class="text-secondary" style="white-space:pre-wrap" data-service-modal-description></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-tools me-2"></i>Voir tout</a>
          <a class="btn btn-brand d-none" data-service-modal-open-page href="#"><i class="bi bi-box-arrow-up-right me-2"></i>Ouvrir la page</a>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Blog post details modal -->
  <div class="modal fade" id="postDetailsModal" tabindex="-1" aria-labelledby="postDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
      <div class="modal-content rounded-4 overflow-hidden">
        <div class="modal-header">
          <h5 class="modal-title" id="postDetailsModalLabel">Article</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4 align-items-lg-start">
            <div class="col-lg-7">
              <div class="ratio ratio-16x9 bg-light rounded-4 overflow-hidden border">
                <div class="d-flex align-items-center justify-content-center w-100 h-100 skeleton" data-post-modal-skeleton></div>
                <img class="w-100 h-100 d-none" style="object-fit:cover" alt="" data-post-modal-image>
                <div id="postModalCarouselWrap" class="w-100 h-100 d-none" data-post-modal-carousel-wrap></div>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="text-secondary small mb-2" data-post-modal-meta></div>
              <h3 class="h4 mb-3" data-post-modal-title></h3>
              <div class="post-modal-text-scroll">
                <div class="text-secondary mb-3" data-post-modal-excerpt></div>
                <div class="text-secondary" style="white-space:pre-wrap" data-post-modal-content></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-newspaper me-2"></i>Voir tout</a>
          <a class="btn btn-brand d-none" data-post-modal-open-page href="#"><i class="bi bi-box-arrow-up-right me-2"></i>Ouvrir la page</a>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <?php if ($showMainFooter): ?>
  <footer class="pt-5 pb-4 mt-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-3">
          <div class="fw-bold h5 mb-2">
            <?php if ($companyLogoUrl !== ''): ?>
              <img src="<?= htmlspecialchars($companyLogoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="" style="height:26px;width:auto;object-fit:contain" class="me-2 align-text-bottom">
            <?php else: ?>
              <i class="bi bi-buildings me-2 text-brand"></i>
            <?php endif; ?>
            <?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?>
          </div>
          <p class="mb-0"><?= htmlspecialchars($companySlogan, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="col-6 col-lg-2">
          <div class="fw-semibold mb-2">Pages</div>
          <div class="d-flex flex-column gap-2">
            <?php if ($isOn('footer_show_services', true)): ?><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a><?php endif; ?>
            <?php if ($isOn('footer_show_projects', true)): ?><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a><?php endif; ?>
            <?php if ($isOn('footer_show_blog', true)): ?><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">Blog</a><?php endif; ?>
            <?php if ($isOn('footer_show_contact', true)): ?><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">Contact</a><?php endif; ?>
          </div>
        </div>
        <div class="col-6 col-lg-2">
          <div class="fw-semibold mb-2">Infos</div>
          <div class="d-flex flex-column gap-2">
            <?php if ($isOn('footer_show_history', true)): ?><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/notre-histoire', ENT_QUOTES, 'UTF-8') ?>">Notre histoire</a><?php endif; ?>
            <?php if ($isOn('footer_show_faq', true)): ?><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/faq', ENT_QUOTES, 'UTF-8') ?>">FAQ</a><?php endif; ?>
            <?php if ($isOn('footer_show_quote', true)): ?><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">Demander un devis</a><?php endif; ?>
          </div>
        </div>
        <div class="col-lg-2">
          <div class="fw-semibold mb-2">Contact</div>
          <div class="d-flex flex-column gap-2">
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2"></i>Écrire un message</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Demande de devis</a>
            <?php if ($isOn('footer_show_admin', true)): ?>
              <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-shield-lock me-2"></i>Espace admin</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-2">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div class="fw-semibold">Partenaires</div>
            <a class="small" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>"></a>
          </div>
          <div class="footer-partners">
            <?php foreach ($footerPartners as $p): ?>
              <?php
                $name = trim((string)($p['name'] ?? ''));
                $logo = trim((string)($p['logo_path'] ?? ''));
                $url = trim((string)($p['url'] ?? ''));
                $cat = trim((string)($p['category'] ?? ''));
                $logoUrl = '';
                if ($logo !== '') {
                  $isAbs = preg_match('#^https?://#i', $logo) === 1;
                  if ($isAbs) {
                    $logoUrl = $logo;
                  } else {
                    $disk = rtrim((string)PUBLIC_PATH, '/\\') . '/' . ltrim($logo, '/\\');
                    if (is_file($disk)) {
                      $logoUrl = (env('APP_URL', '') ?: '') . $logo;
                    }
                  }
                }
                $hintParts = [];
                if ($name !== '') $hintParts[] = $name;
                if ($cat !== '') $hintParts[] = $cat;
                if ($url !== '') $hintParts[] = $url;
                $hint = implode(' • ', $hintParts);
              ?>
              <?php if ($logoUrl !== ''): ?>
                <a
                  class="footer-partner"
                  <?= $url !== '' ? 'target="_blank" rel="noopener"' : '' ?>
                  href="<?= htmlspecialchars($url !== '' ? $url : '#', ENT_QUOTES, 'UTF-8') ?>"
                  <?= $url !== '' ? '' : 'role="button"' ?>
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  data-bs-title="<?= htmlspecialchars($hint !== '' ? $hint : 'Partenaire', ENT_QUOTES, 'UTF-8') ?>"
                  onclick="<?= $url === '' ? 'return false' : '' ?>"
                >
                  <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($name !== '' ? $name : 'Partenaire', ENT_QUOTES, 'UTF-8') ?>">
                </a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        
      </div>

      <hr class="border-light opacity-10 my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <div class="small">© <?= date('Y') ?> Dolice Decoration. Tous droits réservés.</div>
        <div class="d-flex align-items-center gap-3 flex-wrap justify-content-md-end">
          <?php
            $socials = [
              ['k' => 'facebook', 'label' => 'Facebook', 'icon' => 'bi-facebook'],
              ['k' => 'instagram', 'label' => 'Instagram', 'icon' => 'bi-instagram'],
              ['k' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'bi-linkedin'],
              ['k' => 'twitter', 'label' => 'X', 'icon' => 'bi-twitter-x'],
              ['k' => 'youtube', 'label' => 'YouTube', 'icon' => 'bi-youtube'],
              ['k' => 'tiktok', 'label' => 'TikTok', 'icon' => 'bi-tiktok'],
              ['k' => 'whatsapp', 'label' => 'WhatsApp', 'icon' => 'bi-whatsapp'],
            ];
            $hasSocial = false;
            foreach ($socials as $s) {
              if (!empty(\App\Models\Setting::get($s['k'], null))) { $hasSocial = true; break; }
            }
          ?>
          <?php if ($hasSocial): ?>
            <div class="footer-socials">
              <?php foreach ($socials as $s): ?>
                <?php $url = trim((string)(\App\Models\Setting::get($s['k'], '') ?? '')); ?>
                <?php if ($url !== ''): ?>
                  <?php
                    $href = $url;
                    if ($s['k'] === 'whatsapp') {
                      $href = 'https://wa.me/' . preg_replace('/\D+/', '', $url);
                    }
                  ?>
                  <a class="footer-social" target="_blank" rel="noopener"
                     href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                     data-bs-toggle="tooltip" data-bs-placement="top"
                     data-bs-title="<?= htmlspecialchars($s['label'], ENT_QUOTES, 'UTF-8') ?>">
                    <i class="bi <?= htmlspecialchars($s['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                  </a>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="small opacity-75">Design Bootstrap + AOS + Glide + PureCounter.</div>
        </div>
      </div>
    </div>
  </footer>
  <?php endif; ?>

  <!-- Bootstrap bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <!-- AOS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <!-- Glide.js -->
  <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/glide.min.js"></script>
  <!-- PureCounter -->
  <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.5.0/dist/purecounter_vanilla.js"></script>
  <!-- Site JS -->
  <script src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/assets/site.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>
