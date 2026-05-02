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
<section class="card" data-admin-dashboard="1"
  data-kpi-quotes="<?= (int)$quotesNew ?>"
  data-kpi-messages="<?= (int)$messagesNew ?>"
  data-kpi-services="<?= (int)$servicesPublished ?>"
  data-kpi-projects="<?= (int)$projectsPublished ?>"
  data-kpi-posts="<?= (int)$postsPublished ?>"
  data-kpi-testimonials="<?= (int)$testimonialsPending ?>"
>
  <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
    <div>
      <h1 class="page-title"><span class="page-icon">📊</span>Dashboard</h1>
      <p class="page-subtitle">Bienvenue <strong><?= htmlspecialchars($user['name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong> (<?= htmlspecialchars($user['email'] ?? '—', ENT_QUOTES, 'UTF-8') ?>)</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts/create', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-plus-lg"></i>Nouvel article</a>
      <a class="btn" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects/create', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-plus-lg"></i>Nouvelle réalisation</a>
      <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-inbox"></i>Ouvrir l’inbox</a>
    </div>
  </div>

  <hr class="sep">

  <div class="admin-doc-callout">
    <div class="admin-doc-callout-title"><i class="bi bi-lightbulb me-2"></i>Pour les éditeurs</div>
    <p class="admin-doc-callout-text">Les cartes ci-dessous regroupent l’<strong>activité récente</strong> : traitez d’abord l’inbox (devis + messages), puis enrichissez le contenu public (réalisations, articles, témoignages). Les graphiques se mettent à jour selon les données en base — pensez à <strong>publier</strong> vos fiches pour qu’elles comptent dans « Contenu publié ».</p>
    <p class="admin-doc-callout-text mb-0">Utilisez le menu à gauche (icône ☰ sur mobile) : les entrées sont regroupées par thème. Survolez un lien du menu pour une description rapide.</p>
  </div>

  <div class="row g-3">
    <div class="col-12 col-md-6 col-xl-3">
      <a class="admin-kpi" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>">
        <div class="admin-kpi-icon bg-soft-orange"><i class="bi bi-file-earmark-text"></i></div>
        <div class="admin-kpi-body">
          <div class="admin-kpi-label">Nouveaux devis</div>
          <div class="admin-kpi-value" data-count="<?= $quotesNew ?>">0</div>
          <div class="admin-kpi-note">À traiter en priorité</div>
        </div>
      </a>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <a class="admin-kpi" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>">
        <div class="admin-kpi-icon bg-soft-blue"><i class="bi bi-envelope"></i></div>
        <div class="admin-kpi-body">
          <div class="admin-kpi-label">Nouveaux messages</div>
          <div class="admin-kpi-value" data-count="<?= $messagesNew ?>">0</div>
          <div class="admin-kpi-note">Demandes entrantes</div>
        </div>
      </a>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <a class="admin-kpi" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>">
        <div class="admin-kpi-icon bg-soft-indigo"><i class="bi bi-building-gear"></i></div>
        <div class="admin-kpi-body">
          <div class="admin-kpi-label">Réalisations publiées</div>
          <div class="admin-kpi-value" data-count="<?= $projectsPublished ?>">0</div>
          <div class="admin-kpi-note">Portfolio en ligne</div>
        </div>
      </a>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <a class="admin-kpi" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>">
        <div class="admin-kpi-icon bg-soft-green"><i class="bi bi-tools"></i></div>
        <div class="admin-kpi-body">
          <div class="admin-kpi-label">Services publiés</div>
          <div class="admin-kpi-value" data-count="<?= $servicesPublished ?>">0</div>
          <div class="admin-kpi-note">Offres actives</div>
        </div>
      </a>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-12 col-xl-7">
      <div class="panel-card h-100">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
          <h3 class="m-0">Aperçu activité</h3>
          <div class="muted small">Mise à jour en temps réel</div>
        </div>
        <div style="height:260px">
          <canvas id="activityChart" aria-label="Activité"></canvas>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-3">
          <span class="badge text-bg-light border"><i class="bi bi-inbox me-1"></i>Inbox: <?= $inboxTotal ?></span>
          <span class="badge text-bg-light border"><i class="bi bi-collection me-1"></i>Contenu: <?= $contentTotal ?></span>
          <span class="badge text-bg-light border"><i class="bi bi-chat-quote me-1"></i>Avis en attente: <?= $feedbackTotal ?></span>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-5">
      <div class="panel-card h-100">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
          <h3 class="m-0">Contenu publié</h3>
          <a class="btn btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-arrow-right"></i>Gérer</a>
        </div>
        <div style="height:260px">
          <canvas id="contentChart" aria-label="Contenu"></canvas>
        </div>
        <div class="row g-2 mt-3">
          <div class="col-6">
            <div class="admin-mini-stat">
              <div class="muted small">Articles</div>
              <div class="fw-bold" data-count="<?= $postsPublished ?>">0</div>
            </div>
          </div>
          <div class="col-6">
            <div class="admin-mini-stat">
              <div class="muted small">Témoignages (attente)</div>
              <div class="fw-bold" data-count="<?= $testimonialsPending ?>">0</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
