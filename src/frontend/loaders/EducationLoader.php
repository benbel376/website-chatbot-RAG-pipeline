<?php
class EducationLoader {
    private $contentPath;

    public function __construct($contentPath = 'content') {
        $this->contentPath = $contentPath;
    }

    public function loadContent() {
        $filePath = __DIR__ . "/../{$this->contentPath}/profile/education.md";
        
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
        if (!$content || !$content['config']['showEducation']) return '';

        // Generate Education Items
        $educationItems = '';
        foreach ($content['education'] as $edu) {
            $details = '';
            foreach ($edu['details'] as $detail) {
                $details .= "<li>{$detail}</li>";
            }

            $educationItems .= "
                <li class='education-item'>
                    <h4 class='h4 education-item-title'>{$edu['degree']}</h4>
                    <h5 class='h5'>{$edu['institution']}</h5>
                    <span>{$edu['period']}</span>
                    <div class='education-text'>
                        <ul>
                            {$details}
                        </ul>
                    </div>
                </li>
            ";
        }

        // Return complete education section with collapse structure
        return "
            <div class='section-header collapse-trigger' data-target='education-content'>
                <h3 class='h3'>Education</h3>
                <hr class='section-divider' />
            </div>
            <div class='collapse-content' id='education-content'>
                <section class='education'>
                    <div class='education-header'>
                        <div class='icon-box'>
                            <ion-icon name='school-outline'></ion-icon>
                        </div>
                        <h3 class='h3'>Academic Background</h3>
                    </div>

                    <ol class='education-list'>
                        {$educationItems}
                    </ol>
                </section>
            </div>
        ";
    }
}
?> 