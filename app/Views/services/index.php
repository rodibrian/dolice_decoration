<?php
/** @var list<array<string, mixed>> $services */

$base = (string)(env('APP_URL', '') ?: '');
$companyName = \App\Models\Setting::get('company_name', 'Dolice Decoration') ?? 'Dolice Decoration';

$services = $services ?? [];
$cats = [];
foreach ($services as $s) {
    $c = trim((string)($s['category'] ?? ''));
    if ($c !== '') $cats[strtolower($c)] = $c;
}
ksort($cats);
$categories = array_values($cats);
?>

<header class="py-5 bg-soft services-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('public.common.breadcrumb_home'), ENT_QUOTES, 'UTF-8') ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(t('nav.services'), ENT_QUOTES, 'UTF-8') ?></li>
      </ol>
    </nav>

    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
          <i class="bi bi-tools text-brand"></i>
          <span><?= htmlspecialchars(t('public.services_list.hero_badge'), ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <h1 class="display-6 fw-bold mt-3 mb-2 section-title"><?= htmlspecialchars(t('public.services_list.hero_title'), ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="lead text-secondary mb-0"><?= htmlspecialchars(t('public.services_list.hero_lead'), ENT_QUOTES, 'UTF-8') ?></p>
      </div>
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.services_list.aside_title'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="text-secondary mb-3"><?= htmlspecialchars(t('public.services_list.aside_text'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="d-grid gap-2">
              <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('public.services_list.cta_quote'), ENT_QUOTES, 'UTF-8') ?></a>
              <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i><?= htmlspecialchars(t('public.services_list.cta_projects'), ENT_QUOTES, 'UTF-8') ?></a>
            </div>
            <div class="text-secondary small mt-3"><?= htmlspecialchars(t('public.common.by_company', ['company' => $companyName]), ENT_QUOTES, 'UTF-8') ?></div>
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
          <input class="form-control" type="search" placeholder="<?= htmlspecialchars(t('public.services_list.search_ph'), ENT_QUOTES, 'UTF-8') ?>" data-services-search>
          <button class="btn btn-light border" type="button" data-services-reset><i class="bi bi-x-lg"></i></button>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="d-flex flex-wrap gap-2 justify-content-lg-end" data-services-tags>
          <button class="btn btn-sm btn-brand" type="button" data-cat="__all"><?= htmlspecialchars(t('public.common.tag_all'), ENT_QUOTES, 'UTF-8') ?></button>
          <?php foreach (array_slice($categories, 0, 10) as $c): ?>
            <button class="btn btn-sm btn-light border" type="button" data-cat="<?= htmlspecialchars(strtolower($c), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?></button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <?php foreach ($services as $s): ?>
        <?php
          $title = (string)($s['title'] ?? '');
          $category = (string)($s['category'] ?? '');
          $slug = (string)($s['slug'] ?? '');
          $img = trim((string)($s['image_path'] ?? ''));
          $imgUrl = '';
          if ($img !== '') {
            $imgUrl = (preg_match('#^https?://#i', $img) === 1) ? $img : ($base . $img);
          }

          $bp = $s['base_price'] ?? null;
          $show = (int)($s['show_price'] ?? 0) === 1;
          $priceBadge = '';
          if ($show && $bp !== null && $bp !== '') {
            $label = trim((string)($s['price_label'] ?? '')) ?: t('public.quote.price_from');
            $unit = trim((string)($s['price_unit'] ?? ''));
            $priceBadge = $label . ' ' . number_format((float)$bp, 0, ',', ' ') . t('public.common.price_suffix') . ($unit !== '' ? (' ' . $unit) : '');
          }

          $desc = trim((string)($s['description'] ?? ''));
          if ($desc !== '') {
            $desc = mb_substr($desc, 0, 140) . (mb_strlen($desc) > 140 ? '…' : '');
          }
          $hay = strtolower($title . ' ' . $category . ' ' . (string)($s['price_label'] ?? '') . ' ' . (string)($s['price_unit'] ?? ''));
        ?>
        <div class="col-sm-6 col-lg-4" data-aos="fade-up">
          <a
            class="text-decoration-none"
            data-service-modal="1"
            data-service-card
            data-search="<?= htmlspecialchars($hay, ENT_QUOTES, 'UTF-8') ?>"
            data-cat="<?= htmlspecialchars(strtolower($category !== '' ? $category : t('nav.services')), ENT_QUOTES, 'UTF-8') ?>"
            href="<?= htmlspecialchars($base . '/services/' . $slug, ENT_QUOTES, 'UTF-8') ?>"
          >
            <article class="card card-hover h-100 service-card">
              <div class="service-media">
                <?php if ($imgUrl !== ''): ?>
                  <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
                <?php else: ?>
                  <div class="service-media-fallback"><i class="bi bi-image"></i></div>
                <?php endif; ?>
                <?php if ($priceBadge !== ''): ?>
                  <div class="service-price badge text-bg-light border"><i class="bi bi-cash-coin me-1 text-brand"></i><?= htmlspecialchars($priceBadge, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
              </div>
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <span class="badge text-bg-light border"><?= htmlspecialchars($category !== '' ? $category : t('nav.services'), ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="text-secondary small"><?= htmlspecialchars(t('public.common.details'), ENT_QUOTES, 'UTF-8') ?> <i class="bi bi-arrow-right ms-1"></i></span>
                </div>
                <h2 class="h5 mb-2"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
                <?php if ($desc !== ''): ?>
                  <div class="text-secondary service-excerpt"><?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?></div>
                <?php else: ?>
                  <div class="text-secondary"><?= htmlspecialchars(t('public.services_list.card_no_desc'), ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
              </div>
            </article>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-secondary small mt-3" data-services-empty style="display:none">
      <?= htmlspecialchars(t('public.services_list.empty_search'), ENT_QUOTES, 'UTF-8') ?>
    </div>
  </div>
</section>

<script>
  (function () {
    var searchInput = document.querySelector('[data-services-search]');
    var resetBtn = document.querySelector('[data-services-reset]');
    var tagWrap = document.querySelector('[data-services-tags]');
    var empty = document.querySelector('[data-services-empty]');
    var cards = Array.from(document.querySelectorAll('[data-service-card]'));
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
        var col = a.closest('.col-sm-6, .col-lg-4');
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
