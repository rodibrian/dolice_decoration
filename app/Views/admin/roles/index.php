<?php
/** @var list<string> $roles */
/** @var string $selectedRole */
/** @var array<string, array<string, string>> $catalog */
/** @var list<string> $currentCaps */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">🧩</span>Rôles & autorisations</h1>
    <a class="btn btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/users', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-person-gear"></i>Utilisateurs</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div class="row gap" style="margin-top:10px">
    <label style="min-width:260px">
      Rôle
      <select onchange="location.href='<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/roles?role=', ENT_QUOTES, 'UTF-8') ?>'+encodeURIComponent(this.value)">
        <?php foreach ($roles as $r): ?>
          <option value="<?= htmlspecialchars($r, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedRole === $r ? 'selected' : '' ?>><?= htmlspecialchars($r, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="muted" style="align-self:flex-end">
      Astuce: coche “view” pour afficher le menu, et “create/update/delete” pour autoriser le CRUD.
    </div>
  </div>

  <hr class="sep">

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/roles/update', ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="role" value="<?= htmlspecialchars($selectedRole, ENT_QUOTES, 'UTF-8') ?>">

    <?php foreach ($catalog as $group => $caps): ?>
      <div class="panel-card" style="margin-bottom:12px">
        <h3 style="margin:0 0 10px"><?= htmlspecialchars($group, ENT_QUOTES, 'UTF-8') ?></h3>
        <div class="row gap" style="flex-wrap:wrap;align-items:flex-start">
          <?php foreach ($caps as $cap => $label): ?>
            <?php
              $checked = in_array($cap, $currentCaps, true);
              // super_admin role should always keep admin.super
              if ($selectedRole === 'super_admin' && $cap === 'admin.super') {
                  $checked = true;
              }
            ?>
            <label class="check" style="min-width:320px">
              <input type="checkbox" name="caps[]" value="<?= htmlspecialchars($cap, ENT_QUOTES, 'UTF-8') ?>" <?= $checked ? 'checked' : '' ?> <?= ($selectedRole === 'super_admin' && $cap === 'admin.super') ? 'disabled' : '' ?>>
              <span><strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></strong> <span class="muted">— <?= htmlspecialchars($cap, ENT_QUOTES, 'UTF-8') ?></span></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <button class="btn primary" type="submit"><i class="bi bi-check2"></i>Enregistrer</button>
  </form>
</section>

