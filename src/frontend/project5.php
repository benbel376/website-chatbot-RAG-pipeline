<div class="detail-unit details-unit" id="sales-prediction-details" hidden>
    <!-- Title Section -->
    <div class="detail-section">
        <h2 class="project-detail-title">Rossman Store Sales Forecasting</h2>
        <h3 class="project-detail-subtitle">Subtitle: Predictive Analytics on Store Sales Data Using Machine Learning</h3>

        <p class="project-detail-description">
            This project focuses on building a predictive model to forecast daily sales for Rossman stores using historical data. 
            The data includes various features such as store type, assortment level, promotions, and holiday information.
            The goal is to accurately predict the sales figures for each store based on historical trends and store-specific characteristics, 
            aiding in better inventory management, staffing, and promotion planning.
        </p>
        <p class="project-detail-date">Date: November 1, 2024</p>
    </div>

    <!-- Prerequisite Section -->
    <div class="detail-section requirements-section">
        <h3 class="detail-heading">Prerequisite</h3>
        <ul class="requirements-list">
            <li>Python 3.8 or higher</li>
            <li>Docker & Docker Compose</li>
            <li>Pandas, NumPy, Scikit-Learn, and XGBoost installed</li>
            <li>Basic understanding of machine learning and predictive analytics</li>
            <li>Git installed for version control</li>
        </ul>
    </div>

    <!-- Folder Structure Section -->
    <div id="folders">
        <div class="tree">
            <ul>
                <li>
                    <details>
                        <summary><i class="fa fa-folder"></i> Rossman Sales Forecasting Project</summary>
                        <ul>
                            <li>
                                <details>
                                    <summary><i class="fa fa-folder"></i> src <span>- 1.5 MB</span></summary>
                                    <ul>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> apps <span>- 1 MB</span></summary>
                                                <ul>
                                                    <li><i class="fab fa-python"></i> data_loader.py <span>- Loads and preprocesses sales data.</span></li>
                                                    <li><i class="fab fa-python"></i> eda.py <span>- Exploratory data analysis scripts.</span></li>
                                                    <li><i class="fab fa-python"></i> feature_engineering.py <span>- Generates new features from existing data.</span></li>
                                                    <li><i class="fab fa-python"></i> modeling.py <span>- Defines and trains machine learning models.</span></li>
                                                    <li><i class="fab fa-python"></i> predict.py <span>- Makes sales predictions for test data.</span></li>
                                                    <li><i class="fab fa-python"></i> evaluation.py <span>- Evaluates model performance.</span></li>
                                                    <li><i class="fab fa-python"></i> preprocessing.py <span>- Cleans and encodes data for modeling.</span></li>
                                                    <li><i class="fab fa-python"></i> main.py <span>- Main script to execute the end-to-end pipeline.</span></li>
                                                </ul>
                                            </details>
                                        </li>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> infra <span>- 500 KB</span></summary>
                                                <ul>
                                                    <li><i class="fab fa-docker"></i> Dockerfile <span>- Docker configuration for app containerization.</span></li>
                                                    <li><i class="fab fa-docker"></i> docker-compose.yml <span>- Orchestrates app and database containers.</span></li>
                                                    <li>
                                                        <details>
                                                            <summary><i class="fa fa-folder"></i> nginx <span>- 50 KB</span></summary>
                                                            <ul>
                                                                <li><i class="fa fa-file"></i> nginx.conf <span>- Configures Nginx as a reverse proxy.</span></li>
                                                            </ul>
                                                        </details>
                                                    </li>
                                                    <li><i class="fa fa-file"></i> setup_infrastructure.sh <span>- Installs Docker and Docker Compose on a new server.</span></li>
                                                    <li><i class="fa fa-file"></i> deploy.sh <span>- Deploys the application using Docker Compose.</span></li>
                                                </ul>
                                            </details>
                                        </li>
                                    </ul>
                                </details>
                            </li>
                        </ul>
                    </details>
                </li>
            </ul>
        </div>
    </div>

    <!-- Technologies Used Section -->
    <div class="detail-section">
        <h3 class="detail-heading">Technologies Used</h3>
        <ul class="technologies-list">
            <li>Python (Pandas, NumPy, Scikit-Learn, XGBoost)</li>
            <li>Docker & Docker Compose</li>
            <li>PostgreSQL (for storing processed data)</li>
            <li>Nginx (as a reverse proxy for deployment)</li>
            <li>Jupyter Notebooks (for interactive data analysis)</li>
            <li>Git (for version control)</li>
        </ul>
    </div>

    <!-- Project Repository Section -->
    <div class="detail-section">
        <h3 class="detail-heading">Project Repository</h3>
        <p class="project-detail-description">
            You can view the complete code and project files on GitHub: 
            <a href="https://github.com/your-username/rossman-sales-forecasting" target="_blank" class="github-link">Rossman Store Sales Forecasting on GitHub</a>
        </p>
    </div>
</div>
