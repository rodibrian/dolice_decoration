<?php /** @var array<string, mixed> $post */ ?>
<div class="page-header py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('public.common.breadcrumb_home'), ENT_QUOTES, 'UTF-8') ?></a></li>
        <li class="breadcrumb-item"><a href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/blog', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(t('nav.blog'), ENT_QUOTES, 'UTF-8') ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars((string)$post['title'], ENT_QUOTES, 'UTF-8') ?></li>
      </ol>
    </nav>
    <h1 class="display-6 fw-bold mb-2 section-title"><?= htmlspecialchars((string)$post['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <div class="text-secondary">
      <?php if (!empty($post['author'])): ?><i class="bi bi-person me-1"></i><?= htmlspecialchars((string)$post['author'], ENT_QUOTES, 'UTF-8') ?> • <?php endif; ?>
      <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars((string)($post['published_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
          <?php if (!empty($post['featured_image'])): ?>
            <img class="w-100" style="height:320px;object-fit:cover;border-top-left-radius:16px;border-top-right-radius:16px" src="<?= htmlspecialchars((env('APP_URL', '') ?: '') . (string)$post['featured_image'], ENT_QUOTES, 'UTF-8') ?>" alt="">
          <?php endif; ?>
          <div class="card-body p-4">
            <div class="text-secondary" style="white-space:pre-wrap"><?= htmlspecialchars((string)($post['content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
          </div>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="fw-semibold mb-2"><?= htmlspecialchars(t('public.blog_detail.aside_title'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="text-secondary mb-3"><?= htmlspecialchars(t('public.blog_detail.aside_text'), ENT_QUOTES, 'UTF-8') ?></div>
            <a class="btn btn-brand w-100" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-clipboard-check me-2"></i><?= htmlspecialchars(t('public.blog_detail.cta_quote'), ENT_QUOTES, 'UTF-8') ?></a>
            <a class="btn btn-outline-secondary w-100 mt-2" href="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/contact', ENT_QUOTES, 'UTF-8') ?>"><i class="bi bi-chat-square-dots me-2"></i><?= htmlspecialchars(t('nav.contact'), ENT_QUOTES, 'UTF-8') ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

