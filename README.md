# RAG Pipeline for Portfolio Data

This project is a **Retrieval-Augmented Generation (RAG) pipeline** that allows recruiters to ask questions about my portfolio and receive AI-generated responses. The system uses **FastAPI** as the API framework, **Elasticsearch** for data retrieval, and **Google Generative AI** for response generation. This setup creates a conversational interface to explore my skills, experience, and projects in a meaningful way.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Folder Structure](#folder-structure)
- [Technologies Used](#technologies-used)
- [Prerequisites](#prerequisites)
- [Getting Started](#getting-started)
- [Usage](#usage)
- [Future Improvements](#future-improvements)

## Overview

This RAG pipeline project is designed to showcase my portfolio data interactively for recruiters. By leveraging Elasticsearch for retrieving relevant documents and Google Generative AI for response generation, recruiters can query my portfolio and get quick, accurate insights based on indexed data.

## Features

- **FastAPI API**: Provides an endpoint for querying portfolio information.
- **Elasticsearch**: Handles document storage and retrieval, combining vector and BM25 retrieval methods.
- **Google Generative AI**: Generates context-aware responses based on user queries and retrieved documents.
- **Ensemble Retriever**: Combines retrieval techniques for better response accuracy.
- **SSL with NGINX**: Provides a secure API interface.

## Folder Structure

src/ ├── app/ │ └── bot/ │ ├── Dockerfile │ ├── init.py │ ├── app.py │ ├── requirements.txt │ ├── run-app.sh ├── infra/ │ ├── docker/ │ │ └── setup_docker.sh │ ├── elasticsearch/ │ │ └── setup_es.sh └── README.md


## Technologies Used

- **FastAPI**: For building and serving the API.
- **Elasticsearch**: For document storage and retrieval.
- **Google Generative AI**: For generating AI-driven responses.
- **LangChain**: For managing retrieval and compression techniques.
- **Docker**: For containerizing the application.
- **NGINX and Certbot**: For reverse proxy and SSL configuration.

## Prerequisites

- Linux
- Python 3.8 or higher
- Docker and Docker Compose
- Google Generative AI API access
- Elasticsearch setup and credentials

## Getting Started

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-username/rag-pipeline-portfolio.git
   cd rag-pipeline-portfolio
   ```
2. **Set Up the Environment: Install Docker and run the setup scripts in the infra folder:**

  ```
  bash infra/docker/setup_docker.sh
  bash infra/elasticsearch/setup_es.sh
  ```
3. **Build and Run the FastAPI App:**
  ```
  cd src/app/bot
  bash run-app.sh
  ```

## Usage
Send a POST request to the /query/ endpoint with a JSON payload containing your query. Here’s an example of the request format:
```
{
  "query": "What is your experience in machine learning?",
  "top_k": 3
}

```
The response will contain an AI-generated answer based on the retrieved portfolio data, providing insights related to the query.

## Future Improvements
- Enhanced Data Sources: Integrate additional data sources, such as projects, certifications, and blog posts, to provide more comprehensive answers.
- Advanced Query Processing: Implement natural language understanding (NLU) techniques for better parsing of complex queries.
- Expanded Metrics: Add detailed portfolio metrics and achievements to enrich responses.
- Machine Learning Model Integration: Incorporate ML models to refine and personalize responses based on user query patterns.
## License
This project is licensed under the MIT License.
