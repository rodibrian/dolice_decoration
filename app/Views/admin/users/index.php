<?php
/** @var list<array<string, mixed>> $users */
/** @var string|null $flash */
/** @var string|null $error */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">👤</span>Utilisateurs</h1>
    <a class="btn primary btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/users/create', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-plus-lg"></i>Nouveau</a>
  </div>

  <?php if (!empty($flash)): ?>
    <div class="alert"><?= htmlspecialchars((string)$flash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <div data-table-filter>
    <div class="crud-toolbar">
      <input class="form-control" type="search" placeholder="Rechercher un utilisateur..." data-filter-text>
      <select class="form-select" data-filter-status>
        <option value="">Tous les rôles</option>
        <option value="super_admin">Super admin</option>
        <option value="admin">Admin</option>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table table-hover align-middle">
        <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Créé</th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="6" class="muted">Aucun utilisateur.</td></tr>
        <?php else: ?>
          <?php foreach ($users as $u): ?>
            <?php $role = (string)($u['role'] ?? ''); ?>
            <tr data-row data-search="<?= htmlspecialchars(strtolower((string)($u['name'] ?? '') . ' ' . (string)($u['email'] ?? '') . ' ' . $role), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?>">
              <td><?= (int)$u['id'] ?></td>
              <td><?= htmlspecialchars((string)($u['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td class="muted"><?= htmlspecialchars((string)($u['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><span class="badge text-bg-<?= $role === 'super_admin' ? 'warning' : 'primary' ?>"><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></span></td>
              <td class="muted"><?= htmlspecialchars((string)($u['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
              <td class="actions">
                <a class="btn btn-sm" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/users/edit?id=' . (int)$u['id'], ENT_QUOTES, 'UTF-8') ?>">Éditer</a>
                <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/users/delete', ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <button class="btn btn-sm danger" type="submit">Supprimer</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

