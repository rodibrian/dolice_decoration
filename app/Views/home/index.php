<?php
/** @var string $title */
/** @var list<array<string, mixed>> $services */
/** @var list<array<string, mixed>> $projects */
/** @var list<array<string, mixed>> $posts */
/** @var list<array<string, mixed>> $testimonials */
?>
<header class="hero py-5">
  <div class="container py-3">
    <div class="row align-items-center g-4">
      <div class="col-lg-7" data-aos="fade-up">
        <div class="badge text-bg-light text-dark rounded-pill mb-3">
          <i class="bi bi-award me-2 text-brand"></i>Finitions premium • Délais maîtrisés • Suivi pro
        </div>
        <h1 class="display-5 fw-bold section-title mb-3"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="lead text-white-50 mb-4">Entreprise de finition & décoration bâtiment. Des prestations nettes, des matériaux adaptés, et une exécution rigoureuse.</p>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-brand btn-lg" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-clipboard-check me-2"></i>Demander un devis
          </a>
          <a class="btn btn-outline-light btn-lg" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-images me-2"></i>Voir nos réalisations
          </a>
          <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#quickQuoteModal">
            <i class="bi bi-lightning-charge me-2 text-brand"></i>Devis express
          </button>
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
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner rounded-4 border border-light border-opacity-10 overflow-hidden">
            <div class="carousel-item active">
              <div class="p-4 bg-dark bg-opacity-25">
                <div class="skeleton" style="height:280px"></div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="p-4 bg-dark bg-opacity-25">
                <div class="skeleton" style="height:280px"></div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="p-4 bg-dark bg-opacity-25">
                <div class="skeleton" style="height:280px"></div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
          </button>
        </div>
        <p class="text-white-50 small mt-3 mb-0">Astuce: ajoute des images réelles dans les réalisations pour remplacer ces blocs.</p>
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
        <div class="col-sm-6 col-lg-4" data-aos="fade-up">
          <a class="text-decoration-none" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services/' . (string)$s['slug'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="card card-hover h-100">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <div class="icon-badge"><i class="bi bi-tools"></i></div>
                  <span class="badge text-bg-light"><?= htmlspecialchars((string)($s['category'] ?? 'Service'), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <h3 class="h5 mb-2"><?= htmlspecialchars((string)$s['title'], ENT_QUOTES, 'UTF-8') ?></h3>
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
              <a class="text-decoration-none" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations/' . (string)$p['slug'], ENT_QUOTES, 'UTF-8') ?>">
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
                    <div class="mt-3 skeleton" style="height:160px"></div>
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
              <li class="glide__slide">
                <div class="testimonial p-4 h-100">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fw-semibold"><?= htmlspecialchars((string)$t['client_name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php if (!empty($t['rating'])): ?>
                      <div class="text-warning">
                        <?php for ($i=0; $i < (int)$t['rating']; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)$t['content'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
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
          <div class="col-md-6 col-lg-4" data-aos="fade-up">
            <a class="text-decoration-none" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog/' . (string)$post['slug'], ENT_QUOTES, 'UTF-8') ?>">
              <div class="card card-hover h-100">
                <div class="card-body">
                  <div class="text-secondary small mb-2"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars((string)($post['published_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                  <h3 class="h5"><?= htmlspecialchars((string)$post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                  <div class="text-secondary"><?= htmlspecialchars((string)($post['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
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
