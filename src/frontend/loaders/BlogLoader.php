<?php
class BlogLoader {
    private $contentPath;
    private $imagesPath;

    public function __construct($contentPath = 'content', $imagesPath = 'assets/images') {
        $this->contentPath = $contentPath;
        $this->imagesPath = $imagesPath;
    }

    public function loadAllBlogs() {
        $blogsPath = __DIR__ . "/../{$this->contentPath}/blogs";
        error_log("Loading blogs from: " . $blogsPath);
        
        $blogs = [];

        if (!is_dir($blogsPath)) {
            error_log("Blogs directory not found: {$blogsPath}");
            return [];
        }

        foreach (scandir($blogsPath) as $blogDir) {
            if ($blogDir === '.' || $blogDir === '..') continue;
            
            $outlinePath = "{$blogsPath}/{$blogDir}/outline.json";
            error_log("Checking outline file: " . $outlinePath);
            
            if (file_exists($outlinePath)) {
                $content = file_get_contents($outlinePath);
                $outline = json_decode($content, true);
                if ($outline && isset($outline['config']['showBlog']) && $outline['config']['showBlog']) {
                    $blogs[] = $outline;
                    error_log("Successfully loaded blog: " . $outline['title']);
                }
            }
        }

        usort($blogs, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $blogs;
    }

    public function loadBlogPages($blogId) {
        $pagesPath = __DIR__ . "/../{$this->contentPath}/blogs/{$blogId}/details";
        $pages = [];
        
        if (!is_dir($pagesPath)) {
            error_log("Blog pages directory not found: {$pagesPath}");
            return [];
        }

        foreach (glob("{$pagesPath}/page*.json") as $pageFile) {
            $content = file_get_contents($pageFile);
            $page = json_decode($content, true);
            if ($page) {
                $pageNumber = (int) str_replace(['page', '.json'], '', basename($pageFile));
                $pages[$pageNumber] = $page;
            }
        }

        ksort($pages);
        return $pages;
    }

    public function renderBlogsList($blogs) {
        $blogItems = '';
        foreach ($blogs as $blog) {
            $tags = implode(',', $blog['tags']);
            $blogItems .= "
                <li class='blog-post-item content-item active' 
                    data-filter-item 
                    data-category='{$blog['category']}'
                    data-filter-item 
                    data-tags='{$tags}'
                    data-date='{$blog['date']}'
                    data-title='{$blog['title']}'
                    data-content-id='{$blog['id']}'>
                    <a href='#'>
                        <figure class='blog-banner-box content-img'>
                            <img src='./{$this->imagesPath}/projects/{$blog['thumbnail']}' 
                                 alt='{$blog['title']}' 
                                 loading='lazy'>
                        </figure>
                        <div class='blog-content'>
                            <div class='blog-meta'>
                                <p class='blog-category content-category'>{$blog['category']}</p>
                                <span class='dot'></span>
                                <time class='blog-date content-date' datetime='{$blog['date']}'>" . 
                                    date('M j, Y', strtotime($blog['date'])) . 
                                "</time>
                            </div>
                            <h3 class='h3 blog-item-title content-title'>{$blog['title']}</h3>
                            <p class='blog-text'>{$blog['shortDescription']}</p>
                        </div>
                    </a>
                </li>
            ";
        }
        return $blogItems;
    }

    public function renderBlogDetails($blogId) {
        $pages = $this->loadBlogPages($blogId);
        if (empty($pages)) return '';

        $pagesHtml = '';
        foreach ($pages as $pageNum => $page) {
            $pagesHtml .= $this->renderBlogPage($blogId, $pageNum, $page);
        }

        return "
            <div class='detail-unit details-unit' id='{$blogId}-details' hidden>
                <div class='blog-details-container'>
                    <section class='blog-details-content'>
                        {$pagesHtml}
                    </section>
                    <div class='blog-pagination-container'>
                        <button class='blog-prev-page' data-blog-prev-page disabled>Previous</button>
                        <span class='blog-pagination-info'>Page 1 of " . count($pages) . "</span>
                        <button class='blog-next-page' data-blog-next-page>Next</button>
                    </div>
                </div>
            </div>
        ";
    }

    private function renderBlogPage($blogId, $pageNum, $page) {
        $sectionsHtml = '';
        foreach ($page['sections'] as $section) {
            $sectionsHtml .= $this->renderSection($section);
        }

        $bannerHtml = isset($page['banner']) ? "
            <div class='blog-details-banner'>
                <img src='./{$this->imagesPath}/projects/{$page['banner']}' alt='Blog Banner'>
            </div>
        " : '';

        return "
            <div class='blog-page" . ($pageNum === 1 ? ' active' : '') . "' id='page-{$pageNum}'>
                " . ($pageNum === 1 ? "
                    <h2 class='project-detail-title'>{$page['title']}</h2>
                    <h3 class='project-detail-subtitle'>{$page['subtitle']}</h3>
                    {$bannerHtml}
                " : "") . "
                {$sectionsHtml}
            </div>
        ";
    }

    private function renderSection($section) {
        $html = '';
        
        if ($section['type'] === 'text') {
            $html .= "<p class='blog-post-body'>{$section['content']}</p>";
        } else {
            $html .= "<h3 class='blog-post-mid-title'>{$section['title']}</h3>";
            $html .= "<p class='blog-post-body'>{$section['content']}</p>";

            if (isset($section['code'])) {
                $html .= $this->renderCodeBlock($section['code']);
            }

            if (isset($section['subsections'])) {
                foreach ($section['subsections'] as $subsection) {
                    $html .= "
                        <p class='blog-post-body'>
                            <strong>{$subsection['title']}:</strong> {$subsection['content']}
                        </p>
                    ";
                }
            }
        }

        return $html;
    }

    private function renderCodeBlock($code) {
        return "
            <div class='code-display-wrapper' id='custom-code-display'>
                <div class='code-display-header'>
                    <span class='code-display-filename'>{$code['language']}</span>
                    <span class='code-display-actions'>
                        <button class='code-display-copy'>Copy</button>
                    </span>
                </div>
                <div class='code-display-body'>
                    <div class='code-line-numbers'></div>
                    <pre><code id='code-content'>{$code['content']}</code></pre>
                </div>
            </div>
        ";
    }
}
?> 