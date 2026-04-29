<?php
/** @var array<string, mixed>|null $service */
/** @var string|null $error */

$isEdit = is_array($service) && isset($service['id']);
$action = $isEdit ? '/admin/services/update' : '/admin/services/store';
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🛠</span><?= $isEdit ? 'Modifier' : 'Nouveau' ?> service</h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $action, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$service['id'] ?>">
    <?php endif; ?>

    <label>
      Titre
      <input type="text" name="title" required value="<?= htmlspecialchars((string)($service['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Slug (ex: plafond)
      <input type="text" name="slug" required value="<?= htmlspecialchars((string)($service['slug'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Catégorie
      <input type="text" name="category" value="<?= htmlspecialchars((string)($service['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Description
      <textarea name="description" rows="8"><?= htmlspecialchars((string)($service['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
    </label>

    <label>
      Image (jpg/png/webp)
      <input type="file" name="image" accept="image/*">
    </label>

    <?php if (!empty($service['image_path'])): ?>
      <div class="muted">Image actuelle:</div>
      <div class="thumb">
        <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$service['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
      </div>
    <?php endif; ?>

    <div class="row gap">
      <label style="max-width:180px">
        Ordre
        <input type="number" name="display_order" value="<?= (int)($service['display_order'] ?? 0) ?>">
      </label>

      <label class="check">
        <input type="checkbox" name="is_published" value="1" <?= ((int)($service['is_published'] ?? 1) === 1) ? 'checked' : '' ?>>
        Publié
      </label>
    </div>

    <button class="btn primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>
</section>

