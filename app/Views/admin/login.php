<?php /** @var string|null $error */ ?>
<section class="card">
  <h1>Connexion</h1>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>">
    <label>
      Email
      <input type="email" name="email" required autocomplete="email">
    </label>
    <label>
      Mot de passe
      <input type="password" name="password" required autocomplete="current-password">
    </label>
    <button class="btn primary" type="submit">Se connecter</button>
  </form>
</section>
