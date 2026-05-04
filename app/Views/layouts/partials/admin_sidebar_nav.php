<?php
declare(strict_types=1);

/** @var callable(string): string $isActive */
/** @var string $uri */
/** @var bool $isSuper */
/** @var bool $isDashboardHome */

$b = rtrim((string)(env('APP_URL', '') ?: ''), '/');

$tip = static function (string $text): string {
    if ($text === '') {
        return '';
    }

    return ' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '"';
};

$showPublic = \App\Core\Auth::can('services.view')
    || \App\Core\Auth::can('projects.view')
    || \App\Core\Auth::can('posts.view')
    || \App\Core\Auth::can('testimonials.view')
    || \App\Core\Auth::can('partners.view');
$showInbox = \App\Core\Auth::can('quotes.view') || \App\Core\Auth::can('messages.view');
$showConfig = \App\Core\Auth::can('settings.view')
    || \App\Core\Auth::can('notifications.view')
    || \App\Core\Auth::can('hero_slides.view');
?>
<nav class="admin-nav">
  <div class="admin-nav-title"><?= htmlspecialchars(t('admin.nav.public'), ENT_QUOTES, 'UTF-8') ?></div>
  <p class="admin-nav-blurb"><?= htmlspecialchars(t('admin.sidebar.blurb_overview'), ENT_QUOTES, 'UTF-8') ?></p>
  <a class="admin-nav-link<?= $isDashboardHome ? ' is-active' : '' ?>" href="<?= htmlspecialchars($b . '/admin', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_dashboard')) ?>>
    <i class="bi bi-speedometer2"></i><span><?= htmlspecialchars(t('admin.nav.dashboard'), ENT_QUOTES, 'UTF-8') ?></span>
  </a>

  <?php if ($showPublic): ?>
    <div class="admin-nav-title mt-3"><?= htmlspecialchars(t('admin.nav.content'), ENT_QUOTES, 'UTF-8') ?></div>
    <p class="admin-nav-blurb"><?= htmlspecialchars(t('admin.sidebar.blurb_public'), ENT_QUOTES, 'UTF-8') ?></p>
    <?php if (\App\Core\Auth::can('services.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/services') ?>" href="<?= htmlspecialchars($b . '/admin/services', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_services')) ?>>
        <i class="bi bi-tools"></i><span><?= htmlspecialchars(t('admin.nav.services'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('projects.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/projects') ?>" href="<?= htmlspecialchars($b . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_projects')) ?>>
        <i class="bi bi-building-gear"></i><span><?= htmlspecialchars(t('admin.nav.projects'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('posts.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/posts') ?>" href="<?= htmlspecialchars($b . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_blog')) ?>>
        <i class="bi bi-newspaper"></i><span><?= htmlspecialchars(t('admin.nav.blog'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('testimonials.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/testimonials') ?>" href="<?= htmlspecialchars($b . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_testimonials')) ?>>
        <i class="bi bi-chat-quote"></i><span><?= htmlspecialchars(t('admin.nav.testimonials'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('partners.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/partners') ?>" href="<?= htmlspecialchars($b . '/admin/partners', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_partners')) ?>>
        <i class="bi bi-people"></i><span><?= htmlspecialchars(t('admin.nav.partners'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($showInbox): ?>
    <div class="admin-nav-title mt-3"><?= htmlspecialchars(t('admin.nav.inbox'), ENT_QUOTES, 'UTF-8') ?></div>
    <p class="admin-nav-blurb"><?= htmlspecialchars(t('admin.sidebar.blurb_inbox'), ENT_QUOTES, 'UTF-8') ?></p>
    <?php if (\App\Core\Auth::can('quotes.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/quotes') ?>" href="<?= htmlspecialchars($b . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_quotes')) ?>>
        <i class="bi bi-file-earmark-text"></i><span><?= htmlspecialchars(t('admin.nav.quotes'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('messages.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/messages') ?>" href="<?= htmlspecialchars($b . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_messages')) ?>>
        <i class="bi bi-envelope"></i><span><?= htmlspecialchars(t('admin.nav.messages'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($showConfig): ?>
    <div class="admin-nav-title mt-3"><?= htmlspecialchars(t('admin.nav.config'), ENT_QUOTES, 'UTF-8') ?></div>
    <p class="admin-nav-blurb"><?= htmlspecialchars(t('admin.sidebar.blurb_config'), ENT_QUOTES, 'UTF-8') ?></p>
    <?php if (\App\Core\Auth::can('settings.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/home') ?>" href="<?= htmlspecialchars($b . '/admin/home', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_home')) ?>>
        <i class="bi bi-house-gear"></i><span><?= htmlspecialchars(t('admin.nav.home'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('hero_slides.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/hero-slides') ?>" href="<?= htmlspecialchars($b . '/admin/hero-slides', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_slides')) ?>>
        <i class="bi bi-images"></i><span><?= htmlspecialchars(t('admin.nav.slides'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('settings.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/company') ?>" href="<?= htmlspecialchars($b . '/admin/company', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_company')) ?>>
        <i class="bi bi-building-gear"></i><span><?= htmlspecialchars(t('admin.nav.company'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
      <a class="admin-nav-link <?= $isActive('/admin/settings') ?>" href="<?= htmlspecialchars($b . '/admin/settings', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_settings')) ?>>
        <i class="bi bi-gear"></i><span><?= htmlspecialchars(t('admin.nav.settings'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('notifications.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/notifications') ?>" href="<?= htmlspecialchars($b . '/admin/notifications', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_notifications')) ?>>
        <i class="bi bi-bell"></i><span><?= htmlspecialchars(t('admin.nav.notifications'), ENT_QUOTES, 'UTF-8') ?></span>
      </a>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($isSuper): ?>
    <div class="admin-nav-title mt-3"><?= htmlspecialchars(t('admin.nav.advanced'), ENT_QUOTES, 'UTF-8') ?></div>
    <p class="admin-nav-blurb"><?= htmlspecialchars(t('admin.sidebar.blurb_advanced'), ENT_QUOTES, 'UTF-8') ?></p>
    <a class="admin-nav-link <?= $isActive('/admin/users') ?>" href="<?= htmlspecialchars($b . '/admin/users', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_users')) ?>>
      <i class="bi bi-person-gear"></i><span><?= htmlspecialchars(t('admin.nav.users'), ENT_QUOTES, 'UTF-8') ?></span>
    </a>
    <a class="admin-nav-link <?= $isActive('/admin/roles') ?>" href="<?= htmlspecialchars($b . '/admin/roles', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_roles')) ?>>
      <i class="bi bi-sliders"></i><span><?= htmlspecialchars(t('admin.nav.roles'), ENT_QUOTES, 'UTF-8') ?></span>
    </a>
    <a class="admin-nav-link <?= $isActive('/admin/logs') ?>" href="<?= htmlspecialchars($b . '/admin/logs', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_logs')) ?>>
      <i class="bi bi-activity"></i><span><?= htmlspecialchars(t('admin.nav.logs'), ENT_QUOTES, 'UTF-8') ?></span>
    </a>
  <?php endif; ?>

  <div class="admin-nav-title mt-3"><?= htmlspecialchars(t('admin.nav.shortcuts'), ENT_QUOTES, 'UTF-8') ?></div>
  <a class="admin-nav-link" href="<?= htmlspecialchars($b . '/', ENT_QUOTES, 'UTF-8') ?>"<?= $tip(t('admin.sidebar.tip_view_site')) ?>>
    <i class="bi bi-globe2"></i><span><?= htmlspecialchars(t('admin.nav.view_site'), ENT_QUOTES, 'UTF-8') ?></span>
  </a>
</nav>
