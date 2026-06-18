import React, { useState, useEffect } from 'react';

// Компонент карточки товара
function ProductCard({ product, isAuth }) {
    return (
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between p-6 transition hover:shadow-md">
            <div>
                <div className="flex justify-between items-start mb-2">
                    <span className="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded font-semibold uppercase tracking-wide">
                        {product.category_name}
                    </span>
                    <span className="text-lg font-bold text-green-600">
                        {product.price} ₽
                    </span>
                </div>
                
                <h3 className="text-xl font-semibold text-gray-900 mb-2">{product.name}</h3>
                <p className="text-gray-600 text-sm mb-4 line-clamp-3">{product.description}</p>
            </div>

            <div className="mt-4 pt-4 border-t border-gray-100">
                <div className="text-xs text-gray-500 mb-3">
                    Продавец: <span className="font-medium text-gray-700">{product.seller_name}</span>
                </div>

                {isAuth ? (
                    <form action="/cart/add" method="POST" className="w-full">
                        <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').content} />
                        <input type="hidden" name="product_id" value={product.id} />
                        <button type="submit" className="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded transition text-sm">
                            Добавить в корзину
                        </button>
                    </form>
                ) : (
                    <a href="/login" className="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded transition text-sm">
                        Войти, чтобы купить
                    </a>
                )}
            </div>
        </div>
    );
}

// Главный компонент каталога
export default function CatalogApp({ initialUserId }) {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const isAuth = !!initialUserId;

    useEffect(() => {
        fetch('http://127.0.0.1:8001/api/products')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка сети при ответе от API-сервера');
                }
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

    if (loading) {
        return (
            <div className="flex justify-center items-center py-10">
                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <span className="ml-3 text-gray-600">Загрузка актуальных объявлений...</span>
            </div>
        );
    }

    if (error) {
        return (
            <div className="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
                <strong className="font-bold">Ошибка! </strong>
                <span className="block sm:inline">{error}</span>
            </div>
        );
    }

    if (products.length === 0) {
        return (
            <div className="bg-white p-6 text-center rounded-lg shadow border border-gray-200">
                <p className="text-gray-500 text-lg">Пока никто ничего не выставил на продажу. Станьте первым!</p>
            </div>
        );
    }

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {products.map(product => (
                <ProductCard key={product.id} product={product} isAuth={isAuth} />
            ))}
        </div>
    );
}
