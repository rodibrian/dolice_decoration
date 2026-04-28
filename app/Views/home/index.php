<?php /** @var string $title */ ?>
<section class="hero">
  <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
  <p>Site vitrine + administration (MVC PHP/MySQL) — base prête pour XAMPP.</p>
  <div class="actions">
    <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>">Se connecter (admin)</a>
  </div>
</section>
