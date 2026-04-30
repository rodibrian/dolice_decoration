<?php
/** @var array<string, mixed>|null $slide */
/** @var string|null $error */
/**
 * @var string|null $title
 */

$isEdit = is_array($slide) && isset($slide['id']);
$action = $isEdit ? '/admin/hero-slides/update' : '/admin/hero-slides/store';

$mediaType = (string)($slide['media_type'] ?? 'image');
if (!in_array($mediaType, ['image', 'video'], true)) $mediaType = 'image';
$isPublished = ((int)($slide['is_published'] ?? 1) === 1);
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🎞️</span><?= $isEdit ? 'Modifier' : 'Nouveau' ?> slide</h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/hero-slides', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $action, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$slide['id'] ?>">
    <?php endif; ?>

    <label>
      Titre (optionnel)
      <input type="text" name="title" value="<?= htmlspecialchars((string)($slide['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Sous-titre (optionnel)
      <input type="text" name="subtitle" value="<?= htmlspecialchars((string)($slide['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <div class="row gap">
      <label style="min-width:240px">
        Bouton (label, optionnel)
        <input type="text" name="cta_label" value="<?= htmlspecialchars((string)($slide['cta_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label style="min-width:240px">
        Bouton (URL, optionnel)
        <input type="text" name="cta_url" value="<?= htmlspecialchars((string)($slide['cta_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="/realisations">
      </label>
    </div>

    <label>
      Média (image jpg/png/webp ou vidéo mp4/webm)
      <input type="file" name="media" accept="image/*,video/mp4,video/webm" <?= $isEdit ? '' : 'required' ?>>
    </label>

    <?php if ($isEdit && !empty($slide['media_path'])): ?>
      <div class="muted">Média actuel:</div>
      <?php if ($mediaType === 'image'): ?>
        <div class="thumb">
          <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$slide['media_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
        </div>
      <?php else: ?>
        <div class="thumb" style="max-width:360px">
          <video src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$slide['media_path'], ENT_QUOTES, 'UTF-8') ?>" muted playsinline controls style="width:100%;border-radius:12px;border:1px solid var(--border)"></video>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <div class="row gap">
      <label style="max-width:180px">
        Ordre
        <input type="number" name="display_order" value="<?= (int)($slide['display_order'] ?? 0) ?>">
      </label>
      <label class="check">
        <input type="checkbox" name="is_published" value="1" <?= $isPublished ? 'checked' : '' ?>>
        Afficher sur le site
      </label>
    </div>

    <button class="btn primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>
</section>

