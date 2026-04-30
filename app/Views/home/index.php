<?php
/** @var string $title */
/** @var list<array<string, mixed>> $services */
/** @var list<array<string, mixed>> $projects */
/** @var list<array<string, mixed>> $posts */
/** @var list<array<string, mixed>> $testimonials */
/** @var list<array<string, mixed>> $heroSlides */
/** @var array<string, string|null> $settings */

$homeBadge = trim((string)($settings['home_badge_text'] ?? ''));
$homeTitle = trim((string)($settings['home_hero_title'] ?? ''));
$homeSubtitle = trim((string)($settings['home_hero_subtitle'] ?? ''));
$primaryLabel = trim((string)($settings['home_primary_cta_label'] ?? ''));
$primaryUrl = trim((string)($settings['home_primary_cta_url'] ?? ''));
$secondaryLabel = trim((string)($settings['home_secondary_cta_label'] ?? ''));
$secondaryUrl = trim((string)($settings['home_secondary_cta_url'] ?? ''));
$slidesEnabled = ((string)($settings['home_slides_enabled'] ?? '1')) === '1';

$heroCoverRaw = trim((string)($settings['hero_cover_image'] ?? ''));
if ($heroCoverRaw === '') {
    $heroCoverRaw = '/uploads/2151892472.jpg';
}
$isAbsoluteCover = preg_match('#^https?://#i', $heroCoverRaw) === 1;
$heroCoverUrl = $isAbsoluteCover
    ? $heroCoverRaw
    : (rtrim((string)(env('APP_URL', '') ?: ''), '/') . '/' . ltrim($heroCoverRaw, '/'));
?>
<header class="hero hero-fullscreen py-5" style="--hero-cover:url('<?= htmlspecialchars($heroCoverUrl, ENT_QUOTES, 'UTF-8') ?>')">
  <div class="container py-3">
    <div class="row align-items-center g-4">
      <div class="col-lg-7" data-aos="fade-up">
        <div class="badge text-bg-light text-dark rounded-pill mb-3">
          <i class="bi bi-award me-2 text-brand"></i><?= htmlspecialchars($homeBadge !== '' ? $homeBadge : 'Finitions premium • Délais maîtrisés • Suivi pro', ENT_QUOTES, 'UTF-8') ?>
        </div>
        <h1 class="display-5 fw-bold section-title mb-3"><?= htmlspecialchars($homeTitle !== '' ? $homeTitle : $title, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="lead text-white-50 mb-4"><?= htmlspecialchars($homeSubtitle !== '' ? $homeSubtitle : "Entreprise de finition & décoration bâtiment. Des prestations nettes, des matériaux adaptés, et une exécution rigoureuse.", ENT_QUOTES, 'UTF-8') ?></p>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-brand btn-lg" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . ($primaryUrl !== '' ? $primaryUrl : '/devis'), ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars($primaryLabel !== '' ? $primaryLabel : 'Demander un devis', ENT_QUOTES, 'UTF-8') ?>
          </a>
          <a class="btn btn-outline-light btn-lg" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . ($secondaryUrl !== '' ? $secondaryUrl : '/realisations'), ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-images me-2"></i><?= htmlspecialchars($secondaryLabel !== '' ? $secondaryLabel : 'Voir nos réalisations', ENT_QUOTES, 'UTF-8') ?>
          </a>
          <!--button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#quickQuoteModal">
            <i class="bi bi-lightning-charge me-2 text-brand"></i>Devis express
          </button-->
        </div>
        <div class="row g-3 mt-4">
          <div class="col-6 col-md-4">
            <div class="d-flex align-items-center gap-2">
              <div class="icon-badge"><i class="bi bi-shield-check"></i></div>
              <div>
                <div class="fw-semibold">Qualité</div>
                <div class="text-white-50 small">Finitions propres</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-4">
            <div class="d-flex align-items-center gap-2">
              <div class="icon-badge"><i class="bi bi-clock-history"></i></div>
              <div>
                <div class="fw-semibold">Délais</div>
                <div class="text-white-50 small">Planning suivi</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-4">
            <div class="d-flex align-items-center gap-2">
              <div class="icon-badge"><i class="bi bi-chat-square-dots"></i></div>
              <div>
                <div class="fw-semibold">Conseil</div>
                <div class="text-white-50 small">Accompagnement</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5" data-aos="zoom-in">
        <div id="heroCarousel" class="carousel slide hero-media-carousel" data-bs-ride="carousel" data-bs-interval="5200">
          <?php if ($slidesEnabled && !empty($heroSlides)): ?>
            <?php if (count($heroSlides) > 1): ?>
              <div class="carousel-indicators">
                <?php foreach ($heroSlides as $i => $_s): ?>
                  <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= (int)$i ?>" class="<?= $i === 0 ? 'active' : '' ?>" <?= $i === 0 ? 'aria-current="true"' : '' ?> aria-label="Slide <?= (int)($i + 1) ?>"></button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <div class="carousel-inner rounded-4 border border-light border-opacity-10 overflow-hidden">
              <?php foreach ($heroSlides as $i => $s): ?>
                <?php
                  $type = (string)($s['media_type'] ?? 'image');
                  if (!in_array($type, ['image', 'video'], true)) $type = 'image';
                  $path = trim((string)($s['media_path'] ?? ''));
                  $mediaUrl = (env('APP_URL', '') ?: '') . $path;
                  $captionTitle = trim((string)($s['title'] ?? ''));
                  $captionSub = trim((string)($s['subtitle'] ?? ''));
                  $ctaLabel = trim((string)($s['cta_label'] ?? ''));
                  $ctaUrl = trim((string)($s['cta_url'] ?? ''));
                  $ctaHref = $ctaUrl !== '' ? ((preg_match('#^https?://#i', $ctaUrl) === 1) ? $ctaUrl : ((env('APP_URL', '') ?: '') . '/' . ltrim($ctaUrl, '/'))) : '';
                ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                  <div class="hero-slide-frame">
                    <?php if ($type === 'video'): ?>
                      <video class="hero-slide-media" src="<?= htmlspecialchars($mediaUrl, ENT_QUOTES, 'UTF-8') ?>" muted playsinline autoplay loop preload="metadata"></video>
                      <div class="hero-slide-badge"><i class="bi bi-play-circle-fill me-1"></i>Vidéo</div>
                    <?php else: ?>
                      <img class="hero-slide-media" src="<?= htmlspecialchars($mediaUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
                    <?php endif; ?>

                    <?php if ($captionTitle !== '' || $captionSub !== '' || ($ctaLabel !== '' && $ctaHref !== '')): ?>
                      <div class="hero-slide-caption">
                        <?php if ($captionTitle !== ''): ?>
                          <div class="fw-bold"><?= htmlspecialchars($captionTitle, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <?php if ($captionSub !== ''): ?>
                          <div class="small text-white-50"><?= htmlspecialchars($captionSub, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <?php if ($ctaLabel !== '' && $ctaHref !== ''): ?>
                          <div class="mt-2">
                            <a class="btn btn-sm btn-light" href="<?= htmlspecialchars($ctaHref, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($ctaLabel, ENT_QUOTES, 'UTF-8') ?> <i class="bi bi-arrow-right ms-1"></i></a>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <?php if (count($heroSlides) > 1): ?>
              <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
              </button>
            <?php endif; ?>
          <?php else: ?>
            <div class="carousel-inner rounded-4 border border-light border-opacity-10 overflow-hidden">
              <div class="carousel-item active">
                <div class="p-4 bg-dark bg-opacity-25">
                  <div class="skeleton" style="height:280px"></div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <!--p class="text-white-50 small mt-3 mb-0">Astuce: ajoute des images/vidéos dans Admin → Slides accueil.</p>
        -->
      </div>
    </div>
  </div>
</header>

<section class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
      <div>
        <h2 class="h1 section-title mb-1" data-aos="fade-up">Services</h2>
        <div class="text-secondary" data-aos="fade-up" data-aos-delay="50">Des solutions claires, des finitions nettes.</div>
      </div>
      <a class="btn btn-outline-primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">
        Tout voir <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-4">
      <?php foreach (($services ?? []) as $s): ?>
        <?php
          $img = trim((string)($s['image_path'] ?? ''));
          $imgUrl = '';
          if ($img !== '') {
            $imgUrl = (preg_match('#^https?://#i', $img) === 1) ? $img : ((env('APP_URL', '') ?: '') . $img);
          }
        ?>
        <div class="col-sm-6 col-lg-4" data-aos="fade-up">
          <a class="text-decoration-none" data-service-modal="1" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services/' . (string)$s['slug'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="card card-hover h-100">
              <div class="ratio ratio-4x3 card-media-4x3">
                <?php if ($imgUrl !== ''): ?>
                  <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
                <?php else: ?>
                  <div class="card-media-fallback"><i class="bi bi-image"></i></div>
                <?php endif; ?>
              </div>
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <div class="icon-badge"><i class="bi bi-tools"></i></div>
                  <span class="badge text-bg-light border"><?= htmlspecialchars((string)($s['category'] ?? 'Service'), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <h3 class="h5 mb-2 line-clamp-2"><?= htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <?php
                  $bp = $s['base_price'] ?? null;
                  $show = (int)($s['show_price'] ?? 0) === 1;
                ?>
                <?php if ($show && $bp !== null && $bp !== ''): ?>
                  <?php
                    $label = trim((string)($s['price_label'] ?? '')) ?: 'À partir de';
                    $unit = trim((string)($s['price_unit'] ?? ''));
                    $txt = $label . ' ' . number_format((float)$bp, 0, ',', ' ') . ' Ar' . ($unit !== '' ? (' ' . $unit) : '');
                  ?>
                  <div class="mt-2">
                    <span class="badge text-bg-light border"><i class="bi bi-cash-coin me-1"></i><?= htmlspecialchars($txt, ENT_QUOTES, 'UTF-8') ?></span>
                  </div>
                <?php endif; ?>
                <div class="text-secondary small">Clique pour voir le détail + demander un devis.</div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-5 bg-soft">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-5" data-aos="fade-up">
        <div class="badge text-bg-light border rounded-pill px-3 py-2">
          <i class="bi bi-check2-circle me-2 text-brand"></i>Méthode simple & efficace
        </div>
        <h2 class="h1 section-title mt-3 mb-2">Un process clair, du premier contact à la livraison</h2>
        <p class="text-secondary mb-0">Nous cadrons le besoin, proposons une solution adaptée, puis exécutons proprement avec un suivi régulier.</p>
      </div>
      <div class="col-lg-7" data-aos="fade-up" data-aos-delay="80">
        <div class="row g-3">
          <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 border h-100">
              <div class="icon-badge mb-3"><i class="bi bi-chat-square-dots"></i></div>
              <div class="fw-semibold mb-1">1) Brief</div>
              <div class="text-secondary small">Besoin, contraintes, style, budget indicatif.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 border h-100">
              <div class="icon-badge mb-3"><i class="bi bi-clipboard-check"></i></div>
              <div class="fw-semibold mb-1">2) Devis</div>
              <div class="text-secondary small">Proposition claire + délais + options.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 border h-100">
              <div class="icon-badge mb-3"><i class="bi bi-hammer"></i></div>
              <div class="fw-semibold mb-1">3) Exécution</div>
              <div class="text-secondary small">Finitions propres + contrôle qualité.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-soft">
  <div class="container">
    <div class="row g-4 align-items-end mb-4">
      <div class="col-lg-7">
        <h2 class="h1 section-title mb-1" data-aos="fade-up">Réalisations</h2>
        <div class="text-secondary" data-aos="fade-up" data-aos-delay="50">Un aperçu de nos chantiers récents.</div>
      </div>
      <div class="col-lg-5 text-lg-end">
        <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">
          Explorer le portfolio <i class="bi bi-images ms-1"></i>
        </a>
      </div>
    </div>

    <div class="glide" id="glidePortfolio" data-aos="fade-up">
      <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
          <?php foreach (($projects ?? []) as $p): ?>
            <li class="glide__slide">
              <a class="text-decoration-none" data-project-modal="1" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations/' . (string)$p['slug'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="card card-hover h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="text-secondary small mb-1"><?= htmlspecialchars((string)($p['location'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
                        <h3 class="h5 mb-0"><?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                      </div>
                      <?php if ((int)($p['is_featured'] ?? 0) === 1): ?>
                        <span class="badge text-bg-warning"><i class="bi bi-star-fill me-1"></i>Vedette</span>
                      <?php endif; ?>
                    </div>
                    <div class="ratio ratio-4x3 card-media-4x3 mt-3">
                      <?php if (!empty($p['cover_image'])): ?>
                        <img src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$p['cover_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string)$p['title'], ENT_QUOTES, 'UTF-8') ?>">
                      <?php else: ?>
                        <div class="card-media-fallback"><i class="bi bi-image"></i></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="d-flex justify-content-center gap-2 mt-3" data-glide-el="controls">
        <button class="btn btn-outline-secondary btn-sm" data-glide-dir="<"><i class="bi bi-arrow-left"></i></button>
        <button class="btn btn-outline-secondary btn-sm" data-glide-dir=">"><i class="bi bi-arrow-right"></i></button>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($testimonials)): ?>
  <section class="py-5">
    <div class="container">
      <div class="row g-4 align-items-center mb-4">
        <div class="col-lg-6">
          <h2 class="h1 section-title mb-1" data-aos="fade-up">Témoignages</h2>
          <div class="text-secondary" data-aos="fade-up" data-aos-delay="50">La preuve sociale qui rassure.</div>
        </div>
        <div class="col-lg-6">
          <div class="row g-3" data-aos="fade-up" data-aos-delay="100">
            <div class="col-6 col-md-3">
              <div class="p-3 bg-white rounded-4 border">
                <div class="h3 fw-bold mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="120" data-purecounter-duration="1">0</span>+</div>
                <div class="text-secondary small">Projets</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-3 bg-white rounded-4 border">
                <div class="h3 fw-bold mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="8" data-purecounter-duration="1">0</span>+</div>
                <div class="text-secondary small">Années</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-3 bg-white rounded-4 border">
                <div class="h3 fw-bold mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="35" data-purecounter-duration="1">0</span>+</div>
                <div class="text-secondary small">Clients</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-3 bg-white rounded-4 border">
                <div class="h3 fw-bold mb-0"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="98" data-purecounter-duration="1">0</span>%</div>
                <div class="text-secondary small">Satisf.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="glide" id="glideTestimonials" data-aos="fade-up">
        <div class="glide__track" data-glide-el="track">
          <ul class="glide__slides">
            <?php foreach ($testimonials as $t): ?>
              <?php
                $logoPathRaw = trim((string)($t['logo_path'] ?? ''));
                $logoFileExists = false;
                if ($logoPathRaw !== '') {
                    $logoDiskPath = rtrim((string)PUBLIC_PATH, '/\\') . '/' . ltrim($logoPathRaw, '/\\');
                    $logoFileExists = is_file($logoDiskPath);
                }
                $logoUrl = $logoFileExists ? (env('APP_URL', '') ?: '') . $logoPathRaw : '';
                $company = trim((string)($t['client_company'] ?? ''));
                $rating = (int)($t['rating'] ?? 0);
                if ($rating < 0) $rating = 0;
                if ($rating > 5) $rating = 5;
              ?>
              <li class="glide__slide">
                <article class="testimonial-card p-4 h-100">
                  <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                      <div class="testimonial-avatar">
                        <?php if ($logoUrl !== ''): ?>
                          <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
                        <?php else: ?>
                          <i class="bi bi-person-circle" aria-hidden="true"></i>
                        <?php endif; ?>
                      </div>
                      <div class="min-w-0">
                        <div class="fw-semibold text-truncate"><?= htmlspecialchars((string)$t['client_name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php if ($company !== ''): ?>
                          <div class="text-secondary small text-truncate"><?= htmlspecialchars($company, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                      </div>
                    </div>

                    <?php if ($rating > 0): ?>
                      <div class="testimonial-stars text-warning flex-shrink-0" aria-label="<?= htmlspecialchars((string)$rating, ENT_QUOTES, 'UTF-8') ?> sur 5">
                        <?php for ($i=0; $i < $rating; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="testimonial-quote text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)$t['content'], ENT_QUOTES, 'UTF-8') ?></div>
                </article>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="d-flex justify-content-center gap-2 mt-3" data-glide-el="controls">
          <button class="btn btn-outline-secondary btn-sm" data-glide-dir="<"><i class="bi bi-arrow-left"></i></button>
          <button class="btn btn-outline-secondary btn-sm" data-glide-dir=">"><i class="bi bi-arrow-right"></i></button>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if (!empty($posts)): ?>
  <section class="py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
        <div>
          <h2 class="h1 section-title mb-1" data-aos="fade-up">Blog</h2>
          <div class="text-secondary" data-aos="fade-up" data-aos-delay="50">Conseils, tendances et retours chantier.</div>
        </div>
        <a class="btn btn-outline-primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">
          Lire tout <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="row g-4">
        <?php foreach ($posts as $post): ?>
          <?php
            $img = trim((string)($post['featured_image'] ?? ''));
            $imgUrl = '';
            if ($img !== '') {
              $imgUrl = (preg_match('#^https?://#i', $img) === 1) ? $img : ((env('APP_URL', '') ?: '') . $img);
            }
          ?>
          <div class="col-md-6 col-lg-4" data-aos="fade-up">
            <a class="text-decoration-none" data-post-modal="1" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog/' . (string)$post['slug'], ENT_QUOTES, 'UTF-8') ?>">
              <div class="card card-hover h-100">
                <div class="ratio ratio-4x3 card-media-4x3">
                  <?php if ($imgUrl !== ''): ?>
                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
                  <?php else: ?>
                    <div class="card-media-fallback"><i class="bi bi-image"></i></div>
                  <?php endif; ?>
                </div>
                <div class="card-body">
                  <div class="text-secondary small mb-2"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars((string)($post['published_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                  <h3 class="h5 line-clamp-2"><?= htmlspecialchars((string)$post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                  <div class="text-secondary line-clamp-3"><?= htmlspecialchars((string)($post['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- Quick quote modal (posts to existing /devis route) -->
<div class="modal fade" id="quickQuoteModal" tabindex="-1" aria-labelledby="quickQuoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quickQuoteModalLabel">Devis express</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
        <div class="modal-body">
          <input type="text" name="company" value="" style="display:none">
          <div class="mb-3">
            <label class="form-label">Nom</label>
            <input class="form-control" type="text" name="name" required>
          </div>
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Téléphone</label>
              <input class="form-control" type="text" name="phone">
            </div>
            <div class="col-sm-6">
              <label class="form-label">Email</label>
              <input class="form-control" type="email" name="email">
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label">Type de projet</label>
            <input class="form-control" type="text" name="project_type">
          </div>
          <div class="mt-3">
            <label class="form-label">Message</label>
            <textarea class="form-control" name="message" rows="4"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i>Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>
