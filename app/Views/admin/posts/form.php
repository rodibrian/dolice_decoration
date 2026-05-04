<?php
/** @var array<string, mixed>|null $post */
/** @var array<string, string> $trans_en */
/** @var array<string, string> $trans_mg */
/** @var string|null $error */

$trans_en = $trans_en ?? [];
$trans_mg = $trans_mg ?? [];
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

    <hr class="sep">
    <h3 style="margin:0 0 8px"><?= htmlspecialchars(t('admin.posts.trans_en'), ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars(t('admin.posts.trans_mg'), ENT_QUOTES, 'UTF-8') ?></h3>
    <p class="small text-secondary mb-3"><?= htmlspecialchars(t('admin.home.trans_hint'), ENT_QUOTES, 'UTF-8') ?></p>
    <div class="d-flex gap-2 flex-wrap mb-3">
      <button class="btn btn-sm btn-light border" type="button" data-tr-toggle="en">Content In English</button>
      <button class="btn btn-sm btn-light border" type="button" data-tr-toggle="mg">Ampiditra Malagasy</button>
    </div>

    <div class="grid2">
      <div class="d-none" data-tr-panel="en">
        <div class="fw-semibold mb-2"><?= htmlspecialchars(t('admin.posts.trans_en'), ENT_QUOTES, 'UTF-8') ?></div>
        <label>Titre <input type="text" name="tr_en_title" value="<?= htmlspecialchars((string)($trans_en['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Extrait <textarea name="tr_en_excerpt" rows="3"><?= htmlspecialchars((string)($trans_en['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
        <label>Contenu <textarea name="tr_en_content" rows="8"><?= htmlspecialchars((string)($trans_en['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
        <label>Auteur <input type="text" name="tr_en_author" value="<?= htmlspecialchars((string)($trans_en['author'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Mots-clés <input type="text" name="tr_en_keywords" value="<?= htmlspecialchars((string)($trans_en['keywords'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      </div>
      <div class="d-none" data-tr-panel="mg">
        <div class="fw-semibold mb-2"><?= htmlspecialchars(t('admin.posts.trans_mg'), ENT_QUOTES, 'UTF-8') ?></div>
        <label>Titre <input type="text" name="tr_mg_title" value="<?= htmlspecialchars((string)($trans_mg['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Extrait <textarea name="tr_mg_excerpt" rows="3"><?= htmlspecialchars((string)($trans_mg['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
        <label>Contenu <textarea name="tr_mg_content" rows="8"><?= htmlspecialchars((string)($trans_mg['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
        <label>Auteur <input type="text" name="tr_mg_author" value="<?= htmlspecialchars((string)($trans_mg['author'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Mots-clés <input type="text" name="tr_mg_keywords" value="<?= htmlspecialchars((string)($trans_mg['keywords'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      </div>
    </div>

    <script>
      (function () {
        const root = document.currentScript?.closest('form');
        if (!root) return;
        const panels = {
          en: root.querySelector('[data-tr-panel="en"]'),
          mg: root.querySelector('[data-tr-panel="mg"]'),
        };
        root.querySelectorAll('[data-tr-toggle]').forEach(function (btn) {
          btn.addEventListener('click', function () {
            const k = btn.getAttribute('data-tr-toggle');
            const p = panels[k];
            if (!p) return;
            p.classList.toggle('d-none');
          });
        });
      })();
    </script>

    <button class="btn primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>
</section>

