<?php /** @var list<array<string, mixed>> $services */ ?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Services</li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
      <div>
        <h1 class="display-6 fw-bold mb-1 section-title">Services</h1>
        <div class="text-secondary">Des prestations claires, adaptées à votre projet.</div>
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
      <?php foreach ($services as $s): ?>
        <div class="col-sm-6 col-lg-4" data-aos="fade-up">
          <a class="text-decoration-none" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services/' . (string)$s['slug'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="card card-hover h-100">
              <?php if (!empty($s['image_path'])): ?>
                <img class="card-img-top" style="height:180px;object-fit:cover" src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$s['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
              <?php endif; ?>
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <span class="badge text-bg-light"><?= htmlspecialchars((string)($s['category'] ?? 'Service'), ENT_QUOTES, 'UTF-8') ?></span>
                  <i class="bi bi-arrow-right text-secondary"></i>
                </div>
                <h2 class="h5 mb-0"><?= htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8') ?></h2>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

