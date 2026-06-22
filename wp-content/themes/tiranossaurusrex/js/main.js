$(document).ready(function() {
    var carousel = $('.banner-carousel');
    var items = carousel.children('.banner-item');
    var currentIndex = 0;
    var autoplayInterval;
    var autoplaySpeed = 3000;

    function showItem(index) {
        items.removeClass('active');
        items.eq(index).addClass('active');
    }

    function nextItem() {
        currentIndex = (currentIndex + 1) % items.length;
        showItem(currentIndex);
    }

    function startAutoplay() {
        autoplayInterval = setInterval(nextItem, autoplaySpeed);
    }

    function stopAutoplay() {
        clearInterval(autoplayInterval);
    }

    carousel.on('mouseenter', stopAutoplay);
    carousel.on('mouseleave', startAutoplay);

    startAutoplay();
});