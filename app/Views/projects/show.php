<?php
/** @var array<string, mixed> $project */
/** @var list<array<string, mixed>> $images */
?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('public.common.breadcrumb_home'), ENT_QUOTES, 'UTF-8') ?></a></li>
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('nav.projects'), ENT_QUOTES, 'UTF-8') ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars((string)$project['title'], ENT_QUOTES, 'UTF-8') ?></li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-end gap-3">
      <div>
        <h1 class="display-6 fw-bold mb-1 section-title"><?= htmlspecialchars((string)$project['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <div class="text-secondary">
          <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars((string)($project['location'] ?? '—'), ENT_QUOTES, 'UTF-8') ?>
          <?php if (!empty($project['project_date'])): ?> • <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars((string)$project['project_date'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?>
        </div>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('nav.quote'), ENT_QUOTES, 'UTF-8') ?></a>
        <a class="btn btn-outline-primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-chat-square-dots me-2"></i><?= htmlspecialchars(t('nav.contact'), ENT_QUOTES, 'UTF-8') ?></a>
      </div>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-6" data-aos="zoom-in">
        <?php if (!empty($images)): ?>
          <div id="projectCarousel" class="carousel slide">
            <div class="carousel-inner rounded-4 border border-opacity-10 overflow-hidden">
              <?php $i=0; foreach ($images as $img): ?>
                <div class="carousel-item <?= ($i===0) ? 'active' : '' ?>">
                  <img class="d-block w-100" style="height:360px;object-fit:cover" src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$img['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
                </div>
              <?php $i++; endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden"><?= htmlspecialchars(t('public.common.carousel_prev'), ENT_QUOTES, 'UTF-8') ?></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden"><?= htmlspecialchars(t('public.common.carousel_next'), ENT_QUOTES, 'UTF-8') ?></span>
            </button>
          </div>
        <?php else: ?>
          <div class="skeleton" style="height:360px"></div>
        <?php endif; ?>
      </div>
      <div class="col-lg-6" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <h2 class="h4 mb-3"><?= htmlspecialchars(t('public.project_detail.description_heading'), ENT_QUOTES, 'UTF-8') ?></h2>
            <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)($project['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
            <hr class="my-4">
            <div class="row g-3">
              <div class="col-6">
                <div class="p-3 bg-light rounded-4">
                  <div class="text-secondary small"><?= htmlspecialchars(t('public.project_detail.meta_category'), ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="fw-semibold"><?= htmlspecialchars((string)($project['category'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 bg-light rounded-4">
                  <div class="text-secondary small"><?= htmlspecialchars(t('public.project_detail.meta_type'), ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="fw-semibold"><?= htmlspecialchars((string)($project['work_type'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
                </div>
              </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-4">
              <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-send me-2"></i><?= htmlspecialchars(t('nav.quote'), ENT_QUOTES, 'UTF-8') ?></a>
              <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-arrow-left me-2"></i><?= htmlspecialchars(t('public.project_detail.cta_back'), ENT_QUOTES, 'UTF-8') ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
