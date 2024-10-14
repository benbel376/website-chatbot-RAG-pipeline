# FastAPI Dockerfile

FROM python:3.11-slim

# Set work directory for FastAPI
WORKDIR /app

# Install required system packages
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    curl

# Install Python dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy FastAPI app code
COPY . .

# Expose FastAPI port
EXPOSE 8000

# Command to run FastAPI
CMD ["uvicorn", "app:app", "--host", "0.0.0.0", "--port", "8000"]
