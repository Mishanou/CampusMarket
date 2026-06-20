from fastapi import APIRouter, HTTPException
from database import get_db_conn

router = APIRouter(prefix="/api", tags=["Products"])

@router.get("/products")
async def get_all_products():
    conn = await get_db_conn()
    async with conn.cursor() as cur:
        query = """
            SELECT p.*, c.name as category_name, u.name as seller_name 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
        """
        await cur.execute(query)
        products = await cur.fetchall()
    conn.close()
    return products

@router.get("/products/{product_id}")
async def get_product_detail(product_id: int):
    conn = await get_db_conn()
    async with conn.cursor() as cur:
        query = """
            SELECT p.*, c.name as category_name, u.name as seller_name 
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.user_id = u.id
            WHERE p.id = %s
        """
        await cur.execute(query, (product_id,))
        product = await cur.fetchone()
    conn.close()
    
    if not product:
        raise HTTPException(status_code=404, detail="Product not found")
    return product
