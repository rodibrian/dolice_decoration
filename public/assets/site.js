/* Public site JS (CDN libs are loaded in layout).
   - AOS: on-scroll animations
   - PureCounter: animated stats
   - Glide: sliders for testimonials/portfolio when present */

(function () {
  function initAOS() {
    if (!window.AOS) return;
    window.AOS.init({
      once: true,
      duration: 650,
      easing: 'ease-out-cubic',
      offset: 60,
    });
  }

  function initCounters() {
    if (!window.PureCounter) return;
    // PureCounter auto-inits by default; this is just a safe hook.
    new window.PureCounter();
  }

  function initGlide(id, options) {
    if (!window.Glide) return;
    var el = document.querySelector(id);
    if (!el) return;
    // eslint-disable-next-line no-new
    new window.Glide(el, options).mount();
  }

  function initBootstrapUX() {
    // Smooth scroll for same-page anchors (Bootstrap doesn't do this).
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[href^=\"#\"]');
      if (!a) return;
      var target = document.querySelector(a.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  function initNavbarScroll() {
    var nav = document.querySelector('.navbar-blur');
    if (!nav) return;

    var maxScroll = 180;
    var ticking = false;

    function applyNavState() {
      var y = window.scrollY || window.pageYOffset || 0;
      var progress = Math.min(Math.max(y / maxScroll, 0), 1);
      nav.style.setProperty('--nav-progress', String(progress));
      nav.classList.toggle('is-scrolled', y > 8);
      ticking = false;
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(applyNavState);
    }

    applyNavState();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  function buildCarousel(images) {
    var id = 'projectModalCarousel';
    var indicators = images
      .map(function (_, i) {
        return (
          '<button type="button" data-bs-target="#' +
          id +
          '" data-bs-slide-to="' +
          i +
          '" ' +
          (i === 0 ? 'class="active" aria-current="true"' : '') +
          ' aria-label="Slide ' +
          (i + 1) +
          '"></button>'
        );
      })
      .join('');

    var inner = images
      .map(function (src, i) {
        return (
          '<div class="carousel-item ' +
          (i === 0 ? 'active' : '') +
          '">' +
          '<img class="d-block w-100" style="height:100%;object-fit:cover" src="' +
          src +
          '" alt="">' +
          '</div>'
        );
      })
      .join('');

    return (
      '<div id="' +
      id +
      '" class="carousel slide w-100 h-100" data-bs-ride="carousel">' +
      (images.length > 1 ? '<div class="carousel-indicators">' + indicators + '</div>' : '') +
      '<div class="carousel-inner w-100 h-100">' +
      inner +
      '</div>' +
      (images.length > 1
        ? '<button class="carousel-control-prev" type="button" data-bs-target="#' +
          id +
          '" data-bs-slide="prev">' +
          '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
          '<span class="visually-hidden">Précédent</span>' +
          '</button>' +
          '<button class="carousel-control-next" type="button" data-bs-target="#' +
          id +
          '" data-bs-slide="next">' +
          '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
          '<span class="visually-hidden">Suivant</span>' +
          '</button>'
        : '') +
      '</div>'
    );
  }

  function initProjectModal() {
    var modalEl = document.getElementById('projectDetailsModal');
    if (!modalEl || !window.bootstrap) return;
    var modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);

    var titleEl = modalEl.querySelector('[data-project-modal-title]');
    var metaEl = modalEl.querySelector('[data-project-modal-meta]');
    var descEl = modalEl.querySelector('[data-project-modal-description]');
    var badgeCategoryEl = modalEl.querySelector('[data-project-modal-badge-category]');
    var badgeTypeEl = modalEl.querySelector('[data-project-modal-badge-type]');
    var badgeDateEl = modalEl.querySelector('[data-project-modal-badge-date]');
    var skeletonEl = modalEl.querySelector('[data-project-modal-skeleton]');
    var carouselWrap = modalEl.querySelector('[data-project-modal-carousel-wrap]');
    var wrap = modalEl.querySelector('#projectModalCarouselWrap');

    function setBadge(el, value, fallback) {
      if (!el) return;
      var v = (value || '').trim();
      if (v === '') {
        el.textContent = fallback || '';
        el.classList.add('d-none');
      } else {
        el.textContent = v;
        el.classList.remove('d-none');
      }
    }

    function reset() {
      if (titleEl) titleEl.textContent = '';
      if (metaEl) metaEl.textContent = '';
      if (descEl) descEl.textContent = '';
      setBadge(badgeCategoryEl, '', '');
      setBadge(badgeTypeEl, '', '');
      setBadge(badgeDateEl, '', '');
      if (wrap) wrap.innerHTML = '';
      if (skeletonEl) skeletonEl.classList.remove('d-none');
      if (carouselWrap) carouselWrap.classList.add('d-none');
    }

    async function openFromHref(href) {
      reset();
      modal.show();

      var url = new URL(href, window.location.origin);
      url.searchParams.set('modal', '1');

      try {
        var res = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        var data = await res.json();

        var project = data && data.project ? data.project : {};
        var images = (data && data.images) || [];

        if (titleEl) titleEl.textContent = project.title || 'Réalisation';
        if (metaEl) {
          var parts = [];
          if (project.location) parts.push(project.location);
          metaEl.textContent = parts.join(' • ');
        }
        if (descEl) descEl.textContent = project.description || '';
        setBadge(badgeCategoryEl, project.category, '');
        setBadge(badgeTypeEl, project.work_type, '');
        setBadge(badgeDateEl, project.project_date, '');

        if (images.length > 0 && wrap) {
          wrap.innerHTML = buildCarousel(images);
          if (skeletonEl) skeletonEl.classList.add('d-none');
          if (carouselWrap) carouselWrap.classList.remove('d-none');
        } else {
          // Keep skeleton if no images
          if (skeletonEl) skeletonEl.classList.remove('d-none');
          if (carouselWrap) carouselWrap.classList.add('d-none');
        }
      } catch (err) {
        if (titleEl) titleEl.textContent = 'Erreur de chargement';
        if (descEl) descEl.textContent = "Impossible de charger les détails. Réessaye ou ouvre la page.";
      }
    }

    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[data-project-modal=\"1\"]');
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href) return;

      // Keep default open-in-new-tab behavior
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;

      e.preventDefault();
      openFromHref(href);
    });
  }

  window.addEventListener('DOMContentLoaded', function () {
    initAOS();
    initCounters();
    initBootstrapUX();
    initNavbarScroll();
    initProjectModal();

    initGlide('#glideTestimonials', {
      type: 'carousel',
      perView: 2,
      gap: 16,
      autoplay: 3500,
      hoverpause: true,
      breakpoints: {
        992: { perView: 1 },
      },
    });

    initGlide('#glidePortfolio', {
      type: 'carousel',
      perView: 3,
      gap: 16,
      autoplay: 3000,
      hoverpause: true,
      breakpoints: {
        1200: { perView: 2 },
        768: { perView: 1 },
      },
    });
  });
})();

