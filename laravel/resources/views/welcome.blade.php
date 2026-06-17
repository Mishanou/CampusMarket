<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Витрина товаров CampusMarket') }}
            </h2>
            @auth
                <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                    + Выставить товар
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="react-catalog-root" data-user-id="{{ auth()->id() }}">
                <!-- Здесь React отрендерит каталог -->
                <div class="flex justify-center items-center py-10">
                    <span class="text-gray-500">Загрузка каталога объявлений...</span>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/app.jsx')
</x-app-layout>
