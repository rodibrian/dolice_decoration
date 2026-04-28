<?php
/** @var list<array<string, mixed>> $posts */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1>Articles</h1>
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts/create', ENT_QUOTES, 'UTF-8') ?>">Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div class="table-wrap">
    <table class="table">
      <thead>
      <tr>
        <th>#</th>
        <th>Titre</th>
        <th>Slug</th>
        <th>Statut</th>
        <th>Publié le</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($posts)): ?>
        <tr><td colspan="6" class="muted">Aucun article.</td></tr>
      <?php else: ?>
        <?php foreach ($posts as $p): ?>
          <tr>
            <td><?= (int)$p['id'] ?></td>
            <td><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= ((string)$p['status'] === 'published') ? 'Publié' : 'Brouillon' ?></td>
            <td class="muted"><?= htmlspecialchars((string)($p['published_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="actions">
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts/edit?id=' . (int)$p['id'], ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
              <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer cet article ?');">
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button class="btn danger" type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

