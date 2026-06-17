import React from 'react';
import { createRoot } from 'react-dom/client';
import CatalogApp from './catalog';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

console.log('⏳ Ожидание DOMContentLoaded...');

document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ DOMContentLoaded сработал!');
    const container = document.getElementById('react-catalog-root');
    console.log('📦 Контейнер:', container);
    
    if (container) {
        const userId = container.getAttribute('data-user-id');
        console.log('👤 User ID:', userId);
        const root = createRoot(container);
        console.log('🚀 Попытка монтирования React...');
        root.render(React.createElement(CatalogApp, { initialUserId: userId }));
        console.log('✅ React отрендерен!');
    } else {
        console.error('❌ Контейнер #react-catalog-root не найден!');
    }
});