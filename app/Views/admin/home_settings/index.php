<?php
/** @var array<string, string|null> $settings */
/** @var array<string, array{can: bool, cap: string}> $navFooterRights */
/** @var array<string, string> $trans_en */
/** @var array<string, string> $trans_mg */
/** @var string|null $flash */
/** @var string|null $error */

$trans_en = $trans_en ?? [];
$trans_mg = $trans_mg ?? [];
$trEn = static function (string $k) use ($trans_en): string {
    return htmlspecialchars((string)($trans_en[$k] ?? ''), ENT_QUOTES, 'UTF-8');
};
$trMg = static function (string $k) use ($trans_mg): string {
    return htmlspecialchars((string)($trans_mg[$k] ?? ''), ENT_QUOTES, 'UTF-8');
};

$capLabels = [
    'services.view' => 'Voir les services',
    'projects.view' => 'Voir les réalisations',
    'posts.view' => 'Voir les articles',
    'pages.view' => 'Voir les pages',
    'messages.view' => 'Voir les messages',
    'quotes.view' => 'Voir les devis',
    'admin.super' => 'Super admin',
];
$nfCan = static function (string $key) use ($navFooterRights): bool {
    return (bool)($navFooterRights[$key]['can'] ?? true);
};
$nfCapLabel = static function (string $key) use ($navFooterRights, $capLabels): string {
    $cap = (string)($navFooterRights[$key]['cap'] ?? '');
    return $capLabels[$cap] ?? $cap;
};

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
    if ((string)$v === '0') {
        return false;
    }
    return (string)$v === '1';
};

$theme = \App\Core\SiteTheme::normalize($get('site_theme', \App\Core\SiteTheme::DEFAULT));
$themeCatalog = \App\Core\SiteTheme::catalog();
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

    <div class="admin-form-section">
      <div class="fw-semibold mb-1"><?= htmlspecialchars(t('admin.home.trans_site'), ENT_QUOTES, 'UTF-8') ?></div>
      <p class="small text-secondary mb-3"><?= htmlspecialchars(t('admin.home.trans_hint'), ENT_QUOTES, 'UTF-8') ?></p>
      <div class="grid2">
        <div style="grid-column:1/-1" class="d-flex gap-2 flex-wrap mb-2">
          <button class="btn btn-sm btn-light border" type="button" data-tr-toggle="en">Content In English</button>
          <button class="btn btn-sm btn-light border" type="button" data-tr-toggle="mg">Ampiditra Malagasy</button>
        </div>

        <div class="d-none" data-tr-panel="en">
          <div class="fw-semibold mb-2"><?= htmlspecialchars(t('admin.services.trans_en'), ENT_QUOTES, 'UTF-8') ?></div>
          <label>Badge <input type="text" name="en_home_badge_text" value="<?= $trEn('home_badge_text') ?>"></label>
          <label>Titre hero <input type="text" name="en_home_hero_title" value="<?= $trEn('home_hero_title') ?>"></label>
          <label style="grid-column:1/-1">Sous-titre <textarea name="en_home_hero_subtitle" rows="3"><?= $trEn('home_hero_subtitle') ?></textarea></label>
          <label>CTA principal <input type="text" name="en_home_primary_cta_label" value="<?= $trEn('home_primary_cta_label') ?>"></label>
          <label>CTA secondaire <input type="text" name="en_home_secondary_cta_label" value="<?= $trEn('home_secondary_cta_label') ?>"></label>
        </div>
        <div class="d-none" data-tr-panel="mg">
          <div class="fw-semibold mb-2"><?= htmlspecialchars(t('admin.services.trans_mg'), ENT_QUOTES, 'UTF-8') ?></div>
          <label>Badge <input type="text" name="mg_home_badge_text" value="<?= $trMg('home_badge_text') ?>"></label>
          <label>Titre hero <input type="text" name="mg_home_hero_title" value="<?= $trMg('home_hero_title') ?>"></label>
          <label style="grid-column:1/-1">Sous-titre <textarea name="mg_home_hero_subtitle" rows="3"><?= $trMg('home_hero_subtitle') ?></textarea></label>
          <label>CTA principal <input type="text" name="mg_home_primary_cta_label" value="<?= $trMg('home_primary_cta_label') ?>"></label>
          <label>CTA secondaire <input type="text" name="mg_home_secondary_cta_label" value="<?= $trMg('home_secondary_cta_label') ?>"></label>
        </div>
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

    <hr class="sep">

    <div class="admin-form-section">
      <div class="fw-semibold mb-1">Thème du site (5 palettes)</div>
      <p class="small text-secondary mb-3">Couleurs inspirées des palettes <a href="https://flatuicolors.com/" target="_blank" rel="noopener noreferrer">Flat UI Colors</a> : le site public et les accents de l’administration suivent le thème choisi.</p>
      <div class="theme-pick-grid">
        <?php foreach (\App\Core\SiteTheme::allowedIds() as $tid):
            $meta = $themeCatalog[$tid] ?? ['label' => $tid, 'mode' => '', 'palette' => '', 'swatches' => []];
            $sel = $theme === $tid;
        ?>
        <label class="theme-pick-card<?= $sel ? ' is-selected' : '' ?>">
          <input type="radio" name="site_theme" value="<?= htmlspecialchars($tid, ENT_QUOTES, 'UTF-8') ?>" <?= $sel ? 'checked' : '' ?> class="theme-pick-input">
          <span class="theme-pick-swatches" aria-hidden="true">
            <?php foreach ($meta['swatches'] as $hex): ?>
              <span class="theme-pick-dot" style="background:<?= htmlspecialchars((string)$hex, ENT_QUOTES, 'UTF-8') ?>"></span>
            <?php endforeach; ?>
          </span>
          <span class="theme-pick-title"><?= htmlspecialchars((string)$meta['label'], ENT_QUOTES, 'UTF-8') ?></span>
          <span class="theme-pick-badge"><?= htmlspecialchars((string)$meta['mode'], ENT_QUOTES, 'UTF-8') ?></span>
          <span class="theme-pick-hint"><?= htmlspecialchars((string)$meta['palette'], ENT_QUOTES, 'UTF-8') ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </div>

    <hr class="sep">

    <div class="admin-form-section">
      <div class="fw-semibold mb-1">Navigation (afficher / cacher)</div>
      <p class="small text-secondary mb-3 mb-lg-2">Chaque case dépend d’une permission : sans elle, la valeur actuelle est conservée et la case est verrouillée.</p>
      <div class="grid2">
        <?php
        $navRows = [
            ['nav_show_services', 'Services'],
            ['nav_show_projects', 'Réalisations'],
            ['nav_show_blog', 'Blog'],
            ['nav_show_faq', 'FAQ'],
            ['nav_show_contact', 'Contact'],
            ['nav_show_quote', 'Bouton « Devis »'],
            ['nav_show_history', 'Notre histoire'],
        ];
        foreach ($navRows as [$nk, $label]):
            $ok = $nfCan($nk);
        ?>
        <div class="check-card<?= $ok ? '' : ' is-locked' ?>">
          <label class="d-flex align-items-start gap-2 mb-0 w-100">
            <input type="checkbox" name="<?= htmlspecialchars($nk, ENT_QUOTES, 'UTF-8') ?>" value="1" <?= $isOn($nk, true) ? 'checked' : '' ?> <?= $ok ? '' : 'disabled' ?>>
            <span>
              <span class="d-block fw-semibold"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
              <?php if (!$ok): ?>
                <span class="small text-secondary">Permission requise : <?= htmlspecialchars($nfCapLabel($nk), ENT_QUOTES, 'UTF-8') ?></span>
              <?php endif; ?>
            </span>
          </label>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <hr class="sep">

    <div class="admin-form-section">
      <div class="fw-semibold mb-1">Footer (afficher / cacher)</div>
      <p class="small text-secondary mb-3 mb-lg-2">Même logique que pour la navigation : droits par rubrique.</p>
      <div class="grid2">
        <?php
        $footRows = [
            ['footer_show_services', 'Services'],
            ['footer_show_projects', 'Réalisations'],
            ['footer_show_blog', 'Blog'],
            ['footer_show_contact', 'Contact'],
            ['footer_show_history', 'Notre histoire'],
            ['footer_show_faq', 'FAQ'],
            ['footer_show_quote', 'Demander un devis'],
            ['footer_show_admin', 'Lien Admin'],
        ];
        foreach ($footRows as [$fk, $label]):
            $ok = $nfCan($fk);
        ?>
        <div class="check-card<?= $ok ? '' : ' is-locked' ?>">
          <label class="d-flex align-items-start gap-2 mb-0 w-100">
            <input type="checkbox" name="<?= htmlspecialchars($fk, ENT_QUOTES, 'UTF-8') ?>" value="1" <?= $isOn($fk, true) ? 'checked' : '' ?> <?= $ok ? '' : 'disabled' ?>>
            <span>
              <span class="d-block fw-semibold"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
              <?php if (!$ok): ?>
                <span class="small text-secondary">Permission requise : <?= htmlspecialchars($nfCapLabel($fk), ENT_QUOTES, 'UTF-8') ?></span>
              <?php endif; ?>
            </span>
          </label>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <button class="btn primary" type="submit"><i class="bi bi-check2"></i>Enregistrer</button>
  </form>
</section>

