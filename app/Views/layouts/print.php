<?php
/** @var string $content */
/** @var string|null $title */
?>
<!doctype html>
<html lang="<?= htmlspecialchars(\App\Core\Locale::current(), ENT_QUOTES, 'UTF-8') ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? t('meta.print_default_title'), ENT_QUOTES, 'UTF-8') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <style>
    :root{ --ink:#0f172a; --muted:#64748b; --border:#e5e7eb; --brand:#ff7a18; }
    body{ background:#f8fafc; color:var(--ink); }
    .sheet{
      max-width: 900px;
      margin: 24px auto;
      background:#fff;
      border:1px solid var(--border);
      border-radius:16px;
      box-shadow:0 20px 60px rgba(2,6,23,.10);
      overflow:hidden;
    }
    .sheet-header{
      padding:22px 22px;
      border-bottom:1px solid var(--border);
      background:linear-gradient(180deg, rgba(255,122,24,.10), rgba(59,130,246,.06));
    }
    .sheet-body{ padding:22px 22px; }
    .muted{ color:var(--muted); }
    .badge-soft{
      background:rgba(255,122,24,.12);
      border:1px solid rgba(255,122,24,.22);
      color:#8a3f00;
      font-weight:800;
    }
    .kpi{
      border:1px solid var(--border);
      border-radius:14px;
      padding:12px 12px;
      background:#fff;
    }
    .kpi .label{ font-size:.82rem; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); font-weight:800; }
    .kpi .val{ font-weight:900; margin-top:4px; }
    .table{ margin-bottom:0; }
    .table th{ font-size:.78rem; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); }
    .no-print{ display:flex; gap:10px; justify-content:flex-end; padding:14px 22px; background:#fff; border-top:1px solid var(--border); }
    @media print{
      body{ background:#fff; }
      .sheet{ box-shadow:none; border:0; border-radius:0; margin:0; max-width:none; }
      .no-print{ display:none !important; }
      a{ color:inherit; text-decoration:none; }
    }
  </style>
</head>
<body>
  <?= $content ?>
  <script>
    // optional: auto-focus for print dialog on query ?autoprint=1
    (function(){
      try{
        var u = new URL(window.location.href);
        if (u.searchParams.get('autoprint') === '1') window.print();
      }catch(e){}
    })();
  </script>
</body>
</html>

