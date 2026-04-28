<?php /** @var list<array<string, mixed>> $services */ ?>
<section class="card">
  <h1>Services</h1>
  <div class="list">
    <?php foreach ($services as $s): ?>
      <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services/' . (string)$s['slug'], ENT_QUOTES, 'UTF-8') ?>">
        <h3><?= htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars((string)($s['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

