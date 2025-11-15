<?php

use App\Http\Controllers\{ProfileController,AdminController,AdminIconController,AdminPageController};
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


require __DIR__.'/auth.php';
