<?php
/** @var array<string, mixed>|null $post */
/** @var string|null $error */

$isEdit = is_array($post) && isset($post['id']);
$action = $isEdit ? '/admin/posts/update' : '/admin/posts/store';
$status = (string)($post['status'] ?? 'draft');
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">📰</span><?= $isEdit ? 'Modifier' : 'Nouvel' ?> article</h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $action, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
    <?php endif; ?>

    <label>
      Titre
      <input type="text" name="title" required value="<?= htmlspecialchars((string)($post['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Slug (ex: conseil-peinture)
      <input type="text" name="slug" required value="<?= htmlspecialchars((string)($post['slug'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Extrait
      <textarea name="excerpt" rows="3"><?= htmlspecialchars((string)($post['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
    </label>

    <label>
      Contenu
      <textarea name="content" rows="12"><?= htmlspecialchars((string)($post['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
    </label>

    <div class="row gap">
      <label style="min-width:220px">
        Auteur
        <input type="text" name="author" value="<?= htmlspecialchars((string)($post['author'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label style="min-width:220px">
        Mots-clés (séparés par virgule)
        <input type="text" name="keywords" value="<?= htmlspecialchars((string)($post['keywords'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <div class="row gap">
      <label style="max-width:260px">
        Statut
        <select name="status">
          <option value="draft" <?= ($status === 'draft') ? 'selected' : '' ?>>Brouillon</option>
          <option value="published" <?= ($status === 'published') ? 'selected' : '' ?>>Publié</option>
        </select>
      </label>

      <label style="max-width:260px">
        Publié le (YYYY-MM-DD HH:MM:SS)
        <input type="text" name="published_at" value="<?= htmlspecialchars((string)($post['published_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <label>
      Image principale (jpg/png/webp)
      <input type="file" name="featured_image" accept="image/*">
    </label>

    <?php if (!empty($post['featured_image'])): ?>
      <div class="muted">Image actuelle:</div>
      <div class="thumb">
        <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$post['featured_image'], ENT_QUOTES, 'UTF-8') ?>" alt="">
      </div>
    <?php endif; ?>

    <button class="btn primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>
</section>

