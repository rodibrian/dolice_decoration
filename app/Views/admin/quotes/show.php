<?php
/** @var array<string, mixed> $quote */
/** @var string|null $flash */
/** @var string|null $error */

$status = (string)($quote['status'] ?? 'new');
?>
<section class="card">
  <div class="row between">
    <h1>Devis #<?= (int)$quote['id'] ?></h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div class="grid2">
    <div class="card sub">
      <div><strong>Nom:</strong> <?= htmlspecialchars((string)$quote['name'], ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Téléphone:</strong> <?= htmlspecialchars((string)($quote['phone'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Email:</strong> <?= htmlspecialchars((string)($quote['email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Type projet:</strong> <?= htmlspecialchars((string)($quote['project_type'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
      <div><strong>Reçu le:</strong> <?= htmlspecialchars((string)($quote['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    <div class="card sub">
      <strong>Message</strong>
      <div class="muted" style="white-space:pre-wrap;margin-top:8px"><?= htmlspecialchars((string)($quote['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
    </div>
  </div>

  <hr class="sep">

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes/update', ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id" value="<?= (int)$quote['id'] ?>">
    <div class="row gap">
      <label style="max-width:260px">
        Statut
        <select name="status">
          <option value="new" <?= ($status === 'new') ? 'selected' : '' ?>>Nouveau</option>
          <option value="in_progress" <?= ($status === 'in_progress') ? 'selected' : '' ?>>En cours</option>
          <option value="replied" <?= ($status === 'replied') ? 'selected' : '' ?>>Répondu</option>
          <option value="done" <?= ($status === 'done') ? 'selected' : '' ?>>Terminé</option>
          <option value="archived" <?= ($status === 'archived') ? 'selected' : '' ?>>Archivé</option>
        </select>
      </label>
      <label style="flex:1">
        Notes internes
        <textarea name="internal_notes" rows="5"><?= htmlspecialchars((string)($quote['internal_notes'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
      </label>
    </div>
    <button class="btn primary" type="submit">Enregistrer</button>
  </form>
</section>

