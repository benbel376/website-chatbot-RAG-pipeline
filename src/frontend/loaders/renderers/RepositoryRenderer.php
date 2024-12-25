<?php

function renderRepositorySection($repo) {
    if (!$repo) return '';

    return "
        <section class='repository-section detail-section'>
            <div class='repository-content'>
                <h3 class='section-title'>Repository</h3>
                <div class='repository-card'>
                    <div class='repository-info'>
                        <a href='{$repo['url']}' target='_blank' class='repository-link'>
                            <ion-icon name='logo-github'></ion-icon>
                            <span>{$repo['label']}</span>
                        </a>
                        <div class='repository-stats'>
                            <div class='stat-item'>
                                <ion-icon name='star-outline'></ion-icon>
                                <span>{$repo['stats']['stars']}</span>
                            </div>
                            <div class='stat-item'>
                                <ion-icon name='git-branch-outline'></ion-icon>
                                <span>{$repo['stats']['forks']}</span>
                            </div>
                            <div class='stat-item'>
                                <ion-icon name='people-outline'></ion-icon>
                                <span>{$repo['stats']['contributors']}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    ";
} 