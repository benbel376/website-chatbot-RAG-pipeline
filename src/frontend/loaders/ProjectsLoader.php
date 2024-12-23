<?php
class ProjectsLoader {
    private $contentPath;
    private $imagesPath;

    public function __construct($contentPath = 'content', $imagesPath = 'assets/images') {
        $this->contentPath = $contentPath;
        $this->imagesPath = $imagesPath;
    }

    public function loadAllProjects() {
        $projectsPath = __DIR__ . "/../{$this->contentPath}/projects";
        error_log("Loading projects from: " . $projectsPath);
        
        $projects = [];

        if (!is_dir($projectsPath)) {
            error_log("Projects directory not found: {$projectsPath}");
            return [];
        }

        foreach (scandir($projectsPath) as $projectDir) {
            if ($projectDir === '.' || $projectDir === '..') continue;
            
            $outlinePath = "{$projectsPath}/{$projectDir}/outline.json";
            error_log("Checking outline file: " . $outlinePath);
            
            if (file_exists($outlinePath)) {
                $content = file_get_contents($outlinePath);
                $outline = json_decode($content, true);
                if ($outline && isset($outline['config']['showProject']) && $outline['config']['showProject']) {
                    $projects[] = $outline;
                    error_log("Successfully loaded project: " . $outline['title']);
                } else {
                    error_log("Project skipped or failed to decode: " . $outlinePath);
                }
            } else {
                error_log("Outline file not found: " . $outlinePath);
            }
        }

        error_log("Total projects loaded: " . count($projects));
        return $projects;
    }

    public function renderProjectsList($projects) {
        $projectItems = '';
        foreach ($projects as $project) {
            $tags = implode(',', $project['tags']);
            $projectItems .= "
                <li class='project-post-item content-item active' 
                    data-filter-item 
                    data-category='{$project['category']}'
                    data-filter-item 
                    data-tags='{$tags}'
                    data-date='{$project['date']}'
                    data-title='{$project['title']}'
                    data-content-id='{$project['id']}'>
                    <a href='#' class='project-card'>
                        <figure class='project-banner-box'>
                            <img src='./{$this->imagesPath}/projects/{$project['thumbnail']}' 
                                 alt='{$project['title']}' 
                                 loading='lazy'>
                            <ion-icon name='eye-outline'></ion-icon>
                        </figure>
                        <div class='project-content'>
                            <div class='project-meta'>
                                <time class='project-date' datetime='{$project['date']}'>" . 
                                    date('M j, Y', strtotime($project['date'])) . 
                                "</time>
                            </div>
                            <h3 class='project-title'>{$project['title']}</h3>
                            <p class='project-text'>{$project['shortDescription']}</p>
                        </div>
                    </a>
                </li>
            ";
        }
        return $projectItems;
    }
}
?> 