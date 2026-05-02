<?php
/** @var list<array<string, mixed>> $posts */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">📰</span>Articles</h1>
    <a class="btn primary btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts/create', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-plus-lg"></i>Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div data-table-filter>
    <div class="crud-toolbar">
      <input class="form-control" type="search" placeholder="Rechercher un article..." data-filter-text>
      <select class="form-select" data-filter-status>
        <option value="">Tous les statuts</option>
        <option value="publie">Publié</option>
        <option value="brouillon">Brouillon</option>
      </select>
    </div>
  <div class="table-wrap">
    <table class="table table-hover align-middle">
      <thead>
      <tr>
        <th>#</th>
        <th>Photo</th>
        <th>Titre</th>
        <th>Slug</th>
        <th>Statut</th>
        <th>Publié le</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($posts)): ?>
        <tr><td colspan="7" class="muted">Aucun article.</td></tr>
      <?php else: ?>
        <?php foreach ($posts as $p): ?>
          <tr data-row data-search="<?= htmlspecialchars(strtolower((string)$p['title'] . ' ' . (string)$p['slug'] . ' ' . (string)($p['author'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= ((string)$p['status'] === 'published') ? 'publie' : 'brouillon' ?>">
            <td><?= (int)$p['id'] ?></td>
            <td class="table-td-thumb">
              <?php $fi = trim((string)($p['featured_image'] ?? '')); ?>
              <?php if ($fi !== ''): ?>
                <img class="table-thumb" src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $fi, ENT_QUOTES, 'UTF-8') ?>" alt="">
              <?php else: ?>
                <span class="table-thumb-placeholder" title="Aucune image">—</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="badge text-bg-<?= ((string)$p['status'] === 'published') ? 'success' : 'secondary' ?>"><?= ((string)$p['status'] === 'published') ? 'Publié' : 'Brouillon' ?></span></td>
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
  </div>
</section>

