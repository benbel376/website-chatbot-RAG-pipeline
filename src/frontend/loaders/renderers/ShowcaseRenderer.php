<?php

function renderShowcaseSection($showcase, $imagesPath) {
    if (!$showcase) return '';

    // Combine images and videos into slides
    $slides = [];
    
    // Add images to slides
    if (isset($showcase['images'])) {
        foreach ($showcase['images'] as $image) {
            $slides[] = [
                'type' => 'image',
                'url' => $image['url'],
                'caption' => $image['caption'],
                'mediaType' => $image['type']
            ];
        }
    }
    
    // Add videos to slides
    if (isset($showcase['videos'])) {
        foreach ($showcase['videos'] as $video) {
            $slides[] = [
                'type' => 'video',
                'url' => $video['url'],
                'thumbnail' => $video['thumbnail'],
                'caption' => $video['caption']
            ];
        }
    }

    // Generate slides HTML
    $slidesHtml = '';
    foreach ($slides as $index => $slide) {
        $isActive = $index === 0 ? 'active' : '';
        $slidesHtml .= "<div class='showcase-slide {$isActive}'>";

        if ($slide['type'] === 'image') {
            $slidesHtml .= "
                <div class='showcase-media'>
                    <img src='./{$imagesPath}/projects/{$slide['url']}' 
                         alt='{$slide['caption']}' 
                         loading='lazy'>
                </div>
            ";
        } else {
            $slidesHtml .= "
                <div class='showcase-media'>
                    <video controls poster='./{$imagesPath}/projects/{$slide['thumbnail']}'>
                        <source src='./{$imagesPath}/projects/{$slide['url']}' type='video/mp4'>
                    </video>
                </div>
            ";
        }

        $slidesHtml .= "
            <div class='showcase-caption'>
                <p>{$slide['caption']}</p>
            </div>
        </div>";
    }

    // Generate navigation dots
    $dotsHtml = '';
    for ($i = 0; $i < count($slides); $i++) {
        $isActive = $i === 0 ? 'active' : '';
        $dotsHtml .= "<button class='showcase-dot {$isActive}' data-slide='{$i}'></button>";
    }

    return "
        <section class='showcase-section detail-section'>
            <div class='showcase-content'>
                <h3 class='section-title'>Project Showcase</h3>
                
                <div class='showcase-slider'>
                    <div class='showcase-slides'>
                        {$slidesHtml}
                    </div>
                    
                    <button class='showcase-nav prev' data-direction='prev'>
                        <ion-icon name='chevron-back-outline'></ion-icon>
                    </button>
                    
                    <button class='showcase-nav next' data-direction='next'>
                        <ion-icon name='chevron-forward-outline'></ion-icon>
                    </button>
                    
                    <div class='showcase-dots'>
                        {$dotsHtml}
                    </div>
                </div>
            </div>
        </section>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('.showcase-slider');
            
            sliders.forEach(slider => {
                const slides = slider.querySelectorAll('.showcase-slide');
                const dots = slider.querySelectorAll('.showcase-dot');
                const prevBtn = slider.querySelector('.showcase-nav.prev');
                const nextBtn = slider.querySelector('.showcase-nav.next');
                let currentSlide = 0;

                function showSlide(index) {
                    slides.forEach(slide => slide.classList.remove('active'));
                    dots.forEach(dot => dot.classList.remove('active'));
                    
                    slides[index].classList.add('active');
                    dots[index].classList.add('active');
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(currentSlide);
                }

                prevBtn.addEventListener('click', prevSlide);
                nextBtn.addEventListener('click', nextSlide);
                
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentSlide = index;
                        showSlide(currentSlide);
                    });
                });

                // Auto advance slides every 5 seconds
                let slideInterval = setInterval(nextSlide, 5000);

                // Pause auto-advance on hover
                slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
                slider.addEventListener('mouseleave', () => slideInterval = setInterval(nextSlide, 5000));
            });
        });
        </script>
    ";
} 