'use strict';

export function initInfiniteScroll() {
    // Ensure that jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded. Please include jQuery before initializing the carousel.');
        return;
    }

    // Initialize the Slick Carousel
    $(document).ready(function() {
        $('.clients-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            speed: 200,
            cssEase: 'ease',
            infinite: true,
            variableWidth: true, // Handles variable slide widths
            arrows: false,
            draggable: true,
            swipeToSlide: true,
            dots: true, // Enable dots


        });
    });
}