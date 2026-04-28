<?php
/** @var array<string, mixed> $page */
/** @var array<string, string|null> $settings */
/** @var string|null $flash */

$key = (string)($page['page_key'] ?? '');
?>
<section class="card">
  <h1><?= htmlspecialchars((string)$page['title'], ENT_QUOTES, 'UTF-8') ?></h1>
  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <div class="muted" style="white-space:pre-wrap"><?= htmlspecialchars((string)($page['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
</section>

<?php if ($key === 'contact'): ?>
  <section class="card">
    <h2>Contact</h2>
    <p class="muted">
      <?php if (!empty($settings['phone'])): ?>Téléphone: <?= htmlspecialchars((string)$settings['phone'], ENT_QUOTES, 'UTF-8') ?><br><?php endif; ?>
      <?php if (!empty($settings['whatsapp'])): ?>WhatsApp: <?= htmlspecialchars((string)$settings['whatsapp'], ENT_QUOTES, 'UTF-8') ?><br><?php endif; ?>
      <?php if (!empty($settings['email'])): ?>Email: <?= htmlspecialchars((string)$settings['email'], ENT_QUOTES, 'UTF-8') ?><br><?php endif; ?>
    </p>

    <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">
      <input type="text" name="company" value="" style="display:none">
      <label>
        Nom
        <input type="text" name="name" required>
      </label>
      <div class="row gap">
        <label style="min-width:240px">
          Email
          <input type="email" name="email">
        </label>
        <label style="min-width:240px">
          Téléphone
          <input type="text" name="phone">
        </label>
      </div>
      <label>
        Sujet
        <input type="text" name="subject">
      </label>
      <label>
        Message
        <textarea name="message" rows="6" required></textarea>
      </label>
      <button class="btn primary" type="submit">Envoyer</button>
    </form>
  </section>
<?php endif; ?>

