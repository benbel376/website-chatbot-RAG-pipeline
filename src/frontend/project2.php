<div class="detail-unit details-unit" id="rag-pipeline-details" hidden>
    <!-- Title Section -->
    <div class="detail-section">
        <h2 class="project-detail-title">RAG Pipeline for Portfolio Data</h2>
        <h3 class="project-detail-subtitle">Subtitle: A Retrieval-Augmented Generation (RAG) Pipeline for Recruiter-Friendly Portfolio Querying</h3>

        <p class="project-detail-description">
            This project aims to build a Retrieval-Augmented Generation (RAG) pipeline that enables recruiters to ask questions about my portfolio in a seamless and interactive manner. The pipeline leverages FastAPI, Elasticsearch, and Google Generative AI to retrieve and generate responses, providing a comprehensive overview of my skills, experience, and projects based on indexed portfolio data. With this setup, recruiters can gain insights and access relevant information through a conversational interface.
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
            <li>FastAPI</li>
            <li>Elasticsearch setup and access</li>
            <li>Google Generative AI API access</li>
        </ul>
    </div>

    <div id="folders">
        <div class="tree">
            <ul>
                <li>
                    <details>
                        <summary><i class="fa fa-folder"></i> RAG Pipeline Project</summary>
                        <ul>
                            <li>
                                <details>
                                    <summary><i class="fa fa-folder"></i> src <span>- 1 MB</span></summary>
                                    <ul>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> app <span>- 800 KB</span></summary>
                                                <ul>
                                                    <li>
                                                        <details>
                                                            <summary><i class="fa fa-folder"></i> bot <span>- 750 KB</span></summary>
                                                            <ul>
                                                                <li><i class="fab fa-python"></i> app.py <span>- FastAPI app to handle portfolio queries.</span></li>
                                                                <li><i class="fab fa-python"></i> __init__.py <span>- Initializes the bot package.</span></li>
                                                                <li><i class="fa fa-file"></i> requirements.txt <span>- Lists all Python dependencies.</span></li>
                                                                <li><i class="fa fa-file"></i> Dockerfile <span>- Defines Docker setup for the FastAPI app.</span></li>
                                                                <li><i class="fa fa-file"></i> run-app.sh <span>- Script to deploy the FastAPI application.</span></li>
                                                            </ul>
                                                        </details>
                                                    </li>
                                                </ul>
                                            </details>
                                        </li>
                                        <li>
                                            <details>
                                                <summary><i class="fa fa-folder"></i> infra <span>- 200 KB</span></summary>
                                                <ul>
                                                    <li>
                                                        <details>
                                                            <summary><i class="fa fa-folder"></i> docker <span>- 50 KB</span></summary>
                                                            <ul>
                                                                <li><i class="fa fa-file"></i> setup_docker.sh <span>- Installs and sets up Docker on the server.</span></li>
                                                            </ul>
                                                        </details>
                                                    </li>
                                                    <li>
                                                        <details>
                                                            <summary><i class="fa fa-folder"></i> elasticsearch <span>- 100 KB</span></summary>
                                                            <ul>
                                                                <li><i class="fa fa-file"></i> setup_es.sh <span>- Sets up Elasticsearch for data indexing.</span></li>
                                                            </ul>
                                                        </details>
                                                    </li>
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
            <li>Elasticsearch</li>
            <li>Google Generative AI</li>
            <li>LangChain</li>
            <li>Pydantic</li>
            <li>Certbot for SSL</li>
        </ul>
    </div>

    <div class="detail-section">
        <h3 class="detail-heading">Project Repository</h3>
        <p class="project-detail-description">
            You can view the complete code and project files on GitHub: 
            <a href="https://github.com/benbel376/website-chatbot-RAG-pipeline" target="_blank" class="github-link">RAG Pipeline Portfolio Project on GitHub</a>
        </p>
    </div>
</div>
