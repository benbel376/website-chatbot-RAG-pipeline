<?php
class SummaryLoader {
    private $contentPath;
    private $imagesPath;

    public function __construct($contentPath = 'content', $imagesPath = 'assets/images') {
        $this->contentPath = $contentPath;
        $this->imagesPath = $imagesPath;
    }

    public function loadContent() {
        $filePath = __DIR__ . "/../{$this->contentPath}/profile/summary.md";
        
        if (!file_exists($filePath)) {
            error_log("Content file not found: {$filePath}");
            return null;
        }

        $content = file_get_contents($filePath);
        $decoded = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            return null;
        }

        return $decoded;
    }

    public function render() {
        $content = $this->loadContent();
        if (!$content) return '';

        // Generate About Text Section
        $aboutText = '<section class="about-text">';
        foreach ($content['introduction'] as $para) {
            $aboutText .= "<p>{$para}</p>";
        }
        $aboutText .= '</section>';

        // Generate Services Section
        $servicesList = '';
        foreach ($content['services'] as $service) {
            $servicesList .= "
                <li class='service-item'>
                    <div class='service-icon-box'>
                        <img src='{$service['icon']}' alt='{$service['title']} icon' width='40'>
                    </div>
                    <div class='service-content-box'>
                        <h4 class='h4 service-item-title'>{$service['title']}</h4>
                    </div>
                </li>
            ";
        }

        $servicesSection = "
            <section class='service'>
                <h4 class='h4 service-title'>What I Do</h4>
                <ul class='service-list'>
                    {$servicesList}
                </ul>
            </section>
        ";

        // Generate Slideshow Section
        $slides = '';
        foreach ($content['systemDiagrams']['slides'] as $index => $slide) {
            $activeClass = $index === 0 ? 'active-dot' : '';
            $slides .= "
                <div class='slideshow-slide {$activeClass}'>
                    <img src='{$this->imagesPath}/{$slide['image']}' alt='{$slide['title']}'>
                    <div class='slide-caption'>
                        <h3>{$slide['title']}</h3>
                        <p>{$slide['description']}</p>
                    </div>
                </div>
            ";
        }

        $dots = '';
        for ($i = 0; $i < count($content['systemDiagrams']['slides']); $i++) {
            $activeClass = $i === 0 ? 'active-dot' : '';
            $dots .= "<span class='dot {$activeClass}' data-dot='{$i}'></span>";
        }

        $slideshowSection = "
            <section class='slideshow-section'>
                <h4 class='h4 service-title'>{$content['systemDiagrams']['title']}</h4>
                <div class='slideshow-outer-container'>
                    <div class='slideshow-container'>
                        {$slides}
                        <button class='slideshow-btn prev' data-prev>
                            <ion-icon name='chevron-back-outline'></ion-icon>
                        </button>
                        <button class='slideshow-btn next' data-next>
                            <ion-icon name='chevron-forward-outline'></ion-icon>
                        </button>
                    </div>
                    <div class='dots-container'>
                        {$dots}
                    </div>
                </div>
            </section>
        ";

        // Wrap everything in collapse trigger and collapse-content structure
        return "
            <div class='section-header collapse-trigger' data-target='summary-content'>
                <h3 class='h3'>Summary</h3>
                <hr class='section-divider' />
            </div>
            <div class='collapse-content' id='summary-content'>
                {$aboutText}
                {$servicesSection}
                {$slideshowSection}
            </div>
        ";
    }
}
?> 