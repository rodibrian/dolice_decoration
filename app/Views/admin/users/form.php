<?php
/** @var array<string, mixed>|null $userRow */
/** @var string|null $error */

$isEdit = is_array($userRow) && isset($userRow['id']);
$action = $isEdit ? '/admin/users/update' : '/admin/users/store';
$role = (string)($userRow['role'] ?? 'admin');
?>

<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">👤</span><?= $isEdit ? 'Modifier' : 'Nouveau' ?> utilisateur</h1>
    <a class="btn btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/users', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-arrow-left"></i>Retour</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . $action, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$userRow['id'] ?>">
    <?php endif; ?>

    <div class="row gap">
      <label style="min-width:240px">
        Nom
        <input type="text" name="name" required value="<?= htmlspecialchars((string)($userRow['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label style="min-width:240px">
        Email
        <input type="email" name="email" required value="<?= htmlspecialchars((string)($userRow['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
      </label>
    </div>

    <div class="row gap">
      <label style="min-width:240px">
        Rôle
        <select name="role">
          <option value="super_admin" <?= $role === 'super_admin' ? 'selected' : '' ?>>super_admin</option>
          <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>admin</option>
        </select>
      </label>

      <label style="min-width:240px">
        <?= $isEdit ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe' ?>
        <input type="password" name="password" <?= $isEdit ? '' : 'required' ?> autocomplete="new-password">
      </label>
    </div>

    <button class="btn primary" type="submit"><i class="bi bi-check2"></i><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
  </form>
</section>

