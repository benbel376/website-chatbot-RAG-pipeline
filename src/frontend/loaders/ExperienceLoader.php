<?php
class ExperienceLoader {
    private $contentPath;

    public function __construct($contentPath = 'content') {
        $this->contentPath = $contentPath;
    }

    public function loadContent() {
        $filePath = __DIR__ . "/../{$this->contentPath}/profile/experience.md";
        
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
        if (!$content || !$content['config']['showExperience']) return '';

        // Generate Experience Items
        $experienceItems = '';
        foreach ($content['experiences'] as $exp) {
            $highlights = '';
            foreach ($exp['highlights'] as $highlight) {
                $highlights .= "<li>{$highlight}</li>";
            }

            $experienceItems .= "
                <li class='experience-item'>
                    <h4 class='h4 experience-item-title'>{$exp['title']}</h4>
                    <span>{$exp['company']}</span>
                    <span>{$exp['period']}</span>
                    <div class='experience-text'>
                        <ul>
                            {$highlights}
                        </ul>
                    </div>
                </li>
            ";
        }

        // Return complete experience section with collapse structure
        return "
            <div class='section-header collapse-trigger' data-target='experience-content'>
                <h3 class='h3'>Experience</h3>
                <hr class='section-divider' />
            </div>
            <div class='collapse-content' id='experience-content'>
                <section class='experience'>
                    <div class='experience-header'>
                        <div class='icon-box'>
                            <ion-icon name='book-outline'></ion-icon>
                        </div>
                        <h3 class='h3'>Experience</h3>
                    </div>

                    <ul class='experience-list'>
                        {$experienceItems}
                    </ul>
                </section>
            </div>
        ";
    }
}
?> 