<div class="detail-unit details-unit" id="data-pipeline-details" hidden>
    <!-- Title Section -->
    <div class="detail-section">
        <h2 class="project-detail-title">Automated Data Pipeline for Multi-Database ETL</h2>
        <h3 class="project-detail-subtitle">Subtitle: Building an ETL Pipeline with Airflow, MySQL, PostgreSQL, and Redash for Data Analytics</h3>

        <p class="project-detail-description">
            This project builds an automated data pipeline to extract, transform, and load (ETL) data from various sources, including MySQL and PostgreSQL databases, and then visualize it with Redash and Apache Superset. Using Airflow as the orchestrator, the pipeline automates data workflows, integrates with multiple databases, and allows for streamlined data migrations and transformations. The project includes custom scripts for data extraction, transformations, and database migrations, all while leveraging Docker for a containerized, reproducible setup.
        </p>
        <p class="project-detail-date">Date: November 1, 2024</p>
    </div>

    <div class="detail-section requirements-section">
        <h3 class="detail-heading">Prerequisite</h3>
        <ul class="requirements-list">
            <li>Linux</li>
            <li>Python 3.8 or higher</li>
            <li>Docker</li>
            <li>Docker Compose</li>
            <li>Airflow</li>
            <li>MySQL & PostgreSQL access and setup</li>
            <li>Redash and Superset for data visualization</li>
        </ul>
    </div>

    <div id="folders">
        <div class="tree">
            <ul>
                <li>
                    <details>
                        <summary><i class="fa fa-folder"></i> Data Pipeline Project</summary>
                        <ul>
                            <li>
                                <details>
                                    <summary><i class="fa fa-folder"></i> data <span>- 50 MB</span></summary>
                                    <ul>
                                        <li><i class="fa fa-file"></i> data.csv <span>- Raw data files used for initial ETL processes.</span></li>
                                    </ul>
                                </details>
                            </li>
                            <li>
                                <details>
                                    <summary><i class="fa fa-folder"></i> notebooks <span>- 20 MB</span></summary>
                                    <ul>
                                        <li><i class="fab fa-python"></i> extract_and_load.ipynb <span>- Jupyter Notebook for initial data exploration and testing ETL steps.</span></li>
                                    </ul>
                                </details>
                            </li>
                            <li>
                                <details>
                                    <summary><i class="fa fa-folder"></i> scripts <span>- 200 KB</span></summary>
                                    <ul>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> python <span>- 150 KB</span></summary>
                                                <ul>
                                                    <li><i class="fab fa-python"></i> Extractor.py <span>- Script for extracting data from CSV files.</span></li>
                                                    <li><i class="fab fa-python"></i> Loader.py <span>- Script to load data into MySQL and PostgreSQL databases.</span></li>
                                                    <li><i class="fab fa-python"></i> redash_migrator.py <span>- Script to migrate queries from Redash for easier data access.</span></li>
                                                </ul>
                                            </details>
                                        </li>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> sql <span>- 50 KB</span></summary>
                                                <ul>
                                                    <li><i class="fa fa-file"></i> create_source_table.sql <span>- SQL script to create source table in the database.</span></li>
                                                    <li><i class="fa fa-file"></i> init_mysql.sql <span>- SQL script to initialize MySQL database and setup users.</span></li>
                                                    <li><i class="fa fa-file"></i> postgres_init.sql <span>- SQL script to initialize PostgreSQL with required tables and users.</span></li>
                                                </ul>
                                            </details>
                                        </li>
                                    </ul>
                                </details>
                            </li>
                            <li>
                                <details>
                                    <summary><i class="fa fa-folder"></i> docker_ <span>- 1 MB</span></summary>
                                    <ul>
                                        <li><i class="fa fa-file"></i> .env <span>- Environment variables for configuring Redash and databases.</span></li>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> dockerfiles <span>- 300 KB</span></summary>
                                                <ul>
                                                    <li>
                                                        <details>
                                                            <summary><i class="fa fa-folder"></i> airflow <span>- 200 KB</span></summary>
                                                            <ul>
                                                                <li><i class="fa fa-file"></i> Dockerfile <span>- Dockerfile to build Airflow container.</span></li>
                                                                <li><i class="fa fa-file"></i> requirements.txt <span>- Airflow dependencies list.</span></li>
                                                            </ul>
                                                        </details>
                                                    </li>
                                                    <li>
                                                        <details>
                                                            <summary><i class="fa fa-folder"></i> postgres <span>- 100 KB</span></summary>
                                                            <ul>
                                                                <li><i class="fa fa-file"></i> Dockerfile <span>- Dockerfile for PostgreSQL setup.</span></li>
                                                            </ul>
                                                        </details>
                                                    </li>
                                                </ul>
                                            </details>
                                        </li>
                                        <li><i class="fa fa-file"></i> docker-compose-airflow.yml <span>- Docker Compose file to setup Airflow services.</span></li>
                                        <li><i class="fa fa-file"></i> docker-compose-mysql.yml <span>- Docker Compose configuration for MySQL service.</span></li>
                                        <li><i class="fa fa-file"></i> docker-compose-postgres.yml <span>- Docker Compose configuration for PostgreSQL service.</span></li>
                                        <li><i class="fa fa-file"></i> docker-compose-redash.yml <span>- Docker Compose configuration for Redash service.</span></li>
                                        <li><i class="fa fa-file"></i> docker-compose-superset.yml <span>- Docker Compose configuration for Superset service.</span></li>
                                    </ul>
                                </details>
                            </li>
                            <li><i class="fa fa-file"></i> setup.sh <span>- Shell script to initialize the entire pipeline setup.</span></li>
                            <li><i class="fab fa-html5"></i> README.md <span>- Detailed project overview and setup instructions.</span></li>
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
            <li>Python</li>
            <li>Apache Airflow</li>
            <li>Docker</li>
            <li>MySQL</li>
            <li>PostgreSQL</li>
            <li>Redash</li>
            <li>Apache Superset</li>
            <li>SQL</li>
        </ul>
    </div>

    <div class="detail-section">
        <h3 class="detail-heading">Project Repository</h3>
        <p class="project-detail-description">
            You can view the complete code and project files on GitHub: 
            <a href="https://github.com/benbel376/ELT_Pipeline_Project_2/" target="_blank" class="github-link">Data Pipeline Project on GitHub</a>
        </p>
    </div>
</div>
