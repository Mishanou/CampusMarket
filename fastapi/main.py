import asyncio
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from dotenv import load_dotenv

from connection_manager import manager
from database import get_db_conn
from routes.redis_listener import redis_sub_listener
from routes import status_router, products_router, websocket_router

load_dotenv()

app = FastAPI(title="CampusMarket API", version="1.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(status_router)
app.include_router(products_router)
app.include_router(websocket_router)

@app.on_event("startup")
async def startup_event():
    asyncio.create_task(redis_sub_listener())
