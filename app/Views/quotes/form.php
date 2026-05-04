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
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('nav.home'), ENT_QUOTES, 'UTF-8') ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(t('nav.quote'), ENT_QUOTES, 'UTF-8') ?></li>
      </ol>
    </nav>

    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
          <i class="bi bi-lightning-charge-fill text-brand"></i>
          <span><?= htmlspecialchars(t('public.quote.badge'), ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <h1 class="display-6 fw-bold mt-3 mb-2 section-title"><?= htmlspecialchars(t('public.quote.title'), ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="lead text-secondary mb-0"><?= htmlspecialchars(t('public.quote.subtitle'), ENT_QUOTES, 'UTF-8') ?></p>
      </div>
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm quote-steps">
          <div class="card-body p-4">
            <div class="fw-semibold mb-3"><?= htmlspecialchars(t('public.quote.how_title'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="quote-step"><span class="quote-step-n">1</span><div><div class="fw-semibold"><?= htmlspecialchars(t('public.quote.step1_title'), ENT_QUOTES, 'UTF-8') ?></div><div class="text-secondary small"><?= htmlspecialchars(t('public.quote.step1_sub'), ENT_QUOTES, 'UTF-8') ?></div></div></div>
            <div class="quote-step"><span class="quote-step-n">2</span><div><div class="fw-semibold"><?= htmlspecialchars(t('public.quote.step2_title'), ENT_QUOTES, 'UTF-8') ?></div><div class="text-secondary small"><?= htmlspecialchars(t('public.quote.step2_sub'), ENT_QUOTES, 'UTF-8') ?></div></div></div>
            <div class="quote-step"><span class="quote-step-n">3</span><div><div class="fw-semibold"><?= htmlspecialchars(t('public.quote.step3_title'), ENT_QUOTES, 'UTF-8') ?></div><div class="text-secondary small"><?= htmlspecialchars(t('public.quote.step3_sub'), ENT_QUOTES, 'UTF-8') ?></div></div></div>
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
                  <div class="fw-semibold"><?= htmlspecialchars(t('public.quote.your_info'), ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="text-secondary small"><?= htmlspecialchars(t('public.quote.your_info_sub'), ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <span class="badge text-bg-light border"><i class="bi bi-shield-lock me-1"></i><?= htmlspecialchars(t('public.quote.protected'), ENT_QUOTES, 'UTF-8') ?></span>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label"><?= htmlspecialchars(t('public.forms.full_name'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="name" required placeholder="<?= htmlspecialchars(t('public.forms.full_name_ph'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-3">
                  <label class="form-label"><?= htmlspecialchars(t('public.forms.phone'), ENT_QUOTES, 'UTF-8') ?></label>
                  <input class="form-control" type="text" name="phone" inputmode="tel" placeholder="<?= htmlspecialchars(t('public.forms.phone_ph'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-3">
                  <label class="form-label"><?= htmlspecialchars(t('public.forms.email'), ENT_QUOTES, 'UTF-8') ?></label>
                  <input class="form-control" type="email" name="email" inputmode="email" placeholder="<?= htmlspecialchars(t('public.forms.email_ph'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-4">
                  <label class="form-label"><?= htmlspecialchars(t('public.forms.contact_preference'), ENT_QUOTES, 'UTF-8') ?></label>
                  <select class="form-select" name="contact_preference">
                    <option value="">—</option>
                    <option value="phone"><?= htmlspecialchars(t('public.forms.contact_phone'), ENT_QUOTES, 'UTF-8') ?></option>
                    <option value="whatsapp"><?= htmlspecialchars(t('public.social.whatsapp'), ENT_QUOTES, 'UTF-8') ?></option>
                    <option value="email"><?= htmlspecialchars(t('public.forms.contact_email'), ENT_QUOTES, 'UTF-8') ?></option>
                  </select>
                </div>
              </div>

              <hr class="my-4">

              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                  <div class="fw-semibold"><?= htmlspecialchars(t('public.quote.project'), ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="text-secondary small"><?= htmlspecialchars(t('public.quote.project_sub'), ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <span class="badge text-bg-light border"><i class="bi bi-clipboard-check me-1"></i><?= htmlspecialchars(t('public.quote.time_badge'), ENT_QUOTES, 'UTF-8') ?></span>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label"><?= htmlspecialchars(t('public.quote.project_type'), ENT_QUOTES, 'UTF-8') ?></label>
                  <input class="form-control" type="text" name="project_type" placeholder="<?= htmlspecialchars(t('public.quote.project_type_ph'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label"><?= htmlspecialchars(t('public.quote.city'), ENT_QUOTES, 'UTF-8') ?></label>
                  <input class="form-control" type="text" name="city" placeholder="<?= htmlspecialchars(t('public.quote.city_ph'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label"><?= htmlspecialchars(t('public.quote.address'), ENT_QUOTES, 'UTF-8') ?></label>
                  <input class="form-control" type="text" name="address" placeholder="<?= htmlspecialchars(t('public.quote.address_ph'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-3">
                  <label class="form-label"><?= htmlspecialchars(t('public.quote.surface'), ENT_QUOTES, 'UTF-8') ?></label>
                  <input class="form-control" type="text" name="surface" placeholder="<?= htmlspecialchars(t('public.quote.surface_ph'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-md-3">
                  <label class="form-label"><?= htmlspecialchars(t('public.quote.timeline'), ENT_QUOTES, 'UTF-8') ?></label>
                  <select class="form-select" name="timeline">
                    <option value="">—</option>
                    <?php
                      $tlUrgent = t('public.quote.timeline_urgent');
                      $tl23 = t('public.quote.timeline_2_3w');
                      $tl1m = t('public.quote.timeline_1m');
                    ?>
                    <option value="<?= htmlspecialchars($tlUrgent, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($tlUrgent, ENT_QUOTES, 'UTF-8') ?></option>
                    <option value="<?= htmlspecialchars($tl23, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($tl23, ENT_QUOTES, 'UTF-8') ?></option>
                    <option value="<?= htmlspecialchars($tl1m, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($tl1m, ENT_QUOTES, 'UTF-8') ?></option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label"><?= htmlspecialchars(t('public.quote.budget'), ENT_QUOTES, 'UTF-8') ?></label>
                  <select class="form-select" name="budget">
                    <option value="">—</option>
                    <?php
                      $b1 = t('public.quote.budget_lt1m');
                      $b2 = t('public.quote.budget_1_3m');
                      $b3 = t('public.quote.budget_3_8m');
                      $b4 = t('public.quote.budget_8m');
                    ?>
                    <option value="<?= htmlspecialchars($b1, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($b1, ENT_QUOTES, 'UTF-8') ?></option>
                    <option value="<?= htmlspecialchars($b2, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($b2, ENT_QUOTES, 'UTF-8') ?></option>
                    <option value="<?= htmlspecialchars($b3, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($b3, ENT_QUOTES, 'UTF-8') ?></option>
                    <option value="<?= htmlspecialchars($b4, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($b4, ENT_QUOTES, 'UTF-8') ?></option>
                  </select>
                  <div class="form-text"><?= htmlspecialchars(t('public.quote.budget_hint'), ENT_QUOTES, 'UTF-8') ?></div>
                </div>
              </div>

              <?php if (!empty($servicesWithPricing)): ?>
                <hr class="my-4">
                <div class="mt-0">
                  <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-2">
                    <div>
                      <div class="fw-semibold"><?= htmlspecialchars(t('public.quote.services_wanted'), ENT_QUOTES, 'UTF-8') ?> <span class="text-secondary small">(<?= htmlspecialchars(t('public.forms.optional'), ENT_QUOTES, 'UTF-8') ?>)</span></div>
                      <div class="text-secondary small"><?= htmlspecialchars(t('public.quote.services_wanted_sub'), ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                      <input class="form-control form-control-sm" style="max-width:260px" type="search" placeholder="<?= htmlspecialchars(t('public.quote.search_service_ph'), ENT_QUOTES, 'UTF-8') ?>" data-service-search>
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
                          $label = trim((string)($s['price_label'] ?? '')) ?: t('public.quote.price_from');
                          $priceText = t('public.quote.price_on_request');
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
                            <span class="quote-service-tag badge text-bg-light border"><?= htmlspecialchars((string)($s['category'] ?? t('nav.services')), ENT_QUOTES, 'UTF-8') ?></span>
                          </label>
                        </div>
                      <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                      <div class="text-secondary small"><i class="bi bi-calculator me-1"></i><?= htmlspecialchars(t('public.quote.estimate_hint'), ENT_QUOTES, 'UTF-8') ?></div>
                      <div class="fw-bold" data-quote-estimate><?= htmlspecialchars(t('public.quote.total_prefix'), ENT_QUOTES, 'UTF-8') ?> —</div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <hr class="my-4">

              <div class="mt-0">
                <label class="form-label"><?= htmlspecialchars(t('public.quote.more_details'), ENT_QUOTES, 'UTF-8') ?></label>
                <textarea class="form-control" name="message" rows="7" placeholder="<?= htmlspecialchars(t('public.quote.more_details_ph'), ENT_QUOTES, 'UTF-8') ?>"></textarea>
                <div class="form-text"><?= htmlspecialchars(t('public.quote.more_details_hint'), ENT_QUOTES, 'UTF-8') ?></div>
              </div>
              <div class="d-flex gap-2 flex-wrap mt-4">
                <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i><?= htmlspecialchars(t('public.quote.send'), ENT_QUOTES, 'UTF-8') ?></button>
                <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-images me-2"></i><?= htmlspecialchars(t('public.quote.see_projects'), ENT_QUOTES, 'UTF-8') ?></a>
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
                <div class="fw-semibold"><i class="bi bi-receipt-cutoff me-2 text-brand"></i><?= htmlspecialchars(t('public.quote.summary'), ENT_QUOTES, 'UTF-8') ?></div>
                <span class="badge text-bg-light border" data-quote-count>0 <?= htmlspecialchars(t('public.quote.services_count_one'), ENT_QUOTES, 'UTF-8') ?></span>
              </div>
              <div class="text-secondary small mb-3"><?= htmlspecialchars(t('public.quote.summary_sub'), ENT_QUOTES, 'UTF-8') ?></div>

              <div class="d-flex align-items-center justify-content-between border rounded-4 p-3 mb-3" style="background:rgba(255,255,255,.7)">
                <div class="text-secondary small"><?= htmlspecialchars(t('public.quote.total_estimate'), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="h5 mb-0" data-quote-estimate-side>—</div>
              </div>

              <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.quote.ideal_title'), ENT_QUOTES, 'UTF-8') ?></div>
              <ul class="text-secondary small mb-3">
                <li><?= htmlspecialchars(t('public.quote.ideal_li1'), ENT_QUOTES, 'UTF-8') ?></li>
                <li><?= htmlspecialchars(t('public.quote.ideal_li2'), ENT_QUOTES, 'UTF-8') ?></li>
                <li><?= htmlspecialchars(t('public.quote.ideal_li3'), ENT_QUOTES, 'UTF-8') ?></li>
              </ul>

              <div class="border-top pt-3">
                <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.quote.help_title'), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="text-secondary small mb-2"><?= htmlspecialchars(t('public.quote.help_contact', ['company' => $companyName]), ENT_QUOTES, 'UTF-8') ?></div>
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
      var totalPrefix = <?= json_encode(t('public.quote.total_prefix'), JSON_UNESCAPED_UNICODE) ?>;
      if (estimateEl) estimateEl.textContent = hasAny ? (totalPrefix + ' ' + formatAr(total)) : (totalPrefix + ' —');
      if (estimateSide) estimateSide.textContent = hasAny ? formatAr(total) : '—';
      if (countEl) {
        var s1 = <?= json_encode(t('public.quote.services_count_one'), JSON_UNESCAPED_UNICODE) ?>;
        var sN = <?= json_encode(t('public.quote.services_count_many'), JSON_UNESCAPED_UNICODE) ?>;
        countEl.textContent = selectedCount + ' ' + (selectedCount > 1 ? sN : s1);
      }
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

