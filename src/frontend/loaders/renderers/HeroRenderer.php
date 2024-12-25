<?php

function renderHeroSection($hero, $imagesPath) {
    if (!$hero) return '';
    return "
        <div class='project-hero-wrapper'>
            <section class='project-hero-section'>
                <div class='project-hero-banner' style='background-image: url(./{$imagesPath}/projects/{$hero['banner']}')>
                    <div class='project-hero-overlay'></div>
                    <div class='project-hero-content'>
                        <h1 class='project-hero-title'>{$hero['title']}</h1>
                    </div>
                </div>
            </section>
        </div>
    ";
} 