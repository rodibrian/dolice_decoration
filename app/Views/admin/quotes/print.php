<?php
/** @var array<string, mixed> $quote */
/** @var list<array<string, mixed>> $items */
/** @var array<string, string|null> $settings */

$companyName = (string)($settings['company_name'] ?? 'Dolice Decoration');
$companySlogan = (string)($settings['company_slogan'] ?? 'Finition & décoration de bâtiment.');
$address = (string)($settings['company_map_address'] ?? ($settings['address'] ?? ''));
$phonesJson = (string)($settings['company_phones_json'] ?? '');
$emailsJson = (string)($settings['company_emails_json'] ?? '');

$decodeList = static function (string $raw): array {
    $raw = trim($raw);
    if ($raw === '') return [];
    $d = json_decode($raw, true);
    if (!is_array($d)) return [];
    $out = [];
    foreach ($d as $v) {
        $v = trim((string)$v);
        if ($v !== '') $out[] = $v;
    }
    return array_values(array_unique($out));
};
$phones = $decodeList($phonesJson);
$emails = $decodeList($emailsJson);
if (empty($phones) && !empty($settings['phone'])) $phones = [(string)$settings['phone']];
if (empty($emails) && !empty($settings['email'])) $emails = [(string)$settings['email']];

$status = (string)($quote['status'] ?? 'new');
$statusLabel = [
  'new' => 'Nouveau',
  'in_progress' => 'En cours',
  'replied' => 'Répondu',
  'done' => 'Terminé',
  'archived' => 'Archivé',
][$status] ?? $status;

$subtotal = 0.0;
$hasPrices = false;
foreach ($items as $it) {
    $qty = (int)($it['qty'] ?? 1);
    $p = $it['unit_price'];
    if ($p === null || $p === '') continue;
    $hasPrices = true;
    $subtotal += ((float)$p) * max(1, $qty);
}

$fmtAr = static fn (float $v): string => number_format($v, 0, ',', ' ') . ' Ar';
?>

<div class="sheet">
  <div class="sheet-header">
    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
      <div>
        <div class="h4 mb-1 fw-bold"><?= htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="muted"><?= htmlspecialchars($companySlogan, ENT_QUOTES, 'UTF-8') ?></div>
        <?php if ($address !== ''): ?>
          <div class="muted mt-1"><?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
      </div>
      <div class="text-end">
        <div class="badge badge-soft rounded-pill px-3 py-2">Devis #<?= (int)$quote['id'] ?></div>
        <div class="muted mt-2">Statut: <b><?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?></b></div>
        <div class="muted">Reçu le: <?= htmlspecialchars((string)($quote['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
      </div>
    </div>
  </div>

  <div class="sheet-body">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="kpi">
          <div class="label">Client</div>
          <div class="val"><?= htmlspecialchars((string)($quote['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
          <div class="muted mt-2">Téléphone: <?= htmlspecialchars((string)($quote['phone'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
          <div class="muted">Email: <?= htmlspecialchars((string)($quote['email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
          <div class="muted">Type projet: <?= htmlspecialchars((string)($quote['project_type'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="kpi">
          <div class="label">Coordonnées entreprise</div>
          <?php if (!empty($phones)): ?>
            <div class="muted mt-2">Tél: <?= htmlspecialchars(implode(' / ', array_slice($phones, 0, 3)), ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>
          <?php if (!empty($emails)): ?>
            <div class="muted">Email: <?= htmlspecialchars(implode(' / ', array_slice($emails, 0, 2)), ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>
          <?php if (!empty($settings['hours'])): ?>
            <div class="muted">Horaires: <?= htmlspecialchars((string)$settings['hours'], ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>
          <?php if (!empty($settings['service_area'])): ?>
            <div class="muted">Zone: <?= htmlspecialchars((string)$settings['service_area'], ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <div class="kpi">
        <div class="label">Message du client</div>
        <div class="mt-2" style="white-space:pre-wrap"><?= htmlspecialchars((string)($quote['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
      </div>
    </div>

    <?php if (!empty($items)): ?>
      <div class="mt-4">
        <div class="kpi">
          <div class="label">Services demandés</div>
          <div class="table-responsive mt-2">
            <table class="table table-sm align-middle">
              <thead>
              <tr>
                <th>Service</th>
                <th class="text-end">Prix</th>
                <th>Unité</th>
                <th class="text-end">Qté</th>
                <th class="text-end">Total</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($items as $it): ?>
                <?php
                  $qty = (int)($it['qty'] ?? 1);
                  $qty = max(1, $qty);
                  $p = $it['unit_price'];
                  $unit = (string)($it['price_unit'] ?? '—');
                  $priceText = ($p === null || $p === '') ? '—' : $fmtAr((float)$p);
                  $lineTotal = ($p === null || $p === '') ? null : ((float)$p) * $qty;
                ?>
                <tr>
                  <td><?= htmlspecialchars((string)$it['service_title'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td class="text-end"><?= htmlspecialchars($priceText, ENT_QUOTES, 'UTF-8') ?></td>
                  <td class="muted"><?= htmlspecialchars($unit !== '' ? $unit : '—', ENT_QUOTES, 'UTF-8') ?></td>
                  <td class="text-end"><?= (int)$qty ?></td>
                  <td class="text-end"><?= $lineTotal === null ? '—' : htmlspecialchars($fmtAr((float)$lineTotal), ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end gap-3 mt-2">
            <div class="muted">Total estimatif:</div>
            <div class="fw-bold"><?= $hasPrices ? htmlspecialchars($fmtAr((float)$subtotal), ENT_QUOTES, 'UTF-8') : '—' ?></div>
          </div>
          <div class="muted mt-2" style="font-size:.9rem">
            Les prix affichés correspondent au “snapshot” enregistré lors de la demande.
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div class="no-print">
    <a class="btn btn-outline-secondary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes/show?id=' . (int)$quote['id'], ENT_QUOTES, 'UTF-8') ?>">Retour</a>
    <button class="btn btn-primary" onclick="window.print()">Imprimer / Enregistrer PDF</button>
  </div>
</div>

