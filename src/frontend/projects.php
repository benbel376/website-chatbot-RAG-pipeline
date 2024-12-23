<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'loaders/ProjectsLoader.php';
require_once 'loaders/ProjectDetailsLoader.php';

// Debug log
error_log("Initializing Loaders");
$projectsLoader = new ProjectsLoader();
$projectDetailsLoader = new ProjectDetailsLoader();

// Debug log
error_log("Loading all projects");
$projects = $projectsLoader->loadAllProjects();
error_log("Loaded " . count($projects) . " projects");

// Debug output in HTML comment
echo "<!-- Loaded " . count($projects) . " projects -->\n";
?>

<article id="portfolio-container" class="portfolio-container" data-page="projects">
    <header class="list-details-header">
        <h2 class="h2 article-title">Projects</h2>
        <button class="go-back-btn" data-go-back hidden>
            <ion-icon name="arrow-back-outline"></ion-icon>
            Go Back
        </button>
    </header>

    <section class="projects content-list" data-content-list>
        <div class="filter-select-box">
            <button class="filter-select" data-select>
                <div class="select-value" data-select-value>Filter by tags</div>
                <div class="select-icon">
                    <ion-icon name="chevron-down"></ion-icon>
                </div>
            </button>

            <ul class="select-list">
                <li class="select-item">
                    <label>
                        <input type="checkbox" data-select-item value="End-to-end">
                        <span class="tag-label">End-to-end</span>
                    </label>
                </li>
                <li class="select-item">
                    <label>
                        <input type="checkbox" data-select-item value="Machine Learning">
                        <span class="tag-label">Machine Learning</span>
                    </label>
                </li>
                <li class="select-item">
                    <label>
                        <input type="checkbox" data-select-item value="Data">
                        <span class="tag-label">Data</span>
                    </label>
                </li>
                <li class="select-item">
                    <label>
                        <input type="checkbox" data-select-item value="NLP">
                        <span class="tag-label">NLP</span>
                    </label>
                </li>
                <li class="select-item">
                    <label>
                        <input type="checkbox" data-select-item value="Computer Vision">
                        <span class="tag-label">Computer Vision</span>
                    </label>
                </li>
            </ul>
        </div>

        <div class="search-filter">
            <input type="text" class="search-input" data-search-input placeholder="Search by tags or project name...">
        </div>

        <div class="filter-sort sort-controls">
            <button class="filter-sort-btn sort-btn" data-sort="date" data-order="desc">
                <span>Date</span>
                <ion-icon name="arrow-up-outline"></ion-icon>
            </button>
            <button class="filter-sort-btn sort-btn inactive" data-sort="title" data-order="asc">
                <span>Title</span>
                <ion-icon name="arrow-down-outline"></ion-icon>
            </button>
        </div>

        <!-- Projects List -->
        <ul class="content-list-items">
            <?php 
            $projectsList = $projectsLoader->renderProjectsList($projects);
            error_log("Rendered projects list. Length: " . strlen($projectsList));
            echo $projectsList;
            ?>
        </ul>
    </section>

    <!-- Content Details Section -->
    <section class="project-details content-details" data-content-details hidden>
        <?php
        foreach ($projects as $project) {
            error_log("Rendering details for project: " . $project['id']);
            echo $projectDetailsLoader->renderProjectDetails($project['id']);
        }
        ?>
    </section>

    <!-- Debug output -->
    <script>
    console.log('Projects loaded:', <?php echo json_encode($projects); ?>);
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Project list items:', document.querySelectorAll('.project-list .project-item').length);
        console.log('Project details:', document.querySelectorAll('.project-details .detail-unit').length);
    });
    </script>
</article>
