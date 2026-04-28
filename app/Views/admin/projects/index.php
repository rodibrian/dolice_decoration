<?php
/** @var list<array<string, mixed>> $projects */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1>Réalisations</h1>
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects/create', ENT_QUOTES, 'UTF-8') ?>">Nouveau</a>
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
        <th>Titre</th>
        <th>Catégorie</th>
        <th>Lieu</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Vedette</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($projects)): ?>
        <tr><td colspan="8" class="muted">Aucune réalisation.</td></tr>
      <?php else: ?>
        <?php foreach ($projects as $p): ?>
          <tr>
            <td><?= (int)$p['id'] ?></td>
            <td><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?><div class="muted"><?= htmlspecialchars((string)$p['slug'], ENT_QUOTES, 'UTF-8') ?></div></td>
            <td class="muted"><?= htmlspecialchars((string)($p['category'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($p['location'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted"><?= htmlspecialchars((string)($p['project_date'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= ((string)$p['status'] === 'published') ? 'Publié' : 'Brouillon' ?></td>
            <td><?= ((int)$p['is_featured'] === 1) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects/edit?id=' . (int)$p['id'], ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
              <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer cette réalisation ?');">
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

