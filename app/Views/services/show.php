<?php /** @var array<string, mixed> $service */ ?>
<?php
$showPrice = (int)($service['show_price'] ?? 0) === 1;
$basePrice = $service['base_price'] ?? null;
$priceUnit = trim((string)($service['price_unit'] ?? ''));
$priceLabel = trim((string)($service['price_label'] ?? '')) ?: 'À partir de';
$priceText = '';
if ($showPrice && $basePrice !== null && $basePrice !== '') {
    $priceText = $priceLabel . ' ' . number_format((float)$basePrice, 0, ',', ' ') . ' Ar' . ($priceUnit !== '' ? (' ' . $priceUnit) : '');
}
?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars((string)$service['title'], ENT_QUOTES, 'UTF-8') ?></li>
      </ol>
    </nav>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-end gap-3">
      <div>
        <h1 class="display-6 fw-bold mb-1 section-title"><?= htmlspecialchars((string)$service['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <div class="text-secondary">Une prestation pensée pour votre besoin.</div>
        <?php if ($priceText !== ''): ?>
          <div class="mt-2">
            <span class="badge text-bg-light border"><i class="bi bi-cash-coin me-1"></i><?= htmlspecialchars($priceText, ENT_QUOTES, 'UTF-8') ?></span>
          </div>
        <?php endif; ?>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-clipboard-check me-2"></i>Demander un devis
        </a>
        <a class="btn btn-outline-primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">
          <i class="bi bi-chat-square-dots me-2"></i>Parler à un conseiller
        </a>
      </div>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4 align-items-start">
      <div class="col-lg-5" data-aos="zoom-in">
        <?php if (!empty($service['image_path'])): ?>
          <img class="image-cover" style="height:320px" src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$service['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
        <?php else: ?>
          <div class="skeleton" style="height:320px"></div>
        <?php endif; ?>
      </div>
      <div class="col-lg-7" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <h2 class="h4 mb-3">Détails</h2>
            <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)($service['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
            <hr class="my-4">
            <div class="d-flex flex-wrap gap-2">
              <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-send me-2"></i>Demander un devis</a>
              <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i>Voir des exemples</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

