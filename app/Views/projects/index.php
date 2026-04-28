<?php
/** @var list<array<string, mixed>> $projects */
/** @var string|null $category */
?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Réalisations</li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
      <div>
        <h1 class="display-6 fw-bold mb-1 section-title">Réalisations</h1>
        <div class="text-secondary">Galerie de chantiers et résultats livrés.</div>
        <?php if (!empty($category)): ?>
          <div class="mt-2">
            <span class="badge text-bg-primary">Catégorie: <?= htmlspecialchars((string)$category, ENT_QUOTES, 'UTF-8') ?></span>
            <a class="ms-2 small" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">retirer le filtre</a>
          </div>
        <?php endif; ?>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-clipboard-check me-2"></i>Demander un devis
        </a>
        <button class="btn btn-outline-secondary" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas" aria-controls="filterCanvas">
          <i class="bi bi-funnel me-2"></i>Filtrer
        </button>
      </div>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <?php foreach ($projects as $p): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <a class="text-decoration-none" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations/' . (string)$p['slug'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="card card-hover h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start gap-2">
                  <div>
                    <div class="text-secondary small mb-1"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars((string)($p['location'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
                    <h2 class="h5 mb-1"><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <div class="text-secondary small"><?= htmlspecialchars((string)($p['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                  <?php if ((int)($p['is_featured'] ?? 0) === 1): ?>
                    <span class="badge text-bg-warning"><i class="bi bi-star-fill me-1"></i>Vedette</span>
                  <?php endif; ?>
                </div>
                <div class="mt-3 skeleton" style="height:160px"></div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Offcanvas filters (v1: simple category input) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterCanvas" aria-labelledby="filterCanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="filterCanvasLabel">Filtrer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
  </div>
  <div class="offcanvas-body">
    <form method="get" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">
      <div class="mb-3">
        <label class="form-label">Catégorie</label>
        <input class="form-control" type="text" name="category" value="<?= htmlspecialchars((string)($category ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="ex: plafond, peinture...">
      </div>
      <div class="d-grid gap-2">
        <button class="btn btn-brand" type="submit"><i class="bi bi-funnel me-2"></i>Appliquer</button>
        <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réinitialiser</a>
      </div>
    </form>
  </div>
</div>

