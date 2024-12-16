<div class="detail-unit details-unit" id="blood-vessel-segmentation-details" hidden>
    <!-- Title Section -->
    <div class="detail-section">
        <h2 class="project-detail-title">Retina Blood Vessel Segmentation</h2>
        <h3 class="project-detail-subtitle">Subtitle: Deep Learning Model for Segmenting Blood Vessels in Retina Images</h3>

        <p class="project-detail-description">
            This project implements a deep learning-based U-Net model for segmenting blood vessels in retina images, a critical step in diagnosing and monitoring various eye-related diseases, including diabetic retinopathy. The project uses a custom U-Net model architecture with FastAPI for serving predictions. The application is containerized using Docker, with NGINX configured as a reverse proxy for efficient deployment.
        </p>
        <p class="project-detail-date">Date: November 1, 2024</p>
    </div>

    <div class="detail-section requirements-section">
        <h3 class="detail-heading">Prerequisite</h3>
        <ul class="requirements-list">
            <li>Linux</li>
            <li>Python 3.8 or higher</li>
            <li>CUDA-enabled GPU (optional for faster training)</li>
            <li>Docker</li>
            <li>Docker Compose</li>
            <li>FastAPI</li>
            <li>NGINX</li>
            <li>PyTorch</li>
        </ul>
    </div>

    <div id="folders">
        <div class="tree">
            <ul>
                <li>
                    <details>
                        <summary><i class="fa fa-folder"></i> Retina Blood Vessel Segmentation Project</summary>
                        <ul>
                            <li>
                                <details>
                                    <summary><i class="fa fa-folder"></i> src <span>- 1 MB</span></summary>
                                    <ul>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> app <span>- 800 KB</span></summary>
                                                <ul>
                                                    <li><i class="fab fa-python"></i> app.py <span>- FastAPI application to serve model predictions.</span></li>
                                                    <li><i class="fab fa-python"></i> dataset.py <span>- Defines the dataset class for loading images and masks.</span></li>
                                                    <li><i class="fab fa-python"></i> model.py <span>- Implements the U-Net model architecture for segmentation.</span></li>
                                                    <li><i class="fab fa-python"></i> losses.py <span>- Custom loss functions for model training.</span></li>
                                                    <li><i class="fab fa-python"></i> train.py <span>- Script to train the U-Net model on the dataset.</span></li>
                                                    <li><i class="fab fa-python"></i> utils.py <span>- Contains utility functions for preprocessing and metrics.</span></li>
                                                    <li><i class="fab fa-python"></i> main.py <span>- Main script to execute model training or prediction.</span></li>
                                                    <li><i class="fa fa-file"></i> requirements.txt <span>- List of Python dependencies required for the project.</span></li>
                                                    <li><i class="fa fa-file"></i> Dockerfile <span>- Docker configuration to containerize the application.</span></li>
                                                </ul>
                                            </details>
                                        </li>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> infra <span>- 200 KB</span></summary>
                                                <ul>
                                                    <li><i class="fa fa-file"></i> docker-compose.yml <span>- Docker Compose configuration to manage multi-container setup.</span></li>
                                                    <li><i class="fa fa-file"></i> nginx.conf <span>- NGINX configuration file to handle reverse proxy setup.</span></li>
                                                    <li><i class="fa fa-file"></i> run.sh <span>- Script for deployment and starting services.</span></li>
                                                    <li><i class="fab fa-html5"></i> README.md <span>- Project overview and setup instructions.</span></li>
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
            <li>Python</li>
            <li>FastAPI</li>
            <li>Docker</li>
            <li>NGINX</li>
            <li>PyTorch</li>
            <li>CUDA (for GPU training)</li>
            <li>Certbot for SSL</li>
        </ul>
    </div>

    <div class="detail-section">
        <h3 class="detail-heading">Project Repository</h3>
        <p class="project-detail-description">
            You can view the complete code and project files on GitHub: 
            <a href="https://github.com/your-username/retina-blood-vessel-segmentation" target="_blank" class="github-link">Retina Blood Vessel Segmentation Project on GitHub</a>
        </p>
    </div>
</div>
