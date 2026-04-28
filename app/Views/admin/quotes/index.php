<?php
/** @var list<array<string, mixed>> $quotes */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1>Demandes de devis</h1>
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
          <tr>
            <td><?= (int)$q['id'] ?></td>
            <td><?= htmlspecialchars((string)$q['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($q['phone'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($q['email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($q['project_type'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)$q['status'], ENT_QUOTES, 'UTF-8') ?></td>
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
</section>

