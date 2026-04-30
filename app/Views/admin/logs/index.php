<?php
/** @var list<array<string, mixed>> $logs */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">📜</span>Logs</h1>
  </div>

  <?php if (empty($logs)): ?>
    <div class="muted">Aucun log (ou table `audit_logs` non installée).</div>
  <?php else: ?>
    <div data-table-filter>
      <div class="crud-toolbar">
        <input class="form-control" type="search" placeholder="Rechercher (action, email, entity...)" data-filter-text>
      </div>

      <div class="table-wrap">
        <table class="table table-hover align-middle">
          <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>User</th>
            <th>Action</th>
            <th>Entity</th>
            <th>IP</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($logs as $l): ?>
            <?php
              $search = strtolower(
                (string)($l['action'] ?? '') . ' ' .
                (string)($l['user_email'] ?? '') . ' ' .
                (string)($l['entity'] ?? '') . ' ' .
                (string)($l['entity_id'] ?? '') . ' ' .
                (string)($l['ip'] ?? '')
              );
            ?>
            <tr data-row data-search="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
              <td><?= (int)($l['id'] ?? 0) ?></td>
              <td class="muted"><?= htmlspecialchars((string)($l['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['user_email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
              <td><span class="badge text-bg-light border"><?= htmlspecialchars((string)($l['action'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></td>
              <td class="muted"><?= htmlspecialchars((string)($l['entity'] ?? '—'), ENT_QUOTES, 'UTF-8') ?><?= !empty($l['entity_id']) ? (' #' . (int)$l['entity_id']) : '' ?></td>
              <td class="muted"><?= htmlspecialchars((string)($l['ip'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</section>

