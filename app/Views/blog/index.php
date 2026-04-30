<?php /** @var list<array<string, mixed>> $posts */ ?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Blog</li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
      <div>
        <h1 class="display-6 fw-bold mb-1 section-title">Blog</h1>
        <div class="text-secondary">Conseils, tendances et retours chantier.</div>
      </div>
      <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
        <i class="bi bi-clipboard-check me-2"></i>Demander un devis
      </a>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <?php foreach ($posts as $p): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <a class="text-decoration-none" data-post-modal="1" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog/' . (string)$p['slug'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="card card-hover h-100">
              <?php if (!empty($p['featured_image'])): ?>
                <img class="card-img-top" style="height:190px;object-fit:cover" src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$p['featured_image'], ENT_QUOTES, 'UTF-8') ?>" alt="">
              <?php endif; ?>
              <div class="card-body">
                <div class="text-secondary small mb-2"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars((string)($p['published_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                <h2 class="h5 mb-2"><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                <div class="text-secondary"><?= htmlspecialchars((string)($p['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

