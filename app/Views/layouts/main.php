<?php
/** @var string $content */
/** @var string|null $title */
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'Dolice Decoration', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/assets/app.css', ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <a class="brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Dolice Decoration</a>
      <nav class="nav">
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Accueil</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/notre-histoire', ENT_QUOTES, 'UTF-8') ?>">Notre histoire</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/services', ENT_QUOTES, 'UTF-8') ?>">Services</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/realisations', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>">Blog</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/faq', ENT_QUOTES, 'UTF-8') ?>">FAQ</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>">Contact</a>
        <a class="btn primary" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">Devis</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/login', ENT_QUOTES, 'UTF-8') ?>">Admin</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <?= $content ?>
  </main>
</body>
</html>
