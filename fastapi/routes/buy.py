from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
import redis.asyncio as aioredis
import json
from database import get_db_conn  # Импортируем подключение к БД
from datetime import datetime
import os

router = APIRouter(prefix="/api", tags=["Buy"])

redis_host = os.getenv("REDIS_HOST", "redis")
redis_port = os.getenv("REDIS_PORT", "6379")

class BuyPayload(BaseModel):
    product_id: int
    seller_id: int
    buyer_id: int

@router.post("/buy", status_code=201)
async def buy_product(payload: BuyPayload):
    try:
        # Подключаемся к MySQL и собираем нужную информацию
        conn = await get_db_conn()
        async with conn.cursor() as cur:
            # Получаем название товара
            await cur.execute("SELECT name FROM products WHERE id = %s", (payload.product_id,))
            product = await cur.fetchone()
            
            # Получаем данные покупателя
            await cur.execute("SELECT name, email FROM users WHERE id = %s", (payload.buyer_id,))
            buyer = await cur.fetchone()
        conn.close()

        # Защита на случай, если записи не найдены
        product_name = product["name"] if product else "Неизвестный товар"
        buyer_name = buyer["name"] if buyer else "Анонимный покупатель"
        buyer_email = buyer["email"] if buyer else "нет email"
        
        # Фиксируем время заказа
        order_time = datetime.now().strftime("%H:%M:%S")

        # Формируем расширенный пакет данных для отправки
        async with aioredis.from_url(f"redis://{redis_host}:{redis_port}", decode_responses=True) as r:
            message = {
                "seller_id": payload.seller_id,
                "buyer_id": payload.buyer_id,
                "order": {
                    "id": None,
                    "product_id": payload.product_id,
                    "product_name": product_name,
                    "buyer_name": buyer_name,
                    "buyer_email": buyer_email,
                    "created_at": order_time,
                    "status": "pending"
                }
            }
            
            await r.publish("new_order", json.dumps(message))
            
    except Exception as e:
        print(f"Redis error: {e}")
        raise HTTPException(status_code=500, detail="Failed to send notification to Redis")

    return {"message": "Seller notified"}
