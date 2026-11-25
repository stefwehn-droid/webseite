/* fifty slides of USA – Navigation, Zurück-nach-oben, Filter, Lightbox (ohne Bibliotheken) */
(function () {
    'use strict';

    /* ---------- Mobile Navigation ---------- */
    var toggle = document.querySelector('.nav-toggle');
    var links = document.getElementById('nav-links');
    if (toggle && links) {
        toggle.addEventListener('click', function () {
            var open = links.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
        });
    }

    /* ---------- Einblenden beim Scrollen ---------- */
    var revealEls = document.querySelectorAll('.g-item, .card, .about, .contact, .legal, .section-title, .doc-frame, .product, .tile, .shop-head, .shop-band-inner');
    revealEls.forEach(function (el, i) { el.classList.add('reveal'); el.style.transitionDelay = ((i % 6) * 60) + 'ms'; });
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
            });
        }, { rootMargin: '0px 0px -6% 0px', threshold: 0.05 });
        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('in'); });
    }

    /* ---------- Zurück nach oben ---------- */
    var btt = document.getElementById('back-to-top');
    if (btt) {
        window.addEventListener('scroll', function () { btt.hidden = window.pageYOffset < 400; }, { passive: true });
        btt.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }

    /* ---------- Filter (Quer-/Hochformat) ---------- */
    var chips = document.querySelectorAll('.filters .chip');
    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            var f = chip.getAttribute('data-filter');
            chips.forEach(function (c) { c.classList.toggle('is-active', c === chip); });
            document.querySelectorAll('.g-item').forEach(function (a) {
                a.hidden = !(f === 'all' || a.getAttribute('data-orient') === f);
            });
        });
    });

    /* ---------- Lightbox ---------- */
    var anchors = document.querySelectorAll('a[data-lightbox]');
    if (!anchors.length) { return; }

    var lb = document.createElement('div');
    lb.className = 'lb';
    lb.setAttribute('role', 'dialog');
    lb.setAttribute('aria-modal', 'true');
    lb.setAttribute('aria-label', 'Bildansicht');
    lb.innerHTML =
        '<a class="lb-home" href="#gallery">&#9638; Galerie</a>' +
        '<button type="button" class="lb-close" aria-label="Schließen">&times;</button>' +
        '<div class="lb-stage">' +
        '<button type="button" class="lb-prev" aria-label="Vorheriges Bild">&#8249;</button>' +
        '<img class="lb-img" alt="">' +
        '<button type="button" class="lb-next" aria-label="Nächstes Bild">&#8250;</button>' +
        '</div>' +
        '<div class="lb-bar"><strong></strong><span></span></div>';
    document.body.appendChild(lb);

    var img = lb.querySelector('.lb-img');
    var titleEl = lb.querySelector('.lb-bar strong');
    var countEl = lb.querySelector('.lb-bar span');
    var set = [];
    var idx = 0;
    var lastFocus = null;

    function visibleSet() {
        return Array.prototype.filter.call(anchors, function (a) { return !a.hidden; });
    }

    function show(i) {
        idx = (i + set.length) % set.length;
        var a = set[idx];
        img.classList.add('is-loading');
        img.onload = function () { img.classList.remove('is-loading'); };
        img.src = a.href;
        img.alt = a.getAttribute('data-title') || '';
        titleEl.textContent = a.getAttribute('data-title') || '';
        countEl.textContent = (idx + 1) + ' / ' + set.length;
        // nächstes Bild vorladen
        if (set.length > 1) { new Image().src = set[(idx + 1) % set.length].href; }
    }

    function open(a) {
        set = visibleSet();
        lastFocus = document.activeElement;
        lb.classList.add('is-open');
        document.body.classList.add('lb-lock');
        show(set.indexOf(a));
        lb.querySelector('.lb-close').focus();
    }

    function close() {
        lb.classList.remove('is-open');
        document.body.classList.remove('lb-lock');
        img.removeAttribute('src');
        if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
    }

    anchors.forEach(function (a) {
        a.addEventListener('click', function (e) {
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) { return; }
            e.preventDefault();
            open(a);
        });
    });

    lb.querySelector('.lb-close').addEventListener('click', close);
    lb.querySelector('.lb-prev').addEventListener('click', function () { show(idx - 1); });
    lb.querySelector('.lb-next').addEventListener('click', function () { show(idx + 1); });
    lb.querySelector('.lb-home').addEventListener('click', close);
    lb.addEventListener('click', function (e) { if (e.target === lb || e.target.classList.contains('lb-stage')) { close(); } });

    document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('is-open')) { return; }
        if (e.key === 'Escape') { close(); }
        else if (e.key === 'ArrowRight') { show(idx + 1); }
        else if (e.key === 'ArrowLeft') { show(idx - 1); }
        else if (e.key === 'Tab') {
            // Fokus im Dialog halten
            var f = lb.querySelectorAll('button, a');
            var first = f[0], last = f[f.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
    });

    // Wischen auf Touchgeräten
    var x0 = null;
    lb.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) {
        if (x0 === null) { return; }
        var dx = e.changedTouches[0].clientX - x0;
        if (Math.abs(dx) > 50) { show(dx < 0 ? idx + 1 : idx - 1); }
        x0 = null;
    });
})();
