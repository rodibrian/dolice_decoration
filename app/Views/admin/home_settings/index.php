<?php
/** @var array<string, string|null> $settings */
/** @var string|null $flash */
/** @var string|null $error */

$get = static function (string $k, string $default = '') use ($settings): string {
    $v = $settings[$k] ?? null;
    if ($v === null || $v === '') {
        return $default;
    }
    return (string)$v;
};
$isOn = static function (string $k, bool $defaultOn = true) use ($settings): bool {
    $v = $settings[$k] ?? null;
    if ($v === null || $v === '') {
        return $defaultOn;
    }
    return (string)$v === '1';
};

$theme = $get('site_theme', 'default');
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🏠</span>Gérer Accueil</h1>
    <a class="btn btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/hero-slides', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images"></i>Slides</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/home/update', ENT_QUOTES, 'UTF-8') ?>">
    <div class="grid2">
      <label>
        Badge (petit texte)
        <input type="text" name="home_badge_text" placeholder="Ex: Finitions premium • Délais maîtrisés" value="<?= htmlspecialchars($get('home_badge_text', 'Finitions premium • Délais maîtrisés • Suivi pro'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Titre principal (hero)
        <input type="text" name="home_hero_title" placeholder="Ex: Dolice Decoration" value="<?= htmlspecialchars($get('home_hero_title', ''), ENT_QUOTES, 'UTF-8') ?>">
        <small class="muted">Si vide: le titre par défaut de la page.</small>
      </label>

      <label style="grid-column:1/-1">
        Sous-titre (hero)
        <textarea name="home_hero_subtitle" rows="3" placeholder="Ex: Entreprise de finition & décoration bâtiment..."><?= htmlspecialchars($get('home_hero_subtitle', ''), ENT_QUOTES, 'UTF-8') ?></textarea>
      </label>

      <label>
        Bouton principal (label)
        <input type="text" name="home_primary_cta_label" placeholder="Ex: Demander un devis" value="<?= htmlspecialchars($get('home_primary_cta_label', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Bouton principal (URL)
        <input type="text" name="home_primary_cta_url" placeholder="/devis" value="<?= htmlspecialchars($get('home_primary_cta_url', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>

      <label>
        Bouton secondaire (label)
        <input type="text" name="home_secondary_cta_label" placeholder="Ex: Voir nos réalisations" value="<?= htmlspecialchars($get('home_secondary_cta_label', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Bouton secondaire (URL)
        <input type="text" name="home_secondary_cta_url" placeholder="/realisations" value="<?= htmlspecialchars($get('home_secondary_cta_url', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>

      <label style="grid-column:1/-1">
        Image de cover Accueil (hero) + Admin login
        <input type="text" name="hero_cover_image" placeholder="/uploads/cover.jpg" value="<?= htmlspecialchars($get('hero_cover_image', ''), ENT_QUOTES, 'UTF-8') ?>">
        <input type="file" name="hero_cover_file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <small class="muted">Si tu importes un fichier, il remplace le chemin ci-dessus.</small>
      </label>

      <label style="grid-column:1/-1">
        <input type="checkbox" name="home_slides_enabled" value="1" <?= $isOn('home_slides_enabled', true) ? 'checked' : '' ?>>
        Afficher le bloc “Slides” sur l’accueil
      </label>
    </div>

    <hr class="sep">

    <div class="grid2">
      <div style="grid-column:1/-1" class="fw-semibold">Thème du site (3 thèmes)</div>
      <label>
        Choisir un thème
        <select name="site_theme">
          <option value="default" <?= $theme === 'default' ? 'selected' : '' ?>>Default (orange/bleu)</option>
          <option value="ocean" <?= $theme === 'ocean' ? 'selected' : '' ?>>Ocean (bleu/teal)</option>
          <option value="sunset" <?= $theme === 'sunset' ? 'selected' : '' ?>>Sunset (rose/violet)</option>
        </select>
      </label>
      <div class="muted small d-flex align-items-end">Le thème s’applique sur tout le site (navbar, boutons, badges, etc.).</div>
    </div>

    <hr class="sep">

    <div class="grid2">
      <div style="grid-column:1/-1" class="fw-semibold">Navigation (afficher / cacher)</div>

      <label><input type="checkbox" name="nav_show_services" value="1" <?= $isOn('nav_show_services', true) ? 'checked' : '' ?>> Services</label>
      <label><input type="checkbox" name="nav_show_projects" value="1" <?= $isOn('nav_show_projects', true) ? 'checked' : '' ?>> Réalisations</label>
      <label><input type="checkbox" name="nav_show_blog" value="1" <?= $isOn('nav_show_blog', true) ? 'checked' : '' ?>> Blog</label>
      <label><input type="checkbox" name="nav_show_faq" value="1" <?= $isOn('nav_show_faq', true) ? 'checked' : '' ?>> FAQ</label>
      <label><input type="checkbox" name="nav_show_contact" value="1" <?= $isOn('nav_show_contact', true) ? 'checked' : '' ?>> Contact</label>
      <label><input type="checkbox" name="nav_show_quote" value="1" <?= $isOn('nav_show_quote', true) ? 'checked' : '' ?>> Bouton “Devis”</label>
      <label><input type="checkbox" name="nav_show_history" value="1" <?= $isOn('nav_show_history', true) ? 'checked' : '' ?>> Notre histoire</label>
    </div>

    <hr class="sep">

    <div class="grid2">
      <div style="grid-column:1/-1" class="fw-semibold">Footer (afficher / cacher)</div>

      <label><input type="checkbox" name="footer_show_services" value="1" <?= $isOn('footer_show_services', true) ? 'checked' : '' ?>> Services</label>
      <label><input type="checkbox" name="footer_show_projects" value="1" <?= $isOn('footer_show_projects', true) ? 'checked' : '' ?>> Réalisations</label>
      <label><input type="checkbox" name="footer_show_blog" value="1" <?= $isOn('footer_show_blog', true) ? 'checked' : '' ?>> Blog</label>
      <label><input type="checkbox" name="footer_show_contact" value="1" <?= $isOn('footer_show_contact', true) ? 'checked' : '' ?>> Contact</label>
      <label><input type="checkbox" name="footer_show_history" value="1" <?= $isOn('footer_show_history', true) ? 'checked' : '' ?>> Notre histoire</label>
      <label><input type="checkbox" name="footer_show_faq" value="1" <?= $isOn('footer_show_faq', true) ? 'checked' : '' ?>> FAQ</label>
      <label><input type="checkbox" name="footer_show_quote" value="1" <?= $isOn('footer_show_quote', true) ? 'checked' : '' ?>> Demander un devis</label>
      <label><input type="checkbox" name="footer_show_admin" value="1" <?= $isOn('footer_show_admin', true) ? 'checked' : '' ?>> Lien Admin</label>
    </div>

    <button class="btn primary" type="submit"><i class="bi bi-check2"></i>Enregistrer</button>
  </form>
</section>

