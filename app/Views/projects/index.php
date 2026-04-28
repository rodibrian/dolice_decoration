<?php
/** @var list<array<string, mixed>> $projects */
/** @var string|null $category */
?>
<section class="card">
  <div class="row between">
    <h1>Réalisations</h1>
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">Demander un devis</a>
  </div>
  <?php if (!empty($category)): ?>
    <p class="muted">Filtre catégorie: <?= htmlspecialchars((string)$category, ENT_QUOTES, 'UTF-8') ?> (<a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">retirer</a>)</p>
  <?php endif; ?>
  <div class="list">
    <?php foreach ($projects as $p): ?>
      <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations/' . (string)$p['slug'], ENT_QUOTES, 'UTF-8') ?>">
        <h3><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars((string)($p['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

