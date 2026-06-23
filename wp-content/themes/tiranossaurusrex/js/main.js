(function() {
    'use strict';

    const GRADIENTS = typeof TGRADIENTS !== 'undefined' ? TGRADIENTS : [];

    /* ===== LOGO ROTATION ===== */
    function initLogoRotation() {
        const container = document.querySelector('.logo-rotation');
        if (!container) return;
        const logos = container.querySelectorAll('img');
        if (logos.length < 2) return;
        let current = 0;
        logos[current].classList.add('active');
        setInterval(function() {
            logos[current].classList.remove('active');
            current = (current + 1) % logos.length;
            logos[current].classList.add('active');
        }, 15000);
    }

    /* ===== CARD GLOW ON HOVER ===== */
    function initCardGlow() {
        if (GRADIENTS.length === 0) return;
        var cards = document.querySelectorAll('.post-card');
        cards.forEach(function(card) {
            var lastIndex = -1;
            card.addEventListener('mouseover', function() {
                var idx;
                do {
                    idx = Math.floor(Math.random() * GRADIENTS.length);
                } while (idx === lastIndex);
                lastIndex = idx;
                var g = GRADIENTS[idx];
                card.style.setProperty('--glow-start', g.start);
                card.style.setProperty('--glow-end', g.end);
                card.style.setProperty('--glow-dir', g.direction || '135deg');
                card.classList.add('glow-active');
            });
            card.addEventListener('mouseleave', function() {
                card.classList.remove('glow-active');
            });
        });
    }

    /* ===== COLOR CYCLING ON TITLES ===== */
    function initColorCycling() {
        if (GRADIENTS.length === 0) return;
        var shuffled = GRADIENTS.slice().sort(function() { return Math.random() - 0.5; });
        var titles = document.querySelectorAll('h1, h2, h3');
        var cards = document.querySelectorAll('.post-card');
        var i = 0;
        setInterval(function() {
            var g = shuffled[i % shuffled.length];
            var css = 'linear-gradient(' + (g.direction || '135deg') + ', ' + g.start + ', ' + g.end + ')';
            titles.forEach(function(el) {
                el.style.backgroundImage = css;
                el.style.webkitBackgroundClip = 'text';
                el.style.webkitTextFillColor = 'transparent';
                el.style.backgroundClip = 'text';
            });
            cards.forEach(function(el) {
                el.style.borderImage = css + ' 1';
                el.style.borderStyle = 'solid';
            });
            i++;
        }, 5000);
    }

    /* ===== INFINITE SCROLL / LOAD MORE ===== */
    function initInfiniteScroll() {
        var container = document.querySelector('.posts-container');
        var loadMore = document.querySelector('.load-more-btn');
        if (!container || !loadMore) return;
        var items = container.querySelectorAll('.post-card');
        var itemsPerLoad = 9;
        var currentCount = items.length;
        var totalItems = parseInt(container.getAttribute('data-total') || items.length, 10);
        var hiddenItems = [];

        items.forEach(function(item, idx) {
            if (idx >= itemsPerLoad) {
                item.style.display = 'none';
                hiddenItems.push(item);
            }
        });

        if (hiddenItems.length === 0) {
            loadMore.style.display = 'none';
            return;
        }

        loadMore.addEventListener('click', function() {
            var toShow = hiddenItems.splice(0, itemsPerLoad);
            toShow.forEach(function(item) { item.style.display = ''; });
            if (hiddenItems.length === 0) {
                loadMore.textContent = 'Todas as notícias carregadas';
                loadMore.disabled = true;
            }
        });
    }

    /* ===== INIT ===== */
    document.addEventListener('DOMContentLoaded', function() {
        initLogoRotation();
        initCardGlow();
        initColorCycling();
        initInfiniteScroll();
    });
})();
