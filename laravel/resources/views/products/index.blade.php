<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-gray-800">
                {{ __('Панель продавца: Мои товары') }}
            </h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Добавить товар
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    
                    @if($products->isEmpty())
                        <div class="text-center py-5">
                            <p class="text-muted mb-3">У вас пока нет добавленных товаров.</p>
                            <a href="{{ route('products.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i> Добавить первый товар
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Название</th>
                                        <th>Категория</th>
                                        <th>Цена</th>
                                        <th class="text-end">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <small class="text-muted">{{ $product->description }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $product->category->name ?? 'Без категории' }}
                                                </span>
                                            </td>
                                            <td class="fw-bold text-primary">
                                                {{ number_format($product->price, 2) }} ₽
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="fas fa-edit"></i> Редактировать
                                                </a>
                                                
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> Удалить
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>