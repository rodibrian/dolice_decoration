<?php
/** @var list<array<string, mixed>> $projects */
/** @var string|null $category */

$base = (string)(env('APP_URL', '') ?: '');
$companyName = \App\Models\Setting::get('company_name', 'Dolice Decoration') ?? 'Dolice Decoration';

$projects = $projects ?? [];
$cats = [];
foreach ($projects as $p) {
    $c = trim((string)($p['category'] ?? ''));
    if ($c !== '') $cats[strtolower($c)] = $c;
}
ksort($cats);
$categories = array_values($cats);
?>
<header class="py-5 bg-soft projects-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Réalisations</li>
      </ol>
    </nav>

    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
          <i class="bi bi-building-gear text-brand"></i>
          <span>Avant / Après • Chantier • Finitions premium</span>
        </div>
        <h1 class="display-6 fw-bold mt-3 mb-2 section-title">Réalisations</h1>
        <p class="lead text-secondary mb-0">Découvre quelques projets livrés: plafonds, peinture, revêtements, aménagements… Clique sur une réalisation pour voir plus de détails.</p>
        <?php if (!empty($category)): ?>
          <div class="mt-3">
            <span class="badge text-bg-light border"><i class="bi bi-funnel me-1"></i>Filtre: <?= htmlspecialchars((string)$category, ENT_QUOTES, 'UTF-8') ?></span>
            <a class="ms-2 small" href="<?= htmlspecialchars($base . '/realisations', ENT_QUOTES, 'UTF-8') ?>">retirer</a>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="fw-semibold mb-2">Envie d’un projet similaire ?</div>
            <div class="text-secondary mb-3">Décris ton besoin et on te propose une solution adaptée.</div>
            <div class="d-grid gap-2">
              <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Demander un devis</a>
              <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/services', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-tools me-2"></i>Voir les services</a>
            </div>
            <div class="text-secondary small mt-3">Par <span class="fw-semibold"><?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?></span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="py-5">
  <div class="container">
    <div class="row g-3 align-items-center mb-3" data-aos="fade-up">
      <div class="col-lg-6">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input class="form-control" type="search" placeholder="Rechercher une réalisation..." data-projects-search>
          <button class="btn btn-light border" type="button" data-projects-reset><i class="bi bi-x-lg"></i></button>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="d-flex flex-wrap gap-2 justify-content-lg-end" data-projects-tags>
          <button class="btn btn-sm btn-brand" type="button" data-cat="__all">Tous</button>
          <?php foreach (array_slice($categories, 0, 10) as $c): ?>
            <button class="btn btn-sm btn-light border" type="button" data-cat="<?= htmlspecialchars(strtolower($c), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?></button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <?php foreach ($projects as $p): ?>
        <?php
          $id = (int)($p['id'] ?? 0);
          $title = (string)($p['title'] ?? '');
          $slug = (string)($p['slug'] ?? '');
          $location = (string)($p['location'] ?? '—');
          $cat = (string)($p['category'] ?? '');
          $isFeatured = (int)($p['is_featured'] ?? 0) === 1;
          $imgs = $p['images'] ?? [];
          if (!is_array($imgs)) $imgs = [];
          $imgs = array_values(array_filter(array_map(static fn($v): string => trim((string)$v), $imgs), static fn(string $v): bool => $v !== ''));
          $imgs = array_slice($imgs, 0, 5);
          $imgUrls = array_map(static function (string $path) use ($base): string {
            return (preg_match('#^https?://#i', $path) === 1) ? $path : ($base . $path);
          }, $imgs);
          $carouselId = 'projCardCarousel' . $id;
          $hay = strtolower($title . ' ' . $location . ' ' . $cat);
        ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <a
            class="text-decoration-none"
            data-project-modal="1"
            data-project-card
            data-search="<?= htmlspecialchars($hay, ENT_QUOTES, 'UTF-8') ?>"
            data-cat="<?= htmlspecialchars(strtolower($cat), ENT_QUOTES, 'UTF-8') ?>"
            href="<?= htmlspecialchars($base . '/realisations/' . $slug, ENT_QUOTES, 'UTF-8') ?>"
          >
            <article class="card card-hover h-100 project-card">
              <div class="project-media">
                <?php if (count($imgUrls) >= 2): ?>
                  <div id="<?= htmlspecialchars($carouselId, ENT_QUOTES, 'UTF-8') ?>" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2600" data-bs-pause="false">
                    <div class="carousel-inner">
                      <?php foreach ($imgUrls as $i => $u): ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                          <img src="<?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>" alt="">
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <div class="project-media-badge badge text-bg-light border"><i class="bi bi-images me-1 text-brand"></i><?= count($imgUrls) ?> photos</div>
                <?php elseif (count($imgUrls) === 1): ?>
                  <img src="<?= htmlspecialchars($imgUrls[0], ENT_QUOTES, 'UTF-8') ?>" alt="">
                <?php else: ?>
                  <div class="project-media-fallback"><i class="bi bi-image"></i></div>
                <?php endif; ?>
                <?php if ($isFeatured): ?>
                  <div class="project-featured badge text-bg-warning"><i class="bi bi-star-fill me-1"></i>Vedette</div>
                <?php endif; ?>
              </div>

              <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                  <span class="badge text-bg-light border"><?= htmlspecialchars($cat !== '' ? $cat : 'Réalisation', ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="text-secondary small">Détails <i class="bi bi-arrow-right ms-1"></i></span>
                </div>
                <h2 class="h5 mb-1"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
                <div class="text-secondary small"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($location, ENT_QUOTES, 'UTF-8') ?></div>
              </div>
            </article>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-secondary small mt-3" data-projects-empty style="display:none">
      Aucune réalisation ne correspond à ta recherche.
    </div>
  </div>
</section>

<script>
  (function () {
    var searchInput = document.querySelector('[data-projects-search]');
    var resetBtn = document.querySelector('[data-projects-reset]');
    var tagWrap = document.querySelector('[data-projects-tags]');
    var empty = document.querySelector('[data-projects-empty]');
    var cards = Array.from(document.querySelectorAll('[data-project-card]'));
    if (cards.length === 0) return;

    var currentCat = '__all';
    function apply() {
      var q = (searchInput?.value || '').toLowerCase().trim();
      var visible = 0;
      cards.forEach(function (a) {
        var hay = (a.getAttribute('data-search') || '').toLowerCase();
        var cat = (a.getAttribute('data-cat') || '').toLowerCase();
        var okText = (q === '' || hay.indexOf(q) !== -1);
        var okCat = (currentCat === '__all' || cat === currentCat);
        var show = okText && okCat;
        var col = a.closest('.col-md-6, .col-lg-4');
        if (col) col.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      if (empty) empty.style.display = (visible === 0) ? '' : 'none';
    }

    if (searchInput) searchInput.addEventListener('input', apply);
    if (resetBtn) resetBtn.addEventListener('click', function () {
      if (searchInput) searchInput.value = '';
      currentCat = '__all';
      if (tagWrap) {
        tagWrap.querySelectorAll('button[data-cat]').forEach(function (b) {
          b.className = (b.getAttribute('data-cat') === '__all') ? 'btn btn-sm btn-brand' : 'btn btn-sm btn-light border';
        });
      }
      apply();
    });
    if (tagWrap) {
      tagWrap.addEventListener('click', function (e) {
        var btn = e.target && (e.target.closest ? e.target.closest('button[data-cat]') : null);
        if (!btn) return;
        currentCat = btn.getAttribute('data-cat') || '__all';
        tagWrap.querySelectorAll('button[data-cat]').forEach(function (b) {
          b.className = (b === btn) ? 'btn btn-sm btn-brand' : 'btn btn-sm btn-light border';
        });
        apply();
      });
    }
    apply();
  })();
</script>

