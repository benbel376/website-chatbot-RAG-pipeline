<?php

function renderOverviewSection($overview) {
    if (!$overview) return '';

    $keyFeatures = '';
    if (!empty($overview['keyFeatures'])) {
        foreach ($overview['keyFeatures'] as $feature) {
            $keyFeatures .= "
                <div class='overview-feature'>
                    <div class='feature-content'>
                        <h4>{$feature['title']}</h4>
                        <p>{$feature['description']}</p>
                    </div>
                </div>
            ";
        }
    }

    return "
        <section class='overview-section detail-section'>
            <div class='overview-content'>
                <h3 class='section-title'>Project Overview</h3>
                <p class='overview-description'>{$overview['description']}</p>
                
                <div class='overview-features'>
                    {$keyFeatures}
                </div>

                <div class='overview-problem-solution'>
                    <div class='overview-problem'>
                        <h3>
                            <ion-icon name='alert-circle-outline'></ion-icon>
                            Problem
                        </h3>
                        <p>{$overview['problem']}</p>
                    </div>
                    <div class='overview-solution'>
                        <h3>
                            <ion-icon name='bulb-outline'></ion-icon>
                            Solution
                        </h3>
                        <p>{$overview['solution']}</p>
                    </div>
                </div>
            </div>
        </section>
    ";
} 