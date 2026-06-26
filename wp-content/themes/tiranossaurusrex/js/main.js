(function() {
    'use strict';

    const GRADIENTS = typeof TGRADIENTS !== 'undefined' ? TGRADIENTS : [];

    /* ===== LOGO ROTATION — 60s cycle ===== */
    function initLogoRotation() {
        var container = document.querySelector('.logo-rotation');
        if (!container) return;
        var logos = container.querySelectorAll('img');
        if (logos.length < 2) return;
        var current = 0;
        var totalLogos = logos.length;
        var displayTime = 15000; // 15s visible
        var cycleTime = 60000;   // 60s total cycle

        // Show first logo
        logos[current].classList.add('active');

        setInterval(function() {
            logos[current].classList.remove('active');
            logos[current].classList.add('hiding');
            current = (current + 1) % totalLogos;
            logos[current].classList.remove('hiding');
            logos[current].classList.add('active');
        }, cycleTime / totalLogos);
        // Each logo gets 15s (60000/4 = 15000ms)
    }

    /* ===== BANNER ROTATIVO — 15s static then animate ===== */
    function initBannerRotativo() {
        var track = document.querySelector('.banner-track');
        if (!track) return;
        var slides = track.querySelectorAll('.banner-slide');
        if (slides.length < 2) return;
        var current = 0;
        var slideWidth = 100;
        var staticTime = 15000;
        var animDuration = 800;
        var paused = false;

        track.style.transition = 'transform ' + (animDuration / 1000) + 's ease-in-out';

        function showSlide(index) {
            track.style.transform = 'translateX(-' + (index * slideWidth) + '%)';
        }

        function nextSlide() {
            if (paused) {
                window._bannerTimer = setTimeout(nextSlide, 500);
                return;
            }
            current = (current + 1) % slides.length;
            showSlide(current);
            clearTimeout(window._bannerTimer);
            window._bannerTimer = setTimeout(nextSlide, staticTime + animDuration);
        }

        // Hover pause
        var banner = document.querySelector('.banner-rotativo');
        if (banner) {
            banner.addEventListener('mouseenter', function() { paused = true; });
            banner.addEventListener('mouseleave', function() { paused = false; });
        }

        showSlide(0);
        window._bannerTimer = setTimeout(nextSlide, staticTime);
    }

    /* ===== CARD GLOW ON HOVER — stronger, always random ===== */
    function initCardGlow() {
        if (GRADIENTS.length === 0) return;

        // Shared glow state per element
        var glowState = new WeakMap();

        function getGlowIndex(el) {
            var state = glowState.get(el);
            if (!state) {
                state = { lastIndex: -1 };
                glowState.set(el, state);
            }
            var idx;
            do {
                idx = Math.floor(Math.random() * GRADIENTS.length);
            } while (idx === state.lastIndex && GRADIENTS.length > 1);
            state.lastIndex = idx;
            return idx;
        }

        // Event delegation on document for all hover-glow elements
        var glowSelector = '.post-card, .post-card-image, .pagination-btn, .load-more-btn, .btn-home, .footer-card, .widget, .sidebar-ad-square';

        document.addEventListener('mouseover', function(e) {
            var target = e.target.closest(glowSelector);
            if (!target) return;
            var idx = getGlowIndex(target);
            var g = GRADIENTS[idx];
            target.style.setProperty('--glow-start', g.start);
            target.style.setProperty('--glow-end', g.end);
            target.style.setProperty('--glow-dir', g.direction || '135deg');
            target.classList.add('glow-active', 'card-hover-glow');
        });

        document.addEventListener('mouseout', function(e) {
            var target = e.target.closest(glowSelector);
            if (!target) return;
            target.classList.remove('glow-active', 'card-hover-glow');
        });
    }

    /* ===== COLOR CYCLING ON TITLES ===== */
    function initColorCycling() {
        if (GRADIENTS.length === 0) return;
        var shuffled = GRADIENTS.slice().sort(function() { return Math.random() - 0.5; });
        var titles = document.querySelectorAll('h1, h2, h3');
        var cards = document.querySelectorAll('.post-card, .footer-card, .widget');
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

    /* ===== HAMBURGER MENU TOGGLE ===== */
    function initHamburger() {
        var toggle = document.querySelector('.hamburger-toggle');
        var menu = document.querySelector('.main-navigation .menu');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', function() {
            toggle.classList.toggle('active');
            menu.classList.toggle('menu-open');
        });

        // Close menu on link click
        menu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                toggle.classList.remove('active');
                menu.classList.remove('menu-open');
            });
        });
    }

    /* ===== HEADER/NAV HIDE ON SCROLL DOWN ===== */
    function initScrollHide() {
        var nav = document.querySelector('.main-navigation');
        var header = document.querySelector('.site-header');
        if (!nav) return;

        var lastScroll = 0;
        var ticking = false;
        var scrollThreshold = 80;

        window.addEventListener('scroll', function() {
            lastScroll = window.scrollY;
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (lastScroll > scrollThreshold) {
                        nav.classList.add('nav-hidden');
                        if (header) header.classList.add('header-hidden');
                    } else {
                        nav.classList.remove('nav-hidden');
                        if (header) header.classList.remove('header-hidden');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    /* ===== INFINITE SCROLL / LOAD MORE ===== */
    function initInfiniteScroll() {
        var container = document.querySelector('.posts-container');
        var loadMore = document.querySelector('.load-more-btn');
        if (!container || !loadMore) return;
        var items = Array.from(container.querySelectorAll('.post-card'));
        var itemsPerLoad = 9;
        var totalItems = parseInt(container.getAttribute('data-total') || items.length, 10);
        var hiddenItems = [];
        var currentApiPage = 2;
        var isLoading = false;
        var allLoaded = false;
        var templateDir = container.getAttribute('data-theme') || '';

        items.forEach(function(item, idx) {
            if (idx >= itemsPerLoad) {
                item.style.display = 'none';
                hiddenItems.push(item);
            }
        });

        if (hiddenItems.length === 0 && items.length >= totalItems) {
            loadMore.style.display = 'none';
            return;
        }

        function createPostCard(post) {
            var dateStr = '';
            if (post.date) {
                var d = new Date(post.date);
                var months = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
                dateStr = d.getDate() + ' ' + months[d.getMonth()] + '. ' + d.getFullYear();
            }
            var readingTime = post.reading_time || Math.max(1, Math.ceil((post.content.rendered || '').split(' ').length / 200));
            var imgUrl = post.featured_image_url || (templateDir + '/images/Banner_para_o_Blog_Tiranossaurus_Rex.jpg');
            var excerpt = post.excerpt || post.content.rendered.replace(/<[^>]+>/g, '').split(' ').slice(0, 18).join(' ') + '...';

            var article = document.createElement('article');
            article.className = 'post-card';
            article.innerHTML =
                '<a href="/?p=' + post.id + '" style="text-decoration:none;color:inherit;display:contents;">' +
                '<div class="post-card-image">' +
                '<img src="' + imgUrl + '" alt="' + post.title.rendered.replace(/"/g, '&quot;') + '">' +
                '</div>' +
                '<div class="post-card-body">' +
                '<h2 class="post-card-title">' + post.title.rendered + '</h2>' +
                '<div class="post-card-meta">' +
                '<span>' + dateStr + '</span>' +
                '<span>' + readingTime + ' min</span>' +
                '</div>' +
                '<div class="post-card-excerpt">' + excerpt + '</div>' +
                '<span class="post-card-read-more">Ler mais &rarr;</span>' +
                '</div>' +
                '</a>';
            return article;
        }

        function fetchApiPage() {
            if (isLoading || allLoaded) return;
            isLoading = true;
            loadMore.textContent = 'Carregando...';
            loadMore.disabled = true;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/api/v1/posts?page=' + currentApiPage + '&per_page=' + itemsPerLoad, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var data = JSON.parse(xhr.responseText);
                        if (data.posts && data.posts.length > 0) {
                            data.posts.forEach(function(post) {
                                container.appendChild(createPostCard(post));
                            });
                            currentApiPage++;
                            if (currentApiPage > data.total_pages) {
                                allLoaded = true;
                                loadMore.textContent = 'Todas as not\u00edcias carregadas';
                                loadMore.disabled = true;
                            } else {
                                loadMore.textContent = 'Carregar mais not\u00edcias \u2193';
                                loadMore.disabled = false;
                            }
                        } else {
                            allLoaded = true;
                            loadMore.textContent = 'Todas as not\u00edcias carregadas';
                            loadMore.disabled = true;
                        }
                    } catch(e) {
                        loadMore.textContent = 'Erro ao carregar';
                        loadMore.disabled = false;
                    }
                } else {
                    loadMore.textContent = 'Erro ao carregar';
                    loadMore.disabled = false;
                }
                isLoading = false;
            };
            xhr.onerror = function() {
                loadMore.textContent = 'Erro ao carregar';
                loadMore.disabled = false;
                isLoading = false;
            };
            xhr.send();
        }

        loadMore.addEventListener('click', function() {
            if (hiddenItems.length > 0) {
                var toShow = hiddenItems.splice(0, itemsPerLoad);
                toShow.forEach(function(item) { item.style.display = ''; });
                return;
            }
            fetchApiPage();
        });
    }

    /* ===== FOOTER STATUS TEXTS ===== */
    function initFooterStatus() {
        var redTextEl = document.getElementById('status-text-red');
        var greenTextEl = document.getElementById('status-text-green');

        var redTexts = [
            'API: Aguardando consulta',
            'Scan: 0.04s',
            'Cache: HIT',
            'DB: idle',
            'Token: válido',
            'Latência: 12ms',
            'Thread: sleep',
            'Buffer: OK',
            'SSL: handshake',
            'DNS: resolved',
            'Queue: empty',
            'Auth: bearer',
            'Parser: idle',
            'WebSocket: open',
            'Session: active'
        ];

        var greenTexts = [
            'Tiranossaurus Rex Engine',
            'Kernel: 4.2.1',
            'Runtime: 0.89s',
            'Mem: 64.2 MB',
            'CPU: 2.3%',
            'Uptime: 14d 7h',
            'Workers: 4',
            'Cores: 8',
            'I/O: 45 req/s',
            'Tasks: 12',
            'Pipeline: active',
            'Neural: online',
            'Cluster: synced',
            'Daemon: running',
            'Process: 0x4F2A'
        ];

        function rotateText(el, list, interval) {
            var i = 0;
            el.textContent = list[0];
            setInterval(function() {
                i = (i + 1) % list.length;
                el.style.opacity = '0';
                setTimeout(function() {
                    el.textContent = list[i];
                    el.style.opacity = '1';
                }, 400);
            }, interval);
        }

        if (redTextEl) rotateText(redTextEl, redTexts, 3000);
        if (greenTextEl) rotateText(greenTextEl, greenTexts, 3500);
    }

    /* ===== INIT ===== */
    document.addEventListener('DOMContentLoaded', function() {
        initLogoRotation();
        initBannerRotativo();
        initCardGlow();
        initColorCycling();
        initHamburger();
        initScrollHide();
        initInfiniteScroll();
        initFooterStatus();
    });
})();
