<?php /** @var array<string, mixed> $post */ ?>
<section class="card">
  <h1><?= htmlspecialchars((string)$post['title'], ENT_QUOTES, 'UTF-8') ?></h1>
  <p class="muted"><?= htmlspecialchars((string)($post['published_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
  <?php if (!empty($post['featured_image'])): ?>
    <div class="thumb">
      <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$post['featured_image'], ENT_QUOTES, 'UTF-8') ?>" alt="">
    </div>
  <?php endif; ?>
  <div style="white-space:pre-wrap" class="muted"><?= htmlspecialchars((string)($post['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
</section>

