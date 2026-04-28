<?php
/** @var string $content */
/** @var string|null $title */
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/assets/app.css', ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <a class="brand" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin', ENT_QUOTES, 'UTF-8') ?>">Admin</a>
      <nav class="nav">
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/services', ENT_QUOTES, 'UTF-8') ?>">Services</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>">Réalisations</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>">Blog</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>">Témoignages</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>">Devis</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>">Messages</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/partners', ENT_QUOTES, 'UTF-8') ?>">Partenaires</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/pages', ENT_QUOTES, 'UTF-8') ?>">Pages</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/admin/settings', ENT_QUOTES, 'UTF-8') ?>">Paramètres</a>
        <a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>">Site</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <?= $content ?>
  </main>
</body>
</html>
