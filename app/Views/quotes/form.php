<?php
/** @var string|null $flash */
/** @var list<array<string, mixed>> $services */

$servicesWithPricing = array_values(array_filter($services ?? [], static function (array $s): bool {
    return (int)($s['is_published'] ?? 0) === 1;
}));

$companyName = \App\Models\Setting::get('company_name', 'Dolice Decoration') ?? 'Dolice Decoration';
$phone = \App\Models\Setting::get('phone', null);
$whatsapp = \App\Models\Setting::get('whatsapp', null);
$email = \App\Models\Setting::get('email', null);
?>
<header class="py-5 bg-soft quote-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Devis</li>
      </ol>
    </nav>

    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
          <i class="bi bi-lightning-charge-fill text-brand"></i>
          <span>Réponse rapide • Estimation gratuite • Suivi pro</span>
        </div>
        <h1 class="display-6 fw-bold mt-3 mb-2 section-title">Demander un devis</h1>
        <p class="lead text-secondary mb-0">Décris ton projet en quelques minutes. Nous te recontactons rapidement avec une proposition claire.</p>
      </div>
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm quote-steps">
          <div class="card-body p-4">
            <div class="fw-semibold mb-3">Comment ça marche</div>
            <div class="quote-step"><span class="quote-step-n">1</span><div><div class="fw-semibold">Infos essentielles</div><div class="text-secondary small">Contact + projet + localisation.</div></div></div>
            <div class="quote-step"><span class="quote-step-n">2</span><div><div class="fw-semibold">Services (optionnel)</div><div class="text-secondary small">Choisis des prestations, total estimatif si prix dispo.</div></div></div>
            <div class="quote-step"><span class="quote-step-n">3</span><div><div class="fw-semibold">Validation</div><div class="text-secondary small">On te répond et on planifie la suite.</div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8" data-aos="fade-up">
        <?php if (!empty($flash)): ?>
          <div class="alert alert-primary"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
          <div class="card-body p-4 p-lg-5">
            <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>" class="quote-form">
              <input type="text" name="company" value="" style="display:none">

              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                  <div class="fw-semibold">Tes informations</div>
                  <div class="text-secondary small">On utilise ces infos uniquement pour te recontacter.</div>
                </div>
                <span class="badge text-bg-light border"><i class="bi bi-shield-lock me-1"></i>Données protégées</span>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="name" required placeholder="Ex: Jean Rakoto">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Téléphone</label>
                  <input class="form-control" type="text" name="phone" inputmode="tel" placeholder="Ex: 034 00 000 00">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Email</label>
                  <input class="form-control" type="email" name="email" inputmode="email" placeholder="Ex: vous@email.com">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Contact préféré</label>
                  <select class="form-select" name="contact_preference">
                    <option value="">—</option>
                    <option value="Téléphone">Téléphone</option>
                    <option value="WhatsApp">WhatsApp</option>
                    <option value="Email">Email</option>
                  </select>
                </div>
              </div>

              <hr class="my-4">

              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                  <div class="fw-semibold">Projet</div>
                  <div class="text-secondary small">Plus tu es précis, plus le devis est rapide.</div>
                </div>
                <span class="badge text-bg-light border"><i class="bi bi-clipboard-check me-1"></i>2–3 min</span>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Type de projet</label>
                  <input class="form-control" type="text" name="project_type" placeholder="Ex: plafond, peinture, sol...">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ville / Quartier</label>
                  <input class="form-control" type="text" name="city" placeholder="Ex: Antananarivo, Itaosy">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Adresse (optionnel)</label>
                  <input class="form-control" type="text" name="address" placeholder="Rue, lot, repère...">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Surface / dimensions</label>
                  <input class="form-control" type="text" name="surface" placeholder="Ex: 40 m² / 12m x 3m">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Délai souhaité</label>
                  <select class="form-select" name="timeline">
                    <option value="">—</option>
                    <option value="Urgent (1-7 jours)">Urgent (1-7 jours)</option>
                    <option value="2-3 semaines">2-3 semaines</option>
                    <option value="1 mois +">1 mois +</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Budget indicatif (optionnel)</label>
                  <select class="form-select" name="budget">
                    <option value="">—</option>
                    <option value="< 1M Ar">&lt; 1M Ar</option>
                    <option value="1M – 3M Ar">1M – 3M Ar</option>
                    <option value="3M – 8M Ar">3M – 8M Ar</option>
                    <option value="8M+ Ar">8M+ Ar</option>
                  </select>
                  <div class="form-text">Indication facultative pour ajuster la proposition.</div>
                </div>
              </div>

              <?php if (!empty($servicesWithPricing)): ?>
                <hr class="my-4">
                <div class="mt-0">
                  <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-2">
                    <div>
                      <div class="fw-semibold">Services souhaités <span class="text-secondary small">(optionnel)</span></div>
                      <div class="text-secondary small">Tu peux sélectionner plusieurs services.</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                      <input class="form-control form-control-sm" style="max-width:260px" type="search" placeholder="Rechercher un service..." data-service-search>
                      <button class="btn btn-sm btn-light border" type="button" data-services-reset><i class="bi bi-x-lg"></i></button>
                    </div>
                  </div>

                  <div class="border rounded-4 p-3 quote-services">
                    <div class="row g-2" data-services-list>
                      <?php foreach ($servicesWithPricing as $s): ?>
                        <?php
                          $id = (int)($s['id'] ?? 0);
                          $title = (string)($s['title'] ?? '');
                          $showPrice = (int)($s['show_price'] ?? 0) === 1;
                          $basePrice = $s['base_price'] ?? null;
                          $unit = trim((string)($s['price_unit'] ?? ''));
                          $label = trim((string)($s['price_label'] ?? '')) ?: 'À partir de';
                          $priceText = 'Prix sur demande';
                          $priceValue = '';
                          if ($showPrice && $basePrice !== null && $basePrice !== '') {
                              $priceValue = (string)(float)$basePrice;
                              $priceText = $label . ' ' . number_format((float)$basePrice, 0, ',', ' ') . ' Ar' . ($unit !== '' ? (' ' . $unit) : '');
                          }
                        ?>
                        <div class="col-12">
                          <label class="quote-service-item" data-service-row data-search="<?= htmlspecialchars(strtolower($title . ' ' . (string)($s['category'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                            <input class="form-check-input" type="checkbox" name="services[]" value="<?= $id ?>" data-price="<?= htmlspecialchars($priceValue, ENT_QUOTES, 'UTF-8') ?>">
                            <span class="quote-service-main">
                              <span class="fw-semibold d-block"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></span>
                              <span class="text-secondary small"><?= htmlspecialchars($priceText, ENT_QUOTES, 'UTF-8') ?></span>
                            </span>
                            <span class="quote-service-tag badge text-bg-light border"><?= htmlspecialchars((string)($s['category'] ?? 'Service'), ENT_QUOTES, 'UTF-8') ?></span>
                          </label>
                        </div>
                      <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                      <div class="text-secondary small"><i class="bi bi-calculator me-1"></i>Total estimatif (si prix disponibles)</div>
                      <div class="fw-bold" data-quote-estimate>Total: —</div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <hr class="my-4">

              <div class="mt-0">
                <label class="form-label">Détails supplémentaires</label>
                <textarea class="form-control" name="message" rows="7" placeholder="Décris ton besoin: contexte, contraintes, style souhaité, références..."></textarea>
                <div class="form-text">Astuce: indique la surface, la localisation et le délai si possible (ça accélère le devis).</div>
              </div>
              <div class="d-flex gap-2 flex-wrap mt-4">
                <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i>Envoyer la demande</button>
                <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i>Voir nos réalisations</a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="quote-summary sticky-lg-top" style="top:92px">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <div class="fw-semibold"><i class="bi bi-receipt-cutoff me-2 text-brand"></i>Résumé</div>
                <span class="badge text-bg-light border" data-quote-count>0 service</span>
              </div>
              <div class="text-secondary small mb-3">Estimation indicative selon les prix visibles.</div>

              <div class="d-flex align-items-center justify-content-between border rounded-4 p-3 mb-3" style="background:rgba(255,255,255,.7)">
                <div class="text-secondary small">Total estimatif</div>
                <div class="h5 mb-0" data-quote-estimate-side>—</div>
              </div>

              <div class="fw-semibold mb-2">À fournir idéalement</div>
              <ul class="text-secondary small mb-3">
                <li>Type de travaux + surface/dimensions</li>
                <li>Localisation + délai</li>
                <li>Photos / références (si dispo)</li>
              </ul>

              <div class="border-top pt-3">
                <div class="fw-semibold mb-2">Besoin d’aide ?</div>
                <div class="text-secondary small mb-2">Contacte <span class="fw-semibold"><?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?></span></div>
                <div class="d-flex flex-column gap-2">
                  <?php if (!empty($phone)): ?>
                    <a class="btn btn-sm btn-light border text-start" href="tel:<?= htmlspecialchars((string)$phone, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-telephone me-2 text-brand"></i><?= htmlspecialchars((string)$phone, ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endif; ?>
                  <?php if (!empty($whatsapp)): ?>
                    <a class="btn btn-sm btn-light border text-start" target="_blank" rel="noopener" href="<?= htmlspecialchars('https://wa.me/' . preg_replace('/\D+/', '', (string)$whatsapp), ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-whatsapp me-2 text-brand"></i>WhatsApp: <?= htmlspecialchars((string)$whatsapp, ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endif; ?>
                  <?php if (!empty($email)): ?>
                    <a class="btn btn-sm btn-light border text-start" href="mailto:<?= htmlspecialchars((string)$email, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2 text-brand"></i><?= htmlspecialchars((string)$email, ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  (function () {
    var estimateEl = document.querySelector('[data-quote-estimate]');
    var estimateSide = document.querySelector('[data-quote-estimate-side]');
    var countEl = document.querySelector('[data-quote-count]');
    var inputs = Array.from(document.querySelectorAll('input[type="checkbox"][name="services[]"]'));
    var searchInput = document.querySelector('[data-service-search]');
    var resetBtn = document.querySelector('[data-services-reset]');
    var rows = Array.from(document.querySelectorAll('[data-service-row]'));

    function formatAr(n) {
      try {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Ar';
      } catch (e) {
        return n + ' Ar';
      }
    }

    function recompute() {
      var total = 0;
      var hasAny = false;
      var selectedCount = 0;
      inputs.forEach(function (inp) {
        if (!inp.checked) return;
        selectedCount += 1;
        var p = parseFloat(inp.getAttribute('data-price') || '');
        if (!isFinite(p) || p <= 0) return;
        total += p;
        hasAny = true;
      });
      if (estimateEl) estimateEl.textContent = hasAny ? ('Total: ' + formatAr(total)) : 'Total: —';
      if (estimateSide) estimateSide.textContent = hasAny ? formatAr(total) : '—';
      if (countEl) countEl.textContent = selectedCount + (selectedCount > 1 ? ' services' : ' service');
    }

    function applySearch() {
      if (!searchInput || rows.length === 0) return;
      var q = (searchInput.value || '').toLowerCase().trim();
      rows.forEach(function (row) {
        var hay = (row.getAttribute('data-search') || '').toLowerCase();
        row.style.display = (q === '' || hay.indexOf(q) !== -1) ? '' : 'none';
      });
    }

    if (inputs.length > 0) {
      inputs.forEach(function (inp) {
        inp.addEventListener('change', recompute);
      });
      recompute();
    } else {
      if (estimateSide) estimateSide.textContent = '—';
    }

    if (searchInput) {
      searchInput.addEventListener('input', applySearch);
    }
    if (resetBtn) {
      resetBtn.addEventListener('click', function () {
        if (searchInput) searchInput.value = '';
        applySearch();
      });
    }
  })();
</script>

