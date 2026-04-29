<?php
/** @var array{id:int,email:string,name:string,role:string}|null $user */
/** @var array<string,int> $kpi */

$quotesNew = (int)($kpi['quotes_new'] ?? 0);
$messagesNew = (int)($kpi['messages_new'] ?? 0);
$projectsPublished = (int)($kpi['projects_published'] ?? 0);
$servicesPublished = (int)($kpi['services_published'] ?? 0);
$postsPublished = (int)($kpi['posts_published'] ?? 0);
$testimonialsPending = (int)($kpi['testimonials_pending'] ?? 0);

$inboxTotal = $quotesNew + $messagesNew;
$contentTotal = $projectsPublished + $servicesPublished + $postsPublished;
$feedbackTotal = $testimonialsPending;
$globalTotal = max(1, $inboxTotal + $contentTotal + $feedbackTotal);

$inboxPct = (int)round(($inboxTotal / $globalTotal) * 100);
$contentPct = (int)round(($contentTotal / $globalTotal) * 100);
$feedbackPct = max(0, 100 - $inboxPct - $contentPct);
?>
<section class="card">
  <div class="row between">
    <div>
      <h1 class="page-title"><span class="page-icon">📊</span>Dashboard</h1>
      <p class="page-subtitle">Bienvenue <strong><?= htmlspecialchars($user['name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong> (<?= htmlspecialchars($user['email'] ?? '—', ENT_QUOTES, 'UTF-8') ?>)</p>
    </div>
    <div class="muted">Vue d'ensemble de l'activité</div>
  </div>

  <div class="stats-grid">
    <a class="stat-card" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>">
      <div class="stat-top">
        <span class="stat-label">Nouveaux devis</span>
        <span class="stat-icon">📄</span>
      </div>
      <div class="stat-value"><?= $quotesNew ?></div>
      <div class="stat-note">A traiter en priorite</div>
    </a>

    <a class="stat-card" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>">
      <div class="stat-top">
        <span class="stat-label">Nouveaux messages</span>
        <span class="stat-icon">✉️</span>
      </div>
      <div class="stat-value"><?= $messagesNew ?></div>
      <div class="stat-note">Demandes clients entrantes</div>
    </a>

    <a class="stat-card" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>">
      <div class="stat-top">
        <span class="stat-label">Realisations publiees</span>
        <span class="stat-icon">🏗</span>
      </div>
      <div class="stat-value"><?= $projectsPublished ?></div>
      <div class="stat-note">Portfolio visible en ligne</div>
    </a>

    <a class="stat-card" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>">
      <div class="stat-top">
        <span class="stat-label">Services publies</span>
        <span class="stat-icon">🛠</span>
      </div>
      <div class="stat-value"><?= $servicesPublished ?></div>
      <div class="stat-note">Offres actives du site</div>
    </a>
  </div>

  <div class="dashboard-panels">
    <div class="panel-card">
      <h3>Performance contenu</h3>
      <div class="mini-bars">
        <div class="mini-bar-row">
          <span>Services</span>
          <div class="mini-bar"><i style="width: <?= min(100, $servicesPublished * 10) ?>%"></i></div>
          <strong><?= $servicesPublished ?></strong>
        </div>
        <div class="mini-bar-row">
          <span>Realisations</span>
          <div class="mini-bar"><i style="width: <?= min(100, $projectsPublished * 10) ?>%"></i></div>
          <strong><?= $projectsPublished ?></strong>
        </div>
        <div class="mini-bar-row">
          <span>Articles</span>
          <div class="mini-bar"><i style="width: <?= min(100, $postsPublished * 10) ?>%"></i></div>
          <strong><?= $postsPublished ?></strong>
        </div>
      </div>
      <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>">Gerer le contenu</a>
    </div>

    <div class="panel-card">
      <h3>Repartition activite</h3>
      <div class="donut-wrap">
        <div
          class="donut-chart"
          style="background: conic-gradient(#ff7a18 0 <?= $inboxPct ?>%, #3b82f6 <?= $inboxPct ?>% <?= $inboxPct + $contentPct ?>%, #10b981 <?= $inboxPct + $contentPct ?>% 100%);"
          aria-label="Repartition"
        >
          <div class="donut-center"><?= $globalTotal ?></div>
        </div>
        <div class="donut-legend">
          <div><span class="dot dot-orange"></span> Inbox (devis + messages): <?= $inboxTotal ?></div>
          <div><span class="dot dot-blue"></span> Contenu publie: <?= $contentTotal ?></div>
          <div><span class="dot dot-green"></span> Avis en attente: <?= $feedbackTotal ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="list">
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Temoignages en attente</h3>
      <p><?= $testimonialsPending ?></p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts/create', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Action rapide</h3>
      <p>Creer un nouvel article</p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects/create', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Action rapide</h3>
      <p>Ajouter une realisation</p>
    </a>
    <a class="item" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/settings', ENT_QUOTES, 'UTF-8') ?>">
      <h3>Configuration</h3>
      <p>Mettre a jour les informations globales</p>
    </a>
  </div>

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/logout', ENT_QUOTES, 'UTF-8') ?>">
    <button class="btn" type="submit">Déconnexion</button>
  </form>
</section>
