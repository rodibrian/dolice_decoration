<?php /** @var list<array<string, mixed>> $posts */ ?>
<section class="card">
  <h1>Blog</h1>
  <div class="list">
    <?php foreach ($posts as $p): ?>
      <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog/' . (string)$p['slug'], ENT_QUOTES, 'UTF-8') ?>">
        <h3><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars((string)($p['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

