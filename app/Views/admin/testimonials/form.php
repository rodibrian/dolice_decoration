<?php
/** @var array<string, mixed>|null $testimonial */
/** @var string|null $error */

$isEdit = is_array($testimonial) && isset($testimonial['id']);
$action = $isEdit ? '/admin/testimonials/update' : '/admin/testimonials/store';
$status = (string)($testimonial['status'] ?? 'pending');
?>

<section class="card">
  <div class="row between">
    <h1><?= $isEdit ? 'Modifier' : 'Nouveau' ?> témoignage</h1>
    <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>">Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $action, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$testimonial['id'] ?>">
    <?php endif; ?>

    <label>
      Nom du client
      <input type="text" name="client_name" required value="<?= htmlspecialchars((string)($testimonial['client_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Entreprise / statut
      <input type="text" name="client_company" value="<?= htmlspecialchars((string)($testimonial['client_company'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </label>

    <label>
      Contenu
      <textarea name="content" rows="8" required><?= htmlspecialchars((string)($testimonial['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
    </label>

    <div class="row gap">
      <label style="max-width:180px">
        Note (1-5)
        <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars((string)($testimonial['rating'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>

      <label style="max-width:260px">
        Statut
        <select name="status">
          <option value="pending" <?= ($status === 'pending') ? 'selected' : '' ?>>En attente</option>
          <option value="approved" <?= ($status === 'approved') ? 'selected' : '' ?>>Approuvé</option>
        </select>
      </label>
    </div>

    <label>
      Logo / image (optionnel)
      <input type="file" name="logo" accept="image/*">
    </label>

    <?php if (!empty($testimonial['logo_path'])): ?>
      <div class="muted">Image actuelle:</div>
      <div class="thumb">
        <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$testimonial['logo_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
      </div>
    <?php endif; ?>

    <button class="btn primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>
</section>

