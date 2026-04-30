<?php
/** @var list<array<string, mixed>> $slides */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🎞️</span>Slides accueil</h1>
    <a class="btn primary btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/hero-slides/create', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-plus-lg"></i>Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div data-table-filter>
    <div class="crud-toolbar">
      <input class="form-control" type="search" placeholder="Rechercher un slide..." data-filter-text>
      <select class="form-select" data-filter-status>
        <option value="">Tous les statuts</option>
        <option value="oui">Publié</option>
        <option value="non">Non publié</option>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table table-hover align-middle">
        <thead>
        <tr>
          <th>#</th>
          <th>Média</th>
          <th>Titre</th>
          <th>Type</th>
          <th>Publié</th>
          <th>Ordre</th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($slides)): ?>
          <tr><td colspan="7" class="muted">Aucun slide.</td></tr>
        <?php else: ?>
          <?php foreach ($slides as $s): ?>
            <?php
              $type = (string)($s['media_type'] ?? 'image');
              if (!in_array($type, ['image', 'video'], true)) $type = 'image';
              $pub = ((int)($s['is_published'] ?? 0) === 1);
              $title = trim((string)($s['title'] ?? '')) ?: '—';
              $mediaPath = trim((string)($s['media_path'] ?? ''));
            ?>
            <tr
              data-row
              data-search="<?= htmlspecialchars(strtolower($title . ' ' . (string)($s['subtitle'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
              data-status="<?= $pub ? 'oui' : 'non' ?>"
            >
              <td><?= (int)$s['id'] ?></td>
              <td>
                <?php if ($mediaPath !== '' && $type === 'image'): ?>
                  <div class="thumb" style="max-width:180px">
                    <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $mediaPath, ENT_QUOTES, 'UTF-8') ?>" alt="">
                  </div>
                <?php else: ?>
                  <span class="muted"><?= $type === 'video' ? '🎥 Vidéo' : '—' ?></span>
                <?php endif; ?>
              </td>
              <td>
                <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
                <div class="muted"><?= htmlspecialchars((string)($s['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
              </td>
              <td><span class="badge text-bg-<?= $type === 'video' ? 'primary' : 'secondary' ?>"><?= $type === 'video' ? 'Vidéo' : 'Image' ?></span></td>
              <td><span class="badge text-bg-<?= $pub ? 'success' : 'secondary' ?>"><?= $pub ? 'Oui' : 'Non' ?></span></td>
              <td class="muted"><?= (int)($s['display_order'] ?? 0) ?></td>
              <td class="actions">
                <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/hero-slides/edit?id=' . (int)$s['id'], ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
                <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/hero-slides/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer ce slide ?');">
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
  </div>
</section>

