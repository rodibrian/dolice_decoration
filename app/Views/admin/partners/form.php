<?php
/** @var array<string, mixed>|null $partner */
/** @var string|null $error */

$isEdit = is_array($partner) && isset($partner['id']);
$action = $isEdit ? '/admin/partners/update' : '/admin/partners/store';
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🤝</span><?= $isEdit ? 'Modifier' : 'Nouveau' ?> partenaire</h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $action, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$partner['id'] ?>">
    <?php endif; ?>

    <label>
      Nom
      <input type="text" name="name" required value="<?= htmlspecialchars((string)($partner['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <div class="row gap">
      <label style="min-width:240px">
        URL (optionnel)
        <input type="text" name="url" value="<?= htmlspecialchars((string)($partner['url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label style="min-width:240px">
        Catégorie
        <input type="text" name="category" value="<?= htmlspecialchars((string)($partner['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <label>
      Logo (optionnel)
      <input type="file" name="logo" accept="image/*">
    </label>

    <?php if (!empty($partner['logo_path'])): ?>
      <div class="muted">Logo actuel:</div>
      <div class="thumb">
        <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$partner['logo_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
      </div>
    <?php endif; ?>

    <div class="row gap">
      <label style="max-width:180px">
        Ordre
        <input type="number" name="display_order" value="<?= (int)($partner['display_order'] ?? 0) ?>">
      </label>

      <label class="check">
        <input type="checkbox" name="is_published" value="1" <?= ((int)($partner['is_published'] ?? 1) === 1) ? 'checked' : '' ?>>
        Publié
      </label>
    </div>

    <button class="btn primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>
</section>

