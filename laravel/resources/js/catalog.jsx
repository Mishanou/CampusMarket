import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';

// Компонент карточки товара
function ProductCard({ product, isAuth, onBuy }) {
    return (
        <div className="card h-100 shadow-sm">
            <div className="card-body d-flex flex-column">
                <div className="d-flex justify-content-between align-items-start mb-2">
                    <span className="badge bg-secondary">
                        {product.category_name || 'Без категории'}
                    </span>
                    <span className="h5 text-success fw-bold">
                        {product.price} ₽
                    </span>
                </div>
                
                <h5 className="card-title">{product.name}</h5>
                <p className="card-text text-muted small flex-grow-1" style={{ display: '-webkit-box', WebkitLineClamp: 3, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                    {product.description}
                </p>

                <div className="mt-3 pt-3 border-top">
                    <div className="text-muted small mb-2">
                        Продавец: <span className="fw-semibold text-dark">{product.seller_name || 'Студент'}</span>
                    </div>

                    {isAuth ? (
                        <button onClick={() => onBuy(product)} className="btn btn-success w-100">
                            Купить
                        </button>
                    ) : (
                        <a href="/login" className="btn btn-secondary w-100">
                            Войти, чтобы купить
                        </a>
                    )}
                </div>
            </div>
        </div>
    );
}

// Главный компонент каталога
export default function CatalogApp({ initialUserId }) {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    
    const [modalProduct, setModalProduct] = useState(null);
    const [isBuying, setIsBuying] = useState(false);

    const isAuth = !!initialUserId;

    useEffect(() => {
        fetch('http://127.0.0.1:8001/api/products')
            .then(response => {
                if (!response.ok) throw new Error('Ошибка сети при ответе от API-сервера');
                return response.json();
            })
            .then(data => {
                const items = Array.isArray(data) ? data : (data.products || []);
                setProducts(items);
                setLoading(false);
            })
            .catch(err => {
                console.error(err);
                setError('Не удалось загрузить товары с FastAPI. Проверьте работу микросервиса.');
                setLoading(false);
            });
    }, []);

    const handleBuyClick = (product) => {
        setModalProduct(product);
    };

    const confirmBuy = async () => {
        if (!modalProduct) return;
        setIsBuying(true);
        try {
            const response = await fetch('http://127.0.0.1:8001/api/buy', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: modalProduct.id,
                    seller_id: modalProduct.user_id,
                    buyer_id: initialUserId,
                }),
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.detail || 'Ошибка при покупке');
            }
            alert('Уведомление продавцу отправлено!');
            setModalProduct(null);
        } catch (err) {
            alert(err.message);
        } finally {
            setIsBuying(false);
        }
    };

    if (loading) {
        return (
            <div className="text-center py-5">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Загрузка...</span>
                </div>
                <p className="mt-2 text-muted">Загрузка актуальных объявлений...</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className="alert alert-danger" role="alert">
                <strong>Ошибка! </strong> {error}
            </div>
        );
    }

    if (products.length === 0) {
        return (
            <div className="card text-center py-5">
                <div className="card-body">
                    <p className="text-muted fs-5">Пока никто ничего не выставил на продажу. Станьте первым!</p>
                </div>
            </div>
        );
    }

    return (
        <>
            <div className="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                {products.map(product => (
                    <div className="col" key={product.id}>
                        <ProductCard
                            product={product}
                            isAuth={isAuth}
                            onBuy={handleBuyClick}
                        />
                    </div>
                ))}
            </div>

            {/* Модальное окно (Bootstrap-стиль) */}
            {modalProduct && (
                <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 1050 }} tabIndex="-1">
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">Подтверждение покупки</h5>
                                <button
                                    type="button"
                                    className="btn-close"
                                    onClick={() => setModalProduct(null)}
                                ></button>
                            </div>
                            <div className="modal-body">
                                <p>
                                    Вы хотите купить <strong>{modalProduct.name}</strong> за <strong>{modalProduct.price} ₽</strong>?
                                </p>
                            </div>
                            <div className="modal-footer">
                                <button
                                    className="btn btn-secondary"
                                    onClick={() => setModalProduct(null)}
                                >
                                    Нет
                                </button>
                                <button
                                    className="btn btn-success"
                                    onClick={confirmBuy}
                                    disabled={isBuying}
                                >
                                    {isBuying ? 'Отправка...' : 'Да, купить'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}