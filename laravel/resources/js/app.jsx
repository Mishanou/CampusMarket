import React from 'react';
import { createRoot } from 'react-dom/client';
import CatalogApp from './catalog';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('react-catalog-root');
    
    if (container) {
        const userId = container.getAttribute('data-user-id');
        const root = createRoot(container);
        root.render(<CatalogApp initialUserId={userId} />);
    }
});