from .status import router as status_router
from .products import router as products_router
from .websocket import router as websocket_router

__all__ = ['status_router', 'products_router', 'websocket_router']
