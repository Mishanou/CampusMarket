import React from 'react';
import { createRoot } from 'react-dom/client';
import CatalogApp from './catalog';
import Alpine from 'alpinejs';
import 'bootstrap/dist/css/bootstrap.min.css';
import { showOrderNotification } from './notifications';

window.Alpine = Alpine;
Alpine.start();

function initNotificationWebSocket(userId) {
    if (!userId) return;

    const wsUrl = `wss://${window.location.host}/ws/${userId}`;
    const socket = new WebSocket(wsUrl);

    socket.onopen = () => {
        console.log(`[WS] Успешно подключено к серверу уведомлений для пользователя ${userId}`);
    };

    socket.onmessage = (event) => {
        try {
            const payload = JSON.parse(event.data);
            
            if (payload.event === "new_order") {
                console.log("[WS] Получен новый заказ:", payload.data);
                
                showOrderNotification(payload.data);
                
                document.dispatchEvent(new CustomEvent('new-order-alert', { detail: payload.data }));
            }
        } catch (err) {
            console.error("[WS] Ошибка обработки данных:", err);
        }
    };

    socket.onclose = (e) => {
        console.warn("[WS] Соединение закрыто. Попытка переподключения через 5 секунд...", e.reason);
        setTimeout(() => initNotificationWebSocket(userId), 5000);
    };

    socket.onerror = (err) => {
        console.error("[WS] Ошибка соединения:", err);
    };
}

document.addEventListener('DOMContentLoaded', () => {
    const userMeta = document.querySelector('meta[name="user-id"]');
    const globalUserId = userMeta ? userMeta.getAttribute('content') : null;

    if (globalUserId) {
        initNotificationWebSocket(globalUserId);
    }

    const container = document.getElementById('react-catalog-root');
    if (container) {
        const userId = container.getAttribute('data-user-id') || globalUserId;
        const root = createRoot(container);
        root.render(<CatalogApp initialUserId={userId} />);
    }
});
