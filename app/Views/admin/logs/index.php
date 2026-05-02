<?php
/** @var list<array<string, mixed>> $logs */
?>
<section class="card">
  <div class="row between">
    <h1 class="page-title"><span class="page-icon">📜</span>Journaux d’audit</h1>
  </div>
  <p class="page-subtitle">Historique des actions dans l’administration (CRUD, réglages, connexions). La colonne « Détails » résume les données enregistrées au moment de l’action.</p>

  <?php if (empty($logs)): ?>
    <div class="muted">Aucun log (ou table <code>audit_logs</code> absente).</div>
  <?php else: ?>
    <div data-table-filter>
      <div class="crud-toolbar">
        <input class="form-control" type="search" placeholder="Rechercher (action, email, entité, détails…)" data-filter-text>
      </div>

      <div class="table-wrap">
        <table class="table table-hover align-middle">
          <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Utilisateur</th>
            <th>Action</th>
            <th>Entité</th>
            <th>Détails</th>
            <th>IP</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($logs as $l): ?>
            <?php
              $metaRaw = (string)($l['meta_json'] ?? '');
              $metaArr = [];
              if ($metaRaw !== '') {
                  $decoded = json_decode($metaRaw, true);
                  $metaArr = is_array($decoded) ? $decoded : [];
              }
              $metaStr = $metaArr === [] ? '—' : json_encode($metaArr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
              if (strlen($metaStr) > 220) {
                  $metaStr = substr($metaStr, 0, 217) . '…';
              }
              $search = strtolower(
                  (string)($l['action'] ?? '') . ' ' .
                  (string)($l['user_email'] ?? '') . ' ' .
                  (string)($l['entity'] ?? '') . ' ' .
                  (string)($l['entity_id'] ?? '') . ' ' .
                  (string)($l['ip'] ?? '') . ' ' .
                  $metaStr
              );
            ?>
            <tr data-row data-search="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
              <td><?= (int)($l['id'] ?? 0) ?></td>
              <td class="muted text-nowrap small"><?= htmlspecialchars((string)($l['created_at'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
              <td class="small"><?= htmlspecialchars((string)($l['user_email'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
              <td><span class="badge text-bg-light border"><?= htmlspecialchars((string)($l['action'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></td>
              <td class="muted small"><?= htmlspecialchars((string)($l['entity'] ?? '—'), ENT_QUOTES, 'UTF-8') ?><?= !empty($l['entity_id']) ? (' <span class="text-secondary">#' . (int)$l['entity_id'] . '</span>') : '' ?></td>
              <td class="small font-monospace text-break" style="max-width:320px" title="<?= htmlspecialchars($metaRaw !== '' ? $metaRaw : '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($metaStr, ENT_QUOTES, 'UTF-8') ?></td>
              <td class="muted small"><?= htmlspecialchars((string)($l['ip'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</section>
