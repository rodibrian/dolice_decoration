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
  ['k' => 'facebook', 'label' => 'Facebook', 'icon' => 'bi-facebook'],
  ['k' => 'instagram', 'label' => 'Instagram', 'icon' => 'bi-instagram'],
  ['k' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'bi-linkedin'],
  ['k' => 'twitter', 'label' => 'X', 'icon' => 'bi-twitter-x'],
  ['k' => 'youtube', 'label' => 'YouTube', 'icon' => 'bi-youtube'],
  ['k' => 'tiktok', 'label' => 'TikTok', 'icon' => 'bi-tiktok'],
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
          <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
          <li class="breadcrumb-item active" aria-current="page">FAQ</li>
        </ol>
      </nav>

      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
            <i class="bi bi-patch-question-fill text-brand"></i>
            <span>Réponses rapides • Infos pratiques • Devis & délais</span>
          </div>
          <h1 class="display-6 fw-bold mt-3 mb-2 section-title">FAQ</h1>
          <p class="lead text-secondary mb-0">Retrouve ici les questions les plus fréquentes. Si tu ne trouves pas, contacte-nous ou demande un devis.</p>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="fw-semibold mb-2">Rechercher une réponse</div>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input class="form-control" type="search" placeholder="Ex: délais, prix, zone..." data-faq-search>
                <button class="btn btn-light border" type="button" data-faq-reset><i class="bi bi-x-lg"></i></button>
              </div>
              <div class="text-secondary small mt-2">Filtre instantané dans les questions.</div>
              <div class="d-grid gap-2 mt-3">
                <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Demander un devis</a>
                <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/contact', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2"></i>Nous écrire</a>
              </div>
              <div class="text-secondary small mt-3">Par <span class="fw-semibold"><?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?></span></div>
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
            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contact</li>
          </ol>
        </nav>

        <div class="row align-items-center g-4">
          <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-2 badge text-bg-light border rounded-pill px-3 py-2">
              <i class="bi bi-envelope-paper-fill text-brand"></i>
              <span>Réponse rapide • Devis • Rendez-vous</span>
            </div>
            <h1 class="display-6 fw-bold mt-3 mb-2 section-title">Contact</h1>
            <p class="lead text-secondary mb-0">Une question, un besoin, un projet ? Écris-nous. On te répond rapidement avec une solution claire.</p>
          </div>
          <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="fw-semibold mb-2">Contact direct</div>
                <div class="text-secondary small mb-3">Choisis le canal le plus simple pour toi.</div>
                <div class="d-flex flex-column gap-2">
                  <?php foreach (array_slice($phones, 0, 2) as $ph): ?>
                    <a class="btn btn-sm btn-light border text-start" href="tel:<?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-telephone me-2 text-brand"></i><?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endforeach; ?>
                  <?php if (!empty($settings['whatsapp'])): ?>
                    <a class="btn btn-sm btn-light border text-start" target="_blank" rel="noopener" href="<?= htmlspecialchars('https://wa.me/' . preg_replace('/\D+/', '', (string)$settings['whatsapp']), ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-whatsapp me-2 text-brand"></i>WhatsApp</a>
                  <?php endif; ?>
                  <?php foreach (array_slice($emails, 0, 2) as $em): ?>
                    <a class="btn btn-sm btn-light border text-start" href="mailto:<?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2 text-brand"></i><?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?></a>
                  <?php endforeach; ?>
                </div>
                <div class="d-grid gap-2 mt-3">
                  <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Demander un devis</a>
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
            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($base . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars((string)$page['title'], ENT_QUOTES, 'UTF-8') ?></li>
          </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">
          <div>
            <h1 class="display-6 fw-bold mb-1 section-title"><?= htmlspecialchars((string)$page['title'], ENT_QUOTES, 'UTF-8') ?></h1>
            <div class="text-secondary">Informations utiles et contact.</div>
          </div>
          <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-clipboard-check me-2"></i>Demander un devis
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
                            Pour une réponse précise à ton projet, envoie une demande de devis ou un message.
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="text-secondary small mt-3" data-faq-empty style="display:none">
                  Aucun résultat. Essaie un autre mot-clé ou contacte-nous.
                </div>
              <?php else: ?>
                <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)($page['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            <?php else: ?>
              <?php if ($key === 'contact'): ?>
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                  <div>
                    <div class="fw-semibold">Envoyer un message</div>
                    <div class="text-secondary small">Nous répondons rapidement (devis, infos, rendez-vous).</div>
                  </div>
                  <span class="badge text-bg-light border"><i class="bi bi-shield-lock me-1"></i>Données protégées</span>
                </div>

                <form method="post" action="<?= htmlspecialchars($base . '/contact', ENT_QUOTES, 'UTF-8') ?>">
                  <input type="text" name="company" value="" style="display:none">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Nom <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" name="name" required placeholder="Ex: Jean Rakoto">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Sujet</label>
                      <input class="form-control" type="text" name="subject" placeholder="Ex: Demande de devis / infos">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Email</label>
                      <input class="form-control" type="email" name="email" placeholder="vous@email.com">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Téléphone</label>
                      <input class="form-control" type="text" name="phone" placeholder="034 00 000 00">
                    </div>
                    <div class="col-12">
                      <label class="form-label">Message <span class="text-danger">*</span></label>
                      <textarea class="form-control" name="message" rows="7" required placeholder="Décris ta demande: surface, localisation, délai, style..."></textarea>
                    </div>
                  </div>
                  <div class="d-flex gap-2 flex-wrap mt-4">
                    <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i>Envoyer</button>
                    <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Plutôt un devis</a>
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
              <div class="fw-semibold mb-2"><i class="bi bi-headset me-2 text-brand"></i>Besoin d’aide ?</div>
              <div class="text-secondary small mb-3">Contacte-nous ou demande un devis, on répond rapidement.</div>

              <div class="d-flex flex-column gap-2">
                <?php foreach (array_slice($phones, 0, 3) as $ph): ?>
                  <a class="btn btn-sm btn-light border text-start" href="tel:<?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-telephone me-2 text-brand"></i><?= htmlspecialchars($ph, ENT_QUOTES, 'UTF-8') ?></a>
                <?php endforeach; ?>
                <?php if (!empty($settings['whatsapp'])): ?>
                  <a class="btn btn-sm btn-light border text-start" target="_blank" rel="noopener" href="<?= htmlspecialchars('https://wa.me/' . preg_replace('/\D+/', '', (string)$settings['whatsapp']), ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-whatsapp me-2 text-brand"></i>WhatsApp: <?= htmlspecialchars((string)$settings['whatsapp'], ENT_QUOTES, 'UTF-8') ?></a>
                <?php endif; ?>
                <?php foreach (array_slice($emails, 0, 3) as $em): ?>
                  <a class="btn btn-sm btn-light border text-start" href="mailto:<?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-envelope me-2 text-brand"></i><?= htmlspecialchars($em, ENT_QUOTES, 'UTF-8') ?></a>
                <?php endforeach; ?>
              </div>

              <hr class="my-3">
              <div class="d-grid gap-2">
                <a class="btn btn-brand" href="<?= htmlspecialchars($base . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i>Demander un devis</a>
                <a class="btn btn-light border" href="<?= htmlspecialchars($base . '/services', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-tools me-2"></i>Voir les services</a>
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
                  <div class="fw-semibold mb-2">Réseaux sociaux</div>
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
              <div class="fw-semibold"><i class="bi bi-geo-alt-fill me-2 text-brand"></i>Nous trouver</div>
              <?php if ($mapAddress !== ''): ?>
                <div class="text-secondary small"><?= htmlspecialchars($mapAddress, ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif; ?>
            </div>
            <a class="btn btn-sm btn-light border" target="_blank" rel="noopener" href="<?= htmlspecialchars(($lat !== '' && $lng !== '') ? ('https://www.google.com/maps?q=' . rawurlencode($lat . ',' . $lng)) : ('https://www.google.com/maps?q=' . rawurlencode($mapAddress)), ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-box-arrow-up-right me-1"></i>Ouvrir dans Maps</a>
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

