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
            <input type="checkbox" data-select-item value="Ops"> 
            <span class="blog-tag-label">Ops</span>
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
    

    <ul class="blog-posts-list content-list-items">
      <li class="blog-post-item content-item active" data-filter-item data-category="End-to-end" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2022-01-23" data-title="Digital Ad Inventory Bidding" data-content-id="bidlist">
        <a href="#">
          <figure class="blog-banner-box content-img">
            <img src="./assets/images/projects/bidlist.png" alt="bidlist" loading="lazy">
          </figure>
          <div class="blog-content">
            <div class="blog-meta">
              <p class="blog-category content-category">Random</p>
              <span class="dot"></span>
              <time class="blog-date content-date" datetime="2022-02-23">Feb 13, 2022</time>
            </div>
            <h3 class="h3 blog-item-title content-title">Digital Ad Inventory Bidding</h3>
            <p class="blog-text">Veritatis et quasi architecto beatae vitae dicta sunt, explicabo.</p>
          </div>
        </a>
      </li>

      <li class="blog-post-item content-item active" data-filter-item data-category="End-to-end" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2023-06-30" data-title="rag-pipeline" data-content-id="rag-pipeline">
        <a href="#">
          <figure class="blog-banner-box content-img">
            <img src="./assets/images/projects/rag-pipeline.png" alt="sales-prediction" loading="lazy">
          </figure>
          <div class="blog-content">
            <div class="blog-meta">
              <p class="blog-category content-category">End-to-end</p>
              <span class="dot"></span>
              <time class="blog-date content-date" datetime="2022-02-23">Feb 14, 2022</time>
            </div>
            <h3 class="h3 blog-item-title content-title">RAG Pipeline</h3>
            <p class="blog-text">A rag pipeline project that can be used for any document</p>
          </div>
        </a>
      </li>

      <li class="blog-post-item content-item active" data-filter-item data-category="End-to-end" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2023-06-30" data-title="image-segmentation" data-content-id="image-segmentation">
        <a href="#">
          <figure class="blog-banner-box content-img">
            <img src="./assets/images/projects/image-segmentation.png" alt="sales-prediction" loading="lazy">
          </figure>
          <div class="blog-content">
            <div class="blog-meta">
              <p class="blog-category content-category">End-to-end</p>
              <span class="dot"></span>
              <time class="blog-date content-date" datetime="2022-02-23">Feb 14, 2022</time>
            </div>
            <h3 class="h3 blog-item-title content-title">Blood Vessel Segmentation</h3>
            <p class="blog-text">Blood Vessel Segmentation Computer Vision project</p>
          </div>
        </a>
      </li>

      <!-- <li class="blog-post-item content-item active" data-filter-item data-category="Cloud Computing" data-date="2022-03-23" data-title="Cloud Computing digest #80" data-content-id="Cloud Computing-digest-80">
        <a href="#">
          <figure class="blog-banner-box content-img">
            <img src="./assets/images/blog-3.jpg" alt="Cloud Computing digest #80" loading="lazy">
          </figure>
          <div class="blog-content">
            <div class="blog-meta">
              <p class="blog-category content-category">Cloud Computing</p>
              <span class="dot"></span>
              <time class="blog-date content-date" datetime="2022-02-23">Feb 15, 2022</time>
            </div>
            <h3 class="h3 blog-item-title content-title">Cloud Computing digest #80</h3>
            <p class="blog-text">Excepteur sint occaecat cupidatat no proident, quis nostrum exercitationem ullam corporis suscipit.</p>
          </div>
        </a>
      </li>

      <li class="blog-post-item content-item active" data-filter-item data-category="Machine Learning" data-date="2022-04-23" data-title="UI interactions of the week" data-content-id="ui-interactions">
        <a href="#">
          <figure class="blog-banner-box content-img">
            <img src="./assets/images/blog-4.jpg" alt="UI interactions of the week" loading="lazy">
          </figure>
          <div class="blog-content">
            <div class="blog-meta">
              <p class="blog-category content-category">Machine Learning</p>
              <span class="dot"></span>
              <time class="blog-date content-date" datetime="2022-02-23">Feb 16, 2022</time>
            </div>
            <h3 class="h3 blog-item-title content-title">UI interactions of the week</h3>
            <p class="blog-text">Enim ad minim veniam, consectetur adipiscing elit, quis nostrud exercitation ullamco laboris nisi.</p>
          </div>
        </a>
      </li>

      <li class="blog-post-item content-item active" data-filter-item data-category="DevOps" data-date="2022-05-23" data-title="The forgotten art of spacing" data-content-id="forgotten-art-spacing">
        <a href="#">
          <figure class="blog-banner-box content-img">
            <img src="./assets/images/blog-5.jpg" alt="The forgotten art of spacing" loading="lazy">
          </figure>
          <div class="blog-content">
            <div class="blog-meta">
              <p class="blog-category content-category">DevOps</p>
              <span class="dot"></span>
              <time class="blog-date content-date" datetime="2022-02-23">Feb 23, 2022</time>
            </div>
            <h3 class="h3 blog-item-title content-title">The forgotten art of spacing</h3>
            <p class="blog-text">Maxime placeat, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
        </a>
      </li>

      <li class="blog-post-item content-item active" data-filter-item data-category="Cloud Computing" data-date="2022-06-23" data-title="Cloud Computing digest #79" data-content-id="Cloud Computing-digest-79">
        <a href="#">
          <figure class="blog-banner-box content-img">
            <img src="./assets/images/blog-6.jpg" alt="Cloud Computing digest #79" loading="lazy">
          </figure>
          <div class="blog-content">
            <div class="blog-meta">
              <p class="blog-category content-category">Cloud Computing</p>
              <span class="dot"></span>
              <time class="blog-date content-date" datetime="2022-02-23">Feb 23, 2022</time>
            </div>
            <h3 class="h3 blog-item-title content-title">Cloud Computing digest #79</h3>
            <p class="blog-text">Optio cumque nihil impedit uo minus quod maxime placeat, velit esse cillum.</p>
          </div>
        </a>
      </li> -->
    </ul>
  </section>

  <!-- Blog Details Section (Initially Hidden) -->
  <section class="blog-details content-details" data-content-details hidden>
    <?php include 'blog1.php';
        include 'blog2.php';
        include 'blog3.php';
      ?>


    <div class="detail-unit details-unit" id="rag-pipeline-details" hidden>
      <h3 class="blog-detail-title details-title">Best fonts every Cloud Computinger</h3>
      <p class="blog-detail-text details-text">This is the detailed information about the Best fonts every Cloud Computinger blog post.</p>
    </div>
  </section>
</article>
