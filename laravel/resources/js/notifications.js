export function showOrderNotification(orderData) {
    // Находим контейнер на странице
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Создаем элемент карточки-уведомления
    const toast = document.createElement('div');
    toast.className = 'card shadow-lg border-success mb-3 animate__animated animate__fadeInRight';
    
    // Наполняем карточку данными
    toast.innerHTML = `
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold">🛍️ Новый заказ!</span>
            <small class="opacity-75">${orderData.created_at}</small>
            <button type="button" class="btn-close btn-close-white small" aria-label="Закрыть" onclick="this.closest('.card').remove()"></button>
        </div>
        <div class="card-body bg-light text-dark py-3">
            <h6 class="card-title fw-bold text-truncate mb-2" title="${orderData.product_name}">
                ${orderData.product_name}
            </h6>
            <div class="border-top my-2"></div>
            <div class="small text-muted mb-1">
                <strong>Покупатель:</strong> ${orderData.buyer_name}
            </div>
            <div class="small text-muted">
                <strong>Email:</strong> 
                <a href="mailto:${orderData.buyer_email}" class="text-decoration-none text-success fw-semibold">
                    ${orderData.buyer_email}
                </a>
            </div>
        </div>
    `;

    // Добавляем созданное уведомление в контейнер
    container.appendChild(toast);
}