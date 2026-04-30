<?php
/** @var string $content */
/** @var string|null $title */
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
  <meta name="app-base" content="<?= htmlspecialchars((string)(env('APP_URL', '') ?: ''), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-blur">
    <div class="container py-2">
      <a class="navbar-brand fw-bold" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">
        <i class="bi bi-buildings text-brand me-2"></i>Dolice Decoration
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainOffcanvas" aria-controls="mainOffcanvas" aria-label="Menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="d-none d-lg-flex align-items-center gap-3 ms-auto">
        <a class="nav-link" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a>
        <a class="nav-link" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a>
        <a class="nav-link" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">Blog</a>
        <a class="nav-link" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/faq', ENT_QUOTES, 'UTF-8') ?>">FAQ</a>
        <a class="nav-link" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">Contact</a>
        <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-clipboard-check me-2"></i>Demander un devis
        </a>
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
        <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a>
        <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/notre-histoire', ENT_QUOTES, 'UTF-8') ?>">Notre histoire</a>
        <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a>
        <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a>
        <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">Blog</a>
        <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/faq', ENT_QUOTES, 'UTF-8') ?>">FAQ</a>
        <a class="list-group-item list-group-item-action" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">Contact</a>
      </div>

      <div class="d-grid gap-2 mt-4">
        <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-clipboard-check me-2"></i>Demander un devis
        </a>
        <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-shield-lock me-2"></i>Admin
        </a>
      </div>
    </div>
  </div>

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
          <div class="row g-4">
            <div class="col-lg-7">
              <div class="ratio ratio-16x9 bg-light rounded-4 overflow-hidden border">
                <div class="d-flex align-items-center justify-content-center w-100 h-100 skeleton" data-post-modal-skeleton></div>
                <img class="w-100 h-100 d-none" style="object-fit:cover" alt="" data-post-modal-image>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="text-secondary small mb-2" data-post-modal-meta></div>
              <h3 class="h4 mb-3" data-post-modal-title></h3>
              <div class="text-secondary mb-3" data-post-modal-excerpt></div>
              <div class="text-secondary" style="white-space:pre-wrap" data-post-modal-content></div>
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

  <footer class="pt-5 pb-4 mt-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="fw-bold h5 mb-2"><i class="bi bi-buildings me-2 text-brand"></i>Dolice Decoration</div>
          <p class="mb-0">Finition & décoration de bâtiment. Qualité, précision et accompagnement.</p>
        </div>
        <div class="col-6 col-lg-2">
          <div class="fw-semibold mb-2">Pages</div>
          <div class="d-flex flex-column gap-2">
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">Blog</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">Contact</a>
          </div>
        </div>
        <div class="col-6 col-lg-2">
          <div class="fw-semibold mb-2">Infos</div>
          <div class="d-flex flex-column gap-2">
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/notre-histoire', ENT_QUOTES, 'UTF-8') ?>">Notre histoire</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/faq', ENT_QUOTES, 'UTF-8') ?>">FAQ</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">Demander un devis</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="fw-semibold mb-2">Contact</div>
          <div class="d-flex flex-column gap-2">
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2"></i>Écrire un message</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Demande de devis</a>
            <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-shield-lock me-2"></i>Espace admin</a>
          </div>
        </div>
      </div>

      <hr class="border-light opacity-10 my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <div class="small">© <?= date('Y') ?> Dolice Decoration. Tous droits réservés.</div>
        <div class="small opacity-75">Design Bootstrap + AOS + Glide + PureCounter.</div>
      </div>
    </div>
  </footer>

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
