<?php
/** @var array<string, string|null> $settings */
/** @var string|null $flash */

$get = static function (string $k) use ($settings): string {
    $v = $settings[$k] ?? '';
    return $v !== null ? (string)$v : '';
};
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">⚙️</span>Paramètres</h1>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/settings/update', ENT_QUOTES, 'UTF-8') ?>">
    <div class="grid2">
      <label>
        Téléphone
        <input type="text" name="phone" value="<?= htmlspecialchars($get('phone'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        WhatsApp
        <input type="text" name="whatsapp" value="<?= htmlspecialchars($get('whatsapp'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Email
        <input type="text" name="email" value="<?= htmlspecialchars($get('email'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Adresse
        <input type="text" name="address" value="<?= htmlspecialchars($get('address'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Horaires
        <input type="text" name="hours" value="<?= htmlspecialchars($get('hours'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Zone d’intervention
        <input type="text" name="service_area" value="<?= htmlspecialchars($get('service_area'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Facebook (url)
        <input type="text" name="facebook" value="<?= htmlspecialchars($get('facebook'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Instagram (url)
        <input type="text" name="instagram" value="<?= htmlspecialchars($get('instagram'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Image de cover accueil (hero)
        <input type="text" name="hero_cover_image" placeholder="/uploads/2151892472.jpg" value="<?= htmlspecialchars($get('hero_cover_image'), ENT_QUOTES, 'UTF-8') ?>">
        <small class="muted">Chemin public ou URL. Exemple: <code>/uploads/2151892472.jpg</code></small>
        <input type="file" name="hero_cover_file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <small class="muted">Ou importer un fichier image pour remplacer la cover.</small>
      </label>
    </div>
    <button class="btn primary" type="submit">Enregistrer</button>
  </form>
</section>

