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

$phonesJson = (string)($settings['company_phones_json'] ?? '');
$emailsJson = (string)($settings['company_emails_json'] ?? '');
$phones = [];
$emails = [];
if ($phonesJson !== '') {
  $decoded = json_decode($phonesJson, true);
  if (is_array($decoded)) {
    foreach ($decoded as $v) {
      $v = trim((string)$v);
      if ($v !== '') $phones[] = $v;
    }
  }
}
if ($emailsJson !== '') {
  $decoded = json_decode($emailsJson, true);
  if (is_array($decoded)) {
    foreach ($decoded as $v) {
      $v = trim((string)$v);
      if ($v !== '') $emails[] = $v;
    }
  }
}
if (empty($phones) && $get('phone', '') !== '') $phones = [$get('phone', '')];
if (empty($emails) && $get('email', '') !== '') $emails = [$get('email', '')];
$phonesText = implode("\n", $phones);
$emailsText = implode("\n", $emails);
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
      <label style="grid-column:1/-1">
        Téléphones (1 par ligne)
        <textarea name="phones" rows="3" placeholder="Ex:\n034 00 000 00\n032 00 000 00"><?= htmlspecialchars($phonesText, ENT_QUOTES, 'UTF-8') ?></textarea>
        <small class="muted">Le 1er numéro sera utilisé comme “principal” sur le site.</small>
      </label>
      <label style="grid-column:1/-1">
        Emails (1 par ligne)
        <textarea name="emails" rows="3" placeholder="Ex:\ncontact@domaine.com\ndevis@domaine.com"><?= htmlspecialchars($emailsText, ENT_QUOTES, 'UTF-8') ?></textarea>
        <small class="muted">Le 1er email sera utilisé comme “principal” sur le site.</small>
      </label>
      <label>WhatsApp (numéro) <input type="text" name="whatsapp" value="<?= htmlspecialchars($get('whatsapp', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Adresse <input type="text" name="address" value="<?= htmlspecialchars($get('address', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Horaires <input type="text" name="hours" value="<?= htmlspecialchars($get('hours', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Zone d’intervention <input type="text" name="service_area" value="<?= htmlspecialchars($get('service_area', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
    </div>

    <hr class="sep">

    <div class="grid2">
      <div style="grid-column:1/-1" class="fw-semibold">Localisation (carte)</div>
      <label style="grid-column:1/-1">
        Adresse (pour affichage)
        <input type="text" name="company_map_address" placeholder="Ex: Antananarivo, Anosivavaka..." value="<?= htmlspecialchars($get('company_map_address', $get('address', '')), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label style="grid-column:1/-1">
        Google Maps Embed URL (optionnel)
        <input type="text" name="company_map_embed_url" placeholder="https://www.google.com/maps/embed?pb=..." value="<?= htmlspecialchars($get('company_map_embed_url', ''), ENT_QUOTES, 'UTF-8') ?>">
        <small class="muted">Colle le lien “Embed a map” de Google Maps. Sinon on affichera une carte basée sur l’adresse.</small>
      </label>
      <label>
        Latitude (optionnel)
        <input type="text" name="company_map_lat" placeholder="Ex: -18.8792" value="<?= htmlspecialchars($get('company_map_lat', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Longitude (optionnel)
        <input type="text" name="company_map_lng" placeholder="Ex: 47.5079" value="<?= htmlspecialchars($get('company_map_lng', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <hr class="sep">

    <div class="grid2">
      <div style="grid-column:1/-1" class="fw-semibold">Réseaux sociaux (liens)</div>
      <label>Facebook <input type="text" name="facebook" placeholder="https://facebook.com/..." value="<?= htmlspecialchars($get('facebook', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Instagram <input type="text" name="instagram" placeholder="https://instagram.com/..." value="<?= htmlspecialchars($get('instagram', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>TikTok <input type="text" name="tiktok" placeholder="https://tiktok.com/@..." value="<?= htmlspecialchars($get('tiktok', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>YouTube <input type="text" name="youtube" placeholder="https://youtube.com/..." value="<?= htmlspecialchars($get('youtube', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>LinkedIn <input type="text" name="linkedin" placeholder="https://linkedin.com/..." value="<?= htmlspecialchars($get('linkedin', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
      <label>Twitter/X <input type="text" name="twitter" placeholder="https://x.com/..." value="<?= htmlspecialchars($get('twitter', ''), ENT_QUOTES, 'UTF-8') ?>"></label>
    </div>

    <button class="btn primary" type="submit"><i class="bi bi-check2"></i>Enregistrer</button>
  </form>
</section>

