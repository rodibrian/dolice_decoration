<?php
/** @var array<string, mixed> $page */
/** @var array<string, string|null> $settings */
/** @var string|null $flash */

$key = (string)($page['page_key'] ?? '');
$base = (string)(env('APP_URL', '') ?: '');
$companyName = \App\Models\Setting::get('company_name', 'Dolice Decoration') ?? 'Dolice Decoration';

$parseFaq = static function (string $raw): array {
    $content = trim($raw);
    if ($content === '') return [];

    $items = [];

    // Format: Q: ... \n A: ...
    if (preg_match_all('/(^|\n)\s*Q\s*:\s*(.+?)\s*\n\s*A\s*:\s*([\s\S]*?)(?=(\n\s*Q\s*:)|\z)/u', $content, $m, PREG_SET_ORDER)) {
        foreach ($m as $row) {
            $q = trim((string)($row[2] ?? ''));
            $a = trim((string)($row[3] ?? ''));
            if ($q !== '') $items[] = ['q' => $q, 'a' => $a];
        }
        return $items;
    }

    // Try headings if content looks like HTML
    if (strpos($content, '<h') !== false) {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
        $heads = $xpath->query('//h2|//h3');
        if ($heads && $heads->length > 0) {
            foreach ($heads as $h) {
                $q = trim((string)$h->textContent);
                if ($q === '') continue;
                $aParts = [];
                $n = $h->nextSibling;
                while ($n) {
                    if ($n->nodeType === XML_ELEMENT_NODE && in_array(strtolower((string)$n->nodeName), ['h2', 'h3'], true)) {
                        break;
                    }
                    if ($n->nodeType === XML_ELEMENT_NODE) {
                        $t = trim((string)$n->textContent);
                        if ($t !== '') $aParts[] = $t;
                    }
                    $n = $n->nextSibling;
                }
                $a = trim(implode("\n\n", $aParts));
                $items[] = ['q' => $q, 'a' => $a];
            }
            if (count($items) >= 2) return $items;
            $items = [];
        }
    }

    // Markdown-ish bullet list fallback: "- Question ?"
    $lines = preg_split('/\r\n|\r|\n/u', $content) ?: [];
    foreach ($lines as $line) {
        $line = trim((string)$line);
        if ($line === '') continue;
        if (preg_match('/^[-*•]\s+(.*)$/u', $line, $mm)) {
            $q = trim((string)($mm[1] ?? ''));
            if ($q !== '') $items[] = ['q' => $q, 'a' => ''];
        }
    }
    return $items;
};

$faqItems = ($key === 'faq') ? $parseFaq((string)($page['content'] ?? '')) : [];

$jsonList = static function (?string $raw): array {
    $s = trim((string)$raw);
    if ($s === '') return [];
    $decoded = json_decode($s, true);
    if (!is_array($decoded)) return [];
    $out = [];
    foreach ($decoded as $v) {
        $v = trim((string)$v);
        if ($v !== '') $out[] = $v;
    }
    return array_values(array_unique($out));
};

$phones = $jsonList($settings['company_phones_json'] ?? null);
$emails = $jsonList($settings['company_emails_json'] ?? null);
if (empty($phones) && !empty($settings['phone'])) $phones = [(string)$settings['phone']];
if (empty($emails) && !empty($settings['email'])) $emails = [(string)$settings['email']];

$socials = [
  ['k' => 'facebook', 'label' => t('public.social.facebook'), 'icon' => 'bi-facebook'],
  ['k' => 'instagram', 'label' => t('public.social.instagram'), 'icon' => 'bi-instagram'],
  ['k' => 'linkedin', 'label' => t('public.social.linkedin'), 'icon' => 'bi-linkedin'],
  ['k' => 'twitter', 'label' => t('public.social.twitter'), 'icon' => 'bi-twitter-x'],
  ['k' => 'youtube', 'label' => t('public.social.youtube'), 'icon' => 'bi-youtube'],
  ['k' => 'tiktok', 'label' => t('public.social.tiktok'), 'icon' => 'bi-tiktok'],
];

$mapEmbed = trim((string)($settings['company_map_embed_url'] ?? ''));
$mapAddress = trim((string)($settings['company_map_address'] ?? ($settings['address'] ?? '')));
$lat = trim((string)($settings['company_map_lat'] ?? ''));
$lng = trim((string)($settings['company_map_lng'] ?? ''));
$mapFallbackUrl = '';
if ($lat !== '' && $lng !== '') {
  $mapFallbackUrl = 'https://www.google.com/maps?q=' . rawurlencode($lat . ',' . $lng) . '&output=embed';
} elseif ($mapAddress !== '') {
  $mapFallbackUrl = 'https://www.google.com/maps?q=' . rawurlencode($mapAddress) . '&output=embed';
}
?>
<?php if ($key === 'faq'): ?>
  <header class="py-5 bg-soft faq-hero">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
          <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('public.common.breadcrumb_home'), ENT_QUOTES, 'UTF-8') ?></a></li>
          <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(t('nav.faq'), ENT_QUOTES, 'UTF-8') ?></li>
        </ol>
      </nav>

      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
            <i class="bi bi-patch-question-fill text-brand"></i>
            <span><?= htmlspecialchars(t('public.faq_page.hero_badge'), ENT_QUOTES, 'UTF-8') ?></span>
          </div>
          <h1 class="display-6 fw-bold mt-3 mb-2 section-title"><?= htmlspecialchars(t('public.faq_page.hero_title'), ENT_QUOTES, 'UTF-8') ?></h1>
          <p class="lead text-secondary mb-0"><?= htmlspecialchars(t('public.faq_page.hero_lead'), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.faq_page.search_title'), ENT_QUOTES, 'UTF-8') ?></div>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input class="form-control" type="search" placeholder="<?= htmlspecialchars(t('public.faq_page.search_ph'), ENT_QUOTES, 'UTF-8') ?>" data-faq-search>
                <button class="btn btn-light border" type="button" data-faq-reset><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="text-secondary small mt-2"><?= htmlspecialchars(t('public.faq_page.search_hint'), ENT_QUOTES, 'UTF-8') ?></div>
              <div class="d-grid gap-2 mt-3">
                <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('nav.quote'), ENT_QUOTES, 'UTF-8') ?></a>
                <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/contact', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars(t('public.faq_page.cta_write'), ENT_QUOTES, 'UTF-8') ?></a>
              </div>
              <div class="text-secondary small mt-3"><?= htmlspecialchars(t('public.common.by_company', ['company' => $companyName]), ENT_QUOTES, 'UTF-8') ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
<?php else: ?>
  <?php if ($key === 'contact'): ?>
    <header class="py-5 bg-soft contact-hero">
      <div class="container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('public.common.breadcrumb_home'), ENT_QUOTES, 'UTF-8') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(t('nav.contact'), ENT_QUOTES, 'UTF-8') ?></li>
          </ol>
        </nav>

        <div class="row align-items-center g-4">
          <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
              <i class="bi bi-envelope-paper-fill text-brand"></i>
              <span><?= htmlspecialchars(t('public.contact_page.hero_badge'), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <h1 class="display-6 fw-bold mt-3 mb-2 section-title"><?= htmlspecialchars(t('public.contact_page.hero_title'), ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="lead text-secondary mb-0"><?= htmlspecialchars(t('public.contact_page.hero_lead'), ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.contact_page.direct_title'), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="text-secondary small mb-3"><?= htmlspecialchars(t('public.contact_page.direct_hint'), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="d-flex flex-column gap-2">
                  <?php foreach (array_slice($phones, 0, 2) as $ph): ?>
                    <a class="btn btn-sm btn-light border text-start" href="tel:<?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-telephone me-2 text-brand"></i><?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endforeach; ?>
                  <?php if (!empty($settings['whatsapp'])): ?>
                    <a class="btn btn-sm btn-light border text-start" target="_blank" rel="noopener" href="<?= htmlspecialchars('https://wa.me/' . preg_replace('/\D+/', '', (string)$settings['whatsapp']), ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-whatsapp me-2 text-brand"></i><?= htmlspecialchars(t('public.social.whatsapp'), ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endif; ?>
                  <?php foreach (array_slice($emails, 0, 2) as $em): ?>
                    <a class="btn btn-sm btn-light border text-start" href="mailto:<?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2 text-brand"></i><?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endforeach; ?>
                </div>
                <div class="d-grid gap-2 mt-3">
                  <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('nav.quote'), ENT_QUOTES, 'UTF-8') ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
  <?php else: ?>
    <div class="page-header py-4">
      <div class="container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('public.common.breadcrumb_home'), ENT_QUOTES, 'UTF-8') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars((string)$page['title'], ENT_QUOTES, 'UTF-8') ?></li>
          </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
          <div>
            <h1 class="display-6 fw-bold mb-1 section-title"><?= htmlspecialchars((string)$page['title'], ENT_QUOTES, 'UTF-8') ?></h1>
            <div class="text-secondary"><?= htmlspecialchars(t('public.generic_page.subtitle'), ENT_QUOTES, 'UTF-8') ?></div>
          </div>
          <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('public.generic_page.cta_quote'), ENT_QUOTES, 'UTF-8') ?>
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>

<section class="py-5">
  <div class="container">
    <?php if (!empty($flash)): ?>
      <div class="alert alert-primary"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-lg-7" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4 p-lg-5">
            <?php if ($key === 'faq'): ?>
              <?php if (!empty($faqItems)): ?>
                <div class="accordion faq-accordion" id="faqAccordion">
                  <?php foreach ($faqItems as $i => $it): ?>
                    <?php
                      $q = (string)($it['q'] ?? '');
                      $a = (string)($it['a'] ?? '');
                      $collapseId = 'faqCollapse' . (int)$i;
                      $headingId = 'faqHeading' . (int)$i;
                      $hay = strtolower($q . ' ' . $a);
                    ?>
                    <div class="accordion-item" data-faq-item data-search="<?= htmlspecialchars($hay, ENT_QUOTES, 'UTF-8') ?>">
                      <h2 class="accordion-header" id="<?= htmlspecialchars($headingId, ENT_QUOTES, 'UTF-8') ?>">
                        <button class="accordion-button <?= $i === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>">
                          <?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>
                        </button>
                      </h2>
                      <div id="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" aria-labelledby="<?= htmlspecialchars($headingId, ENT_QUOTES, 'UTF-8') ?>" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary" style="white-space:pre-wrap">
                          <?php if (trim($a) !== ''): ?>
                            <?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?>
                          <?php else: ?>
                            <?= htmlspecialchars(t('public.faq.empty_answer'), ENT_QUOTES, 'UTF-8') ?>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="text-secondary small mt-3" data-faq-empty style="display:none">
                  <?= htmlspecialchars(t('public.faq.no_results'), ENT_QUOTES, 'UTF-8') ?>
                </div>
              <?php else: ?>
                <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)($page['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            <?php else: ?>
              <?php if ($key === 'contact'): ?>
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                  <div>
                    <div class="fw-semibold"><?= htmlspecialchars(t('public.contact.send_title'), ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="text-secondary small"><?= htmlspecialchars(t('public.contact.send_sub'), ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                  <span class="badge text-bg-light border"><i class="bi bi-shield-lock me-1"></i><?= htmlspecialchars(t('public.quote.protected'), ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <form method="post" action="<?= htmlspecialchars($base . '/contact', ENT_QUOTES, 'UTF-8') ?>">
                  <input type="text" name="company" value="" style="display:none">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label"><?= htmlspecialchars(t('public.forms.name'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="name" required placeholder="<?= htmlspecialchars(t('public.forms.full_name_ph'), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label"><?= htmlspecialchars(t('public.forms.subject'), ENT_QUOTES, 'UTF-8') ?></label>
                      <input class="form-control" type="text" name="subject" placeholder="<?= htmlspecialchars(t('public.forms.subject_ph'), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label"><?= htmlspecialchars(t('public.forms.email'), ENT_QUOTES, 'UTF-8') ?></label>
                      <input class="form-control" type="email" name="email" placeholder="<?= htmlspecialchars(t('public.forms.email_ph'), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label"><?= htmlspecialchars(t('public.forms.phone'), ENT_QUOTES, 'UTF-8') ?></label>
                      <input class="form-control" type="text" name="phone" placeholder="<?= htmlspecialchars(t('public.forms.phone_ph'), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-12">
                      <label class="form-label"><?= htmlspecialchars(t('public.forms.message'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                      <textarea class="form-control" name="message" rows="7" required placeholder="<?= htmlspecialchars(t('public.forms.message_ph'), ENT_QUOTES, 'UTF-8') ?>"></textarea>
                    </div>
                  </div>
                  <div class="d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i><?= htmlspecialchars(t('public.forms.send'), ENT_QUOTES, 'UTF-8') ?></button>
                    <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('public.contact.quote_cta'), ENT_QUOTES, 'UTF-8') ?></a>
                  </div>
                </form>
              <?php else: ?>
                <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)($page['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
        <div class="sticky-lg-top" style="top:92px">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="fw-semibold mb-2"><i class="bi bi-headset me-2 text-brand"></i><?= htmlspecialchars(t('public.contact.help_title'), ENT_QUOTES, 'UTF-8') ?></div>
              <div class="text-secondary small mb-3"><?= htmlspecialchars(t('public.contact.help_sub'), ENT_QUOTES, 'UTF-8') ?></div>

              <div class="d-flex flex-column gap-2">
                <?php foreach (array_slice($phones, 0, 3) as $ph): ?>
                  <a class="btn btn-sm btn-light border text-start" href="tel:<?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-telephone me-2 text-brand"></i><?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?></a>
                <?php endforeach; ?>
                <?php if (!empty($settings['whatsapp'])): ?>
                  <a class="btn btn-sm btn-light border text-start" target="_blank" rel="noopener" href="<?= htmlspecialchars('https://wa.me/' . preg_replace('/\D+/', '', (string)$settings['whatsapp']), ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-whatsapp me-2 text-brand"></i><?= htmlspecialchars(t('public.common.whatsapp_with', ['value' => (string)$settings['whatsapp']]), ENT_QUOTES, 'UTF-8') ?></a>
                <?php endif; ?>
                <?php foreach (array_slice($emails, 0, 3) as $em): ?>
                  <a class="btn btn-sm btn-light border text-start" href="mailto:<?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2 text-brand"></i><?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?></a>
                <?php endforeach; ?>
              </div>

              <hr class="my-3">
              <div class="d-grid gap-2">
                <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('nav.quote'), ENT_QUOTES, 'UTF-8') ?></a>
                <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/services', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-tools me-2"></i><?= htmlspecialchars(t('public.contact.see_services'), ENT_QUOTES, 'UTF-8') ?></a>
              </div>
              <?php if (!empty($settings['service_area'])): ?>
                <div class="text-secondary small mt-3"><i class="bi bi-map me-2"></i><?= htmlspecialchars((string)$settings['service_area'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>

              <?php if ($key === 'contact'): ?>
                <?php
                  $hasSocial = false;
                  foreach ($socials as $s) {
                    if (!empty($settings[$s['k']] ?? null)) { $hasSocial = true; break; }
                  }
                ?>
                <?php if ($hasSocial): ?>
                  <hr class="my-3">
                  <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.contact.socials'), ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($socials as $s): ?>
                      <?php $url = trim((string)($settings[$s['k']] ?? '')); ?>
                      <?php if ($url !== ''): ?>
                        <a class="btn btn-sm btn-light border" target="_blank" rel="noopener" href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
                          <i class="bi <?= htmlspecialchars($s['icon'], ENT_QUOTES, 'UTF-8') ?> me-1 text-brand"></i><?= htmlspecialchars($s['label'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($key === 'contact' && ($mapEmbed !== '' || $mapFallbackUrl !== '')): ?>
  <section class="pb-5">
    <div class="container" data-aos="fade-up">
      <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-4 p-lg-5">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
              <div class="fw-semibold"><i class="bi bi-geo-alt-fill me-2 text-brand"></i><?= htmlspecialchars(t('public.contact.map_title'), ENT_QUOTES, 'UTF-8') ?></div>
              <?php if ($mapAddress !== ''): ?>
                <div class="text-secondary small"><?= htmlspecialchars($mapAddress, ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            </div>
            <a class="btn btn-sm btn-light border" target="_blank" rel="noopener" href="<?= htmlspecialchars(($lat !== '' && $lng !== '') ? ('https://www.google.com/maps?q=' . rawurlencode($lat . ',' . $lng)) : ('https://www.google.com/maps?q=' . rawurlencode($mapAddress)), ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-box-arrow-up-right me-1"></i><?= htmlspecialchars(t('public.contact.map_open'), ENT_QUOTES, 'UTF-8') ?></a>
          </div>

          <div class="ratio ratio-16x9 rounded-4 overflow-hidden border">
            <iframe
              src="<?= htmlspecialchars($mapEmbed !== '' ? $mapEmbed : $mapFallbackUrl, ENT_QUOTES, 'UTF-8') ?>"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              style="border:0"
              allowfullscreen
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if ($key === 'faq' && !empty($faqItems)): ?>
  <script>
    (function () {
      var input = document.querySelector('[data-faq-search]');
      var reset = document.querySelector('[data-faq-reset]');
      var items = Array.from(document.querySelectorAll('[data-faq-item]'));
      var empty = document.querySelector('[data-faq-empty]');
      if (!input || items.length === 0) return;

      function apply() {
        var q = (input.value || '').toLowerCase().trim();
        var visible = 0;
        items.forEach(function (it) {
          var hay = (it.getAttribute('data-search') || '').toLowerCase();
          var ok = (q === '' || hay.indexOf(q) !== -1);
          it.style.display = ok ? '' : 'none';
          if (ok) visible++;
        });
        if (empty) empty.style.display = (visible === 0) ? '' : 'none';
      }
      input.addEventListener('input', apply);
      if (reset) reset.addEventListener('click', function () { input.value = ''; apply(); });
      apply();
    })();
  </script>
<?php endif; ?>

