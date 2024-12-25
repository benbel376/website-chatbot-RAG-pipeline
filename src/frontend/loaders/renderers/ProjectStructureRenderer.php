<?php

function renderProjectStructure($structure) {
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