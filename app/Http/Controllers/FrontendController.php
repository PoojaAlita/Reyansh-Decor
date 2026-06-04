<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Slider,Category,Product,HomeBanner,HomeVideo};

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('isshown', 1)->orderBy('id', 'desc')->get();

        // for category cards on homepage
        $categories = Category::where('isshown', 1)->orderBy('id', 'desc')->take(6)->get();

        // filters for selected category/subcategory/childcategory on home page
        $filterCategories = Category::where('isshown', 1)->orderBy('name')->get();
        $categoryId = request()->query('category');
        $subcatId = request()->query('subcategory');
        $childcatId = request()->query('childcategory');

        $productQuery = Product::where('isshown', 1);

        if ($categoryId) {
            $productQuery->where('category_id', $categoryId);
        }
        if ($subcatId) {
            $productQuery->where('subcategory_id', $subcatId);
        }
        if ($childcatId) {
            $productQuery->where('child_category_id', $childcatId);
        }

        $products = $productQuery->with('category', 'subcategory', 'childCategory')->orderBy('id', 'desc')->take(6)->get();

        $filterSubcategories = $categoryId ? \App\Models\SubCategory::where('category_id', $categoryId)->where('isshown', 1)->orderBy('subcat_name')->get() : collect();
        $filterChildcategories = $subcatId ? \App\Models\ChildCategory::where('subcategory_id', $subcatId)->where('isshown', 1)->orderBy('name')->get() : collect();

        $banners = HomeBanner::where('isshown', 1)->orderBy('position', 'asc')->get();
        $videos = HomeVideo::orderBy('position', 'asc')->get();

        return view('frontend.home', compact('sliders', 'categories', 'products', 'banners', 'videos', 'filterCategories', 'filterSubcategories', 'filterChildcategories', 'categoryId', 'subcatId', 'childcatId'));
    }

    public function category($id)
    {
        $category = Category::findOrFail($id);
        $subcatId = request()->query('subcategory');
        $childcatId = request()->query('childcategory');

        $productQuery = Product::where('category_id', $category->id)->where('isshown', 1);

        if ($subcatId) {
            $productQuery->where('subcategory_id', $subcatId);
        }

        if ($childcatId) {
            $productQuery->where('child_category_id', $childcatId);
        }

        $products = $productQuery->paginate(12)->withQueryString();

        $subcategories = \App\Models\SubCategory::where('category_id', $category->id)->where('isshown', 1)->orderBy('subcat_name')->get();
        $childcategories = $subcatId ? \App\Models\ChildCategory::where('subcategory_id', $subcatId)->where('isshown', 1)->orderBy('name')->get() : collect();

        return view('frontend.category', compact('category', 'products', 'subcategories', 'childcategories', 'subcatId', 'childcatId'));
    }

    public function product($id)
    {
        $product = Product::findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('isshown', 1)
            ->take(4)
            ->get();

        return view('frontend.product_detail', compact('product', 'relatedProducts'));
    }

    public function about()
    {
        // you can populate from database or static page model if you have one
        $content = 'Reyansh Decor is a family-owned atelier specializing in premium drapery and home textiles. Founded with a vision to marry traditional artisanship with modern design, we create bespoke pieces that elevate spaces across India and beyond.';
        return view('frontend.about', compact('content'));
    }

    public function checkout()
    {
        $carts = \App\Models\Cart::where('admin_id', \Illuminate\Support\Facades\Auth::id())->with(['product', 'variant'])->get();
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        return view('frontend.checkout', compact('carts'));
    }
}
