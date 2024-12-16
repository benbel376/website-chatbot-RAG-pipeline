<div class="detail-unit details-unit" id="rag-pipeline-details" hidden>
      <div class="blog-details-container">

        <!-- Blog Content Section with Pagination -->
        <section class="blog-details-content">
        <div class="blog-page active" id="page-1">

<h2 class="project-detail-title">RAG Pipeline for Portfolio Data</h2>

<h3 class="project-detail-subtitle">Subtitle: A Retrieval-Augmented Generation (RAG) Pipeline for Recruiter-Friendly Portfolio Querying</h3>

<div class="blog-details-banner">
    <img src="./assets/images/rag.webp" alt="Blog Banner">
</div>

<h3 class="blog-post-mid-title"> Introduction</h3>
<p class="plog-post-body">
    In today's competitive job market, it's essential for recruiters to quickly and efficiently access relevant information about potential candidates. My Retrieval-Augmented Generation (RAG) pipeline project enables recruiters to ask questions about my portfolio, such as my skills, experience, and previous projects, and receive immediate, AI-generated responses. This blog post will walk through the project's purpose, architecture, and implementation, showcasing the power of combining FastAPI, Elasticsearch, and Google Generative AI to provide insightful, conversational interactions with my portfolio data.
    <br><br>
    We'll cover the main components of the project, including setting up the API, configuring Elasticsearch for data retrieval, and using Google Generative AI to generate responses. Each section will include code snippets to help you understand the inner workings of the RAG pipeline.
</p>

<h3 class="blog-post-mid-title"> 1. Setting Up the FastAPI Application</h3>
<p class="plog-post-body">
    The RAG pipeline begins with a FastAPI application that serves as the entry point for user queries. FastAPI is lightweight, fast, and ideal for building web APIs. The following code initializes the FastAPI app, adds middleware for CORS, and sets up basic API configuration.
</p>

<div class="code-display-wrapper" id="custom-code-display">
    <div class="code-display-header">
        <span class="code-display-filename">python</span>
        <span class="code-display-actions">
            <button class="code-display-copy">Copy</button>
        </span>
    </div>
    <div class="code-display-body">
        <div class="code-line-numbers"></div>
        <pre><code id="code-content">
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()
app.add_middleware(
CORSMiddleware,
allow_origins=["*"],
allow_credentials=True,
allow_methods=["*"],
allow_headers=["*"],
)
        </code></pre>
    </div>
</div>

<p class="plog-post-body">
    The CORS middleware allows the app to be accessed from any origin, which is helpful when deploying the API to a server. Next, we set up the route for handling queries.
</p>

<h3 class="blog-post-mid-title"> 2. Connecting to Elasticsearch for Data Retrieval</h3>
<p class="plog-post-body">
    This project uses Elasticsearch to store and retrieve portfolio data. We set up an Elasticsearch client and define two retrievers: one using vector-based similarity search and another using BM25 for text-based search. These are combined into an ensemble retriever using Reciprocal Rank Fusion (RRF) to enhance retrieval effectiveness.
</p>

<div class="code-display-wrapper" id="custom-code-display">
    <div class="code-display-header">
        <span class="code-display-filename">python</span>
        <span class="code-display-actions">
            <button class="code-display-copy">Copy</button>
        </span>
    </div>
    <div class="code-display-body">
        <div class="code-line-numbers"></div>
        <pre><code id="code-content">
from elasticsearch import Elasticsearch
from langchain_elasticsearch import ElasticsearchRetriever
from langchain.retrievers.ensemble import EnsembleRetriever

es_client = Elasticsearch("https://ex.biniyambelayneh.com", basic_auth=("elastic", "elasticsearchpass"))

vector_retriever = ElasticsearchRetriever(
index_name="portfolio",
body_func=lambda query: {"knn": {"field": "embeddings", "query_vector": embeddings.embed_query(query), "k": 5, "num_candidates": 10}},
content_field="content",
es_client=es_client,
)

bm25_retriever = ElasticsearchRetriever(
es_client=es_client,
index_name="portfolio",
body_func=lambda query: {"query": {"match": {"content": query}}},
content_field="content"
)

hybrid_retriever = EnsembleRetriever(retrievers=[bm25_retriever, vector_retriever], mode="rrf")
        </code></pre>
    </div>
</div>

<p class="plog-post-body">
    Here, we configure two retrieval methods: vector-based and BM25-based. The ensemble retriever combines their results, providing more accurate and relevant responses to recruiter queries.
</p>

<h3 class="blog-post-mid-title"> 3. Integrating Google Generative AI for Response Generation</h3>
<p class="plog-post-body">
    After retrieving relevant documents, the RAG pipeline uses Google Generative AI to generate human-like responses. We configure the API key and create a function to structure the RAG prompt. This prompt integrates the user query with retrieved passages to generate a coherent answer.
</p>

<div class="code-display-wrapper" id="custom-code-display">
    <div class="code-display-header">
        <span class="code-display-filename">python</span>
        <span class="code-display-actions">
            <button class="code-display-copy">Copy</button>
        </span>
    </div>
    <div class="code-display-body">
        <div class="code-line-numbers"></div>
        <pre><code id="code-content">
import google.generativeai as genai

genai.configure(api_key="YOUR_GOOGLE_API_KEY")

def make_rag_prompt(query, relevant_passage):
prompt = (
    f"You are a helpful chatbot that answers questions using the following passage:\n\n"
    f"QUESTION: '{query}'\n"
    f"PASSAGE: '{relevant_passage}'\n\n"
    f"ANSWER:"
)
return prompt

def generate_response(user_prompt):
model = genai.GenerativeModel("gemini-1.5-flash")
answer = model.generate_content(user_prompt)
return answer.text
        </code></pre>
    </div>
</div>

<p class="plog-post-body">
    This code configures Google Generative AI and structures the prompt used to generate responses. The function <code>generate_response()</code> sends the prompt to the AI model, which returns a coherent answer based on the query and retrieved content.
</p>

<h3 class="blog-post-mid-title"> 4. Endpoint for Handling User Queries</h3>
<p class="plog-post-body">
    Finally, we define the API endpoint that accepts a query from the user, retrieves relevant passages, creates a prompt, and generates a response. This endpoint is the core of our RAG pipeline.
</p>

<div class="code-display-wrapper" id="custom-code-display">
    <div class="code-display-header">
        <span class="code-display-filename">python</span>
        <span class="code-display-actions">
            <button class="code-display-copy">Copy</button>
        </span>
    </div>
    <div class="code-display-body">
        <div class="code-line-numbers"></div>
        <pre><code id="code-content">
from fastapi import HTTPException

@app.post("/query/")
async def query_endpoint(query_request: QueryRequest):
query = query_request.query
try:
    retrieved_docs = hybrid_retriever.get_relevant_documents(query)
    relevant_text = [doc.page_content for doc in retrieved_docs]
    prompt = make_rag_prompt(query, relevant_passage=relevant_text)
    answer = generate_response(prompt)
    return {"query": query, "answer": answer}
except Exception as e:
    raise HTTPException(status_code=500, detail=str(e))
        </code></pre>
    </div>
</div>

<p class="plog-post-body">
    This endpoint accepts a query, retrieves documents using the ensemble retriever, and generates a response using the Google Generative AI model. The <code>try-except</code> block ensures error handling, returning a 500 error in case of failures.
</p>

</div>



        </section>

        <!-- Blog Pagination Section -->
        <div class="blog-pagination-container">
          <button class="blog-prev-page" data-blog-prev-page disabled>Previous</button>
          <span class="blog-pagination-info">Page 1 of 3</span>
          <button class="blog-next-page" data-blog-next-page>Next</button>
        </div>

  </div>
</div>