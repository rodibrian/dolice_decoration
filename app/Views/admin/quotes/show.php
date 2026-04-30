<?php
/** @var array<string, mixed> $quote */
/** @var list<array<string, mixed>> $items */
/** @var string|null $flash */
/** @var string|null $error */

$status = (string)($quote['status'] ?? 'new');
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">📄</span>Devis #<?= (int)$quote['id'] ?></h1>
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

  <?php if (!empty($items)): ?>
    <hr class="sep">
    <div class="card sub">
      <strong>Services demandés</strong>
      <div class="table-wrap" style="margin-top:10px">
        <table class="table table-hover align-middle" style="min-width:520px">
          <thead>
          <tr>
            <th>Service</th>
            <th>Prix</th>
            <th>Unité</th>
            <th>Qté</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($items as $it): ?>
            <?php
              $p = $it['unit_price'];
              $priceText = ($p === null || $p === '') ? '—' : number_format((float)$p, 0, ',', ' ') . ' Ar';
            ?>
            <tr>
              <td><?= htmlspecialchars((string)$it['service_title'], ENT_QUOTES, 'UTF-8') ?></td>
              <td class="muted"><?= htmlspecialchars($priceText, ENT_QUOTES, 'UTF-8') ?></td>
              <td class="muted"><?= htmlspecialchars((string)($it['price_unit'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
              <td class="muted"><?= (int)($it['qty'] ?? 1) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="muted" style="margin-top:8px">Les prix sont enregistrés comme “snapshot” au moment de la demande.</div>
    </div>
  <?php endif; ?>

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

