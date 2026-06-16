import os
import aiomysql
from fastapi import HTTPException

async def get_db_conn():
    try:
        return await aiomysql.connect(
            host=os.getenv("DB_HOST", "127.0.0.1"),
            port=int(os.getenv("DB_PORT", 3306)),
            user=os.getenv("DB_USER", "cm"),
            password=os.getenv("DB_PASSWORD", ""),
            db=os.getenv("DB_NAME", "campus_market"),
            cursorclass=aiomysql.DictCursor
        )
    except Exception as e:
        print(f"Ошибка подключения к MySQL: {e}")
        raise HTTPException(status_code=500, detail="Database connection failed")
