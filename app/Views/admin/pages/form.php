<?php
/** @var array<string, mixed> $page */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1>Éditer page: <?= htmlspecialchars((string)$page['page_key'], ENT_QUOTES, 'UTF-8') ?></h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages/update', ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="page_key" value="<?= htmlspecialchars((string)$page['page_key'], ENT_QUOTES, 'UTF-8') ?>">

    <label>
      Titre
      <input type="text" name="title" required value="<?= htmlspecialchars((string)($page['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Contenu (texte)
      <textarea name="content" rows="14"><?= htmlspecialchars((string)($page['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
    </label>

    <button class="btn primary" type="submit">Enregistrer</button>
  </form>
</section>

