<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_available', true)
                      ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $featuredProducts = Product::where('is_available', true)
            ->with('category')
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }

    public function showProduct(Product $product)
    {
        // Load related products from the same category
        $relatedProducts = Product::where('is_available', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->limit(4)
            ->get();

        return view('product.detail', compact('product', 'relatedProducts'));
    }

    public function categories(Request $request)
    {
        $query = Category::where('is_active', true)
            ->with(['products' => function ($query) use ($request) {
                $query->where('is_available', true);
                
                // Filter by price range if provided
                if ($request->filled('min_price')) {
                    $query->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $query->where('price', '<=', $request->max_price);
                }
                
                // Sort products
                $sort = $request->get('sort', 'name');
                switch ($sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'name':
                    default:
                        $query->orderBy('name', 'asc');
                        break;
                }
            }]);

        // Filter by search term
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('name')->get();

        // Get all products for general stats
        $allProducts = Product::where('is_available', true)->get();
        $priceRange = [
            'min' => $allProducts->min('price') ?? 0,
            'max' => $allProducts->max('price') ?? 100000
        ];

        return view('categories.index', compact('categories', 'priceRange'));
    }

    public function showCategory(Category $category, Request $request)
    {
        $query = $category->products()->where('is_available', true);

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search within category
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort products
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Get price range for this category
        $categoryProducts = $category->products()->where('is_available', true)->get();
        $priceRange = [
            'min' => $categoryProducts->min('price') ?? 0,
            'max' => $categoryProducts->max('price') ?? 100000
        ];

        return view('categories.show', compact('category', 'products', 'priceRange'));
    }
}
