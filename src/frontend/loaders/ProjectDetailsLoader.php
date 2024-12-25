<?php
require_once 'renderers/HeroRenderer.php';
require_once 'renderers/OverviewRenderer.php';
require_once 'renderers/TechnologiesRenderer.php';
require_once 'renderers/ProjectStructureRenderer.php';
require_once 'renderers/ShowcaseRenderer.php';
require_once 'renderers/RepositoryRenderer.php';
require_once 'renderers/CodeBlocksRenderer.php';

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

        // Always process new format details
        return $this->processNewFormatDetails($details);
    }

    private function processNewFormatDetails($details) {
        $processed = [];
        if (!isset($details['sections'])) {
            return $processed;
        }

        foreach ($details['sections'] as $section) {
            $section['value'] = $this->loadContentField($section);
            $processed[] = $section;
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

        // Always use new format rendering
        return $this->renderNewFormatDetails($projectId, $details);
    }

    private function renderNewFormatDetails($projectId, $sections) {
        $output = "<div class='detail-unit details-unit' id='{$projectId}-details' hidden>";
        foreach ($sections as $section) {
            $output .= $this->renderSection($section);
        }
        $output .= "</div>";
        return $output;
    }

    private function renderSection($section) {
        $loader = $section['loader'] ?? null;
        if (!$loader) return '';

        // Render based on loader
        switch ($loader) {
            case 'hero-loader':
                return renderHeroSection($section['value'], $this->imagesPath);
            case 'overview-loader':
                return renderOverviewSection($section['value']);
            case 'tech-loader':
                return renderTechnologiesSection($section['value']);
            case 'structure-loader':
                return renderProjectStructure($section['value']);
            case 'showcase-loader':
                return renderShowcaseSection($section['value'], $this->imagesPath);
            case 'repo-loader':
                return renderRepositorySection($section['value']);
            case 'code-loader':
                return renderCodeBlocksSection($section['value']);
                default:
                return '';
        }
    }
}
?> 