<article id="portfolio-container" class="portfolio-container" data-page="projects">

  <header class="list-details-header">
    <h2 class="section-title article-title">Projects</h2>
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
            <input type="checkbox" data-select-item value="Ops"> 
            <span class="tag-label">Ops</span>
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
    

    <ul class="project-list content-list-items">

      <li class="project-item content-item active" data-filter-item data-category="End-to-end" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2023-10-30" data-title="Digital Ad Inventory Bidding" data-content-id="bidlist">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/bidlist.png" alt="bidlist" loading="lazy">
          </figure>
          <h3 class="project-title content-title">Digital Ad Inventory Bidding</h3>
          <p class="project-category content-category">End-to-end</p>
          <p class="project-date content-date">June 15, 2023</p>
        </a>
      </li>

      <li class="project-item content-item active" data-filter-item data-category="End-to-end" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2023-06-30" data-title="rag-pipeline" data-content-id="rag-pipeline">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/rag-pipeline.png" alt="sales-prediction" loading="lazy">
          </figure>
          <h3 class="project-title content-title">RAG Pipeline</h3>
          <p class="project-category content-category">End-to-end</p>
          <p class="project-date content-date">June 30, 2023</p>
        </a>
      </li>

      <li class="project-item content-item active" data-filter-item data-category="Data" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2022-08-04" data-title="ELT Data Pipeline" data-content-id="data-pipeline">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/project-etl.png" alt="ELT Data Pipeline" loading="lazy">
          </figure>
          <h3 class="project-title content-title">ELT Data Pipeline</h3>
          <p class="project-category content-category">Data</p>
          <p class="project-date content-date">August 4, 2022</p>
        </a>
      </li>

      <li class="project-item content-item active" data-filter-item data-category="Cloud Computing" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2023-10-30" data-title="Topic Modeling" data-content-id="topic-modeling">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/topic-modeling.png" alt="topic-modeling" loading="lazy">
          </figure>
          <h3 class="project-title content-title">Topic Modeling</h3>
          <p class="project-category content-category">Machine Learning</p>
          <p class="project-date content-date">February 30, 2023</p>
        </a>
      </li>

      <li class="project-item content-item active" data-filter-item data-category="Machine Learning" data-filter-item data-tags="Data,Machine Learning" data-date="2023-10-30" data-title="Sales Prediction" data-content-id="sales-prediction">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/sales-prediction.png" alt="sales-prediction" loading="lazy">
          </figure>
          <h3 class="project-title content-title">Sales Prediction</h3>
          <p class="project-category content-category">Machine Learning</p>
          <p class="project-date content-date">September 30, 2023</p>
        </a>
      </li>

      <li class="project-item content-item active" data-filter-item data-category="Machine Learning" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2023-10-30" data-title="DSM" data-content-id="llm-finetuning">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/llm-finetuning.png" alt="llm-finetuning." loading="lazy">
          </figure>
          <h3 class="project-title content-title">LLM Finetuning for Amaharic</h3>
          <p class="project-category content-category">Machine Learning</p>
          <p class="project-date content-date">November 30, 2023</p>
        </a>
      </li>

      <li class="project-item content-item active" data-filter-item data-category="Machine Learning" data-filter-item data-tags="End-to-end,Ops" data-date="2023-10-30" data-title="Blood Vessel Segmentation" data-content-id="blood-vessel-segmentation">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/image-segmentation.png" alt="Blood Vessel Segmentation" loading="lazy">
          </figure>
          <h3 class="project-title content-title">Blood Vessel Segmentation</h3>
          <p class="project-category content-category">Machine Learning</p>
          <p class="project-date content-date">June 12, 2023</p>
        </a>
      </li>

      <li class="project-item content-item active" data-filter-item data-category="Machine Learning" data-filter-item data-tags="End-to-end,Machine Learning" data-date="2023-10-30" data-title="Engagement Prediction" data-content-id="engagement-prediction">
        <a href="#">
          <figure class="project-img content-img">
            <div class="project-item-icon-box content-item-icon-box">
              <ion-icon name="eye-outline"></ion-icon>
            </div>
            <img src="./assets/images/projects/engagement-prediction.png" alt="engagement-prediction" loading="lazy">
          </figure>
          <h3 class="project-title content-title">Engagement Prediction</h3>
          <p class="project-category content-category">Machine Learning</p>
          <p class="project-date content-date">June 15, 2023</p>
        </a>
      </li>

    </ul>

  </section>

  <!-- Content Details Section (Initially Hidden) -->
  <section class="project-details content-details" data-content-details hidden>

    <?php include 'project1.php';
          include 'project2.php';
          include 'project3.php';
          include 'project4.php';
          include 'project5.php';
          include 'project7.php';
          include 'project8.php';
          include 'project6.php';
    ?>

    

  </section>

</article>'''
