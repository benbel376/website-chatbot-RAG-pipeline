from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import google.generativeai as genai
from langchain.retrievers.ensemble import EnsembleRetriever
from langchain.retrievers.document_compressors import LLMChainExtractor
from langchain.retrievers import ContextualCompressionRetriever
from langchain_google_genai import ChatGoogleGenerativeAI
from elasticsearch import Elasticsearch
from langchain_elasticsearch import ElasticsearchRetriever
from elasticsearch import Elasticsearch
from langchain_elasticsearch import ElasticsearchRetriever
from langchain_google_genai import GoogleGenerativeAIEmbeddings
from langchain.retrievers.document_compressors import LLMChainExtractor
from langchain.retrievers import ContextualCompressionRetriever
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain.retrievers.ensemble import EnsembleRetriever
import google.generativeai as genai
from fastapi.middleware.cors import CORSMiddleware

import logging

# FastAPI app
app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Adjust based on your needs
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


# Configure Google Generative AI (Gemini)
genai.configure(api_key="AIzaSyBX5W20EX_YX1koJs5v8BEaYT5DaPsu57E")

# Elasticsearch connection details
elasticsearch_url = "https://ex.biniyambelayneh.com"
es_username = "elastic"
es_password = "elasticsearchpass"
es_client = Elasticsearch(elasticsearch_url, basic_auth=(es_username, es_password))

# Define the retrievers (same as in the original code)
embeddings = GoogleGenerativeAIEmbeddings(model="models/embedding-001", google_api_key="AIzaSyBX5W20EX_YX1koJs5v8BEaYT5DaPsu57E")

# Define the vector and BM25 retrievers
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

# Combine the retrievers into an ensemble
hybrid_retriever = EnsembleRetriever(
    retrievers=[bm25_retriever, vector_retriever],
    mode="rrf"  # Reciprocal Rank Fusion (RRF)
)

# LLM for compression and final response generation
llm = ChatGoogleGenerativeAI(model="gemini-1.5-flash", temperature=0, api_key="AIzaSyBX5W20EX_YX1koJs5v8BEaYT5DaPsu57E")
compressor = LLMChainExtractor.from_llm(llm)
compression_retriever = ContextualCompressionRetriever(base_compressor=compressor, base_retriever=hybrid_retriever)


# Pydantic model for request validation
class QueryRequest(BaseModel):
    query: str
    top_k: int = 3


# Function to create the prompt for RAG (Retrieve and Generate)
def make_rag_prompt(query, relevant_passage):
    relevant_passage = ' '.join(relevant_passage)  # Combine all relevant passages into one text block
    prompt = (
        f"You are a helpful and informative chatbot that answers questions using text from the reference passage included below. "
        f"Respond in a complete sentence and make sure that your response is easy to understand for everyone. "
        f"Maintain a friendly and conversational tone. If the passage is irrelevant, feel free to ignore it.\n\n"
        f"QUESTION: '{query}'\n"
        f"PASSAGE: '{relevant_passage}'\n\n"
        f"ANSWER:"
    )
    return prompt


# Function to send the prompt to the Google Generative AI model (Gemini)
def generate_response(user_prompt):
    model = genai.GenerativeModel('gemini-1.5-flash')
    answer = model.generate_content(user_prompt)
    return answer.text


# Endpoint to handle user queries
@app.post("/query/")
async def query_endpoint(query_request: QueryRequest):
    query = query_request.query
    top_k = query_request.top_k

    # Use the compression retriever to get relevant documents
    try:
        retrieved_docs = hybrid_retriever.get_relevant_documents(query)
        # Extract the content from the retrieved documents
        relevant_text = []
        for i, result in enumerate(retrieved_docs):
            # Exclude embeddings from the metadata
            filtered_metadata = {k: v for k, v in result.metadata.items()}
            
            content = f"{result.page_content}  {filtered_metadata['_source']['metainfo']}"
            relevant_text.append(content)

        # Create the RAG-style prompt with the retrieved content
        prompt = make_rag_prompt(query, relevant_passage=relevant_text)

        # Generate the final answer using the LLM
        answer = generate_response(prompt)

        return {"query": query, "answer": answer}

    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


# Run the FastAPI app with: uvicorn filename:app --reload
