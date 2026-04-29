<?php
/** @var list<array<string, mixed>> $quotes */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">📄</span>Demandes de devis</h1>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div data-table-filter>
    <div class="crud-toolbar">
      <input class="form-control" type="search" placeholder="Rechercher une demande..." data-filter-text>
      <select class="form-select" data-filter-status>
        <option value="">Tous les statuts</option>
        <option value="new">Nouveau</option>
        <option value="in_progress">En cours</option>
        <option value="replied">Répondu</option>
        <option value="done">Terminé</option>
        <option value="archived">Archivé</option>
      </select>
    </div>
  <div class="table-wrap">
    <table class="table table-hover align-middle">
      <thead>
      <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Tél</th>
        <th>Email</th>
        <th>Type projet</th>
        <th>Statut</th>
        <th>Reçu le</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($quotes)): ?>
        <tr><td colspan="8" class="muted">Aucune demande.</td></tr>
      <?php else: ?>
        <?php foreach ($quotes as $q): ?>
          <tr data-row data-search="<?= htmlspecialchars(strtolower((string)$q['name'] . ' ' . (string)($q['email'] ?? '') . ' ' . (string)($q['phone'] ?? '') . ' ' . (string)($q['project_type'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars((string)$q['status'], ENT_QUOTES, 'UTF-8') ?>">
            <td><?= (int)$q['id'] ?></td>
            <td><?= htmlspecialchars((string)$q['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($q['phone'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($q['email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($q['project_type'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="badge text-bg-info"><?= htmlspecialchars((string)$q['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
            <td class="muted"><?= htmlspecialchars((string)($q['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="actions">
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes/show?id=' . (int)$q['id'], ENT_QUOTES, 'UTF-8') ?>">Voir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
  </div>
</section>

