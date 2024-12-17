<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'loaders/BlogLoader.php';

// Initialize blog loader
$blogLoader = new BlogLoader();

// Load all blogs
$blogs = $blogLoader->loadAllBlogs();
error_log("Loaded " . count($blogs) . " blogs");
?>

<article id="blog-container" class="blog-container" data-page="blog">
    <header class="list-details-header">
        <h2 class="section-title article-title">Blog</h2>
        <button class="go-back-btn" data-go-back hidden>
            <ion-icon name="arrow-back-outline"></ion-icon>
            Go Back
        </button>
    </header>

    <!-- Blog Posts List -->
    <section class="blog-posts content-list" data-content-list>
        <div class="blog-filter-select">
            <button class="blog-filter-button" data-select>
                <div class="blog-filter-value" data-select-value>Filter by tags</div>
                <div class="blog-filter-icon">
                    <ion-icon name="chevron-down"></ion-icon>
                </div>
            </button>

            <ul class="blog-filter-list">
                <li class="blog-filter-item">
                    <label>
                        <input type="checkbox" data-select-item value="End-to-end">
                        <span class="blog-tag-label">End-to-end</span>
                    </label>
                </li>
                <li class="blog-filter-item">
                    <label>
                        <input type="checkbox" data-select-item value="Machine Learning">
                        <span class="blog-tag-label">Machine Learning</span>
                    </label>
                </li>
                <li class="blog-filter-item">
                    <label>
                        <input type="checkbox" data-select-item value="Data">
                        <span class="blog-tag-label">Data</span>
                    </label>
                </li>
            </ul>
        </div>

        <div class="blog-search">
            <input type="text" class="blog-search-input" data-search-input placeholder="Search by tags or blog title...">
        </div>

        <div class="blog-sort-controls">
            <button class="blog-sort-btn" data-sort="date" data-order="desc">
                <span>Date</span>
                <ion-icon name="arrow-up-outline"></ion-icon>
            </button>
            <button class="blog-sort-btn inactive" data-sort="title" data-order="asc">
                <span>Title</span>
                <ion-icon name="arrow-down-outline"></ion-icon>
            </button>
        </div>

        <!-- Blog Posts List -->
        <ul class="blog-posts-list content-list-items">
            <?php 
            $blogsList = $blogLoader->renderBlogsList($blogs);
            error_log("Rendered blogs list. Length: " . strlen($blogsList));
            echo $blogsList;
            ?>
        </ul>
    </section>

    <!-- Blog Details Section -->
    <section class="blog-details content-details" data-content-details hidden>
        <?php
        foreach ($blogs as $blog) {
            error_log("Rendering details for blog: " . $blog['id']);
            echo $blogLoader->renderBlogDetails($blog['id']);
        }
        ?>
    </section>

    <!-- Debug output -->
    <script>
    console.log('Blogs loaded:', <?php echo json_encode($blogs); ?>);
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Blog list items:', document.querySelectorAll('.blog-posts-list .blog-post-item').length);
        console.log('Blog details:', document.querySelectorAll('.blog-details .detail-unit').length);
    });
    </script>
</article>
