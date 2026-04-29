<?php
/** @var array<string, mixed> $message */
/** @var string|null $flash */
/** @var string|null $error */

$status = (string)($message['status'] ?? 'new');
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">✉️</span>Message #<?= (int)$message['id'] ?></h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div class="grid2">
    <div class="card sub">
      <div><strong>Nom:</strong> <?= htmlspecialchars((string)$message['name'], ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Téléphone:</strong> <?= htmlspecialchars((string)($message['phone'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Email:</strong> <?= htmlspecialchars((string)($message['email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Sujet:</strong> <?= htmlspecialchars((string)($message['subject'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Reçu le:</strong> <?= htmlspecialchars((string)($message['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    <div class="card sub">
      <strong>Message</strong>
      <div class="muted" style="white-space:pre-wrap;margin-top:8px"><?= htmlspecialchars((string)($message['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
    </div>
  </div>

  <hr class="sep">

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages/status', ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id" value="<?= (int)$message['id'] ?>">
    <div class="row gap">
      <label style="max-width:260px">
        Statut
        <select name="status">
          <option value="new" <?= ($status === 'new') ? 'selected' : '' ?>>Nouveau</option>
          <option value="read" <?= ($status === 'read') ? 'selected' : '' ?>>Lu</option>
          <option value="archived" <?= ($status === 'archived') ? 'selected' : '' ?>>Archivé</option>
        </select>
      </label>
    </div>
    <button class="btn primary" type="submit">Enregistrer</button>
  </form>
</section>

