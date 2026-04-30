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

$logo = $get('company_logo', '');
$logoUrl = '';
if ($logo !== '') {
  $isAbs = preg_match('#^https?://#i', $logo) === 1;
  $logoUrl = $isAbs ? $logo : ((env('APP_URL', '') ?: '') . $logo);
}
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🏢</span>Gérer Entreprise</h1>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/company/update', ENT_QUOTES, 'UTF-8') ?>">
    <div class="grid2">
      <label>
        Nom de l’entreprise
        <input type="text" name="company_name" placeholder="Ex: Dolice Decoration" value="<?= htmlspecialchars($get('company_name', 'Dolice Decoration'), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Slogan
        <input type="text" name="company_slogan" placeholder="Ex: Finition & décoration de bâtiment" value="<?= htmlspecialchars($get('company_slogan', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>

      <label style="grid-column:1/-1">
        Logo (chemin public) / import
        <input type="text" name="company_logo" placeholder="/uploads/logo.png" value="<?= htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') ?>">
        <input type="file" name="company_logo_file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <?php if ($logoUrl !== ''): ?>
          <div class="mt-2 d-flex align-items-center gap-2">
            <span class="muted small">Aperçu:</span>
            <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="" style="height:38px;width:auto;object-fit:contain" class="border rounded bg-white p-1">
          </div>
        <?php endif; ?>
      </label>
    </div>

    <hr class="sep">

    <div class="grid2">
      <div style="grid-column:1/-1" class="fw-semibold">Contacts</div>
      <label>Téléphone <input type="text" name="phone" value="<?= htmlspecialchars($get('phone', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>WhatsApp <input type="text" name="whatsapp" value="<?= htmlspecialchars($get('whatsapp', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Email <input type="text" name="email" value="<?= htmlspecialchars($get('email', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Adresse <input type="text" name="address" value="<?= htmlspecialchars($get('address', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Horaires <input type="text" name="hours" value="<?= htmlspecialchars($get('hours', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Zone d’intervention <input type="text" name="service_area" value="<?= htmlspecialchars($get('service_area', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
    </div>

    <hr class="sep">

    <div class="grid2">
      <div style="grid-column:1/-1" class="fw-semibold">Réseaux sociaux (liens)</div>
      <label>Facebook <input type="text" name="facebook" placeholder="https://facebook.com/..." value="<?= htmlspecialchars($get('facebook', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Instagram <input type="text" name="instagram" placeholder="https://instagram.com/..." value="<?= htmlspecialchars($get('instagram', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>TikTok <input type="text" name="tiktok" placeholder="https://tiktok.com/@..." value="<?= htmlspecialchars($get('tiktok', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>YouTube <input type="text" name="youtube" placeholder="https://youtube.com/..." value="<?= htmlspecialchars($get('youtube', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>LinkedIn <input type="text" name="linkedin" placeholder="https://linkedin.com/..." value="<?= htmlspecialchars($get('linkedin', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
    </div>

    <button class="btn primary" type="submit"><i class="bi bi-check2"></i>Enregistrer</button>
  </form>
</section>

