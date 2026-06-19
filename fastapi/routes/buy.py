from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
import redis.asyncio as aioredis
import json

router = APIRouter(prefix="/api", tags=["Buy"])

class BuyPayload(BaseModel):
    product_id: int
    seller_id: int
    buyer_id: int

@router.post("/buy", status_code=201)
async def buy_product(payload: BuyPayload):
    try:
        async with aioredis.from_url("redis://127.0.0.1:6379", decode_responses=True) as r:
            message = {
                "seller_id": payload.seller_id,
                "buyer_id": payload.buyer_id,
                "order": {
                    "id": None,
                    "product_id": payload.product_id,
                    "status": "pending"
                }
            }
            
            await r.publish("new_order", json.dumps(message))
            
    except Exception as e:
        print(f"Redis error: {e}")
        raise HTTPException(status_code=500, detail="Failed to send notification to Redis")

    return {"message": "Seller notified"}