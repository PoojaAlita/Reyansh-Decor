<?php

use App\Http\Controllers\{ProfileController,AdminController,AdminIconController,AdminPageController,CategoryController,SubCategoryController,ChildCategoryController,ProductController,ProductImageController,ProductVariantController,SliderController,HomeVideoController,HomeBannerController,CartController,ProductVideoController,MenuController,FrontendController,EnquiryController};
use Illuminate\Support\Facades\Route;


Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('frontend.about');
Route::get('/category/{id}', [FrontendController::class, 'category'])->name('frontend.category');
Route::get('/product/{id}', [FrontendController::class, 'product'])->name('frontend.product');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [FrontendController::class, 'checkout'])->name('frontend.checkout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   
Route::prefix('users')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.users.index');
    Route::post('/store', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::post('/delete', [AdminController::class, 'destroy'])->name('admin.user.delete');
    Route::post('/toggle-status', [AdminController::class, 'toggleStatus']);
    Route::post('/rights-store', [AdminController::class, 'rightsStore'])->name('admin.rights-store');
    Route::get('/get-rights/{id}', [AdminController::class, 'getRights']);
});

Route::prefix('admin-icons')->group(function () {
    Route::get('/', [AdminIconController::class, 'index'])->name('admin.icons.index');
    Route::post('/store', [AdminIconController::class, 'store'])->name('admin.icons.store');
    Route::get('/edit', [AdminIconController::class, 'edit']);  
    Route::post('/toggle-status', [AdminIconController::class, 'toggleStatus']);
    Route::post('/delete', [AdminIconController::class, 'destroy'])->name('admin.icons.delete'); 

});

Route::prefix('admin-pages')->group(function () {
    Route::get('/', [AdminPageController::class, 'index'])->name('admin.pages.index');
    Route::post('/store', [AdminPageController::class, 'store'])->name('admin.pages.store');
    Route::post('/edit', [AdminPageController::class, 'edit'])->name('admin.pages.edit');
    Route::post('/delete', [AdminPageController::class, 'destroy'])->name('admin.pages.delete');
    Route::post('/toggle-status', [AdminPageController::class, 'toggleStatus'])->name('admin.pages.toggle');
    Route::post('/get-sorting', [AdminPageController::class, 'getAdminPagesForSorting']);
    Route::post('/save-sorting', [AdminPageController::class, 'saveAdminPagesPosition']);
    Route::get('/menu-html', [AdminPageController::class, 'menuHtml'])->name('admin.pages.menuHtml');


});


Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('category');
    Route::post('/category/store', 'store');
    Route::post('/category/edit', 'edit');
    Route::post('/category/delete', 'delete');
    Route::post('/category/toggle-status', 'toggleStatus');
    Route::post('/category/check-name', 'checkCategoryUnique');
});


Route::get('/subcategory', [SubCategoryController::class, 'index']);
Route::post('/subcategory/store', [SubCategoryController::class, 'store']);
Route::post('/subcategory/edit', [SubCategoryController::class, 'edit']);
Route::post('/subcategory/delete', [SubCategoryController::class, 'delete']);
Route::post('/subcategory/toggle-status', [SubCategoryController::class, 'toggleStatus']);
Route::post('/subcategory/check-name', [SubCategoryController::class, 'checkSubCategoryUnique']);

Route::prefix('childcategory')->group(function () {
    Route::get('/', [ChildCategoryController::class, 'index']);
    Route::post('/store', [ChildCategoryController::class, 'store']);
    Route::post('/edit', [ChildCategoryController::class, 'edit']);
    Route::post('/delete', [ChildCategoryController::class, 'delete']);
    Route::post('/toggle-status', [ChildCategoryController::class, 'toggleStatus']);
    Route::post('/check-name', [ChildCategoryController::class, 'checkChildCategoryUnique']);
});

// Products Module
Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'index');
    Route::post('/product/store', 'store');
    Route::post('/product/edit', 'edit');
    Route::post('/product/delete', 'delete');
    Route::post('/product/toggle-status', 'toggleStatus');
    Route::post('/product/check-name', 'checkNameUnique');

    Route::post('/product/get-subcategories', 'getSubcategories');
    Route::post('/product/get-childcategories', 'getChildcategories');
    
});

Route::controller(ProductImageController::class)->group(function () {

    Route::get('/productimages', 'index');
    Route::post('/productimages/store', 'store');
    Route::post('/productimages/edit', 'edit');
    Route::post('/productimages/delete', 'delete');
    Route::post('/productimages/toggle-status', 'toggleStatus');

});


Route::get('/productvariants', [ProductVariantController::class, 'index']);
Route::post('/productvariants/store', [ProductVariantController::class, 'store']);
Route::post('/productvariants/edit', [ProductVariantController::class, 'edit']);
Route::post('/productvariants/delete', [ProductVariantController::class, 'delete']);
Route::post('/productvariants/toggle-status', [ProductVariantController::class, 'toggleStatus']);
Route::post('/productvariants/check-variant', [ProductVariantController::class, 'checkProductVariantUnique']);

Route::controller(SliderController::class)->group(function () {
    Route::get('/sliders', 'index');
    Route::post('/sliders/store', 'store');
    Route::post('/sliders/edit', 'edit');
    Route::post('/sliders/delete', 'delete');
    Route::post('/sliders/toggle-status', 'toggleStatus');
    Route::post('/sliders/check-name', 'checkSliderUnique');
});

Route::prefix('homevideo')->group(function () {
    Route::get('/', [HomeVideoController::class, 'index']);
    Route::post('/store', [HomeVideoController::class, 'store']);
    Route::post('/edit', [HomeVideoController::class, 'edit']);
    Route::post('/delete', [HomeVideoController::class, 'delete']);
    Route::post('/toggle-status', [HomeVideoController::class, 'toggleStatus']);
    Route::post('/check-name', [HomeVideoController::class, 'checkHomeVideoUnique']);
    Route::post('/get-sorting', [HomeVideoController::class, 'getAdminPagesForSorting']);
    Route::post('/save-sorting', [HomeVideoController::class, 'saveAdminPagesPosition']);
});

Route::prefix('homebanner')->group(function () {
    Route::get('/', [HomeBannerController::class, 'index']);
    Route::post('/store', [HomeBannerController::class, 'store']);
    Route::post('/edit', [HomeBannerController::class, 'edit']);
    Route::post('/delete', [HomeBannerController::class, 'delete']);
    Route::post('/toggle-status', [HomeBannerController::class, 'toggleStatus']);
    Route::post('/check-name', [HomeBannerController::class, 'checkUnique']);
    Route::post('/get-sorting', [HomeBannerController::class, 'getSorting']);
    Route::post('/save-sorting', [HomeBannerController::class, 'saveSorting']);
});


Route::prefix('cart')->group(function() {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/store', [CartController::class, 'store'])->name('cart.store');
    Route::post('/edit', [CartController::class, 'edit'])->name('cart.edit');
    Route::post('/delete', [CartController::class, 'delete'])->name('cart.delete');
    Route::post('/toggle-status', [CartController::class, 'toggleStatus'])->name('cart.toggleStatus');
    Route::post('/check-unique', [CartController::class, 'checkCartUnique'])->name('cart.checkUnique');
    Route::post('/get-variants', [CartController::class, 'getVariants'])->name('cart.getVariants');
});


Route::prefix('productvideo')->group(function () {
    Route::get('/', [ProductVideoController::class, 'index'])->name('productvideo.index');
    Route::post('/store', [ProductVideoController::class, 'store'])->name('productvideo.store');
    Route::post('/edit', [ProductVideoController::class, 'edit'])->name('productvideo.edit');
    Route::post('/delete', [ProductVideoController::class, 'delete'])->name('productvideo.delete');
    Route::post('/toggle-status', [ProductVideoController::class, 'toggleStatus'])->name('productvideo.toggleStatus');
    Route::post('/check-name', [ProductVideoController::class, 'checkProductVideoUnique'])->name('productvideo.checkName');
});


Route::prefix('enquiry')->group(function () {
    Route::get('/', [EnquiryController::class, 'index'])->name('enquiry.index');
    Route::post('/delete', [EnquiryController::class, 'delete'])->name('enquiry.delete');
    Route::post('/toggle-status', [EnquiryController::class, 'toggleStatus'])->name('enquiry.toggleStatus');
});


});











require __DIR__.'/auth.php';
