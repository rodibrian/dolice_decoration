<?php
/** @var list<array<string, mixed>> $messages */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1>Messages</h1>
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
        <th>Email</th>
        <th>Sujet</th>
        <th>Statut</th>
        <th>Reçu le</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($messages)): ?>
        <tr><td colspan="7" class="muted">Aucun message.</td></tr>
      <?php else: ?>
        <?php foreach ($messages as $m): ?>
          <tr>
            <td><?= (int)$m['id'] ?></td>
            <td><?= htmlspecialchars((string)$m['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($m['email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($m['subject'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)$m['status'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($m['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="actions">
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages/show?id=' . (int)$m['id'], ENT_QUOTES, 'UTF-8') ?>">Voir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

