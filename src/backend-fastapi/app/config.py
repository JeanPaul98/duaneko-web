import os
from pydantic_settings import BaseSettings

# Ignore the .env file when running inside Docker
if os.getenv("DOCKER_ENV") == "1":
    _env_file = None
else:
    _env_file = ".env"

class Settings(BaseSettings):
    DATABASE_URL: str
    SECRET_KEY: str
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 30
    UPLOAD_DIR: str = "/uploads"
    OCR_WORKER_URL: str = "http://ocr-test-worker-2:8001"

    class Config:
        env_file = _env_file

settings = Settings()