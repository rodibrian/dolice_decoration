<?php
/** @var list<array<string, mixed>> $services */
/** @var string|null $flash */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🛠</span>Services</h1>
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services/create', ENT_QUOTES, 'UTF-8') ?>">Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div data-table-filter>
    <div class="crud-toolbar">
      <input class="form-control" type="search" placeholder="Rechercher un service..." data-filter-text>
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
        <th>Titre</th>
        <th>Slug</th>
        <th>Catégorie</th>
        <th>Prix</th>
        <th>Publié</th>
        <th>Ordre</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($services)): ?>
        <tr><td colspan="8" class="muted">Aucun service.</td></tr>
      <?php else: ?>
        <?php foreach ($services as $s): ?>
          <tr data-row data-search="<?= htmlspecialchars(strtolower((string)$s['title'] . ' ' . (string)$s['slug'] . ' ' . (string)($s['category'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= ((int)$s['is_published'] === 1) ? 'oui' : 'non' ?>">
            <td><?= (int)$s['id'] ?></td>
            <td><?= htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)$s['slug'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($s['category'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted">
              <?php $bp = $s['base_price'] ?? null; ?>
              <?php if ((int)($s['show_price'] ?? 0) === 1 && $bp !== null && $bp !== ''): ?>
                <?php
                  $label = trim((string)($s['price_label'] ?? '')) ?: 'À partir de';
                  $unit = trim((string)($s['price_unit'] ?? ''));
                  $txt = $label . ' ' . number_format((float)$bp, 0, ',', ' ') . ' Ar' . ($unit !== '' ? (' ' . $unit) : '');
                ?>
                <?= htmlspecialchars($txt, ENT_QUOTES, 'UTF-8') ?>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
            <td><span class="badge text-bg-<?= ((int)$s['is_published'] === 1) ? 'success' : 'secondary' ?>"><?= ((int)$s['is_published'] === 1) ? 'Oui' : 'Non' ?></span></td>
            <td class="muted"><?= (int)$s['display_order'] ?></td>
            <td class="actions">
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services/edit?id=' . (int)$s['id'], ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
              <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer ce service ?');">
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
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

