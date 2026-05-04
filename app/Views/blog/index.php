<?php
/** @var list<array<string, mixed>> $posts */

$companyName = \App\Models\Setting::get('company_name', 'Dolice Decoration') ?? 'Dolice Decoration';
$base = (string)(env('APP_URL', '') ?: '');

$posts = $posts ?? [];
$featured = !empty($posts) ? $posts[0] : null;
$rest = !empty($posts) ? array_slice($posts, 1) : [];

$readingTime = static function (?string $text): int {
    $t = trim((string)$text);
    if ($t === '') return 0;
    $words = preg_split('/\s+/u', strip_tags($t)) ?: [];
    $count = 0;
    foreach ($words as $w) {
        if (trim((string)$w) !== '') $count++;
    }
    return (int)max(1, ceil($count / 180));
};

$splitKeywords = static function ($kw): array {
    $s = trim((string)$kw);
    if ($s === '') return [];
    $parts = preg_split('/[,|]/', $s) ?: [];
    $out = [];
    foreach ($parts as $p) {
        $p = trim((string)$p);
        if ($p !== '') $out[] = $p;
    }
    return array_values(array_unique($out));
};

$tagSet = [];
foreach ($posts as $p) {
    foreach ($splitKeywords($p['keywords'] ?? '') as $kwTag) {
        $tagSet[strtolower($kwTag)] = $kwTag;
    }
}
ksort($tagSet);
$tags = array_values($tagSet);
?>

<header class="py-5 bg-soft blog-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('public.common.breadcrumb_home'), ENT_QUOTES, 'UTF-8') ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(t('nav.blog'), ENT_QUOTES, 'UTF-8') ?></li>
      </ol>
    </nav>

    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
          <i class="bi bi-journal-text text-brand"></i>
          <span><?= htmlspecialchars(t('public.blog_list.hero_badge'), ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <h1 class="display-6 fw-bold mt-3 mb-2 section-title"><?= htmlspecialchars(t('public.blog_list.hero_title'), ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="lead text-secondary mb-0"><?= htmlspecialchars(t('public.blog_list.hero_lead'), ENT_QUOTES, 'UTF-8') ?></p>
      </div>
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm blog-hero-card">
          <div class="card-body p-4">
            <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.blog_list.aside_title'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="text-secondary mb-3"><?= htmlspecialchars(t('public.blog_list.aside_text'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="d-grid gap-2">
              <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('public.blog_list.cta_quote'), ENT_QUOTES, 'UTF-8') ?></a>
              <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i><?= htmlspecialchars(t('public.blog_list.cta_projects'), ENT_QUOTES, 'UTF-8') ?></a>
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
    <div class="row g-4">
      <div class="col-12" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-3 p-lg-4">
            <div class="row g-3 align-items-center">
              <div class="col-lg-6">
                <div class="input-group">
                  <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                  <input class="form-control" type="search" placeholder="<?= htmlspecialchars(t('public.blog_list.search_ph'), ENT_QUOTES, 'UTF-8') ?>" data-blog-search>
                  <button class="btn btn-light border" type="button" data-blog-reset><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="text-secondary small mt-2"><?= htmlspecialchars(t('public.blog_list.search_hint'), ENT_QUOTES, 'UTF-8') ?></div>
              </div>
              <div class="col-lg-6">
                <?php if (!empty($tags)): ?>
                  <div class="d-flex flex-wrap gap-2 justify-content-lg-end" data-blog-tags>
                    <button class="btn btn-sm btn-brand" type="button" data-tag="__all"><?= htmlspecialchars(t('public.common.tag_all'), ENT_QUOTES, 'UTF-8') ?></button>
                    <?php foreach (array_slice($tags, 0, 12) as $tag): ?>
                      <button class="btn btn-sm btn-light border" type="button" data-tag="<?= htmlspecialchars(strtolower($tag), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></button>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if (empty($posts)): ?>
        <div class="col-12">
          <div class="alert alert-light border"><?= htmlspecialchars(t('public.blog_list.empty_posts'), ENT_QUOTES, 'UTF-8') ?></div>
        </div>
      <?php else: ?>
        <?php if (is_array($featured)): ?>
          <?php
            $img = trim((string)($featured['featured_image'] ?? ''));
            $imgUrl = $img !== '' ? ((preg_match('#^https?://#i', $img) === 1) ? $img : ($base . $img)) : '';
            $kw = $splitKeywords($featured['keywords'] ?? '');
            $rt = $readingTime(($featured['content'] ?? '') . "\n" . ($featured['excerpt'] ?? ''));
            $date = (string)($featured['published_at'] ?? '');
            $author = trim((string)($featured['author'] ?? ''));
            $title = (string)($featured['title'] ?? '');
            $excerpt = (string)($featured['excerpt'] ?? '');
            $slug = (string)($featured['slug'] ?? '');
            $searchHay = strtolower($title . ' ' . $excerpt . ' ' . $author . ' ' . (string)($featured['keywords'] ?? ''));
          ?>
          <div class="col-12" data-aos="fade-up">
            <a class="text-decoration-none" data-post-modal="1" href="<?= htmlspecialchars($base . '/blog/' . $slug, ENT_QUOTES, 'UTF-8') ?>">
              <article class="card border-0 shadow-sm blog-featured" data-blog-card data-search="<?= htmlspecialchars($searchHay, ENT_QUOTES, 'UTF-8') ?>" data-tags="<?= htmlspecialchars(strtolower(implode(',', $kw)), ENT_QUOTES, 'UTF-8') ?>">
                <div class="row g-0">
                  <div class="col-lg-6">
                    <div class="blog-media">
                      <?php if ($imgUrl !== ''): ?>
                        <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
                      <?php else: ?>
                        <div class="blog-media-fallback">
                          <i class="bi bi-image"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="card-body p-4 p-lg-5">
                      <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge text-bg-light border"><i class="bi bi-star-fill text-brand me-1"></i><?= htmlspecialchars(t('public.blog_list.featured_badge'), ENT_QUOTES, 'UTF-8') ?></span>
                        <?php if ($date !== ''): ?><span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                        <?php if ($rt > 0): ?><span class="text-secondary small"><i class="bi bi-clock me-1"></i><?= htmlspecialchars(t('public.blog_list.read_min', ['min' => (string)(int)$rt]), ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                        <?php if ($author !== ''): ?><span class="text-secondary small"><i class="bi bi-person me-1"></i><?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                      </div>
                      <h2 class="h3 mb-3"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
                      <p class="text-secondary mb-3"><?= htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8') ?></p>
                      <?php if (!empty($kw)): ?>
                        <div class="d-flex flex-wrap gap-2">
                          <?php foreach (array_slice($kw, 0, 6) as $kwTag): ?>
                            <span class="badge text-bg-light border">#<?= htmlspecialchars($kwTag, ENT_QUOTES, 'UTF-8') ?></span>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                      <div class="mt-4">
                        <span class="btn btn-brand btn-sm"><i class="bi bi-eye me-1"></i><?= htmlspecialchars(t('public.blog_list.read_btn'), ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="btn btn-light border btn-sm ms-2"><i class="bi bi-box-arrow-up-right me-1"></i><?= htmlspecialchars(t('public.blog_list.open_btn'), ENT_QUOTES, 'UTF-8') ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </article>
            </a>
          </div>
        <?php endif; ?>

        <?php foreach ($rest as $p): ?>
          <?php
            $img = trim((string)($p['featured_image'] ?? ''));
            $imgUrl = $img !== '' ? ((preg_match('#^https?://#i', $img) === 1) ? $img : ($base . $img)) : '';
            $kw = $splitKeywords($p['keywords'] ?? '');
            $rt = $readingTime(($p['content'] ?? '') . "\n" . ($p['excerpt'] ?? ''));
            $date = (string)($p['published_at'] ?? '');
            $author = trim((string)($p['author'] ?? ''));
            $title = (string)($p['title'] ?? '');
            $excerpt = (string)($p['excerpt'] ?? '');
            $slug = (string)($p['slug'] ?? '');
            $searchHay = strtolower($title . ' ' . $excerpt . ' ' . $author . ' ' . (string)($p['keywords'] ?? ''));
          ?>
          <div class="col-md-6 col-lg-4" data-aos="fade-up">
            <a class="text-decoration-none" data-post-modal="1" href="<?= htmlspecialchars($base . '/blog/' . $slug, ENT_QUOTES, 'UTF-8') ?>">
              <article class="card card-hover h-100 blog-card" data-blog-card data-search="<?= htmlspecialchars($searchHay, ENT_QUOTES, 'UTF-8') ?>" data-tags="<?= htmlspecialchars(strtolower(implode(',', $kw)), ENT_QUOTES, 'UTF-8') ?>">
                <div class="blog-media-sm">
                  <?php if ($imgUrl !== ''): ?>
                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
                  <?php else: ?>
                    <div class="blog-media-fallback"><i class="bi bi-image"></i></div>
                  <?php endif; ?>
                </div>
                <div class="card-body">
                  <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <?php if ($date !== ''): ?><span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                    <?php if ($rt > 0): ?><span class="text-secondary small"><i class="bi bi-clock me-1"></i><?= htmlspecialchars(t('public.blog_list.read_min', ['min' => (string)(int)$rt]), ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                    <?php if ($author !== ''): ?><span class="text-secondary small"><i class="bi bi-person me-1"></i><?= htmlspecialchars($author, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                  </div>
                  <h2 class="h5 mb-2"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
                  <div class="text-secondary mb-3"><?= htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8') ?></div>
                  <?php if (!empty($kw)): ?>
                    <div class="d-flex flex-wrap gap-2">
                      <?php foreach (array_slice($kw, 0, 3) as $kwTag): ?>
                        <span class="badge text-bg-light border">#<?= htmlspecialchars($kwTag, ENT_QUOTES, 'UTF-8') ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </article>
            </a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
  (function () {
    var searchInput = document.querySelector('[data-blog-search]');
    var resetBtn = document.querySelector('[data-blog-reset]');
    var tagWrap = document.querySelector('[data-blog-tags]');
    var cards = Array.from(document.querySelectorAll('[data-blog-card]'));
    if (cards.length === 0) return;

    var currentTag = '__all';
    function apply() {
      var q = (searchInput?.value || '').toLowerCase().trim();
      cards.forEach(function (c) {
        var hay = (c.getAttribute('data-search') || '').toLowerCase();
        var tags = (c.getAttribute('data-tags') || '').toLowerCase();
        var okText = (q === '' || hay.indexOf(q) !== -1);
        var okTag = (currentTag === '__all' || (tags && tags.indexOf(currentTag) !== -1));
        c.closest('[data-aos]')?.style && (c.closest('[data-aos]').style.display = (okText && okTag) ? '' : 'none');
      });
    }

    if (searchInput) searchInput.addEventListener('input', apply);
    if (resetBtn) resetBtn.addEventListener('click', function () {
      if (searchInput) searchInput.value = '';
      currentTag = '__all';
      if (tagWrap) {
        tagWrap.querySelectorAll('button[data-tag]').forEach(function (b) {
          b.className = (b.getAttribute('data-tag') === '__all') ? 'btn btn-sm btn-brand' : 'btn btn-sm btn-light border';
        });
      }
      apply();
    });

    if (tagWrap) {
      tagWrap.addEventListener('click', function (e) {
        var btn = e.target && (e.target.closest ? e.target.closest('button[data-tag]') : null);
        if (!btn) return;
        currentTag = btn.getAttribute('data-tag') || '__all';
        tagWrap.querySelectorAll('button[data-tag]').forEach(function (b) {
          b.className = (b === btn) ? 'btn btn-sm btn-brand' : 'btn btn-sm btn-light border';
        });
        apply();
      });
    }
    apply();
  })();
</script>

