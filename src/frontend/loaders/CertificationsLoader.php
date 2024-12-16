<?php
class CertificationsLoader {
    private $contentPath;
    private $imagesPath;

    public function __construct($contentPath = 'content', $imagesPath = 'assets/images') {
        $this->contentPath = $contentPath;
        $this->imagesPath = $imagesPath;
    }

    public function loadContent() {
        $filePath = __DIR__ . "/../{$this->contentPath}/profile/certifications.md";
        
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
        if (!$content || !$content['config']['showCertifications']) return '';

        // Generate Certification Slides
        $slides = '';
        foreach ($content['certifications'] as $cert) {
            $slides .= "
                <div class='certification-slide'>
                    <div class='certification-card'>
                        <div class='certification-image'>
                            <img src='{$this->imagesPath}/{$cert['image']}' alt='{$cert['title']} Logo'>
                        </div>
                        <div class='certification-details'>
                            <h4>{$cert['title']}</h4>
                            <p class='issuer'>{$cert['issuer']}</p>
                            <p class='description'>{$cert['description']}</p>
                            <a href='{$cert['credentialUrl']}' 
                               target='_blank' 
                               class='view-credential-btn'>
                                View Credential
                                <ion-icon name='arrow-forward-outline'></ion-icon>
                            </a>
                        </div>
                    </div>
                </div>
            ";
        }

        // Return complete certifications section with collapse structure
        return "
            <div class='section-header collapse-trigger' data-target='certifications-content'>
                <h3 class='h3'>Certifications</h3>
                <hr class='section-divider' />
            </div>
            <div class='collapse-content' id='certifications-content'>
                <div class='certifications-wrapper'>
                    <section class='certifications'>
                        <div class='certifications-slider' data-certifications-slider>
                            {$slides}
                        </div>
                        
                        <div class='certification-controls'>
                            <button class='control-btn prev' data-certification-prev>
                                <ion-icon name='chevron-back-outline'></ion-icon>
                            </button>
                            <div class='certification-indicators' data-certification-indicators>
                                " . str_repeat("<div class='indicator'></div>", count($content['certifications'])) . "
                            </div>
                            <button class='control-btn next' data-certification-next>
                                <ion-icon name='chevron-forward-outline'></ion-icon>
                            </button>
                        </div>
                    </section>
                </div>
            </div>

            <script>
                // Initialize certification slider after content is loaded
                if (typeof initCertificationSlider === 'function') {
                    initCertificationSlider();
                }
            </script>
        ";
    }
}
?> 