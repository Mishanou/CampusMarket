from fastapi import APIRouter

router = APIRouter(tags=["System"])

@router.get("/api/status")
async def get_status():
    return {"status": "ok", "service": "campus-market-api"}
