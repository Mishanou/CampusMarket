<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            {{ __('Добавление нового товара') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            
                            <form action="{{ route('products.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Название товара <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name') }}" 
                                           required
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name') 
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Категория <span class="text-danger">*</span></label>
                                    <select name="category_id" 
                                            id="category_id" 
                                            required
                                            class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="">Выберите категорию</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') 
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Цена (₽) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="price" 
                                           id="price" 
                                           step="0.01" 
                                           value="{{ old('price') }}" 
                                           required
                                           class="form-control @error('price') is-invalid @enderror">
                                    @error('price') 
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label">Описание товара <span class="text-danger">*</span></label>
                                    <textarea name="description" 
                                              id="description" 
                                              rows="4" 
                                              required
                                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description') 
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                        Отмена
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Создать
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>