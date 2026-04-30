<?php
/** @var string|null $error */
/** @var string|null $heroCoverUrl */

$isLocal = (env('APP_ENV', '') === 'local');
$demoEmail = $isLocal ? 'admin@dolice.local' : '';
$demoPassword = $isLocal ? 'Admin@1234' : '';
?>
<div class="admin-auth-wrap">
  <div class="admin-auth-card">
    <div class="admin-auth-brand">
      <img class="admin-auth-logo" src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/assets/logo.svg', ENT_QUOTES, 'UTF-8') ?>" alt="Dolice Decoration">
      <div>
        <div class="admin-auth-title">Dolice Decoration</div>
        <div class="admin-auth-subtitle">Espace d’administration</div>
      </div>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form class="admin-auth-form" method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>">
      <label>
        Email
        <input
          type="email"
          name="email"
          required
          autocomplete="email"
          value="<?= htmlspecialchars($demoEmail, ENT_QUOTES, 'UTF-8') ?>"
        >
      </label>
      <label>
        Mot de passe
        <input
          type="password"
          name="password"
          required
          autocomplete="current-password"
          value="<?= htmlspecialchars($demoPassword, ENT_QUOTES, 'UTF-8') ?>"
        >
      </label>
      <button class="btn primary w-100" type="submit"><i class="bi bi-box-arrow-in-right"></i>Se connecter</button>
    </form>

    <div class="admin-auth-hint">
      <span class="muted">Après connexion, vous serez redirigé vers le dashboard.</span>
    </div>
  </div>
</div>
