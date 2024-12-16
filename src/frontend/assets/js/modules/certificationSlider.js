export default function initCertificationSlider() {
    const slider = document.querySelector('[data-certifications-slider]');
    if (!slider) return;

    const slides = slider.querySelectorAll('.certification-slide');
    const prevBtn = document.querySelector('[data-certification-prev]');
    const nextBtn = document.querySelector('[data-certification-next]');
    const indicatorsContainer = document.querySelector('[data-certification-indicators]');

    let currentSlide = 0;
    let isAnimating = false;

    // Create indicators
    slides.forEach((_, index) => {
        const indicator = document.createElement('div');
        indicator.classList.add('indicator');
        if (index === 0) indicator.classList.add('active');
        indicator.addEventListener('click', () => goToSlide(index));
        indicatorsContainer.appendChild(indicator);
    });

    const indicators = indicatorsContainer.querySelectorAll('.indicator');

    function updateSlides() {
        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === currentSlide);
            indicators[index].classList.toggle('active', index === currentSlide);
        });
    }

    function goToSlide(index) {
        if (isAnimating || index === currentSlide) return;
        isAnimating = true;

        slides[currentSlide].classList.remove('active');
        indicators[currentSlide].classList.remove('active');

        currentSlide = index;

        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');

        setTimeout(() => {
            isAnimating = false;
        }, 500); // Match this with CSS transition duration
    }

    function nextSlide() {
        goToSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        goToSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    // Event listeners
    prevBtn.addEventListener('click', prevSlide);
    nextBtn.addEventListener('click', nextSlide);

    // Optional: Auto-play
    let autoplayInterval;

    function startAutoplay() {
        autoplayInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoplay() {
        clearInterval(autoplayInterval);
    }

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    // Initialize
    updateSlides();
    startAutoplay();
}