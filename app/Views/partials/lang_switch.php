<?php
declare(strict_types=1);

use App\Core\Locale;

$cur = Locale::current();
$flags = [
    'fr' => '🇫🇷',
    'en' => '🇬🇧',
    'mg' => '🇲🇬',
];
$wrapClass = trim('lang-switch ' . trim((string)($langSwitchWrapClass ?? '')));
?>
<div class="<?= htmlspecialchars($wrapClass, ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars(t('lang.switch'), ENT_QUOTES, 'UTF-8') ?>">
  <select
    class="form-select form-select-sm lang-switch-select"
    aria-label="<?= htmlspecialchars(t('lang.switch'), ENT_QUOTES, 'UTF-8') ?>"
    onchange="if (this.value) window.location.href=this.value"
  >
    <?php foreach (Locale::allowed() as $code): ?>
      <option value="<?= htmlspecialchars(Locale::switchHref($code), ENT_QUOTES, 'UTF-8') ?>"<?= $cur === $code ? ' selected' : '' ?>>
        <?= $flags[$code] ?? '' ?> <?= strtoupper($code) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>
