import os
import json
import asyncio
import redis.asyncio as aioredis
from connection_manager import manager

async def redis_sub_listener():
    redis_host = os.getenv("REDIS_HOST", "127.0.0.1")
    redis_port = int(os.getenv("REDIS_PORT", 6379))
    
    print(f"Запуск подписки на Redis канал 'new_order' ({redis_host}:{redis_port})...")
    
    while True:
        try:
            r = aioredis.from_url(
                f"redis://{redis_host}:{redis_port}",
                decode_responses=True
            )
            
            pubsub = r.pubsub()
            await pubsub.subscribe("new_order")
            print("Подписка на канал 'new_order' активирована")
            
            async for message in pubsub.listen():
                if message and message["type"] == "message":
                    data = json.loads(message["data"])
                    seller_id = int(data.get("seller_id"))
                    order_details = data.get("order")
                    
                    await manager.send_personal_message(
                        {"event": "new_order", "data": order_details},
                        user_id=seller_id
                    )
                    
        except Exception as e:
            print(f"Ошибка Redis: {e}. Переподключение через 5 секунд...")
            break
            await asyncio.sleep(5)
