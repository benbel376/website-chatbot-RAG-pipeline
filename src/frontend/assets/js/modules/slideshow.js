'use strict';

export function initSlideshow(container) {
    const slides = container.querySelectorAll('.slideshow-slide');
    const dots = container.querySelectorAll('[data-dot]');
    const nextBtn = container.querySelector('[data-next]');
    const prevBtn = container.querySelector('[data-prev]');
    let currentSlide = 0;

    const showSlide = (index) => {
        slides.forEach((slide, i) => {
            slide.classList.remove('active-dot');
            dots[i].classList.remove('active-dot');
            if (i === index) {
                slide.classList.add('active-dot');
                dots[i].classList.add('active-dot');
            }
        });
    };

    const nextSlide = () => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    };

    const prevSlide = () => {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    };

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    showSlide(currentSlide);
}
