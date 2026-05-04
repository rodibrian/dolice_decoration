/* Public site JS (CDN libs are loaded in layout).
   - AOS: on-scroll animations
   - PureCounter: animated stats
   - Glide: sliders for testimonials/portfolio when present */

(function () {
  var SITE_I18N = {};
  try {
    var ij = document.getElementById('site-i18n');
    if (ij && ij.textContent) SITE_I18N = JSON.parse(ij.textContent);
  } catch (e) {
    SITE_I18N = {};
  }

  function i18nFill(template, params) {
    var s = String(template || '');
    if (!params) return s;
    Object.keys(params).forEach(function (k) {
      s = s.split(':' + k).join(params[k] != null ? String(params[k]) : '');
    });
    return s;
  }

  function getAppBase() {
    var meta = document.querySelector('meta[name="app-base"]');
    var base = (meta && meta.getAttribute('content')) || '';
    base = (base || '').trim();
    if (base !== '' && base.endsWith('/')) base = base.slice(0, -1);
    return base;
  }

  function resolveHref(href) {
    var h = (href || '').trim();
    if (h === '') return h;
    // Absolute URL
    if (/^https?:\/\//i.test(h)) return h;

    var base = getAppBase();
    if (base === '') return h; // fallback: keep as-is

    // Root-relative path (/services/x)
    if (h.startsWith('/')) return base + h;

    // Relative path (services/x)
    return base + '/' + h;
  }

  function buildFallbackUrl(fullUrl) {
    // When mod_rewrite is disabled, pretty URLs 404.
    // Fallback: {APP_BASE}/index.php?path=/route
    var base = getAppBase();
    if (base === '') return null;

    var basePath = '';
    try {
      basePath = new URL(base).pathname || '';
    } catch (e) {
      basePath = '';
    }

    var routePath = fullUrl.pathname || '/';
    // URL.pathname is percent-encoded; decode to avoid double-encoding when
    // putting it into a querystring (URLSearchParams will encode once).
    try {
      routePath = decodeURIComponent(routePath);
    } catch (e) {
      // keep as-is
    }
    if (basePath && routePath.toLowerCase().startsWith(basePath.toLowerCase())) {
      routePath = routePath.slice(basePath.length) || '/';
    }
    if (!routePath.startsWith('/')) routePath = '/' + routePath;

    var fb = new URL(base + '/index.php', window.location.href);
    fb.searchParams.set('path', routePath);
    // Keep any existing query params (including modal=1 for JSON endpoints).
    fullUrl.searchParams.forEach(function (v, k) {
      fb.searchParams.set(k, v);
    });
    return fb;
  }

  async function fetchJsonWithRewriteFallback(fullUrl) {
    var res = await fetch(fullUrl.toString(), { headers: { Accept: 'application/json' } });

    function looksLikeJson(r) {
      try {
        var ct = (r.headers.get('content-type') || '').toLowerCase();
        return ct.indexOf('application/json') !== -1;
      } catch (e) {
        return false;
      }
    }

    // If pretty URLs are not working (404) OR they work but drop query params (returns HTML),
    // fallback to index.php?path=/route while keeping the same search params (modal=1).
    if (res.status === 404 || (res.ok && !looksLikeJson(res))) {
      var fb = buildFallbackUrl(fullUrl);
      if (fb) {
        res = await fetch(fb.toString(), { headers: { Accept: 'application/json' } });
      }
    }

    return res;
  }

  async function readJsonOrThrow(res, urlForDebug) {
    var ct = '';
    try {
      ct = (res.headers.get('content-type') || '').toLowerCase();
    } catch (e) {
      ct = '';
    }
    if (ct.indexOf('application/json') === -1) {
      var text = '';
      try {
        text = await res.text();
      } catch (e2) {
        text = '';
      }
      var snippet = (text || '').trim().slice(0, 220);
      throw new Error(
        i18nFill(SITE_I18N.non_json || 'Non-JSON (:status) ct=:ct url=:url:snippet', {
          status: String(res.status || 0),
          ct: ct || 'n/a',
          url: String(urlForDebug || ''),
          snippet: snippet ? (SITE_I18N.snippet_prefix || '') + snippet : '',
        })
      );
    }
    return await res.json();
  }

  function safeText(el, value) {
    if (!el) return;
    el.textContent = value || '';
  }

  function setBadge(el, value) {
    if (!el) return;
    var v = (value || '').trim();
    if (v === '') {
      el.textContent = '';
      el.classList.add('d-none');
    } else {
      el.textContent = v;
      el.classList.remove('d-none');
    }
  }

  function resetMedia(skeletonEl, imgEl) {
    if (skeletonEl) skeletonEl.classList.remove('d-none');
    if (imgEl) {
      imgEl.classList.add('d-none');
      imgEl.removeAttribute('src');
    }
  }

  function showMedia(skeletonEl, imgEl, src) {
    if (!imgEl) return;
    if (skeletonEl) skeletonEl.classList.add('d-none');
    imgEl.src = src;
    imgEl.classList.remove('d-none');
  }

  function initAOS() {
    if (!window.AOS) return;
    window.AOS.init({
      once: true,
      duration: 650,
      easing: 'ease-out-cubic',
      offset: 60,
    });
  }

  function refreshAOS() {
    if (!window.AOS || typeof window.AOS.refreshHard !== 'function') return;
    try {
      window.AOS.refreshHard();
    } catch (e) {
      // ignore
    }
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
    var g = new window.Glide(el, options);
    g.on(['mount.after', 'run.after', 'resize'], function () {
      // Keep AOS in sync with dynamic heights (helps avoid "only after resize" issues)
      refreshAOS();
    });
    g.mount();
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

    // Enable Bootstrap tooltips
    if (window.bootstrap && typeof window.bootstrap.Tooltip === 'function') {
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        try {
          window.bootstrap.Tooltip.getOrCreateInstance(el, { container: 'body' });
        } catch (e) {
          // ignore
        }
      });
    }
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

  function escapeHtmlAttr(s) {
    return String(s || '')
      .replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;')
      .replace(/</g, '&lt;');
  }

  function buildCarousel(images, carouselId) {
    var id = carouselId || 'projectModalCarousel';
    var indicators = images
      .map(function (_, i) {
        return (
          '<button type="button" data-bs-target="#' +
          id +
          '" data-bs-slide-to="' +
          i +
          '" ' +
          (i === 0 ? 'class="active" aria-current="true"' : '') +
          ' aria-label="' +
          escapeHtmlAttr((SITE_I18N.slide_aria || 'Slide :n').replace(':n', String(i + 1))) +
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
          escapeHtmlAttr(src) +
          '" alt="">' +
          '</div>'
        );
      })
      .join('');

    return (
      '<div id="' +
      id +
      '" class="carousel slide w-100 h-100" data-bs-ride="carousel" data-bs-interval="4500" data-bs-wrap="true">' +
      (images.length > 1 ? '<div class="carousel-indicators">' + indicators + '</div>' : '') +
      '<div class="carousel-inner w-100 h-100">' +
      inner +
      '</div>' +
      (images.length > 1
        ? '<button class="carousel-control-prev" type="button" data-bs-target="#' +
          id +
          '" data-bs-slide="prev">' +
          '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
          '<span class="visually-hidden">' +
          escapeHtmlAttr(SITE_I18N.carousel_prev || 'Previous') +
          '</span>' +
          '</button>' +
          '<button class="carousel-control-next" type="button" data-bs-target="#' +
          id +
          '" data-bs-slide="next">' +
          '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
          '<span class="visually-hidden">' +
          escapeHtmlAttr(SITE_I18N.carousel_next || 'Next') +
          '</span>' +
          '</button>'
        : '') +
        '</div>'
    );
  }

  function disposeBootstrapCarouselIn(container) {
    if (!container) return;
    var c = container.querySelector('.carousel');
    if (c && window.bootstrap && window.bootstrap.Carousel) {
      var inst = window.bootstrap.Carousel.getInstance(c);
      if (inst) {
        try {
          inst.dispose();
        } catch (e) {
          // ignore
        }
      }
    }
    container.innerHTML = '';
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
      if (wrap) disposeBootstrapCarouselIn(wrap);
      if (skeletonEl) skeletonEl.classList.remove('d-none');
      if (carouselWrap) carouselWrap.classList.add('d-none');
    }

    async function openFromHref(href) {
      reset();
      modal.show();

      var url = new URL(resolveHref(href), window.location.href);
      url.searchParams.set('modal', '1');

      try {
        var res = await fetchJsonWithRewriteFallback(url);
        if (!res.ok) throw new Error('HTTP ' + res.status + ' url=' + (res.url || url.toString()));
        var data = await readJsonOrThrow(res, url.toString());

        var project = data && data.project ? data.project : {};
        var images = (data && data.images) || [];

        if (titleEl) titleEl.textContent = project.title || SITE_I18N.modal_project || 'Project';
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
          wrap.innerHTML = buildCarousel(images, 'projectModalCarousel');
          if (skeletonEl) skeletonEl.classList.add('d-none');
          if (carouselWrap) carouselWrap.classList.remove('d-none');
          var carEl = wrap.querySelector('.carousel');
          if (carEl && images.length > 1 && window.bootstrap && window.bootstrap.Carousel) {
            window.bootstrap.Carousel.getOrCreateInstance(carEl, { interval: 4500, ride: 'carousel' });
          }
        } else {
          // Keep skeleton if no images
          if (skeletonEl) skeletonEl.classList.remove('d-none');
          if (carouselWrap) carouselWrap.classList.add('d-none');
        }
      } catch (err) {
        try {
          // eslint-disable-next-line no-console
          console.error('[project modal] load failed', { href: href, resolved: url.toString(), err: err });
        } catch (e) {
          // ignore
        }
        if (titleEl) titleEl.textContent = SITE_I18N.load_error_title || 'Error';
        if (descEl) {
          descEl.textContent = i18nFill(SITE_I18N.project_load_error || 'Load failed.\nDebug: :debug', {
            debug: String((err && err.message) || err || ''),
          });
        }
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

  function initServiceModal() {
    var modalEl = document.getElementById('serviceDetailsModal');
    if (!modalEl || !window.bootstrap) return;
    var modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);

    var titleEl = modalEl.querySelector('[data-service-modal-title]');
    var metaEl = modalEl.querySelector('[data-service-modal-meta]');
    var descEl = modalEl.querySelector('[data-service-modal-description]');
    var badgeCategoryEl = modalEl.querySelector('[data-service-modal-badge-category]');
    var badgePriceEl = modalEl.querySelector('[data-service-modal-badge-price]');
    var skeletonEl = modalEl.querySelector('[data-service-modal-skeleton]');
    var imgEl = modalEl.querySelector('[data-service-modal-image]');
    var openPageEl = modalEl.querySelector('[data-service-modal-open-page]');

    function reset() {
      safeText(titleEl, '');
      safeText(metaEl, '');
      safeText(descEl, '');
      setBadge(badgeCategoryEl, '');
      setBadge(badgePriceEl, '');
      resetMedia(skeletonEl, imgEl);
      if (openPageEl) openPageEl.classList.add('d-none');
    }

    async function openFromHref(href) {
      reset();
      modal.show();

      var url = new URL(resolveHref(href), window.location.href);
      url.searchParams.set('modal', '1');

      try {
        var res = await fetchJsonWithRewriteFallback(url);
        if (!res.ok) throw new Error('HTTP ' + res.status + ' url=' + (res.url || url.toString()));
        var data = await readJsonOrThrow(res, url.toString());
        var service = (data && data.service) || {};

        safeText(titleEl, service.title || SITE_I18N.modal_service || 'Service');
        safeText(metaEl, SITE_I18N.service_meta || '');
        setBadge(badgeCategoryEl, service.category || '');
        safeText(descEl, service.description || '');

        // Price badge (if enabled)
        var showPrice = Number(service.show_price || 0) === 1;
        var basePrice = service.base_price;
        var unit = (service.price_unit || '').trim();
        var label = (service.price_label || '').trim() || SITE_I18N.price_from || '';
        if (showPrice && basePrice !== null && basePrice !== '' && isFinite(Number(basePrice))) {
          var formatted = Math.round(Number(basePrice)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
          var sfx = SITE_I18N.price_suffix || '';
          setBadge(badgePriceEl, label + ' ' + formatted + sfx + (unit ? ' ' + unit : ''));
        } else {
          setBadge(badgePriceEl, '');
        }

        if (service.image) {
          showMedia(skeletonEl, imgEl, service.image);
        } else {
          resetMedia(skeletonEl, imgEl);
        }

        if (openPageEl) {
          openPageEl.href = href;
          openPageEl.classList.remove('d-none');
        }
      } catch (err) {
        try {
          // eslint-disable-next-line no-console
          console.error('[service modal] load failed', { href: href, resolved: url.toString(), err: err });
        } catch (e) {
          // ignore
        }
        safeText(titleEl, SITE_I18N.load_error_title || 'Error');
        safeText(
          descEl,
          i18nFill(SITE_I18N.service_load_error || 'Load failed.\nDebug: :debug', {
            debug: String((err && err.message) || err || ''),
          })
        );
      }
    }

    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[data-service-modal=\"1\"]');
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href) return;

      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
      e.preventDefault();
      openFromHref(href);
    });
  }

  function initPostModal() {
    var modalEl = document.getElementById('postDetailsModal');
    if (!modalEl || !window.bootstrap) return;
    var modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);

    var titleEl = modalEl.querySelector('[data-post-modal-title]');
    var metaEl = modalEl.querySelector('[data-post-modal-meta]');
    var excerptEl = modalEl.querySelector('[data-post-modal-excerpt]');
    var contentEl = modalEl.querySelector('[data-post-modal-content]');
    var skeletonEl = modalEl.querySelector('[data-post-modal-skeleton]');
    var imgEl = modalEl.querySelector('[data-post-modal-image]');
    var carouselWrap = modalEl.querySelector('[data-post-modal-carousel-wrap]');
    var wrap = modalEl.querySelector('#postModalCarouselWrap');
    var openPageEl = modalEl.querySelector('[data-post-modal-open-page]');

    function reset() {
      safeText(titleEl, '');
      safeText(metaEl, '');
      safeText(excerptEl, '');
      safeText(contentEl, '');
      if (wrap) disposeBootstrapCarouselIn(wrap);
      if (carouselWrap) carouselWrap.classList.add('d-none');
      resetMedia(skeletonEl, imgEl);
      if (openPageEl) openPageEl.classList.add('d-none');
    }

    async function openFromHref(href) {
      reset();
      modal.show();

      var url = new URL(resolveHref(href), window.location.href);
      url.searchParams.set('modal', '1');

      try {
        var res = await fetchJsonWithRewriteFallback(url);
        if (!res.ok) throw new Error('HTTP ' + res.status + ' url=' + (res.url || url.toString()));
        var data = await readJsonOrThrow(res, url.toString());
        var post = (data && data.post) || {};

        safeText(titleEl, post.title || SITE_I18N.modal_post || 'Post');
        var metaParts = [];
        if (post.published_at) metaParts.push(post.published_at);
        if (post.author) metaParts.push(post.author);
        safeText(metaEl, metaParts.join(' • '));
        safeText(excerptEl, post.excerpt || '');

        // Keep it readable in a modal: excerpt + a slice of content if very long
        var content = post.content || '';
        if (content.length > 900) content = content.slice(0, 900) + '…';
        safeText(contentEl, content);

        var images = (data && data.images) || [];
        if (!Array.isArray(images)) images = [];
        if (images.length === 0 && post.image) images = [post.image];

        if (images.length > 1 && wrap) {
          wrap.innerHTML = buildCarousel(images, 'postModalCarousel');
          if (skeletonEl) skeletonEl.classList.add('d-none');
          if (carouselWrap) carouselWrap.classList.remove('d-none');
          if (imgEl) {
            imgEl.classList.add('d-none');
            imgEl.removeAttribute('src');
          }
          var carEl = wrap.querySelector('.carousel');
          if (carEl && window.bootstrap && window.bootstrap.Carousel) {
            window.bootstrap.Carousel.getOrCreateInstance(carEl, { interval: 4500, ride: 'carousel' });
          }
        } else if (images.length === 1) {
          if (wrap) disposeBootstrapCarouselIn(wrap);
          if (carouselWrap) carouselWrap.classList.add('d-none');
          showMedia(skeletonEl, imgEl, images[0]);
        } else {
          if (wrap) disposeBootstrapCarouselIn(wrap);
          if (carouselWrap) carouselWrap.classList.add('d-none');
          resetMedia(skeletonEl, imgEl);
        }

        if (openPageEl) {
          openPageEl.href = href;
          openPageEl.classList.remove('d-none');
        }
      } catch (err) {
        try {
          // eslint-disable-next-line no-console
          console.error('[post modal] load failed', { href: href, resolved: url.toString(), err: err });
        } catch (e) {
          // ignore
        }
        safeText(titleEl, SITE_I18N.load_error_title || 'Error');
        safeText(
          contentEl,
          i18nFill(SITE_I18N.post_load_error || 'Load failed.\nDebug: :debug', {
            debug: String((err && err.message) || err || ''),
          })
        );
        if (wrap) disposeBootstrapCarouselIn(wrap);
        if (carouselWrap) carouselWrap.classList.add('d-none');
        resetMedia(skeletonEl, imgEl);
      }
    }

    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[data-post-modal=\"1\"]');
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href) return;

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
    initServiceModal();
    initPostModal();

    initGlide('#glideTestimonials', {
      type: 'carousel',
      perView: 3,
      focusAt: 'center',
      gap: 18,
      autoplay: 3800,
      hoverpause: true,
      animationDuration: 650,
      rewind: true,
      breakpoints: {
        1200: { perView: 2 },
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

    // Images can change layout after DOMContentLoaded; refresh AOS after they load.
    var imgs = Array.from(document.querySelectorAll('img'));
    imgs.forEach(function (img) {
      if (img.complete) return;
      img.addEventListener('load', function () {
        refreshAOS();
      }, { once: true });
    });
  });

  // Final safety refresh after full page load (fonts/images)
  window.addEventListener('load', function () {
    refreshAOS();
    // One extra tick after layout settles
    setTimeout(refreshAOS, 120);
  });
})();

