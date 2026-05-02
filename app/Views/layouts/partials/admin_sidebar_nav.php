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
  <div class="admin-nav-title">Vue d’ensemble</div>
  <p class="admin-nav-blurb">Tableau de bord, chiffres clés et raccourcis utiles après connexion.</p>
  <a class="admin-nav-link<?= $isDashboardHome ? ' is-active' : '' ?>" href="<?= htmlspecialchars($b . '/admin', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Vue synthétique : nouveaux devis, messages et volumes de contenu publié.') ?>>
    <i class="bi bi-speedometer2"></i><span>Tableau de bord</span>
  </a>

  <?php if ($showPublic): ?>
    <div class="admin-nav-title mt-3">Contenu du site public</div>
    <p class="admin-nav-blurb">Pages visitées par vos clients : textes, images et ordre d’affichage (hors bandeau d’accueil, géré avec la page d’accueil).</p>
    <?php if (\App\Core\Auth::can('services.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/services') ?>" href="<?= htmlspecialchars($b . '/admin/services', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Offres, descriptions et tarifs affichés sur la page Services. Publiez ou mettez en brouillon selon l’avancement.') ?>>
        <i class="bi bi-tools"></i><span>Services</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('projects.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/projects') ?>" href="<?= htmlspecialchars($b . '/admin/projects', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Portfolio : chantiers, photos multiples, texte et visibilité sur la page Réalisations.') ?>>
        <i class="bi bi-building-gear"></i><span>Réalisations</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('posts.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/posts') ?>" href="<?= htmlspecialchars($b . '/admin/posts', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Articles du blog : titre, extrait, image à la une, date de publication et statut brouillon/publié.') ?>>
        <i class="bi bi-newspaper"></i><span>Blog</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('testimonials.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/testimonials') ?>" href="<?= htmlspecialchars($b . '/admin/testimonials', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Avis clients : modération avant affichage sur le site, texte et auteur.') ?>>
        <i class="bi bi-chat-quote"></i><span>Témoignages</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('partners.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/partners') ?>" href="<?= htmlspecialchars($b . '/admin/partners', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Logos et noms des partenaires (pied de page, section dédiée). Ordre d’affichage et publication.') ?>>
        <i class="bi bi-people"></i><span>Partenaires</span>
      </a>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($showInbox): ?>
    <div class="admin-nav-title mt-3">Demandes &amp; messages</div>
    <p class="admin-nav-blurb">Boîte de réception : à traiter régulièrement pour répondre aux prospects.</p>
    <?php if (\App\Core\Auth::can('quotes.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/quotes') ?>" href="<?= htmlspecialchars($b . '/admin/quotes', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Formulaires de devis reçus depuis le site. Ouvrez chaque fiche pour suivre le statut et imprimer / exporter si besoin.') ?>>
        <i class="bi bi-file-earmark-text"></i><span>Devis</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('messages.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/messages') ?>" href="<?= htmlspecialchars($b . '/admin/messages', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Messages envoyés depuis le formulaire Contact. Vérifiez l’adresse e-mail de réponse dans Paramètres si besoin.') ?>>
        <i class="bi bi-envelope"></i><span>Messages</span>
      </a>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($showConfig): ?>
    <div class="admin-nav-title mt-3">Identité &amp; réglages du site</div>
    <p class="admin-nav-blurb">Accueil (contenu + diaporama), fiche entreprise, e-mails automatiques et réglages globaux.</p>
    <?php if (\App\Core\Auth::can('settings.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/home') ?>" href="<?= htmlspecialchars($b . '/admin/home', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Textes et sections de la page d’accueil : titres, boutons et liens vers services ou réalisations.') ?>>
        <i class="bi bi-house-gear"></i><span>Page d’accueil</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('hero_slides.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/hero-slides') ?>" href="<?= htmlspecialchars($b . '/admin/hero-slides', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Diaporama du haut de page d’accueil : images, accroches et boutons. À coordonner avec les textes de « Page d’accueil ».') ?>>
        <i class="bi bi-images"></i><span>Slides d’accueil</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('settings.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/company') ?>" href="<?= htmlspecialchars($b . '/admin/company', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Coordonnées, slogan, logo et informations société utilisées dans le pied de page et les e-mails.') ?>>
        <i class="bi bi-building-gear"></i><span>Fiche entreprise</span>
      </a>
      <a class="admin-nav-link <?= $isActive('/admin/settings') ?>" href="<?= htmlspecialchars($b . '/admin/settings', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Coordonnées affichées, réseaux, cover globale, affichage de la barre de navigation et du pied de page.') ?>>
        <i class="bi bi-gear"></i><span>Paramètres généraux</span>
      </a>
    <?php endif; ?>
    <?php if (\App\Core\Auth::can('notifications.view')): ?>
      <a class="admin-nav-link <?= $isActive('/admin/notifications') ?>" href="<?= htmlspecialchars($b . '/admin/notifications', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Destinataires et contenus des e-mails automatiques (alertes admin, confirmations visiteur).') ?>>
        <i class="bi bi-bell"></i><span>Notifications e-mail</span>
      </a>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($isSuper): ?>
    <div class="admin-nav-title mt-3">Administration avancée</div>
    <p class="admin-nav-blurb">Réservé aux super-administrateurs : comptes, droits d’accès et journal des actions.</p>
    <a class="admin-nav-link <?= $isActive('/admin/users') ?>" href="<?= htmlspecialchars($b . '/admin/users', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Création de comptes, rôles attribués et désactivation des accès.') ?>>
      <i class="bi bi-person-gear"></i><span>Utilisateurs</span>
    </a>
    <a class="admin-nav-link <?= $isActive('/admin/roles') ?>" href="<?= htmlspecialchars($b . '/admin/roles', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Permissions par rôle : quelles sections du back-office chaque profil peut voir ou modifier.') ?>>
      <i class="bi bi-sliders"></i><span>Rôles &amp; autorisations</span>
    </a>
    <a class="admin-nav-link <?= $isActive('/admin/logs') ?>" href="<?= htmlspecialchars($b . '/admin/logs', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Journal des actions CRUD et connexions : audit, débogage et traçabilité.') ?>>
      <i class="bi bi-activity"></i><span>Journaux</span>
    </a>
  <?php endif; ?>

  <div class="admin-nav-title mt-3">Raccourcis</div>
  <a class="admin-nav-link" href="<?= htmlspecialchars($b . '/', ENT_QUOTES, 'UTF-8') ?>"<?= $tip('Ouvre le site public dans cet onglet pour vérifier vos modifications après publication.') ?>>
    <i class="bi bi-globe2"></i><span>Voir le site</span>
  </a>
</nav>
