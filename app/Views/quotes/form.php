<?php /** @var string|null $flash */ ?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Demander un devis</li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
      <div>
        <h1 class="display-6 fw-bold mb-1 section-title">Demander un devis</h1>
        <div class="text-secondary">Réponse rapide avec les infos essentielles.</div>
      </div>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8" data-aos="fade-up">
        <?php if (!empty($flash)): ?>
          <div class="alert alert-primary"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
              <input type="text" name="company" value="" style="display:none">
              <div class="mb-3">
                <label class="form-label">Nom</label>
                <input class="form-control" type="text" name="name" required>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Téléphone</label>
                  <input class="form-control" type="text" name="phone">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input class="form-control" type="email" name="email">
                </div>
              </div>
              <div class="mt-3">
                <label class="form-label">Type de projet</label>
                <input class="form-control" type="text" name="project_type" placeholder="ex: plafond, peinture, sol...">
              </div>
              <div class="mt-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" name="message" rows="7" placeholder="Surface, localisation, délai, photos (si dispo)"></textarea>
              </div>
              <div class="d-flex gap-2 flex-wrap mt-4">
                <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i>Envoyer</button>
                <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i>Voir nos réalisations</a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="fw-semibold mb-2">Conseil</div>
            <div class="text-secondary mb-3">Plus tu es précis, plus le devis est rapide.</div>
            <ul class="text-secondary mb-0">
              <li>Type de travaux</li>
              <li>Surface / dimensions</li>
              <li>Localisation</li>
              <li>Délai souhaité</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

