<?php

function renderTechnologiesSection($technologies) {
    if (!$technologies) return '';

    // Define icons for each technology
    $techIcons = [
        'Python' => 'logo-python',
        'SQL' => 'server-outline',
        'JavaScript' => 'logo-javascript',
        'PHP' => 'code-slash-outline',
        'Linux' => 'logo-tux',
        'Docker' => 'cube-outline',
        'Docker Compose' => 'layers-outline',
        'BigQuery' => 'analytics-outline',
        'Pandas' => 'grid-outline',
        'NumPy' => 'calculator-outline',
        'Scikit-Learn' => 'brain-outline',
        'SQLAlchemy' => 'database-outline',
        'PostgreSQL' => 'server-outline',
        'Apache Spark' => 'flame-outline',
        'Google Cloud SDK' => 'cloud-outline'
    ];

    // Split technologies into two columns
    $midPoint = ceil(count($technologies) / 2);
    $firstColumn = array_slice($technologies, 0, $midPoint);
    $secondColumn = array_slice($technologies, $midPoint);

    $renderColumn = function($techs) use ($techIcons) {
        $items = '';
        foreach ($techs as $tech) {
            $icon = $techIcons[$tech] ?? 'hardware-chip-outline';
            $items .= "
                <li class='tech-item'>
                    <ion-icon name='{$icon}'></ion-icon>
                    <span>{$tech}</span>
                </li>
            ";
        }
        return "<ul class='tech-list'>{$items}</ul>";
    };

    return "
        <section class='technologies-section detail-section'>
            <div class='technologies-content'>
                <h3 class='section-title'>Technologies & Tools</h3>
                <div class='tech-columns'>
                    {$renderColumn($firstColumn)}
                    {$renderColumn($secondColumn)}
                </div>
            </div>
        </section>
    ";
} 