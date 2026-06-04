<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------
// RUTAS PÚBLICAS
// -----------------------------------------------
Route::get('/', function () {
    return redirect()->route('shop.index');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Carrito
    Route::get   ('/carrito',              [CartController::class, 'index'])  ->name('cart.index');
    Route::post  ('/carrito/{product}',    [CartController::class, 'add'])    ->name('cart.add');
    Route::patch ('/carrito/{product}',    [CartController::class, 'update']) ->name('cart.update');
    Route::delete('/carrito/{product}',    [CartController::class, 'remove']) ->name('cart.remove');
    Route::delete('/carrito',              [CartController::class, 'clear'])  ->name('cart.clear');

    // Órdenes
    Route::get  ('/mis-ordenes',           [OrderController::class, 'index']) ->name('orders.index');
    Route::get  ('/mis-ordenes/{order}',   [OrderController::class, 'show'])  ->name('orders.show');
    Route::post ('/checkout',              [OrderController::class, 'store']) ->name('orders.store');
    Route::patch('/mis-ordenes/{order}/cancelar', [OrderController::class, 'cancel'])->name('orders.cancel');

});

Route::get('/tienda', [ShopProductController::class, 'index'])->name('shop.index');
Route::get('/tienda/{product}', [ShopProductController::class, 'show'])->name('shop.show');

// -----------------------------------------------
// RUTAS AUTENTICADAS
// -----------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -----------------------------------------------
// RUTAS ADMIN
// -----------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', AdminProductController::class);
});

require __DIR__.'/auth.php';