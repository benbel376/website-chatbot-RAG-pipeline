<?php
class SkillsLoader {
    private $contentPath;

    public function __construct($contentPath = 'content') {
        $this->contentPath = $contentPath;
    }

    public function loadContent() {
        $filePath = __DIR__ . "/../{$this->contentPath}/profile/skills.md";
        
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
        if (!$content || !$content['config']['showSkills']) return '';

        // Generate Category Options
        $categoryOptions = '';
        foreach ($content['categories'] as $category) {
            $categoryOptions .= "<option value='" . strtolower($category) . "'>{$category}</option>";
        }

        // Generate Table Rows
        $tableRows = '';
        foreach ($content['tools'] as $tool) {
            $tableRows .= "
                <tr>
                    <td data-label='Tool'>
                        <div class='tool-cell'>
                            <div class='tool-icon'>
                                <ion-icon name='{$tool['icon']}'></ion-icon>
                            </div>
                            <span class='tool-name'>{$tool['name']}</span>
                        </div>
                    </td>
                    <td data-label='Category'>{$tool['category']}</td>
                    <td data-label='Description'>
                        <span class='details-snippet'>" . substr($tool['description'], 0, 50) . "...</span>
                        <span class='details-full hidden'>{$tool['description']}</span>
                        <a href='#' class='toggle-details' aria-expanded='false'>more</a>
                    </td>
                </tr>
            ";
        }

        // Return complete skills section with collapse structure
        return "
            <div class='section-header collapse-trigger' data-target='skills-content'>
                <h3 class='h3'>Skills</h3>
                <hr class='section-divider' />
            </div>
            <div class='collapse-content' id='skills-content'>
                <section class='skills'>
                    <div class='skills-content'>
                        <div class='controls-wrapper'>
                            <div class='dataTables-controls'>
                                <div class='filter-group'>
                                    <div class='dataTables_length'>
                                        <div class='select-wrapper'>
                                            <select id='customLengthSelect'>
                                                <option value='5'>5 entries</option>
                                                <option value='10'>10 entries</option>
                                                <option value='25'>25 entries</option>
                                                <option value='50'>50 entries</option>
                                            </select>
                                            <ion-icon name='chevron-down-outline'></ion-icon>
                                        </div>
                                    </div>
                                    
                                    <div class='custom-filter'>
                                        <div class='select-wrapper'>
                                            <select id='categoryFilter'>
                                                <option value=''>All Categories</option>
                                                {$categoryOptions}
                                            </select>
                                            <ion-icon name='chevron-down-outline'></ion-icon>
                                        </div>
                                    </div>
                                </div>

                                <div class='search-box'>
                                    <div class='search-input-wrapper'>
                                        <ion-icon name='search-outline'></ion-icon>
                                        <input type='text' id='customSearchBox' placeholder='Search tools...'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id='toolsTable' class='display'>
                            <thead>
                                <tr>
                                    <th>Tool</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$tableRows}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <script>
                // Initialize DataTable after content is loaded
                $(document).ready(function() {
                    if (typeof initToolsTable === 'function') {
                        initToolsTable();
                    }
                });
            </script>
        ";
    }
}
?> 