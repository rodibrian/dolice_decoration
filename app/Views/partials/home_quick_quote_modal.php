<?php
/** Modal « devis rapide » — hors du conteneur Locomotive (position fixed) */
?>
<div class="modal fade" id="quickQuoteModal" tabindex="-1" aria-labelledby="quickQuoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quickQuoteModalLabel"><?= htmlspecialchars(t('home.sections.quick_quote_title'), ENT_QUOTES, 'UTF-8') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= htmlspecialchars(t('modal.close'), ENT_QUOTES, 'UTF-8') ?>"></button>
      </div>
      <form method="post" action="<?= htmlspecialchars((env('APP_URL', '') ?: '') . '/devis', ENT_QUOTES, 'UTF-8') ?>">
        <div class="modal-body">
          <input type="text" name="company" value="" style="display:none">
          <div class="mb-3">
            <label class="form-label"><?= htmlspecialchars(t('home.sections.quick_quote_name'), ENT_QUOTES, 'UTF-8') ?></label>
            <input class="form-control" type="text" name="name" required>
          </div>
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label"><?= htmlspecialchars(t('public.forms.phone'), ENT_QUOTES, 'UTF-8') ?></label>
              <input class="form-control" type="text" name="phone">
            </div>
            <div class="col-sm-6">
              <label class="form-label"><?= htmlspecialchars(t('public.forms.email'), ENT_QUOTES, 'UTF-8') ?></label>
              <input class="form-control" type="email" name="email">
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label"><?= htmlspecialchars(t('home.sections.quick_quote_project_type'), ENT_QUOTES, 'UTF-8') ?></label>
            <input class="form-control" type="text" name="project_type">
          </div>
          <div class="mt-3">
            <label class="form-label"><?= htmlspecialchars(t('home.sections.quick_quote_message'), ENT_QUOTES, 'UTF-8') ?></label>
            <textarea class="form-control" name="message" rows="4"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= htmlspecialchars(t('home.sections.quick_quote_cancel'), ENT_QUOTES, 'UTF-8') ?></button>
          <button class="btn btn-brand" type="submit"><i class="bi bi-send me-2"></i><?= htmlspecialchars(t('home.sections.quick_quote_send'), ENT_QUOTES, 'UTF-8') ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
