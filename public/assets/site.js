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

  window.addEventListener('DOMContentLoaded', function () {
    initAOS();
    initCounters();
    initBootstrapUX();
    initNavbarScroll();

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

