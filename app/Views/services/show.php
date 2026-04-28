<?php /** @var array<string, mixed> $service */ ?>
<section class="card">
  <div class="row between">
    <h1><?= htmlspecialchars((string)$service['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">Demander un devis</a>
  </div>
  <?php if (!empty($service['image_path'])): ?>
    <div class="thumb">
      <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$service['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
    </div>
  <?php endif; ?>
  <p class="muted" style="white-space:pre-wrap"><?= htmlspecialchars((string)($service['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
</section>

