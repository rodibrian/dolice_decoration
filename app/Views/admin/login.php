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
        <div class="admin-auth-subtitle"><?= htmlspecialchars(t('admin.auth.subtitle'), ENT_QUOTES, 'UTF-8') ?></div>
      </div>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form class="admin-auth-form" method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>">
      <label>
        <?= htmlspecialchars(t('admin.auth.email'), ENT_QUOTES, 'UTF-8') ?>
        <input
          type="email"
          name="email"
          required
          autocomplete="email"
          value="<?= htmlspecialchars($demoEmail, ENT_QUOTES, 'UTF-8') ?>"
        >
      </label>
      <label>
        <?= htmlspecialchars(t('admin.auth.password'), ENT_QUOTES, 'UTF-8') ?>
        <input
          type="password"
          name="password"
          required
          autocomplete="current-password"
          value="<?= htmlspecialchars($demoPassword, ENT_QUOTES, 'UTF-8') ?>"
        >
      </label>

      <label class="check">
        <input type="checkbox" name="super_admin" value="1">
        <?= htmlspecialchars(t('admin.auth.super'), ENT_QUOTES, 'UTF-8') ?>
      </label>
      <button class="btn primary w-100" type="submit"><i class="bi bi-box-arrow-in-right"></i><?= htmlspecialchars(t('admin.auth.login'), ENT_QUOTES, 'UTF-8') ?></button>
    </form>

    <div class="admin-auth-hint">
      <div class="muted"><?= htmlspecialchars(t('admin.auth.hint'), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="mt-3 d-grid gap-2">
        <a class="btn btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-arrow-left"></i><?= htmlspecialchars(t('admin.auth.back'), ENT_QUOTES, 'UTF-8') ?></a>
      </div>
    </div>
  </div>
</div>
