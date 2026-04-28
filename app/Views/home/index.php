<?php
/** @var string $title */
/** @var list<array<string, mixed>> $services */
/** @var list<array<string, mixed>> $projects */
/** @var list<array<string, mixed>> $posts */
/** @var list<array<string, mixed>> $testimonials */
?>
<section class="hero">
  <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
  <p>Finition & décoration de bâtiment. Découvrez nos services, nos réalisations, et demandez un devis rapidement.</p>
  <div class="actions">
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">Demander un devis</a>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Voir nos réalisations</a>
  </div>
</section>

<section class="card">
  <h2>Services</h2>
  <div class="list">
    <?php foreach (($services ?? []) as $s): ?>
      <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services/' . (string)$s['slug'], ENT_QUOTES, 'UTF-8') ?>">
        <h3><?= htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars((string)($s['category'] ?? 'Demander un devis'), ENT_QUOTES, 'UTF-8') ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="card">
  <h2>Réalisations récentes</h2>
  <div class="list">
    <?php foreach (($projects ?? []) as $p): ?>
      <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations/' . (string)$p['slug'], ENT_QUOTES, 'UTF-8') ?>">
        <h3><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars((string)($p['location'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php if (!empty($testimonials)): ?>
  <section class="card">
    <h2>Témoignages</h2>
    <div class="list">
      <?php foreach ($testimonials as $t): ?>
        <div class="item">
          <h3><?= htmlspecialchars((string)$t['client_name'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p style="white-space:pre-wrap"><?= htmlspecialchars((string)$t['content'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

<?php if (!empty($posts)): ?>
  <section class="card">
    <h2>Blog</h2>
    <div class="list">
      <?php foreach ($posts as $post): ?>
        <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog/' . (string)$post['slug'], ENT_QUOTES, 'UTF-8') ?>">
          <h3><?= htmlspecialchars((string)$post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p><?= htmlspecialchars((string)($post['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>
