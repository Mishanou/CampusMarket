<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Отображение витрины всех товаров для главной страницы.
     */
    public function welcome()
    {
        $products = Product::with(['category', 'seller'])->latest()->get();

        return view('welcome', compact('products'));
    }

    /**
     * Показать товары текущего продавца (Личный кабинет)
     */
    public function index()
    {
        $products = Product::with('category')
            ->where('user_id', auth()->id())
            ->get();

        return view('products.index', compact('products'));
    }

    /**
     * Показать форму создания товара
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Сохранить новый товар в БД
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('products.index')->with('success', 'Товар успешно добавлен!');
    }

    /**
     * Показать форму редактирования товара
     */
    public function edit(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'У вас нет доступа к этому товару');
        }

        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Обновить товар в БД
     */
    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'У вас нет доступа к этому товару');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Товар обновлен!');
    }

    /**
     * Удалить товар из БД
     */
    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'У вас нет доступа к этому товару');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Товар удален!');
    }
}