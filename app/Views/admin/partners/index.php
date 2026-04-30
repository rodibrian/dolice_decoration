<?php
/** @var list<array<string, mixed>> $partners */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🤝</span>Partenaires</h1>
    <a class="btn primary btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners/create', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-plus-lg"></i>Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div data-table-filter>
    <div class="crud-toolbar">
      <input class="form-control" type="search" placeholder="Rechercher un partenaire..." data-filter-text>
      <select class="form-select" data-filter-status>
        <option value="">Tous les statuts</option>
        <option value="oui">Publié</option>
        <option value="non">Non publié</option>
      </select>
    </div>
  <div class="table-wrap">
    <table class="table table-hover align-middle">
      <thead>
      <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Catégorie</th>
        <th>Publié</th>
        <th>Ordre</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($partners)): ?>
        <tr><td colspan="6" class="muted">Aucun partenaire.</td></tr>
      <?php else: ?>
        <?php foreach ($partners as $p): ?>
          <tr data-row data-search="<?= htmlspecialchars(strtolower((string)$p['name'] . ' ' . (string)($p['category'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= ((int)$p['is_published'] === 1) ? 'oui' : 'non' ?>">
            <td><?= (int)$p['id'] ?></td>
            <td><?= htmlspecialchars((string)$p['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($p['category'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="badge text-bg-<?= ((int)$p['is_published'] === 1) ? 'success' : 'secondary' ?>"><?= ((int)$p['is_published'] === 1) ? 'Oui' : 'Non' ?></span></td>
            <td class="muted"><?= (int)$p['display_order'] ?></td>
            <td class="actions">
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners/edit?id=' . (int)$p['id'], ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
              <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer ce partenaire ?');">
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

