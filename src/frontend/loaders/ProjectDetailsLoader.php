<?php
class ProjectDetailsLoader {
    private $contentPath;
    private $imagesPath;

    public function __construct($contentPath = 'content', $imagesPath = 'assets/images') {
        $this->contentPath = $contentPath;
        $this->imagesPath = $imagesPath;
    }

    public function loadProjectDetails($projectId) {
        $detailsPath = __DIR__ . "/../{$this->contentPath}/projects/{$projectId}/details.json";
        error_log("Loading details for project: " . $projectId);
        
        if (!file_exists($detailsPath)) {
            error_log("Project details not found: {$detailsPath}");
            return null;
        }

        $content = file_get_contents($detailsPath);
        $details = json_decode($content, true);
        
        if ($details === null) {
            error_log("Failed to decode JSON for details: " . $detailsPath);
            return null;
        }

        // Support both old and new formats
        if (isset($details['metadata']) && $details['metadata']['version'] === '2') {
            return $this->processNewFormatDetails($details);
        }

        return $details; // Return original format for backward compatibility
    }

    private function processNewFormatDetails($details) {
        $processed = [];
        foreach ($details['content'] as $key => $field) {
            $processed[$key] = $this->loadContentField($field);
        }
        return $processed;
    }

    private function loadContentField($field) {
        if (!isset($field['loader']) || !isset($field['version']) || !isset($field['value'])) {
            return $field;
        }
        return $field['value']; // For now, just return the value directly
    }

    public function renderProjectDetails($projectId) {
        $details = $this->loadProjectDetails($projectId);
        if (!$details) return '';

        // Support both old and new formats
        if (isset($details['hero'])) {
            // New format
            return $this->renderNewFormatDetails($projectId, $details);
        }

        // Old format - maintain backward compatibility
        return $this->renderOldFormatDetails($projectId, $details);
    }

    private function renderNewFormatDetails($projectId, $details) {
        return "
            <div class='detail-unit details-unit' id='{$projectId}-details' hidden>
                {$this->renderHeroSection($details['hero'])}
                {$this->renderOverviewSection($details['overview'])}
                {$this->renderTechnologiesSection($details['technologies'])}
                " . (isset($details['codeBlocks']) ? $this->renderCodeBlocks($details['codeBlocks']) : '') . "
                {$this->renderProjectStructure($details['projectStructure'])}
                {$this->renderShowcaseSection($details['showcase'])}
                {$this->renderRepositorySection($details['repository'])}
            </div>
        ";
    }

    private function renderOldFormatDetails($projectId, $details) {
        // Maintain the original rendering logic for backward compatibility
        $subtitle = $details['subtitle'] ?? '';
        $description = $details['description'] ?? '';
        $date = $details['date'] ?? '';
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
                " . $this->renderTechnologiesSection($technologies) . "
                " . ($projectStructure ? $this->renderProjectStructure($projectStructure) : "") . "
                " . $this->renderRepositorySection($repository) . "
            </div>
        ";
    }

    // Add individual section rendering methods...
    private function renderHeroSection($hero) {
        return "
            <div class='project-hero-wrapper'>
                <section class='project-hero-section'>
                    <div class='project-hero-banner' style='background-image: url(./assets/images/projects/{$hero['banner']})'>
                        <div class='project-hero-overlay'></div>
                        <div class='project-hero-content'>
                            <h1 class='project-hero-title'>{$hero['title']}</h1>
                        </div>
                    </div>
                </section>
            </div>
        ";
    }

    private function renderOverviewSection($overview) {
        if (!$overview) return '';

        $keyFeatures = '';
        foreach ($overview['keyFeatures'] as $feature) {
            $keyFeatures .= "
                <div class='overview-feature'>
                    <div class='feature-content'>
                        <h4>{$feature['title']}</h4>
                        <p>{$feature['description']}</p>
                    </div>
                </div>
            ";
        }

        return "
            <section class='overview-section detail-section'>
                <div class='overview-content'>
                    <h3 class='section-title'>Project Overview</h3>
                    <p class='overview-description'>{$overview['description']}</p>
                    
                    <div class='overview-features'>
                        {$keyFeatures}
                    </div>

                    <div class='overview-problem-solution'>
                        <div class='overview-problem'>
                            <h3>
                                <ion-icon name='alert-circle-outline'></ion-icon>
                                Problem
                            </h3>
                            <p>{$overview['problem']}</p>
                        </div>
                        <div class='overview-solution'>
                            <h3>
                                <ion-icon name='bulb-outline'></ion-icon>
                                Solution
                            </h3>
                            <p>{$overview['solution']}</p>
                        </div>
                    </div>
                </div>
            </section>
        ";
    }

    private function renderTechnologiesSection($technologies) {
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

    private function renderProjectStructure($structure) {
        if (!$structure) return '';

        $getFileIcon = function($file) {
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            switch ($fileExtension) {
                case 'py':
                    return '<i class="fab fa-python"></i>';
                case 'js':
                    return '<i class="fab fa-js"></i>';
                case 'php':
                    return '<i class="fab fa-php"></i>';
                case 'html':
                    return '<i class="fab fa-html5"></i>';
                case 'css':
                    return '<i class="fab fa-css3"></i>';
                case 'json':
                    return '<i class="fas fa-code"></i>';
                case 'md':
                    return '<i class="fab fa-markdown"></i>';
                case 'txt':
                    return '<i class="fas fa-file-alt"></i>';
                default:
                    return '<i class="fas fa-file-code"></i>';
            }
        };

        $renderFile = function($file) use ($getFileIcon) {
            $fileIcon = $getFileIcon($file);
            $description = isset($file['description']) ? $file['description'] : 'No description available.';
            
            return "
                <li class='file-item'>
                    <div class='file-header'>
                        <div class='file-name'>
                            {$fileIcon} {$file['name']}
                            <button class='description-toggle' onclick='toggleDescription(event, this)'>
                                <ion-icon name='information-circle-outline'></ion-icon>
                            </button>
                        </div>
                    </div>
                    <div class='description-content'>
                        <p>{$description}</p>
                    </div>
                </li>
            ";
        };

        $renderFolder = function($folder) use (&$renderFolder, $renderFile) {
            $html = "<li><details>";
            $html .= "<summary>";
            $html .= "<div class='folder-header'>";
            $html .= "<ion-icon name='chevron-forward-outline' class='arrow-icon'></ion-icon>";
            $html .= "<i class='fas fa-folder'></i><i class='fas fa-folder-open'></i> {$folder['name']}";
            if (isset($folder['size'])) {
                $html .= "<span class='size'>- {$folder['size']}</span>";
            }
            $html .= "</div>";
            $html .= "</summary>";
            
            $html .= "<ul>";

            // Render subfolders
            if (isset($folder['subfolders']) && is_array($folder['subfolders'])) {
                foreach ($folder['subfolders'] as $subfolder) {
                    $html .= $renderFolder($subfolder);
                }
            }

            // Render files
            if (isset($folder['files']) && is_array($folder['files'])) {
                foreach ($folder['files'] as $file) {
                    $html .= $renderFile($file);
                }
            }

            $html .= "</ul></details></li>";
            return $html;
        };

        return "
            <section class='project-structure-section detail-section'>
                <div class='project-structure-content'>
                    <h3 class='section-title'>Project Structure</h3>
                    <div class='tree'>
                        <ul>
                            <li>
                                <details open>
                                    <summary>
                                        <div class='folder-header'>
                                            <ion-icon name='chevron-forward-outline' class='arrow-icon'></ion-icon>
                                            <i class='fas fa-folder'></i><i class='fas fa-folder-open'></i> {$structure['name']}
                                            " . (isset($structure['size']) ? "<span class='size'>- {$structure['size']}</span>" : "") . "
                                        </div>
                                    </summary>
                                    <ul>
                                        " . (isset($structure['folders']) ? implode('', array_map($renderFolder, $structure['folders'])) : '') . "
                                        " . (isset($structure['files']) ? implode('', array_map($renderFile, $structure['files'])) : '') . "
                                    </ul>
                                </details>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <script>
            function toggleDescription(event, button) {
                event.preventDefault();
                event.stopPropagation();
                
                const fileItem = button.closest('.file-item');
                const descriptionContent = fileItem.querySelector('.description-content');
                const isExpanded = descriptionContent.classList.contains('expanded');
                
                // Close all other open descriptions
                document.querySelectorAll('.description-content.expanded').forEach(content => {
                    if (content !== descriptionContent) {
                        content.classList.remove('expanded');
                        const otherButton = content.closest('.file-item').querySelector('.description-toggle');
                        otherButton.innerHTML = '<ion-icon name=\"information-circle-outline\"></ion-icon>';
                    }
                });
                
                if (!isExpanded) {
                    descriptionContent.classList.add('expanded');
                    button.innerHTML = '<ion-icon name=\"close-circle-outline\"></ion-icon>';
                } else {
                    descriptionContent.classList.remove('expanded');
                    button.innerHTML = '<ion-icon name=\"information-circle-outline\"></ion-icon>';
                }
            }
            </script>
        ";
    }

    private function renderShowcaseSection($showcase) {
        if (!$showcase) return '';

        // Combine images and videos into slides
        $slides = [];
        
        // Add images to slides
        if (isset($showcase['images'])) {
            foreach ($showcase['images'] as $image) {
                $slides[] = [
                    'type' => 'image',
                    'url' => $image['url'],
                    'caption' => $image['caption'],
                    'mediaType' => $image['type']
                ];
            }
        }
        
        // Add videos to slides
        if (isset($showcase['videos'])) {
            foreach ($showcase['videos'] as $video) {
                $slides[] = [
                    'type' => 'video',
                    'url' => $video['url'],
                    'thumbnail' => $video['thumbnail'],
                    'caption' => $video['caption']
                ];
            }
        }

        // Generate slides HTML
        $slidesHtml = '';
        foreach ($slides as $index => $slide) {
            $isActive = $index === 0 ? 'active' : '';
            $slidesHtml .= "<div class='showcase-slide {$isActive}'>";
            
            if ($slide['type'] === 'image') {
                $slidesHtml .= "
                    <div class='showcase-media'>
                        <img src='./assets/images/projects/{$slide['url']}' 
                             alt='{$slide['caption']}' 
                             loading='lazy'>
                    </div>
                ";
            } else {
                $slidesHtml .= "
                    <div class='showcase-media'>
                        <video controls poster='./assets/images/projects/{$slide['thumbnail']}'>
                            <source src='./assets/images/projects/{$slide['url']}' type='video/mp4'>
                        </video>
                    </div>
                ";
            }
            
            $slidesHtml .= "
                <div class='showcase-caption'>
                    <p>{$slide['caption']}</p>
                </div>
            </div>";
        }

        // Generate navigation dots
        $dotsHtml = '';
        for ($i = 0; $i < count($slides); $i++) {
            $isActive = $i === 0 ? 'active' : '';
            $dotsHtml .= "<button class='showcase-dot {$isActive}' data-slide='{$i}'></button>";
        }

        return "
            <section class='showcase-section detail-section'>
                <div class='showcase-content'>
                    <h3 class='section-title'>Project Showcase</h3>
                    
                    <div class='showcase-slider'>
                        <div class='showcase-slides'>
                            {$slidesHtml}
                        </div>
                        
                        <button class='showcase-nav prev' data-direction='prev'>
                            <ion-icon name='chevron-back-outline'></ion-icon>
                        </button>
                        
                        <button class='showcase-nav next' data-direction='next'>
                            <ion-icon name='chevron-forward-outline'></ion-icon>
                        </button>
                        
                        <div class='showcase-dots'>
                            {$dotsHtml}
                        </div>
                    </div>
                </div>
            </section>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sliders = document.querySelectorAll('.showcase-slider');
                
                sliders.forEach(slider => {
                    const slides = slider.querySelectorAll('.showcase-slide');
                    const dots = slider.querySelectorAll('.showcase-dot');
                    const prevBtn = slider.querySelector('.showcase-nav.prev');
                    const nextBtn = slider.querySelector('.showcase-nav.next');
                    let currentSlide = 0;

                    function showSlide(index) {
                        slides.forEach(slide => slide.classList.remove('active'));
                        dots.forEach(dot => dot.classList.remove('active'));
                        
                        slides[index].classList.add('active');
                        dots[index].classList.add('active');
                    }

                    function nextSlide() {
                        currentSlide = (currentSlide + 1) % slides.length;
                        showSlide(currentSlide);
                    }

                    function prevSlide() {
                        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                        showSlide(currentSlide);
                    }

                    prevBtn.addEventListener('click', prevSlide);
                    nextBtn.addEventListener('click', nextSlide);
                    
                    dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => {
                            currentSlide = index;
                            showSlide(currentSlide);
                        });
                    });

                    // Auto advance slides every 5 seconds
                    let slideInterval = setInterval(nextSlide, 5000);

                    // Pause auto-advance on hover
                    slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
                    slider.addEventListener('mouseleave', () => slideInterval = setInterval(nextSlide, 5000));
                });
            });
            </script>
        ";
    }

    private function renderRepositorySection($repo) {
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

    private function splitIntoCodeLines($codeString) {
        $lines = explode("\n", str_replace("\r\n", "\n", $codeString));
        $processedLines = [];
        
        foreach ($lines as $index => $line) {
            $processedLines[] = [
                'number' => $index + 1,
                'content' => $line,
                'lastChar' => strlen($line) > 0 ? substr(trim($line), -1) : '',
                'previousLine' => $index > 0 ? [
                    'content' => $lines[$index - 1],
                    'lastChar' => strlen($lines[$index - 1]) > 0 ? substr(trim($lines[$index - 1]), -1) : ''
                ] : null
            ];
        }
        
        return $processedLines;
    }

    private function tokenizeLine($line) {
        $pythonComponents = [
            'keywords' => [
                'def', 'class', 'return', 'import', 'from', 'if', 'else', 'elif',
                'for', 'while', 'try', 'except', 'as', 'with', 'in', 'is', 'not',
                'and', 'or', 'True', 'False', 'None', 'self', 'lambda', 'yield',
                'raise', 'break', 'continue', 'pass', 'assert', 'del', 'global',
                'nonlocal', 'async', 'await'
            ],
            'builtins' => [
                'print', 'len', 'range', 'str', 'int', 'float', 'list', 'dict',
                'set', 'tuple', 'bool', 'map', 'filter', 'zip', 'enumerate',
                'super', 'isinstance', 'type', 'min', 'max', 'sum', 'any', 'all'
            ],
            'operators' => [
                '+', '-', '*', '/', '%', '=', '==', '!=', '>', '<', '>=', '<=',
                '+=', '-=', '*=', '/=', '**', '//', '&', '|', '^', '~', '>>', '<<'
            ],
            'delimiters' => ['(', ')', '[', ']', '{', '}', ':', ',', '.', ';']
        ];

        $tokens = [];
        $buffer = '';
        $inString = false;
        $stringChar = '';

        for ($i = 0; $i < strlen($line); $i++) {
            $char = $line[$i];

            // Handle strings
            if (($char === '"' || $char === "'") && ($i === 0 || $line[$i-1] !== '\\')) {
                if (!$inString) {
                    if ($buffer !== '') {
                        $tokens[] = $this->processBuffer($buffer, $pythonComponents);
                        $buffer = '';
                    }
                    $inString = true;
                    $stringChar = $char;
                    $buffer = $char;
                    continue;
                } elseif ($char === $stringChar) {
                    $buffer .= $char;
                    $tokens[] = ['type' => 'string', 'content' => $buffer];
                    $buffer = '';
                    $inString = false;
                    continue;
                }
            }

            if ($inString) {
                $buffer .= $char;
                continue;
            }

            // Handle comments
            if ($char === '#') {
                if ($buffer !== '') {
                    $tokens[] = $this->processBuffer($buffer, $pythonComponents);
                }
                $tokens[] = ['type' => 'comment', 'content' => substr($line, $i)];
                break;
            }

            // Handle spaces
            if ($char === ' ') {
                if ($buffer !== '') {
                    $tokens[] = $this->processBuffer($buffer, $pythonComponents);
                    $buffer = '';
                }
                $tokens[] = ['type' => 'space', 'content' => ' '];
                continue;
            }

            // Handle operators and delimiters
            if (in_array($char, array_merge($pythonComponents['operators'], $pythonComponents['delimiters']))) {
                if ($buffer !== '') {
                    $tokens[] = $this->processBuffer($buffer, $pythonComponents);
                    $buffer = '';
                }
                $tokens[] = ['type' => 'operator', 'content' => $char];
                continue;
            }

            $buffer .= $char;
        }

        // Handle remaining buffer
        if ($buffer !== '') {
            $tokens[] = $this->processBuffer($buffer, $pythonComponents);
        }

        return $tokens;
    }

    private function processBuffer($buffer, $pythonComponents) {
        // Check if buffer is a number (integer or float)
        if (preg_match('/^-?\d*\.?\d+$/', $buffer)) {
            return ['type' => 'number', 'content' => $buffer];
        }
        
        // Check for keywords
        if (in_array($buffer, $pythonComponents['keywords'])) {
            return ['type' => 'keyword', 'content' => $buffer];
        }
        
        // Check for built-ins
        if (in_array($buffer, $pythonComponents['builtins'])) {
            return ['type' => 'builtin', 'content' => $buffer];
        }
        
        // Default to text
        return ['type' => 'text', 'content' => $buffer];
    }

    private function renderCodeBlock($codeLines) {
        $html = "<pre class='code-block'><code>";
        
        foreach ($codeLines as $line) {
            $tokens = $this->tokenizeLine($line['content']);
            $lineHtml = "<span class='line-number'>{$line['number']}</span>";
            
            foreach ($tokens as $token) {
                $lineHtml .= "<span class='{$token['type']}'>" . 
                            htmlspecialchars($token['content']) . 
                            "</span>";
            }
            
            $html .= "<div class='code-line'>{$lineHtml}</div>";
        }
        
        $html .= "</code></pre>";
        return $html;
    }

    private function renderCodeBlocks($codeBlocks) {
        if (!$codeBlocks) return '';

        $blocksHtml = '';
        foreach ($codeBlocks['blocks'] as $block) {
            $codeLines = $this->splitIntoCodeLines($block['code']);
            $highlightedCode = "<pre class='code-block'><code>";
            
            foreach ($codeLines as $line) {
                $tokens = $this->tokenizeLine($line['content']);
                $lineHtml = $this->renderCodeLine($line, $tokens, $block['highlights'] ?? []);
                $highlightedCode .= $lineHtml;
            }
            
            $highlightedCode .= "</code></pre>";

            $blocksHtml .= "
                <div class='code-block-container'>
                    <div class='code-block-header'>
                        <h4 class='code-block-title'>{$block['title']}</h4>
                        <button class='copy-button' onclick='copyCode(this)'>
                            <ion-icon name='copy-outline'></ion-icon>
                            <span>Copy</span>
                        </button>
                    </div>
                    {$highlightedCode}
                </div>
            ";
        }

        return "
            <section class='code-blocks-section detail-section'>
                <div class='code-blocks-content'>
                    <h3 class='section-title'>{$codeBlocks['title']}</h3>
                    <div class='code-blocks'>
                        {$blocksHtml}
                    </div>
                </div>
            </section>
        ";
    }

    private function calculateIndentationLevel($currentLine, $previousLine) {
        static $currentLevel = 0;
        
        // First check the end of previous line for indentation hints
        if ($previousLine) {
            $lastChar = trim($previousLine['lastChar']);
            if ($lastChar === ':') {
                // Increase indent for the next line after a colon
                $currentLevel++;
            }
        }

        // Then check the start of current line for unindentation hints
        $currentLineContent = trim($currentLine['content']);
        if ($currentLineContent) {
            // Check for keywords that typically indicate unindentation
            $unindentKeywords = ['else:', 'elif', 'except:', 'finally:', 'return', 'break', 'continue', 'pass'];
            foreach ($unindentKeywords as $keyword) {
                if (str_starts_with($currentLineContent, $keyword)) {
                    $currentLevel = max(0, $currentLevel - 1);
                    break;
                }
            }

            // Check for closing brackets at start of line
            if (in_array($currentLineContent[0] ?? '', [')', '}', ']'])) {
                $currentLevel = max(0, $currentLevel - 1);
            }

            // Function and class definitions should reset to base level
            if (str_starts_with($currentLineContent, 'def ') || str_starts_with($currentLineContent, 'class ')) {
                $currentLevel = 0;
            }
        }

        // Ensure level stays within bounds
        return min(6, max(0, $currentLevel));
    }

    private function renderCodeLine($lineData, $tokens, $highlights = []) {
        // Get indentation level
        $indentLevel = $this->calculateIndentationLevel($lineData, $lineData['previousLine']);
        $indentClass = "indent-level-{$indentLevel}";
        
        // Add highlight class if line number is in highlights array
        $highlightClass = in_array($lineData['number'], $highlights) ? ' highlight' : '';
        
        $lineHtml = "<div class='code-line {$indentClass}{$highlightClass}'>";
        $lineHtml .= "<span class='line-number'>{$lineData['number']}</span>";
        
        foreach ($tokens as $token) {
            $content = htmlspecialchars($token['content']);
            $type = $token['type'] === 'code' ? 'text' : $token['type'];
            $lineHtml .= "<span class='{$type}'>{$content}</span>";
        }
        
        $lineHtml .= "</div>";
        return $lineHtml;
    }
}
?> 