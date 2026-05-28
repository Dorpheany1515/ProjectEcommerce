<?php
namespace App\Http\Controllers\FrontController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function home(){
        $newProducts=Product::query()->OrderBy('id','DESC')->paginate(4, ['*'], 'new_page');
        $promoProducts=Product::query()
                        ->whereColumn('regular_price', '<>', 'sale_price')
                        ->OrderBy('id','DESC')
                        ->paginate(4, ['*'], 'promo_page');

        $popProducts=Product::query()->OrderBy('views','DESC')->paginate(4, ['*'], 'pop_page');
        return view('Frontend.home',compact('newProducts','promoProducts','popProducts'));
    }
    public function shop(Request $request)
{
    $query = Product::query();

    if ($request->has('cate')) {
        $query->join('category', 'product.cate_id', '=', 'category.id')
              ->where('category.category_name', $request->query('cate'))
              ->select('product.*');
    }
     if ($request->filled('s')) {
        $searchTerm = $request->query('s');
        $query->where('product.product_name', 'like', "%{$searchTerm}%");
    }
    if ($request->query('price') == 'max') {
        $query->orderBy('regular_price', 'desc');
    } elseif ($request->query('price') == 'min') {
        $query->orderBy('regular_price', 'asc');
    } else {
        $query->orderBy('product.id', 'desc');
    }
    if ($request->query('promotion') == 'true') {
        $query->whereColumn('regular_price', '>', 'sale_price');
    }

    $shops = $query->paginate(6);
    $cate = Category::all();

    return view('Frontend.shop', compact('shops', 'cate'));
    }

        public function searchProduct(Request $request)
        {
            $keyword = $request->query('s');

            $products = Product::query()
                ->join('category', 'product.cate_id', '=', 'category.id')
                ->join('users', 'product.user_id', '=', 'users.id')
                ->where(function ($query) use ($keyword) {
                    $query->where('product.product_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('category.category_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('product.description', 'LIKE', "%{$keyword}%");
                })
                ->select('product.*', 'category.category_name as category', 'users.profile as profile')
                ->paginate(7);

            $newProducts = Product::latest()->paginate(8);
            $promoProducts = Product::whereColumn('regular_price', '>', 'sale_price')->paginate(8);
            $popProducts = Product::orderBy('views', 'desc')->paginate(8);

            return view('Frontend.home', compact('products', 'keyword', 'newProducts', 'promoProducts', 'popProducts'));
        }

         public function show($id)
            {
                $product = Product::findOrFail($id);
                $sizes = ['XS', 'S', 'M', 'L', 'XL']; 
                return view('frontend.Checkout', compact('product', 'sizes'));
            }
            
        }