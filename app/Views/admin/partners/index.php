<?php
/** @var list<array<string, mixed>> $partners */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1>Partenaires</h1>
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners/create', ENT_QUOTES, 'UTF-8') ?>">Nouveau</a>
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
          <tr>
            <td><?= (int)$p['id'] ?></td>
            <td><?= htmlspecialchars((string)$p['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($p['category'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= ((int)$p['is_published'] === 1) ? 'Oui' : 'Non' ?></td>
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
</section>

