<?php
// About Page Content
?>
<!--
        - #ABOUT
      -->

<article class="about  active" data-page="profile">

  <header class="profile-header">
    <h2 class="h2 article-title">Profile</h2>
    <a href="./assets/data/resume.pdf" download="resume.pdf" class="resume-button">Download Resume</a>
  </header>

  <div class="hero-wrapper">
  <section class="hero hero-profile">
    <div class="hero__container">
      <!-- Left Section: Avatar and Info -->
      <div class="hero__avatar-section">
        <figure class="hero__avatar">
          <img src="./assets/images/profile7.png" alt="Biniyam Belayneh" width="300">
        </figure>
        <div class="hero__info">
          <h1 class="hero__name">Biniyam Belayneh</h1>
          <p class="hero__title">ML and MLOps Engineer</p>
        </div>
      </div>

      <!-- Right Section: Description and Social Links -->
      <div class="hero__content">
        <p class="hero__description">
          Experienced ML Engineer specializing in developing and deploying machine learning solutions. 
          Strong background in data analysis and model optimization. 
        </p>
        <div class="hero__social-links">
          <a href="mailto:biniyambelayneh376@gmail.com" class="hero__icon">
            <ion-icon name="mail-outline"></ion-icon>
            <span>Email</span>
          </a>
          <a href="tel:+251939890540" class="hero__icon">
            <ion-icon name="phone-portrait-outline"></ion-icon>
            <span>Phone</span>
          </a>
          <a href="https://www.linkedin.com/in/biniyam-belayneh-demisse-42909617a" class="hero__icon" target="_blank">
            <ion-icon name="logo-linkedin"></ion-icon>
            <span>LinkedIn</span>
          </a>
          <a href="https://github.com/benbel376" class="hero__icon" target="_blank">
            <ion-icon name="logo-github"></ion-icon>
            <span>GitHub</span>
          </a>
        </div>
      </div>
    </div>
  </section>
</div>
  <!-- Summary Section -->
  <div class="section-header collapse-trigger" data-target="summary-content">
    <h3 class="h3">Summary</h3>
    <hr class="section-divider" />
  </div>
  <div class="collapse-content" id="summary-content">
    <section class="about-text">
      <p>
        As the AI and MLOps team lead at Adludio, I spearheaded the development of end-to-end machine learning systems,
        automating data processing, model training, and deployment pipelines. I bring expertise in cloud computing, CI/CD,
        and scalable architectures, ensuring robust, versioned models that drive reliable production.
      </p>
      <p>
        Additionally, I design streamlined UI systems for monitoring real-time performance, security, and cost metrics,
        empowering teams with actionable insights. From development to deployment, I focus on delivering machine learning
        solutions that are efficient, secure, and scale-ready.
      </p>
    </section>

    <!--
      - service
    -->
    <section class="service">
      <h4 class="h4 service-title">What I Do</h4>

      <ul class="service-list">
        <li class="service-item">
          <div class="service-icon-box">
            <img src="./assets/images/icon-design.svg" alt="design icon" width="40">
          </div>
          <div class="service-content-box">
            <h4 class="h4 service-item-title">End-to-End Machine Learning Pipelines</h4>
          </div>
        </li>

        <li class="service-item">
          <div class="service-icon-box">
            <img src="./assets/images/icon-dev.svg" alt="Cloud Infrastructure Icon" width="40">
          </div>
          <div class="service-content-box">
            <h4 class="h4 service-item-title">Cloud Infrastructure Design for AI systems</h4>
          </div>
        </li>

        <li class="service-item">
          <div class="service-icon-box">
            <img src="./assets/images/icon-app.svg" alt="Monitoring Dashboard Icon" width="40">
          </div>
          <div class="service-content-box">
            <h4 class="h4 service-item-title">AI System Monitoring & Optimization</h4>
          </div>
        </li>

        <li class="service-item">
          <div class="service-icon-box">
            <img src="./assets/images/icon-photo.svg" alt="CI/CD Icon" width="40">
          </div>
          <div class="service-content-box">
            <h4 class="h4 service-item-title">CI/CD for Scalable AI Solutions</h4>
          </div>
        </li>
      </ul>
    </section>

    <p style="color: white"> Here are the diagrams for Some of the Systems I can build for you!</p>

    <!-- Slideshow Section -->
    <div class="detail-section slideshow-section">
      <!-- Slideshow Outer Container -->
      <div class="slideshow-outer-container">
        <!-- Slideshow Section -->
        <div class="slideshow-container" data-slideshow>
          <div class="slideshow-slide active-dot">
            <a href="#"><img src="./assets/images/ml-pipeline2.png" alt="Screenshot 1"></a>
            <p class="slide-description">Machine learning pipeline.</p>
          </div>

          <div class="slideshow-slide">
            <img src="./assets/images/mlops2.png" alt="Screenshot 2">
            <p class="slide-description">Machine Learning Operations</p>
          </div>

          <div class="slideshow-slide">
            <img src="./assets/images/data-processing.jpg" alt="Schematic 1">
            <p class="slide-description">Data Processing System</p>
          </div>

          <!-- Navigation arrows -->
          <button class="prev" data-prev>&#10094;</button>
          <button class="next" data-next>&#10095;</button>

          <!-- Dot indicators -->
          <div class="dots-container" data-dots>
            <span class="dot active-dot" data-dot></span>
            <span class="dot" data-dot></span>
            <span class="dot" data-dot></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Experience Section -->
  <div class="section-header collapse-trigger" data-target="experience-content">
    <h3 class="h3">Experience</h3>
    <hr class="section-divider" />
  </div>
  <div class="collapse-content" id="experience-content">
    <section class="experience">
        <div class="experience-header">
            <div class="icon-box">
                <ion-icon name="business-outline"></ion-icon>
            </div>
            <h3 class="h3">Professional Experience</h3>
        </div>
        <ol class="experience-list">
            <li class="experience-item">
                <h4 class="h4 experience-item-title">ML and MLOps Engineer (2 years)</h4>
                <h5 class="h5">At Adludio</h5>
                <span>2022 - 2024</span>
                <ul class="experience-text">
                    <li>
                        Led the AI and MLOps team, overseeing infrastructure optimization and machine learning deployment that
                        resulted in a 50% reduction in infrastructure costs through the strategic use of serverless options and the
                        decommissioning of non-essential services.
                    </li>
                    <li>
                        Architected and implemented end-to-end machine learning pipelines for all major AI projects, using tools
                        such as MLflow, AWS SageMaker, and other cloud-native services to ensure efficient and scalable production
                        deployment.
                    </li>
                    <li>
                        worked on the bid optimization project, which led to a 15% improvement in KPI returns by refining the
                        bidding algorithms to maximize ad performance while optimizing costs.
                    </li>
                    <li>
                        worked on the Lorenzo project, a machine learning initiative focused on campaign insights, where I handled
                        end-to-end MLOps responsibilities, adding substantial value to Adludio's ad tech solutions.
                    </li>
                    <li>
                        Developed a predictive model for campaign performance, utilizing campaign attributes such as time of day,
                        day of the week, and month to identify high-performance windows. This model optimized scheduling and
                        maximized KPI returns by aligning ad runs with optimal times.
                    </li>
                    <li>
                        Made a significant contribution to the bid inventory recommendation system, which enhanced inventory
                        targeting and improved ad spend efficiency, helping Adludio make more data-driven decisions in inventory
                        management.
                    </li>
                    <li>
                        Participated in the development of an LLM-based brief information collection system, contributing to the
                        efficient gathering of campaign briefs for ad creation, thus streamlining the campaign setup process.
                    </li>
                    <li>
                        Managed the company’s cloud infrastructure, ensuring secure, scalable, and cost-effective operations. My
                        cost-reduction strategies included extensive use of AWS serverless options, resulting in a substantial cost
                        savings of 50%.
                    </li>
                    <li>
                        Built and maintained fully automated CI/CD pipelines with Jenkins and AWS CodePipeline, significantly
                        reducing manual effort and improving deployment efficiency across multiple environments.
                    </li>
                    <li>
                        Designed a secure cloud infrastructure that successfully passed third-party security audits, implementing
                        best practices across AWS services to ensure data protection and compliance.
                    </li>
                </ul>
            </li>

            <li class="experience-item">
                <h4 class="h4 experience-item-title">Data Analyst (1 year)</h4>
                <h5 class="h5">At Ethio Telecom</h5>
                <span>2021 – 2022</span>
                <ul class="experience-text">
                    <li>
                        Analyzed network and customer data from MSAG and MSAN boxes, using BigQuery for data processing and Google
                        Data Studio for visualizing insights to aid decision-making.
                    </li>
                    <li>
                        Developed a predictive maintenance model for network equipment using Random Forest classification, resulting
                        in a 9.5% reduction in downtime within a selected test region.
                    </li>
                    <li>
                        Improved data quality through comprehensive cleaning processes, which enhanced the accuracy of network
                        analytics and enabled more effective targeted maintenance strategies.
                    </li>
                    <li>
                        Analyzed network and customer data from MSAG and MSAN boxes, using BigQuery for data processing and Google
                        Data Studio for visualizing insights to aid decision-making.
                    </li>
                    <li>
                        Developed a predictive maintenance model for network equipment using Random Forest classification, resulting
                        in a 9.5% reduction in downtime within a selected test region.
                    </li>
                    <li>
                        Improved data quality through comprehensive cleaning processes, which enhanced the accuracy of network
                        analytics and enabled more effective targeted maintenance strategies.
                    </li>
                    <li>
                        Analyzed network and customer data from MSAG and MSAN boxes, using BigQuery for data processing and Google
                        Data Studio for visualizing insights to aid decision-making.
                    </li>
                    <li>
                        Developed a predictive maintenance model for network equipment using Random Forest classification, resulting
                        in a 9.5% reduction in downtime within a selected test region.
                    </li>
                    <li>
                        Improved data quality through comprehensive cleaning processes, which enhanced the accuracy of network
                        analytics and enabled more effective targeted maintenance strategies.
                    </li>
                </ul>
            </li>
        </ol>
    </section>
  </div>

  <!-- Education Section -->
  <div class="section-header collapse-trigger" data-target="education-content">
    <h3 class="h3">Education</h3>
    <hr class="section-divider" />
  </div>
  <div class="collapse-content" id="education-content">
    <section class="education">
        <div class="education-header">
            <div class="icon-box">
                <ion-icon name="school-outline"></ion-icon>
            </div>
            <h3 class="h3">Academic Background</h3>
        </div>
        <ol class="education-list">
            <li class="education-item">
                <h4 class="h4 education-item-title">ML and Data Engineering Program</h4>
                <h5 class="h5">10 Academy</h5>
                <span>2021 — 2022</span>
                <p class="education-text">
                    Developed expertise in end-to-end machine learning workflows, from data wrangling to model deployment.
                    Hands-on with ML frameworks like TensorFlow, PyTorch, and lifecycle management tools like MLflow, bridging the
                    gap between data and impactful machine learning solutions.
                </p>
            </li>

            <li class="education-item">
                <h4 class="h4 education-item-title">Cloud Solutions Architecture</h4>
                <h5 class="h5">AWS Skill Builder</h5>
                <span>2021 — 2023</span>
                <p class="education-text">
                    Certified in AWS, with deep skills in designing scalable, secure cloud architectures. Experienced in
                    optimizing resources across services like EC2, S3, Lambda, and RDS to power resilient systems on the cloud.
                </p>
            </li>

            <li class="education-item">
                <h4 class="h4 education-item-title">Software Engineering Intensive</h4>
                <h5 class="h5">ALX Africa</h5>
                <span>2021 — 2022</span>
                <p class="education-text">
                    Solidified foundational software engineering principles, focusing on efficient, high-quality code in both
                    front and backend development, with a focus on problem-solving and system design.
                </p>
            </li>

            <li class="education-item">
                <h4 class="h4 education-item-title">Bachelor's in Electronic Communication Engineering</h4>
                <h5 class="h5">Addis Ababa Science and Technology University</h5>
                <span>2015 — 2020</span>
                <p class="education-text">
                    Built a foundation in systems architecture, networking, and programming. Gained practical experience in
                    designing robust, scalable systems across various architectures like microservices and distributed networks.
                </p>
            </li>
        </ol>
    </section>
  </div>

  <!-- Skills Section -->
  <div class="section-header collapse-trigger" data-target="skills-content">
    <h3 class="h3">Skills</h3>
    <hr class="section-divider" />
  </div>
  <div class="collapse-content" id="skills-content">
    <section>
      <!-- Wrapper for Filters and DataTables Controls -->
      <div style="display: hidden" class="controls-wrapper">
        <!-- Custom Category Filter -->
        <div class="custom-filter">
          <label for="categoryFilter">Category:</label>
          <select id="categoryFilter">
            <option value="">All</option>
            <option value="AWS">AWS</option>
            <option value="GCP">GCP</option>
            <option value="DevOps">DevOps</option>
            <option value="MLOps">MLOps</option>
            <option value="Machine Learning">Machine Learning</option>
            <option value="Data Processing">Data Processing</option>
            <option value="Data Visualization">Data Visualization</option>
            <option value="NLP">NLP</option>
            <option value="Backend">Backend</option>
            <option value="Scripting">Scripting</option>
          </select>
        </div>

        <!-- DataTables Search and Length Controls -->
        <div class="dataTables-controls">
          <div class="dataTables_filter">
            <label>Search:<input type="search" id="customSearchBox" placeholder="" aria-controls="toolsTable"></label>
          </div>
          <div class="dataTables_length">
            <label>Show
              <select name="toolsTable_length" id="customLengthSelect" aria-controls="toolsTable">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select> entries
            </label>
          </div>
        </div>
      </div>

      <div class="table-container">
        <table id="toolsTable" class="display has-scrollbar">
          <thead>
            <tr>
              <th>Tools</th>
              <th>Group</th>
              <th>Details</th>
            </tr>
          </thead>

          <tbody>
            <!-- MLOps Tools -->
            <tr>
              <td>
                <div class="tool-cell">
                  <span class="tool-icon"><ion-icon name="analytics-outline"></ion-icon></span>
                  <span class="tool-name">MLflow</span>
                </div>
              </td>
              <td>MLOps</td>
              <td class="details-cell">
                <span class="details-snippet">Tracked experiments with MLflow...</span>
                <span class="details-full hidden">Tracked machine learning experiments with MLflow, managing model
                  versions and documenting metrics for reproducibility.</span>
                <a href="#" class="toggle-details">more</a>
              </td>
            </tr>
            <tr>
              <td>
                <div class="tool-cell">
                  <span class="tool-icon"><ion-icon name="construct-outline"></ion-icon></span>
                  <span class="tool-name">Kubeflow</span>
                </div>
              </td>
              <td>MLOps</td>
              <td class="details-cell">
                <span class="details-snippet">Deployed ML workflows with Kubeflow...</span>
                <span class="details-full hidden">Deployed machine learning workflows with Kubeflow on Kubernetes,
                  managing end-to-end training and deployment pipelines.</span>
                <a href="#" class="toggle-details">more</a>
              </td>
            </tr>
            <tr>
              <td>
                <div class="tool-cell">
                  <span class="tool-icon"><ion-icon name="analytics-outline"></ion-icon></span>
                  <span class="tool-name">Airflow</span>
                </div>
              </td>
              <td>MLOps</td>
              <td class="details-cell">
                <span class="details-snippet">Orchestrated workflows with Apache Airflow...</span>
                <span class="details-full hidden">Orchestrated complex ETL workflows with Apache Airflow, managing
                  dependencies and automating data pipelines.</span>
                <a href="#" class="toggle-details">more</a>
              </td>
            </tr>

            <!-- Machine Learning Tools -->
            <tr>
              <td>
                <div class="tool-cell">
                  <span class="tool-icon"><ion-icon name="construct-outline"></ion-icon></span>
                  <span class="tool-name">TensorFlow</span>
                </div>
              </td>
              <td>Machine Learning</td>
              <td class="details-cell">
                <span class="details-snippet">Developed deep learning models...</span>
                <span class="details-full hidden">Developed deep learning models using TensorFlow, implementing custom
                  neural networks for predictive analytics and NLP.</span>
                <a href="#" class="toggle-details">more</a>
              </td>
            </tr>
            <tr>
              <td>
                <div class="tool-cell">
                  <span class="tool-icon"><ion-icon name="analytics-outline"></ion-icon></span>
                  <span class="tool-name">PyTorch</span>
                </div>
              </td>
              <td>Machine Learning</td>
              <td class="details-cell">
                <span class="details-snippet">Built models with PyTorch for flexibility...</span>
                <span class="details-full hidden">Built models with PyTorch for flexibility in deep learning
                  experimentation, focusing on image processing and NLP applications.</span>
                <a href="#" class="toggle-details">more</a>
              </td>
            </tr>
            <tr>
              <td>
                <div class="tool-cell">
                  <span class="tool-icon"><ion-icon name="bar-chart-outline"></ion-icon></span>
                  <span class="tool-name">scikit-learn</span>
                </div>
              </td>
              <td>Machine Learninghh</td>
              <td class="details-cell">
                <span class="details-snippet">Implemented classical ML algorithms...</span>
                <span class="details-full hidden">Implemented classical machine learning algorithms with scikit-learn,
                  focusing on model evaluation, tuning, and feature engineering.</span>
                <a href="#" class="toggle-details">more</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <!-- Certifications Section -->
  <div class="section-header collapse-trigger" data-target="certifications-content">
    <h3 class="h3">Certifications</h3>
    <hr class="section-divider" />
  </div>
  <div class="collapse-content" id="certifications-content">
    <div class="certifications-wrapper">
      <section class="certifications">
        <div class="certifications-slider" data-certifications-slider>
          <!-- AWS Cloud Practitioner -->
          <div class="certification-slide">
            <div class="certification-card">
              <div class="certification-image">
                <img src="./assets/images/aws2.png" alt="AWS Certification Logo">
              </div>
              <div class="certification-details">
                <h4>AWS Certified Cloud Practitioner</h4>
                <p class="issuer">Amazon Web Services</p>
                <p class="description">
                  Validates overall understanding of the AWS Cloud, focusing on cloud concepts, security, technology, 
                  and billing and pricing. Demonstrates knowledge of AWS services and their common use cases.
                </p>
                <a href="https://www.credly.com/badges/c0521912-08a8-4575-8521-6b2e92c9c486/public_url" 
                   target="_blank" 
                   class="view-credential-btn">
                  View Credential
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </div>
            </div>
          </div>

          <!-- Azure Fundamentals -->
          <div class="certification-slide">
            <div class="certification-card">
              <div class="certification-image">
                <img src="./assets/images/azure.png" alt="Azure Certification Logo">
              </div>
              <div class="certification-details">
                <h4>Microsoft Azure Fundamentals</h4>
                <p class="issuer">Microsoft</p>
                <p class="description">
                  Demonstrates foundational knowledge of cloud services and how those services are provided with Microsoft Azure.
                  Covers core Azure concepts, core Azure services, Azure pricing, SLA, and lifecycle.
                </p>
                <a href="https://www.credly.com/badges/c7b8e13a-ed22-4263-bab7-4c3dd27f50d9/public_url" 
                   target="_blank" 
                   class="view-credential-btn">
                  View Credential
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </div>
            </div>
          </div>

          <!-- AWS Developer -->
          <div class="certification-slide">
            <div class="certification-card">
              <div class="certification-image">
                <img src="./assets/images/aws1.png" alt="AWS Developer Certification Logo">
              </div>
              <div class="certification-details">
                <h4>AWS Certified Developer</h4>
                <p class="issuer">Amazon Web Services</p>
                <p class="description">
                  Advanced certification validating expertise in developing and maintaining AWS-based applications.
                  Demonstrates proficiency in writing code for AWS applications and AWS-focused development.
                </p>
                <a href="https://www.credly.com/badges/34ad8adb-abcc-4cd6-8ca2-c7d395869eb3/public_url" 
                   target="_blank" 
                   class="view-credential-btn">
                  View Credential
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </div>
            </div>
          </div>

          <!-- Google Cloud -->
          <div class="certification-slide">
            <div class="certification-card">
              <div class="certification-image">
                <img src="./assets/images/google.png" alt="Google Cloud Certification Logo">
              </div>
              <div class="certification-details">
                <h4>Google Cloud Professional</h4>
                <p class="issuer">Google Cloud Platform</p>
                <p class="description">
                  Professional certification demonstrating expertise in building, deploying, and managing applications on Google Cloud Platform.
                  Covers compute, storage, networking, and machine learning services.
                </p>
                <a href="https://www.credly.com/badges/23596277-3129-441f-a177-1106b3554a93/public_url" 
                   target="_blank" 
                   class="view-credential-btn">
                  View Credential
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </div>
            </div>
          </div>

          <!-- Fortinet -->
          <div class="certification-slide">
            <div class="certification-card">
              <div class="certification-image">
                <img src="./assets/images/fortinet.png" alt="Fortinet Certification Logo">
              </div>
              <div class="certification-details">
                <h4>Network Security Expert</h4>
                <p class="issuer">Fortinet</p>
                <p class="description">
                  Advanced security certification validating expertise in network security management and operations.
                  Covers security fabric, network security, and secure access solutions.
                </p>
                <a href="https://www.credly.com/badges/23596277-3129-441f-a177-1106b3554a93/public_url" 
                   target="_blank" 
                   class="view-credential-btn">
                  View Credential
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </div>
            </div>
          </div>

          <!-- ISC2 -->
          <div class="certification-slide">
            <div class="certification-card">
              <div class="certification-image">
                <img src="./assets/images/ISC2.png" alt="CISSP Certification Logo">
              </div>
              <div class="certification-details">
                <h4>CISSP</h4>
                <p class="issuer">ISC2</p>
                <p class="description">
                  Elite security certification demonstrating expertise in cybersecurity, information assurance, and risk management.
                  Validates advanced knowledge across eight security domains.
                </p>
                <a href="https://www.credly.com/badges/9b19a9c0-73e5-4ac0-8c7b-b41a3368020f/public_url" 
                   target="_blank" 
                   class="view-credential-btn">
                  View Credential
                  <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="certification-controls">
          <button class="control-btn prev" data-certification-prev>
            <ion-icon name="chevron-back-outline"></ion-icon>
          </button>
          <div class="certification-indicators" data-certification-indicators></div>
          <button class="control-btn next" data-certification-next>
            <ion-icon name="chevron-forward-outline"></ion-icon>
          </button>
        </div>
      </section>
    </div>
  </div>



  <!--
          - testimonials
        -->
  <div class="section-header collapse-trigger" data-target="testimonials-content">
    <h3 class="h3">Testimonials</h3>
    <hr class="section-divider" />
  </div>
  <div class="collapse-content" id="testimonials-content">
  <section style="margin-top: 30px;" class="testimonials">


    <ul class="testimonials-list has-scrollbar">

      <li class="testimonials-item">
        <div class="content-card" data-testimonials-item>

          <figure class="testimonials-avatar-box">
            <img src="./assets/images/abeboss.png" alt="Ian Liddicoat" width="60" data-testimonials-avatar>
          </figure>

          <h4 class="h4 testimonials-item-title" data-testimonials-title>Ian Liddicoat</h4>

          <div class="testimonials-text" data-testimonials-text>
            <h3> CTO </h3>
            <p>
              As the CTO at Adludio, I had the pleasure of working with Biniyam,
              who played a pivotal role in transforming our machine learning operations.
              He built a seamless, end-to-end infrastructure that automated our ML model training and data processing
              workflows,
              drastically improving efficiency and scalability.
              his expertise in MLOps, cloud computing,
              and automation was instrumental in streamlining our processes and ensuring reliable, scalable deployments.
              Thanks to his work, we can now focus on innovation with the confidence that our infrastructure is robust
              and future-proof.
              <a href="https://www.linkedin.com/in/ian-liddicoat-986753a/">linkedin</a>
            </p>
          </div>

        </div>
      </li>

      <li class="testimonials-item">
        <div class="content-card" data-testimonials-item>

          <figure class="testimonials-avatar-box">
            <img src="./assets/images/abebauw.png" alt="Abebawu Eshetu" width="60" data-testimonials-avatar>
          </figure>

          <h4 class="h4 testimonials-item-title" data-testimonials-title>Abebawu Eshetu</h4>

          <div class="testimonials-text" data-testimonials-text>
            <h3> Chief Data Scientist </h3>
            <p>
              "As the chief data scientist at adludio, I worked with Biniyam closely.
              He was an invaluable asset to our data science team at Adludio.
              his deep understanding of the ML lifecycle, combined with his MLOps expertise,
              helped us seamlessly integrate our machine learning models into production.
              They automated critical data processing and model training workflows,
              which allowed our team to focus on refining models rather than managing infrastructure.
              his attention to detail, proactive problem-solving, and ability to build scalable,
              efficient systems greatly accelerated our machine learning initiatives.
              We couldn’t have achieved the level of success we did without his contributions."
              <a href="https://www.linkedin.com/in/abebawu-eshetu/">linkedin</a>
            </p>
          </div>

        </div>
      </li>

      <li class="testimonials-item">
        <div class="content-card" data-testimonials-item>

          <figure class="testimonials-avatar-box">
            <img src="./assets/images/yabibal.png" alt="Yabebal Fantaye" width="60" data-testimonials-avatar>
          </figure>

          <h4 class="h4 testimonials-item-title" data-testimonials-title> Yabebal Fantaye</h4>

          <div class="testimonials-text" data-testimonials-text>
            <h3> 10 Academy Co founder </h3>
            <p>
              I had the pleasure of both teaching and working alongside Biniyam during his time at 10 Academy,
              where they quickly distinguished themselves as an exceptional learner and practitioner in the field of ML
              engineering and MLOps.
              In our collaborative projects, [Your Name] demonstrated an impressive ability to build scalable machine
              learning pipelines,
              automate workflows, and solve complex problems with a hands-on, innovative approach. his strong technical
              skills,
              combined with a passion for continuous learning and practical application, made them a standout
              contributor to our projects.
              I have no doubt they will continue to excel in his career.
              <a href="https://www.linkedin.com/in/yabebal-fantaye/">linkedin</a>
            </p>
          </div>

        </div>
      </li>

      <li class="testimonials-item">
        <div class="content-card" data-testimonials-item>

          <figure class="testimonials-avatar-box">
            <img src="./assets/images/rahel.png" alt="Rahel Abreham" width="60" data-testimonials-avatar>
          </figure>

          <h4 class="h4 testimonials-item-title" data-testimonials-title>Rahel Abreham</h4>

          <div class="testimonials-text" data-testimonials-text>
            <h3> ML Engineer </h3>
            <p>
              I had the opportunity to work closely with Biniyam as an ML engineer,
              and his expertise in MLOps truly elevated our team's productivity.
              They were instrumental in automating many of our workflows, from data processing to model deployment,
              making the entire machine learning lifecycle more efficient and reliable.
              his ability to integrate ML models into scalable, cloud-based infrastructures allowed us to focus on
              improving
              model performance without worrying about operational hurdles.
              Biniyam is not only technically skilled but also a great collaborator, always willing to share knowledge
              and help the team succeed.
              <a href="https://www.linkedin.com/in/rahel-abreham-59a29a191/">linkedin</a>
            </p>
          </div>

        </div>
      </li>

    </ul>

  </section>
</div>
  <!--
          - testimonials modal
        -->

  <div class="modal-container" data-modal-container>

    <div class="overlay" data-overlay></div>

    <section class="testimonials-modal">

      <button class="modal-close-btn" data-modal-close-btn>
        <ion-icon name="close-outline"></ion-icon>
      </button>

      <div class="modal-img-wrapper">
        <figure class="modal-avatar-box">
          <img src="./assets/images/avatar-1.png" alt="Ian Liddicoat" width="80" data-modal-img>
        </figure>

        <img src="./assets/images/icon-quote.svg" alt="quote icon">
      </div>

      <div class="modal-content">

        <h4 class="h3 modal-title" data-modal-title>Ian Liddicoat</h4>

        <time datetime="2021-06-14">14 June, 2021</time>

        <div data-modal-text>
          <p>
            Richard was hired to create a corporate identity. We were very pleased with the work done. She has a
            lot of experience
            and is very concerned about the needs of client. Lorem ipsum dolor sit amet, ullamcous cididt
            consectetur adipiscing
            elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
          </p>
        </div>

      </div>

    </section>

  </div>

</article>