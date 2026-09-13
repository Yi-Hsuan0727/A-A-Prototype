/**
 * Ascenzi & Associates — front-end behavior.
 * Vanilla JS, no framework. Every module guards on the DOM it needs so this
 * one bundle is safe to load on every template.
 */
(function () {
  'use strict';

  var data = window.ascenziData || {};

  /* ---------------------------------------------------------------------
   * Sticky header: solid at top, frosted glass once scrolled.
   * ------------------------------------------------------------------- */
  (function stickyHeader() {
    var header = document.querySelector('[data-site-header]');
    if (!header) { return; }
    function update() {
      header.classList.toggle('is-scrolled', window.scrollY > 8);
    }
    update();
    window.addEventListener('scroll', update, { passive: true });
  })();

  /* ---------------------------------------------------------------------
   * Mobile menu toggle
   * ------------------------------------------------------------------- */
  (function mobileMenu() {
    var toggle = document.querySelector('[data-menu-toggle]');
    var header = document.querySelector('[data-site-header]');
    if (!toggle || !header) { return; }
    toggle.addEventListener('click', function () {
      var open = header.classList.toggle('menu-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  })();

  /* ---------------------------------------------------------------------
   * Services mega-dropdown: hover-open with a debounced close (the small
   * gap between trigger and panel must not fold the menu before the
   * pointer arrives), click-to-toggle for touch, Escape closes, per-row
   * hover swaps the preview photo + caption.
   * ------------------------------------------------------------------- */
  (function servicesDropdown() {
    var wrap = document.querySelector('[data-nav-services]');
    if (!wrap) { return; }
    var trigger = wrap.querySelector('[data-services-trigger]');
    var closeTimer = null;

    function open() { clearTimeout(closeTimer); wrap.classList.add('is-open'); trigger.setAttribute('aria-expanded', 'true'); }
    function scheduleClose() { clearTimeout(closeTimer); closeTimer = setTimeout(function () { wrap.classList.remove('is-open'); trigger.setAttribute('aria-expanded', 'false'); }, 150); }
    function toggle() { wrap.classList.contains('is-open') ? scheduleClose() : open(); }

    wrap.addEventListener('mouseenter', open);
    wrap.addEventListener('mouseleave', scheduleClose);
    trigger.addEventListener('click', function (e) { e.preventDefault(); toggle(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { wrap.classList.remove('is-open'); } });
    document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) { wrap.classList.remove('is-open'); } });

    var rows = wrap.querySelectorAll('[data-services-row]');
    var previewImgs = wrap.querySelectorAll('[data-preview-index]');
    var captionTitle = wrap.querySelector('[data-services-preview-title]');
    rows.forEach(function (row) {
      row.addEventListener('mouseenter', function () {
        var idx = row.getAttribute('data-preview-index');
        previewImgs.forEach(function (img) {
          img.classList.toggle('is-shown', img.getAttribute('data-preview-index') === idx && img.classList.contains('nav-services__preview-img'));
        });
        if (captionTitle) { captionTitle.textContent = row.querySelector('.nav-services__row-title').textContent; }
      });
    });
  })();

  /* ---------------------------------------------------------------------
   * Language switch: cookie + reload. Debounced close matches the Services
   * dropdown fix (the trigger-to-menu gap must not fold it prematurely).
   * ------------------------------------------------------------------- */
  (function langSwitch() {
    document.querySelectorAll('[data-nav-lang]').forEach(function (wrap) {
      var trigger = wrap.querySelector('[data-lang-trigger]');
      var closeTimer = null;

      function cancelClose() { clearTimeout(closeTimer); }
      function open() { cancelClose(); wrap.classList.add('is-open'); trigger.setAttribute('aria-expanded', 'true'); }
      function scheduleClose() { clearTimeout(closeTimer); closeTimer = setTimeout(function () { wrap.classList.remove('is-open'); trigger.setAttribute('aria-expanded', 'false'); }, 180); }

      trigger.addEventListener('click', function (e) {
        e.preventDefault();
        wrap.classList.contains('is-open') ? scheduleClose() : open();
      });
      wrap.addEventListener('mouseenter', cancelClose);
      wrap.addEventListener('mouseleave', scheduleClose);
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { wrap.classList.remove('is-open'); } });
      document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) { wrap.classList.remove('is-open'); } });

      wrap.querySelectorAll('[data-lang-option]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var lang = btn.getAttribute('data-lang-option');
          document.cookie = 'ascenzi_lang=' + lang + '; path=/; max-age=' + (60 * 60 * 24 * 365);
          window.location.reload();
        });
      });
    });
  })();

  /* ---------------------------------------------------------------------
   * Contact / partnership modal
   * ------------------------------------------------------------------- */
  (function contactModal() {
    var overlay = document.querySelector('[data-contact-overlay]');
    if (!overlay) { return; }
    var modal = overlay.querySelector('[data-contact-modal]');
    var progress = overlay.querySelector('[data-contact-progress] .contact-modal__progress-bar');
    var progressWrap = overlay.querySelector('[data-contact-progress]');
    var steps = {
      org: overlay.querySelector('[data-contact-step="org"]'),
      details: overlay.querySelector('[data-contact-step="details"]'),
      partner: overlay.querySelector('[data-contact-step="partner"]'),
    };
    var done = overlay.querySelector('[data-contact-done]');
    var nav = overlay.querySelector('[data-contact-nav]');
    var backBtn = overlay.querySelector('[data-contact-back]');
    var skipBtn = overlay.querySelector('[data-contact-skip]');
    var submitBtn = overlay.querySelector('[data-contact-submit]');
    var stepCountEl = overlay.querySelector('[data-contact-step-count]');
    var noteEl = overlay.querySelector('[data-contact-note]');
    var errorEls = overlay.querySelectorAll('[data-contact-error]');
    var kickerEl = overlay.querySelector('[data-contact-kicker]');

    var state = { mode: 'contact', step: 0, orgType: '' };

    function allSteps() { return [steps.org, steps.details, steps.partner, done]; }
    function hideAll() { allSteps().forEach(function (el) { el.classList.remove('is-active'); }); }

    var strings = data.strings || {};
    function stepOf(n) { return (strings.stepOf || 'Step {n} / 2').replace('{n}', n); }

    function render() {
      hideAll();
      errorEls.forEach(function (el) { el.classList.remove('is-active'); el.textContent = ''; });
      if (state.mode === 'partner') {
        steps.partner.classList.add('is-active');
        nav.classList.add('is-active');
        progressWrap.style.display = 'none';
        backBtn.style.visibility = 'hidden';
        skipBtn.style.display = 'none';
        submitBtn.style.display = '';
        noteEl.style.display = '';
        stepCountEl.textContent = '';
        if (kickerEl) { kickerEl.textContent = strings.partnershipKicker || 'Partnership'; }
      } else if (state.step === 0) {
        steps.org.classList.add('is-active');
        nav.classList.add('is-active');
        progressWrap.style.display = '';
        progress.style.width = '50%';
        backBtn.style.visibility = 'hidden';
        skipBtn.style.display = '';
        submitBtn.style.display = 'none';
        noteEl.style.display = 'none';
        stepCountEl.textContent = stepOf(1);
        if (kickerEl) { kickerEl.textContent = (strings.enquiryKicker || 'Enquiry') + ' — ' + stepOf(1); }
      } else {
        steps.details.classList.add('is-active');
        nav.classList.add('is-active');
        progressWrap.style.display = '';
        progress.style.width = '100%';
        backBtn.style.visibility = 'visible';
        skipBtn.style.display = 'none';
        submitBtn.style.display = '';
        noteEl.style.display = 'none';
        stepCountEl.textContent = stepOf(2);
        if (kickerEl) { kickerEl.textContent = (strings.enquiryKicker || 'Enquiry') + ' — ' + stepOf(2); }
      }
    }

    function open(mode) {
      state.mode = mode === 'partner' ? 'partner' : 'contact';
      state.step = 0;
      state.orgType = '';
      modal.setAttribute('data-mode', state.mode);
      overlay.querySelectorAll('.contact-modal__org-option').forEach(function (b) { b.classList.remove('is-selected'); });
      render();
      overlay.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    }
    function close() {
      overlay.classList.remove('is-open');
      document.body.style.overflow = '';
    }

    document.querySelectorAll('[data-open-contact]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        open(btn.getAttribute('data-open-contact'));
      });
    });
    overlay.querySelectorAll('[data-contact-close]').forEach(function (btn) { btn.addEventListener('click', close); });
    overlay.addEventListener('click', function (e) { if (e.target === overlay) { close(); } });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && overlay.classList.contains('is-open')) { close(); } });

    overlay.querySelectorAll('[data-org-option]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        state.orgType = btn.getAttribute('data-org-option');
        overlay.querySelectorAll('.contact-modal__org-option').forEach(function (b) { b.classList.remove('is-selected'); });
        btn.classList.add('is-selected');
        state.step = 1;
        render();
      });
    });
    skipBtn.addEventListener('click', function () { state.step = 1; render(); });
    backBtn.addEventListener('click', function () { state.step = 0; render(); });

    function currentFieldsStep() {
      return state.mode === 'partner' ? steps.partner : steps.details;
    }

    submitBtn.addEventListener('click', function () {
      var stepEl = currentFieldsStep();
      var errorEl = stepEl.querySelector('[data-contact-error]');
      var inputs = stepEl.querySelectorAll('input, textarea');
      var payload = { mode: state.mode, org_type: state.orgType };
      var valid = true;
      inputs.forEach(function (input) {
        if (input.hasAttribute('required') && !input.value.trim()) { valid = false; }
        payload[input.name] = input.value.trim();
      });
      if (!valid) {
        errorEl.textContent = (data.strings && data.strings.submitError) || 'Please fill in the required fields.';
        errorEl.classList.add('is-active');
        return;
      }

      submitBtn.disabled = true;
      var originalLabel = submitBtn.textContent;
      submitBtn.textContent = (data.strings && data.strings.submitting) || 'Sending…';

      var body = new URLSearchParams(Object.assign({ action: 'ascenzi_contact_submit', nonce: data.contactNonce }, payload));
      fetch(data.ajaxUrl, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString() })
        .then(function (r) { return r.json(); })
        .then(function (json) {
          submitBtn.disabled = false;
          submitBtn.textContent = originalLabel;
          if (json && json.success) {
            hideAll();
            done.classList.add('is-active');
            nav.classList.remove('is-active');
            if (kickerEl && state.mode !== 'partner') {
              kickerEl.textContent = (strings.enquiryKicker || 'Enquiry') + ' — ' + (strings.complete || 'Complete');
            }
          } else {
            var msg = (json && json.data && json.data.message) || (data.strings && data.strings.submitError) || 'Something went wrong.';
            errorEl.textContent = msg;
            errorEl.classList.add('is-active');
          }
        })
        .catch(function () {
          submitBtn.disabled = false;
          submitBtn.textContent = originalLabel;
          errorEl.textContent = (data.strings && data.strings.submitError) || 'Something went wrong.';
          errorEl.classList.add('is-active');
        });
    });
  })();

  /* ---------------------------------------------------------------------
   * Reveal-on-scroll (one-shot)
   * ------------------------------------------------------------------- */
  (function revealOnScroll() {
    var els = document.querySelectorAll('[data-reveal]');
    if (!els.length) { return; }
    if (!('IntersectionObserver' in window)) {
      els.forEach(function (el) { el.classList.add('is-revealed'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var delay = parseInt(entry.target.getAttribute('data-reveal-delay') || '0', 10);
          setTimeout(function () { entry.target.classList.add('is-revealed'); }, delay);
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    els.forEach(function (el) { io.observe(el); });
    // Fallback in case something never intersects (e.g. already-tiny viewport).
    setTimeout(function () { els.forEach(function (el) { el.classList.add('is-revealed'); }); }, 3000);
  })();

  /* ---------------------------------------------------------------------
   * Typewriter tagline
   * ------------------------------------------------------------------- */
  (function typewriter() {
    var live = document.querySelector('[data-tagline-live]');
    if (!live) { return; }
    var a = live.getAttribute('data-a') || '';
    var b = live.getAttribute('data-b') || '';
    var full = a + ' ' + b;
    var partA = live.querySelector('[data-tagline-a]');
    var partB = live.querySelector('[data-tagline-b]');
    var cursor = live.querySelector('[data-tagline-cursor]');
    var started = false;

    function type(i) {
      var shown = full.slice(0, i);
      if (shown.length <= a.length) {
        partA.textContent = shown;
        partB.textContent = '';
      } else {
        partA.textContent = a;
        partB.textContent = shown.slice(a.length + 1);
      }
      if (i >= full.length) {
        cursor.classList.add('is-done');
        return;
      }
      setTimeout(function () { type(i + 1); }, 28);
    }

    function start() {
      if (started) { return; }
      started = true;
      type(0);
    }

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) { if (entry.isIntersecting) { start(); io.disconnect(); } });
      }, { threshold: 0.5 });
      io.observe(live);
    } else {
      start();
    }
  })();

  /* ---------------------------------------------------------------------
   * Hover-reveal card groups: "What We Do" cards and package / other-
   * services cards. First card in each group starts open; only one open
   * at a time per group.
   * ------------------------------------------------------------------- */
  (function hoverCards() {
    function setup(containerSelector, cardSelector) {
      document.querySelectorAll(containerSelector).forEach(function (container) {
        var cards = container.querySelectorAll(cardSelector);
        if (!cards.length) { return; }
        cards[0].classList.add('is-open');
        cards.forEach(function (card) {
          card.addEventListener('mouseenter', function () {
            cards.forEach(function (c) { c.classList.remove('is-open'); });
            card.classList.add('is-open');
          });
          card.addEventListener('focus', function () {
            cards.forEach(function (c) { c.classList.remove('is-open'); });
            card.classList.add('is-open');
          });
        });
        container.addEventListener('mouseleave', function () {
          cards.forEach(function (c) { c.classList.remove('is-open'); });
          cards[0].classList.add('is-open');
        });
      });
    }
    setup('.purpose__grid', '.purpose-card');
    setup('.capabilities__row, .pkg-cards', '.pkg-card');
    setup('.other-grid', '.pkg-card');
  })();

  /* ---------------------------------------------------------------------
   * FAQ accordion
   * ------------------------------------------------------------------- */
  (function faq() {
    document.querySelectorAll('.faq-item').forEach(function (item) {
      var q = item.querySelector('.faq-item__q');
      var sign = item.querySelector('.faq-item__sign');
      if (!q) { return; }
      q.addEventListener('click', function () {
        var willOpen = !item.classList.contains('is-open');
        item.closest('.faq-list').querySelectorAll('.faq-item').forEach(function (i) {
          i.classList.remove('is-open');
          var s = i.querySelector('.faq-item__sign');
          if (s) { s.textContent = '+'; }
          i.querySelector('.faq-item__q').setAttribute('aria-expanded', 'false');
        });
        if (willOpen) {
          item.classList.add('is-open');
          sign.textContent = '−';
          q.setAttribute('aria-expanded', 'true');
        }
      });
    });
  })();

  /* ---------------------------------------------------------------------
   * Road / progress timeline: dots track the SVG curve exactly, in both
   * scroll directions (no CSS transition on position — recomputed every
   * animation frame from the live scroll offset instead).
   * ------------------------------------------------------------------- */
  (function roadTimeline() {
    var canvas = document.querySelector('[data-road-canvas]');
    if (!canvas) { return; }
    var stepEls = canvas.querySelectorAll('.road__step');
    if (!stepEls.length) { return; }

    var P = [[-0.04, 0.72], [0.34, 0.42], [1.06, 0.12]];
    function roadPoint(t) {
      var u = 1 - t;
      return {
        x: u * u * P[0][0] + 2 * u * t * P[1][0] + t * t * P[2][0],
        y: u * u * P[0][1] + 2 * u * t * P[1][1] + t * t * P[2][1],
      };
    }
    var LUT = null;
    function roadT(s) {
      if (!LUT) {
        var N = 240, xs = [];
        for (var i = 0; i <= N; i++) { xs.push(roadPoint(i / N).x); }
        var x0 = xs[0], x1 = xs[N];
        LUT = xs.map(function (x) { return (x - x0) / (x1 - x0); });
      }
      var n = LUT.length - 1, target = Math.max(0, Math.min(1, s));
      var lo = 0, hi = n;
      while (hi - lo > 1) {
        var mid = (lo + hi) >> 1;
        if (LUT[mid] < target) { lo = mid; } else { hi = mid; }
      }
      var span = LUT[hi] - LUT[lo] || 1;
      return (lo + (target - LUT[lo]) / span) / n;
    }

    var roadP = 0, roadW = canvas.getBoundingClientRect().width || 1000;

    function update() {
      var rect = canvas.getBoundingClientRect();
      var vh = window.innerHeight || 800;
      var p = Math.max(0, Math.min(1, (vh - rect.top) / (vh + rect.height)));
      roadP = p;
      roadW = rect.width || roadW;

      stepEls.forEach(function (el, i) {
        var boxW = Math.max(150, roadW * 0.20) + 22;
        var lead = Math.max(24, Math.min(120, roadW * 0.05));
        var xEnd = Math.max(lead + 40, roadW - lead - boxW);
        var xFrac = (lead + (xEnd - lead) * (i / 4)) / roadW;
        var base = Math.max(0, Math.min(1, (xFrac + 0.04) / 1.10));
        var k = Math.max(0, Math.min(1, (roadP - 0.10 - i * 0.055) / 0.30));
        var e = 1 - Math.pow(1 - k, 3);
        var s = base + (1.34 - base) * (1 - e);
        var pt = roadPoint(roadT(s));
        var over = Math.max(0, s - 1) * 0.92;
        var fade = Math.max(0, Math.min(1, k / 0.12));
        el.style.left = ((pt.x + over) * 100).toFixed(3) + '%';
        el.style.top = (pt.y * 100).toFixed(3) + '%';
        el.style.opacity = fade.toFixed(3);
      });
    }

    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    requestAnimationFrame(function tick() { update(); requestAnimationFrame(tick); });
  })();

  /* ---------------------------------------------------------------------
   * Partnership section parallax
   * ------------------------------------------------------------------- */
  (function partnershipParallax() {
    var section = document.querySelector('.partnership');
    var bg = document.querySelector('.partnership__bg');
    if (!section || !bg) { return; }
    function update() {
      var vh = window.innerHeight || 800;
      var raw = Math.max(-vh, Math.min(vh, section.getBoundingClientRect().top));
      bg.style.transform = 'translateY(' + Math.round(raw * 0.12) + 'px)';
    }
    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
  })();

  /* ---------------------------------------------------------------------
   * Insight list: category filter pills + keyword search + client-side
   * pagination (6 per page), matching the original prototype.
   * ------------------------------------------------------------------- */
  (function insightFilters() {
    var bar = document.querySelector('.insight-filterbar');
    var grid = document.querySelector('[data-insight-grid]');
    if (!bar || !grid) { return; }
    var cards = Array.prototype.slice.call(grid.querySelectorAll('.insight-card'));
    var pills = bar.querySelectorAll('.insight-pill');
    var searchInput = bar.querySelector('[data-insight-search]');
    var emptyEl = document.querySelector('[data-insight-empty]');
    var pagerEl = document.querySelector('[data-insight-pager]');
    var perPage = 6;
    var state = { category: 'all', query: '', page: 1 };

    function matches(card) {
      var cat = card.getAttribute('data-category') || '';
      var text = (card.getAttribute('data-search-text') || '').toLowerCase();
      var okCat = state.category === 'all' || cat === state.category;
      var okQuery = !state.query || text.indexOf(state.query) !== -1;
      return okCat && okQuery;
    }

    function render() {
      var visible = cards.filter(matches);
      cards.forEach(function (c) { c.style.display = 'none'; });
      var totalPages = Math.max(1, Math.ceil(visible.length / perPage));
      state.page = Math.min(state.page, totalPages);
      var start = (state.page - 1) * perPage;
      visible.slice(start, start + perPage).forEach(function (c) { c.style.display = ''; });

      if (emptyEl) { emptyEl.style.display = visible.length ? 'none' : ''; }
      grid.style.display = visible.length ? '' : 'none';

      if (pagerEl) {
        pagerEl.innerHTML = '';
        if (totalPages > 1) {
          for (var p = 1; p <= totalPages; p++) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = String(p);
            if (p === state.page) { btn.className = 'current'; }
            btn.addEventListener('click', function (pageNum) {
              return function () { state.page = pageNum; render(); window.scrollTo({ top: bar.offsetTop - 100, behavior: 'smooth' }); };
            }(p));
            pagerEl.appendChild(btn);
          }
        }
      }
    }

    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        pills.forEach(function (p) { p.classList.remove('is-active'); });
        pill.classList.add('is-active');
        state.category = pill.getAttribute('data-category');
        state.page = 1;
        render();
      });
    });
    if (searchInput) {
      searchInput.addEventListener('input', function () {
        state.query = searchInput.value.trim().toLowerCase();
        state.page = 1;
        render();
      });
    }
    var clearBtn = document.querySelector('[data-insight-clear]');
    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        state = { category: 'all', query: '', page: 1 };
        pills.forEach(function (p) { p.classList.toggle('is-active', p.getAttribute('data-category') === 'all'); });
        if (searchInput) { searchInput.value = ''; }
        render();
      });
    }
    render();
  })();

  /* ---------------------------------------------------------------------
   * "Back" buttons that use real browser history (Insight article back link)
   * ------------------------------------------------------------------- */
  document.querySelectorAll('[data-history-back]').forEach(function (btn) {
    btn.addEventListener('click', function () { window.history.back(); });
  });

  /* ---------------------------------------------------------------------
   * Newsletter signup stub (front-end only confirmation — no email-service
   * account is connected, same as the original prototype).
   * ------------------------------------------------------------------- */
  document.querySelectorAll('[data-newsletter-form]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var note = form.parentElement.querySelector('[data-newsletter-note]');
      if (note) { note.textContent = (data.strings && data.strings.subscribed) || "Thanks — you're on the list."; }
      form.reset();
    });
  });
})();
