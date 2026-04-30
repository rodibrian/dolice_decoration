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
$enabled = ($get('emailjs_enabled', '') === '1');
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🔔</span>Notifications EmailJS</h1>
    <a class="btn btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-arrow-left"></i>Retour</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div class="muted" style="max-width:980px">
    <div class="fw-semibold">Objectif</div>
    <div class="small">Envoyer une notification email automatique à chaque nouvelle <b>demande de devis</b> et <b>message de contact</b>, via EmailJS (Gmail / autres services).</div>
    <div class="small mt-2">Côté EmailJS, tu dois créer 2 templates: un pour <b>devis</b> et un pour <b>contact</b>.</div>
    <div class="small mt-2">Doc: <a target="_blank" rel="noopener" href="<?= htmlspecialchars('https://emailjs.com/docs/rest-api/send', ENT_QUOTES, 'UTF-8') ?>">REST API /send</a></div>
  </div>

  <hr class="sep">

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/notifications/update', ENT_QUOTES, 'UTF-8') ?>">
    <label class="check">
      <input type="checkbox" name="emailjs_enabled" value="1" <?= $enabled ? 'checked' : '' ?>>
      Activer les notifications EmailJS
    </label>

    <div class="grid2">
      <label>
        Service ID
        <input type="text" name="emailjs_service_id" placeholder="Ex: service_xxxxx" value="<?= htmlspecialchars($get('emailjs_service_id', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Public Key (user_id)
        <input type="text" name="emailjs_public_key" placeholder="Ex: 9bA... (EmailJS Public Key)" value="<?= htmlspecialchars($get('emailjs_public_key', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Private Key (accessToken) (optionnel)
        <input type="text" name="emailjs_private_key" placeholder="Requis si strict mode activé" value="<?= htmlspecialchars($get('emailjs_private_key', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Email de réception (to_email)
        <input type="text" name="emailjs_to_email" placeholder="Ex: contact@domaine.com" value="<?= htmlspecialchars($get('emailjs_to_email', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>
        Nom du destinataire (to_name)
        <input type="text" name="emailjs_to_name" placeholder="Ex: Dolice Admin" value="<?= htmlspecialchars($get('emailjs_to_name', ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <hr class="sep">

    <div class="grid2">
      <label>
        Template ID — Devis
        <input type="text" name="emailjs_template_quote" placeholder="Ex: template_quote_xxx" value="<?= htmlspecialchars($get('emailjs_template_quote', ''), ENT_QUOTES, 'UTF-8') ?>">
        <small class="muted">Params recommandés: to_email, to_name, subject, name, phone, email, project_type, message.</small>
      </label>
      <label>
        Template ID — Contact
        <input type="text" name="emailjs_template_contact" placeholder="Ex: template_contact_xxx" value="<?= htmlspecialchars($get('emailjs_template_contact', ''), ENT_QUOTES, 'UTF-8') ?>">
        <small class="muted">Params recommandés: to_email, to_name, subject, name, phone, email, message.</small>
      </label>
    </div>

    <button class="btn primary" type="submit"><i class="bi bi-check2"></i>Enregistrer</button>
  </form>

  <hr class="sep">

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/notifications/test', ENT_QUOTES, 'UTF-8') ?>" class="row gap" style="align-items:end">
    <label style="min-width:240px">
      Tester l’envoi
      <select name="test_type">
        <option value="quote">Test devis</option>
        <option value="contact">Test contact</option>
      </select>
    </label>
    <button class="btn" type="submit"><i class="bi bi-send"></i>Envoyer un test</button>
    <div class="muted small">Le test utilise `to_email` et `to_name`.</div>
  </form>
</section>

