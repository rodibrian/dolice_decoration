<?php /** @var string|null $flash */ ?>
<section class="card">
  <h1>Demander un devis</h1>
  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
    <input type="text" name="company" value="" style="display:none">
    <label>
      Nom
      <input type="text" name="name" required>
    </label>
    <div class="row gap">
      <label style="min-width:240px">
        Téléphone
        <input type="text" name="phone">
      </label>
      <label style="min-width:240px">
        Email
        <input type="email" name="email">
      </label>
    </div>
    <label>
      Type de projet
      <input type="text" name="project_type">
    </label>
    <label>
      Message
      <textarea name="message" rows="7"></textarea>
    </label>
    <button class="btn primary" type="submit">Envoyer</button>
  </form>
</section>

