<?php /** @var array{id:int,email:string,name:string,role:string}|null $user */ ?>
<section class="card">
  <h1>Dashboard</h1>
  <p>Connecté en tant que <strong><?= htmlspecialchars($user['name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong> (<?= htmlspecialchars($user['email'] ?? '—', ENT_QUOTES, 'UTF-8') ?>).</p>

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/logout', ENT_QUOTES, 'UTF-8') ?>">
    <button class="btn" type="submit">Déconnexion</button>
  </form>
</section>
