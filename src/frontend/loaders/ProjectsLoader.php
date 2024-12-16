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
                if ($outline) {
                    $projects[] = $outline;
                    error_log("Successfully loaded project: " . $outline['title']);
                } else {
                    error_log("Failed to decode JSON for: " . $outlinePath);
                    error_log("JSON error: " . json_last_error_msg());
                }
            } else {
                error_log("Outline file not found: " . $outlinePath);
            }
        }

        error_log("Total projects loaded: " . count($projects));
        return $projects;
    }

    public function loadProjectDetails($projectId) {
        $detailsPath = __DIR__ . "/../{$this->contentPath}/projects/{$projectId}/details.json";
        error_log("Loading details for project: " . $projectId);
        error_log("Details path: " . $detailsPath);
        
        if (!file_exists($detailsPath)) {
            error_log("Project details not found: {$detailsPath}");
            return null;
        }

        $content = file_get_contents($detailsPath);
        $details = json_decode($content, true);
        
        if ($details === null) {
            error_log("Failed to decode JSON for details: " . $detailsPath);
            error_log("JSON error: " . json_last_error_msg());
        } else {
            error_log("Successfully loaded details for: " . $projectId);
        }

        return $details;
    }

    public function renderProjectsList($projects) {
        $projectItems = '';
        foreach ($projects as $project) {
            $tags = implode(',', $project['tags']);
            $projectItems .= "
                <li class='project-item content-item active' 
                    data-filter-item 
                    data-category='{$project['category']}'
                    data-filter-item 
                    data-tags='{$tags}'
                    data-date='{$project['date']}'
                    data-title='{$project['title']}'
                    data-content-id='{$project['id']}'>
                    <a href='#'>
                        <figure class='project-img content-img'>
                            <div class='project-item-icon-box content-item-icon-box'>
                                <ion-icon name='eye-outline'></ion-icon>
                            </div>
                            <img src='./{$this->imagesPath}/projects/{$project['thumbnail']}' 
                                 alt='{$project['title']}' 
                                 loading='lazy'>
                        </figure>
                        <h3 class='project-title content-title'>{$project['title']}</h3>
                        <p class='project-category content-category'>{$project['category']}</p>
                        <p class='project-date content-date'>" . date('F j, Y', strtotime($project['date'])) . "</p>
                    </a>
                </li>
            ";
        }
        return $projectItems;
    }

    public function renderProjectDetails($projectId) {
        $details = $this->loadProjectDetails($projectId);
        if (!$details) return '';

        // Default values for optional fields
        $subtitle = $details['subtitle'] ?? '';
        $description = $details['description'] ?? '';
        $date = $details['date'] ?? '';
        $requirements = $details['requirements'] ?? [];
        $technologies = $details['technologies'] ?? [];
        $projectStructure = $details['projectStructure'] ?? null;
        $repository = $details['repository'] ?? ['url' => '#', 'label' => 'View Project'];

        return "
            <div class='detail-unit details-unit' id='{$projectId}-details' hidden>
                <div class='detail-section'>
                    <h2 class='project-detail-title'>{$details['title']}</h2>
                    " . ($subtitle ? "<h3 class='project-detail-subtitle'>{$subtitle}</h3>" : "") . "
                    " . ($description ? "<p class='project-detail-description'>{$description}</p>" : "") . "
                    " . ($date ? "<p class='project-detail-date'>Date: {$date}</p>" : "") . "
                </div>

                " . (count($requirements) > 0 ? "
                    <div class='detail-section requirements-section'>
                        <h3 class='detail-heading'>Requirements</h3>
                        <ul class='requirements-list'>
                            " . implode('', array_map(function($req) {
                                return "<li>{$req}</li>";
                            }, $requirements)) . "
                        </ul>
                    </div>
                " : "") . "

                " . (count($technologies) > 0 ? "
                    <div class='detail-section technologies-section'>
                        <h3 class='detail-heading'>Technologies Used</h3>
                        <ul class='technologies-list'>
                            " . implode('', array_map(function($tech) {
                                return is_array($tech) ? 
                                    "<li>{$tech['name']} - {$tech['purpose']}</li>" : 
                                    "<li>{$tech}</li>";
                            }, $technologies)) . "
                        </ul>
                    </div>
                " : "") . "

                " . ($projectStructure ? "
                    <div id='folders'>
                        <div class='tree'>
                            " . $this->renderProjectStructure($projectStructure) . "
                        </div>
                    </div>
                " : "") . "

                <div class='detail-section'>
                    <h3 class='detail-heading'>Project Repository</h3>
                    <p class='project-detail-description'>
                        <a href='{$repository['url']}' target='_blank' class='github-link'>
                            {$repository['label']}
                        </a>
                    </p>
                </div>
            </div>
        ";
    }

    private function renderProjectStructure($structure) {
        $html = "<ul><li><details><summary><i class='fa fa-folder'></i> {$structure['name']}</summary><ul>";
        
        if (isset($structure['folders'])) {
            foreach ($structure['folders'] as $folder) {
                $html .= "<li><details><summary><i class='fa fa-folder'></i> {$folder['name']} <span>- {$folder['size']}</span></summary><ul>";
                
                if (isset($folder['subfolders'])) {
                    foreach ($folder['subfolders'] as $subfolder) {
                        $html .= "<li><details><summary><i class='fa fa-folder'></i> {$subfolder['name']} <span>- {$subfolder['size']}</span></summary><ul>";
                        foreach ($subfolder['files'] as $file) {
                            $html .= "<li><i class='fa fa-file'></i> {$file['name']} <span>- {$file['description']}</span></li>";
                        }
                        $html .= "</ul></details></li>";
                    }
                }
                
                if (isset($folder['files'])) {
                    foreach ($folder['files'] as $file) {
                        $html .= "<li><i class='{$file['type']}'></i> {$file['name']}</li>";
                    }
                }
                
                $html .= "</ul></details></li>";
            }
        }
        
        $html .= "</ul></details></li></ul>";
        return $html;
    }
}
?> 