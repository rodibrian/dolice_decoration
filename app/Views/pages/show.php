<?php
/** @var array<string, mixed> $page */
/** @var array<string, string|null> $settings */
/** @var string|null $flash */

$key = (string)($page['page_key'] ?? '');
?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars((string)$page['title'], ENT_QUOTES, 'UTF-8') ?></li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
      <div>
        <h1 class="display-6 fw-bold mb-1 section-title"><?= htmlspecialchars((string)$page['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <div class="text-secondary">Informations utiles et contact.</div>
      </div>
      <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
        <i class="bi bi-clipboard-check me-2"></i>Demander un devis
      </a>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <?php if (!empty($flash)): ?>
      <div class="alert alert-primary"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-lg-7" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)($page['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
          </div>
        </div>
      </div>
      <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="fw-semibold mb-3">Coordonnées</div>
            <div class="d-flex flex-column gap-2 text-secondary">
              <?php if (!empty($settings['phone'])): ?>
                <div><i class="bi bi-telephone me-2"></i><?= htmlspecialchars((string)$settings['phone'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
              <?php if (!empty($settings['whatsapp'])): ?>
                <div><i class="bi bi-whatsapp me-2"></i><?= htmlspecialchars((string)$settings['whatsapp'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
              <?php if (!empty($settings['email'])): ?>
                <div><i class="bi bi-envelope me-2"></i><?= htmlspecialchars((string)$settings['email'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
              <?php if (!empty($settings['address'])): ?>
                <div><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars((string)$settings['address'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
              <?php if (!empty($settings['hours'])): ?>
                <div><i class="bi bi-clock me-2"></i><?= htmlspecialchars((string)$settings['hours'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
              <?php if (!empty($settings['service_area'])): ?>
                <div><i class="bi bi-map me-2"></i><?= htmlspecialchars((string)$settings['service_area'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            </div>

            <div class="d-grid gap-2 mt-4">
              <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Demander un devis</a>
              <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-tools me-2"></i>Voir les services</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($key === 'contact'): ?>
  <section class="pb-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-8" data-aos="fade-up">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <h2 class="h4 mb-3">Envoyer un message</h2>
              <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">
                <input type="text" name="company" value="" style="display:none">
                <div class="mb-3">
                  <label class="form-label">Nom</label>
                  <input class="form-control" type="text" name="name" required>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input class="form-control" type="text" name="phone">
                  </div>
                </div>
                <div class="mt-3">
                  <label class="form-label">Sujet</label>
                  <input class="form-control" type="text" name="subject">
                </div>
                <div class="mt-3">
                  <label class="form-label">Message</label>
                  <textarea class="form-control" name="message" rows="6" required></textarea>
                </div>
                <div class="d-flex gap-2 flex-wrap mt-4">
                  <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i>Envoyer</button>
                  <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Plutôt un devis</a>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="fw-semibold mb-2">Réponse rapide</div>
              <div class="text-secondary mb-3">Pour gagner du temps, décris: type de travaux, surface, localisation, délais souhaités.</div>
              <div class="d-grid gap-2">
                <a class="btn btn-brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-lightning-charge me-2"></i>Devis express</a>
                <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i>Voir nos réalisations</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

