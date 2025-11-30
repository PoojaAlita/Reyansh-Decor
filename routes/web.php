<?php

use App\Http\Controllers\{ProfileController,AdminController,AdminIconController,AdminPageController,CategoryController,SubCategoryController,ChildCategoryController,ProductController,ProductImageController,ProductVariantController};
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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
    Route::post('/', [AdminIconController::class, 'store'])->name('admin.icons.store');
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
});


Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('category');
    Route::post('/category/store', 'store');
    Route::post('/category/edit', 'edit');
    Route::post('/category/delete', 'delete');
    Route::post('/category/toggle-status', 'toggleStatus');
    Route::post('/category/check-name', 'checkCategoryUnique');
});

// ========================
//      SUB CATEGORY
// ========================
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








require __DIR__.'/auth.php';
