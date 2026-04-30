<?php
/** @var array<string, mixed>|null $project */
/** @var list<array<string, mixed>> $images */
/** @var string|null $error */

$isEdit = is_array($project) && isset($project['id']);
$action = $isEdit ? '/admin/projects/update' : '/admin/projects/store';
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🏗</span><?= $isEdit ? 'Modifier' : 'Nouvelle' ?> réalisation</h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $action, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$project['id'] ?>">
    <?php endif; ?>

    <label>
      Titre
      <input type="text" name="title" required value="<?= htmlspecialchars((string)($project['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Slug (ex: projet-plafond-2026)
      <input type="text" name="slug" required value="<?= htmlspecialchars((string)($project['slug'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <div class="row gap">
      <label style="min-width:240px">
        Catégorie
        <input type="text" name="category" value="<?= htmlspecialchars((string)($project['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label style="min-width:240px">
        Type de travaux
        <input type="text" name="work_type" value="<?= htmlspecialchars((string)($project['work_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <div class="row gap">
      <label style="min-width:240px">
        Localisation
        <input type="text" name="location" value="<?= htmlspecialchars((string)($project['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label style="max-width:220px">
        Date (YYYY-MM-DD)
        <input type="text" name="project_date" value="<?= htmlspecialchars((string)($project['project_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <label>
      Description
      <textarea name="description" rows="10"><?= htmlspecialchars((string)($project['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
    </label>

    <div class="row gap">
      <label style="max-width:260px">
        Statut
        <select name="status">
          <?php $st = (string)($project['status'] ?? 'draft'); ?>
          <option value="draft" <?= ($st === 'draft') ? 'selected' : '' ?>>Brouillon</option>
          <option value="published" <?= ($st === 'published') ? 'selected' : '' ?>>Publié</option>
        </select>
      </label>

      <label class="check">
        <input type="checkbox" name="is_featured" value="1" <?= ((int)($project['is_featured'] ?? 0) === 1) ? 'checked' : '' ?>>
        Mettre en avant
      </label>
    </div>

    <label>
      Images (multi)
      <input type="file" name="images[]" accept="image/*" multiple>
    </label>

    <button class="btn primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>

  <?php if ($isEdit): ?>
    <hr class="sep">
    <h2 class="page-title"><span class="page-icon">🖼</span>Images</h2>
    <?php if (empty($images)): ?>
      <p class="muted">Aucune image.</p>
    <?php else: ?>
      <div class="media-grid">
        <?php foreach ($images as $img): ?>
          <div class="media-tile">
            <div class="media-thumb">
              <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$img['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
            </div>
            <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects/images/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer cette image ?');">
              <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
              <input type="hidden" name="image_id" value="<?= (int)$img['id'] ?>">
              <button class="btn btn-sm danger w-100" type="submit"><i class="bi bi-trash"></i>Supprimer</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section>

