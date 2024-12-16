<?php
class TestimonialsLoader {
    private $contentPath;
    private $imagesPath;

    public function __construct($contentPath = 'content', $imagesPath = 'assets/images') {
        $this->contentPath = $contentPath;
        $this->imagesPath = $imagesPath;
    }

    public function loadContent() {
        $filePath = __DIR__ . "/../{$this->contentPath}/profile/testimonials.md";
        
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
        if (!$content || !$content['config']['showTestimonials']) return '';

        // Generate Testimonial Items
        $testimonialItems = '';
        foreach ($content['testimonials'] as $testimonial) {
            $testimonialItems .= "
                <li class='testimonials-item'>
                    <div class='content-card' data-testimonials-item>
                        <figure class='testimonials-avatar-box'>
                            <img src='{$this->imagesPath}/{$testimonial['avatar']}' 
                                 alt='{$testimonial['name']}' 
                                 width='60' 
                                 data-testimonials-avatar>
                        </figure>

                        <h4 class='h4 testimonials-item-title' data-testimonials-title>{$testimonial['name']}</h4>

                        <div class='testimonials-text' data-testimonials-text>
                            <h3>{$testimonial['title']}</h3>
                            <p>
                                {$testimonial['text']}
                                <a href='{$testimonial['linkedin']}'>linkedin</a>
                            </p>
                        </div>
                    </div>
                </li>
            ";
        }

        // Generate Modal Content
        $modalContent = "
            <div class='modal-container' data-modal-container>
                <div class='overlay' data-overlay></div>

                <section class='testimonials-modal'>
                    <button class='modal-close-btn' data-modal-close-btn>
                        <ion-icon name='close-outline'></ion-icon>
                    </button>

                    <div class='modal-img-wrapper'>
                        <figure class='modal-avatar-box'>
                            <img src='' alt='modal img' width='80' data-modal-img>
                        </figure>

                        <img src='./assets/images/icon-quote.svg' alt='quote icon'>
                    </div>

                    <div class='modal-content'>
                        <h4 class='h3 modal-title' data-modal-title></h4>
                        <time datetime='2021-06-14'>14 June, 2021</time>
                        <div data-modal-text></div>
                    </div>
                </section>
            </div>
        ";

        // Return complete testimonials section with collapse structure
        return "
            <div class='section-header collapse-trigger' data-target='testimonials-content'>
                <h3 class='h3'>Testimonials</h3>
                <hr class='section-divider' />
            </div>
            <div class='collapse-content' id='testimonials-content'>
                <section style='margin-top: 30px;' class='testimonials'>
                    <ul class='testimonials-list has-scrollbar'>
                        {$testimonialItems}
                    </ul>
                    {$modalContent}
                </section>
            </div>

            <script>
                // Initialize testimonials functionality after content is loaded
                if (typeof initTestimonials === 'function') {
                    initTestimonials();
                }
            </script>
        ";
    }
}
?> 