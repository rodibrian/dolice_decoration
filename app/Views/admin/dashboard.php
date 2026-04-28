<?php
/** @var array{id:int,email:string,name:string,role:string}|null $user */
/** @var array<string,int> $kpi */
?>
<section class="card">
  <h1>Dashboard</h1>
  <p>Connecté en tant que <strong><?= htmlspecialchars($user['name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong> (<?= htmlspecialchars($user['email'] ?? '—', ENT_QUOTES, 'UTF-8') ?>).</p>

  <div class="list">
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Devis (nouveaux)</h3>
      <p><?= (int)($kpi['quotes_new'] ?? 0) ?></p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Messages (nouveaux)</h3>
      <p><?= (int)($kpi['messages_new'] ?? 0) ?></p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Réalisations (publiées)</h3>
      <p><?= (int)($kpi['projects_published'] ?? 0) ?></p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Services (publiés)</h3>
      <p><?= (int)($kpi['services_published'] ?? 0) ?></p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Articles (publiés)</h3>
      <p><?= (int)($kpi['posts_published'] ?? 0) ?></p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Témoignages (en attente)</h3>
      <p><?= (int)($kpi['testimonials_pending'] ?? 0) ?></p>
    </a>
  </div>

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/logout', ENT_QUOTES, 'UTF-8') ?>">
    <button class="btn" type="submit">Déconnexion</button>
  </form>
</section>
