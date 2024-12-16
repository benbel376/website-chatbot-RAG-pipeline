<?php
class HeroLoader {
    private $contentPath;
    private $imagesPath;

    public function __construct($contentPath = 'content', $imagesPath = 'assets/images') {
        $this->contentPath = $contentPath;
        $this->imagesPath = $imagesPath;
    }

    public function loadContent() {
        $filePath = __DIR__ . "/../{$this->contentPath}/profile/hero.json";
        
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
        if (!$content || !$content['config']['showHero']) return '';

        // Generate Contact Links
        $contactLinks = '';
        foreach ($content['hero']['contacts'] as $contact) {
            $href = $contact['value'];
            if ($contact['type'] === 'email') $href = "mailto:{$contact['value']}";
            if ($contact['type'] === 'phone') $href = "tel:{$contact['value']}";
            
            $target = ($contact['type'] !== 'email' && $contact['type'] !== 'phone') ? 'target="_blank"' : '';

            $contactLinks .= "
                <a href='{$href}' class='hero__icon' {$target}>
                    <ion-icon name='{$contact['icon']}'></ion-icon>
                    <span>{$contact['label']}</span>
                </a>
            ";
        }

        // Return complete hero section matching existing structure
        return "
            <div class='hero-wrapper'>
                <section class='hero hero-profile'>
                    <div class='hero__container'>
                        <!-- Left Section: Avatar and Info -->
                        <div class='hero__avatar-section'>
                            <figure class='hero__avatar'>
                                <img src='./{$this->imagesPath}/{$content['hero']['avatar']}' 
                                     alt='{$content['hero']['name']}' 
                                     width='300'>
                            </figure>
                            <div class='hero__info'>
                                <h1 class='hero__name'>{$content['hero']['name']}</h1>
                                <p class='hero__title'>{$content['hero']['title']}</p>
                            </div>
                        </div>

                        <!-- Right Section: Description and Social Links -->
                        <div class='hero__content'>
                            <p class='hero__description'>
                                {$content['hero']['description']}
                            </p>
                            <div class='hero__social-links'>
                                {$contactLinks}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        ";
    }
}
?> 