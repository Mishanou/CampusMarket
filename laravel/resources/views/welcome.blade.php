<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-gray-800">
                {{ __('Витрина товаров CampusMarket') }}
            </h2>
            @auth
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Добавить товар
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="react-catalog-root" data-user-id="{{ auth()->id() }}">
                <div class="flex justify-center items-center py-10">
                    <span class="text-gray-500">Загрузка каталога объявлений...</span>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/app.jsx')
</x-app-layout>
