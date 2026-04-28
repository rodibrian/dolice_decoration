<?php
/** @var array<string, mixed> $project */
/** @var list<array<string, mixed>> $images */
?>
<section class="card">
  <div class="row between">
    <h1><?= htmlspecialchars((string)$project['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">Demander un devis</a>
  </div>
  <p class="muted">
    <?= htmlspecialchars((string)($project['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
    <?php if (!empty($project['project_date'])): ?> — <?= htmlspecialchars((string)$project['project_date'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?>
  </p>
  <p class="muted" style="white-space:pre-wrap"><?= htmlspecialchars((string)($project['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>

  <?php if (!empty($images)): ?>
    <div class="grid">
      <?php foreach ($images as $img): ?>
        <div class="grid-item">
          <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$img['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

