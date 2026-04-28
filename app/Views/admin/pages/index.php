<?php
/** @var list<array<string, mixed>> $pages */
/** @var string|null $flash */
?>
<section class="card">
  <div class="row between">
    <h1>Pages</h1>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <p class="muted">Clés recommandées: <code>about</code>, <code>faq</code>, <code>contact</code>, <code>zones</code>, <code>legal</code></p>

  <div class="table-wrap">
    <table class="table">
      <thead>
      <tr>
        <th>Clé</th>
        <th>Titre</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <?php if (empty($pages)): ?>
        <tr><td colspan="3" class="muted">Aucune page. Tu peux en créer via l’URL d’édition (ex: about).</td></tr>
      <?php else: ?>
        <?php foreach ($pages as $p): ?>
          <tr>
            <td class="muted"><?= htmlspecialchars((string)$p['page_key'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td class="actions">
              <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages/edit?page_key=' . urlencode((string)$p['page_key']), ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="row gap" style="margin-top:12px">
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages/edit?page_key=about', ENT_QUOTES, 'UTF-8') ?>">Créer/éditer “about”</a>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages/edit?page_key=faq', ENT_QUOTES, 'UTF-8') ?>">Créer/éditer “faq”</a>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages/edit?page_key=contact', ENT_QUOTES, 'UTF-8') ?>">Créer/éditer “contact”</a>
  </div>
</section>

