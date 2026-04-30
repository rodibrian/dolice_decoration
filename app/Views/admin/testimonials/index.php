<?php
/** @var list<array<string, mixed>> $testimonials */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">💬</span>Témoignages</h1>
    <a class="btn primary btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials/create', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-plus-lg"></i>Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div data-table-filter>
    <div class="crud-toolbar">
      <input class="form-control" type="search" placeholder="Rechercher un témoignage..." data-filter-text>
      <select class="form-select" data-filter-status>
        <option value="">Tous les statuts</option>
        <option value="approuve">Approuvé</option>
        <option value="attente">En attente</option>
      </select>
    </div>
  <div class="table-wrap">
    <table class="table table-hover align-middle">
      <thead>
      <tr>
        <th>#</th>
        <th>Client</th>
        <th>Note</th>
        <th>Statut</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($testimonials)): ?>
        <tr><td colspan="5" class="muted">Aucun témoignage.</td></tr>
      <?php else: ?>
        <?php foreach ($testimonials as $t): ?>
          <tr data-row data-search="<?= htmlspecialchars(strtolower((string)$t['client_name'] . ' ' . (string)($t['client_company'] ?? '') . ' ' . (string)($t['content'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= ((string)$t['status'] === 'approved') ? 'approuve' : 'attente' ?>">
            <td><?= (int)$t['id'] ?></td>
            <td>
              <?= htmlspecialchars((string)$t['client_name'], ENT_QUOTES, 'UTF-8') ?>
              <div class="muted"><?= htmlspecialchars((string)($t['client_company'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
            </td>
            <td class="muted"><?= htmlspecialchars((string)($t['rating'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="badge text-bg-<?= ((string)$t['status'] === 'approved') ? 'success' : 'warning' ?>"><?= ((string)$t['status'] === 'approved') ? 'Approuvé' : 'En attente' ?></span></td>
            <td class="actions">
              <?php if ((string)$t['status'] !== 'approved'): ?>
                <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials/approve', ENT_QUOTES, 'UTF-8') ?>">
                  <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                  <button class="btn" type="submit">Approuver</button>
                </form>
              <?php endif; ?>
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials/edit?id=' . (int)$t['id'], ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
              <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer ce témoignage ?');">
                <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
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

