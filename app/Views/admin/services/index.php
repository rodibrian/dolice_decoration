<?php
/** @var list<array<string, mixed>> $services */
/** @var string|null $flash */
?>
<section class="card">
  <div class="row between">
    <h1>Services</h1>
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services/create', ENT_QUOTES, 'UTF-8') ?>">Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div class="table-wrap">
    <table class="table">
      <thead>
      <tr>
        <th>#</th>
        <th>Titre</th>
        <th>Slug</th>
        <th>Catégorie</th>
        <th>Publié</th>
        <th>Ordre</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($services)): ?>
        <tr><td colspan="7" class="muted">Aucun service.</td></tr>
      <?php else: ?>
        <?php foreach ($services as $s): ?>
          <tr>
            <td><?= (int)$s['id'] ?></td>
            <td><?= htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)$s['slug'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($s['category'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= ((int)$s['is_published'] === 1) ? 'Oui' : 'Non' ?></td>
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
</section>

