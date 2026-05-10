<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| FRONTEND CONTROLLER
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Frontend\ProdukController as FrontendProdukController;

/*
|--------------------------------------------------------------------------
| BACKEND CONTROLLER
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Backend\ProdukController as BackendProdukController;
use App\Http\Controllers\Backend\KategoriController;
use App\Http\Controllers\Backend\BerandaController;
use App\Http\Controllers\Backend\TransaksiController;
use App\Http\Controllers\Backend\UserCustomerController;

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (auth()->user()->role == 1) {
        return redirect('/backend/beranda');
    }
    return redirect('/home');
})->middleware('auth')->name('dashboard');

Route::get('/', [FrontendProdukController::class, 'frontend'])->name('home');

Route::get('/home', [FrontendProdukController::class, 'frontend'])->name('home.page');

Route::get('/shop', [FrontendProdukController::class, 'shop'])->name('shop');

Route::get('/detail/{id}', [FrontendProdukController::class, 'detail'])->name('detail');

Route::get('/kategori/{id}', [FrontendProdukController::class, 'kategori'])->name('kategori');

Route::get('/contact', function () {
    return view('v_contact.index');
});

/*
|--------------------------------------------------------------------------
| CART & CHECKOUT
|--------------------------------------------------------------------------
*/

Route::get('/cart', [FrontendProdukController::class, 'cart']);

Route::get('/add-cart/{id}', [FrontendProdukController::class, 'addCart']);

Route::post('/update-cart', [FrontendProdukController::class, 'updateCart']);

Route::get('/delete-cart/{id}', [FrontendProdukController::class, 'deleteCart']);

Route::get('/checkout', [FrontendProdukController::class, 'checkout']);

Route::post('/checkout-store', [FrontendProdukController::class, 'checkoutStore'])->name('frontend.checkout.store');

Route::get('/invoice/{id}', [FrontendProdukController::class, 'invoice']);

// API Routes for Raja Ongkir
Route::get('/api/cities/{provinceId}', [FrontendProdukController::class, 'getCities']);
Route::get('/api/cities-search', [FrontendProdukController::class, 'searchCities']);
Route::post('/api/shipping-cost', [FrontendProdukController::class, 'getShippingCost']);

// Backend Login Route
Route::get('/loginbackend', function () {
    return view('auth.login');
})->name('backend.login');

Route::post('/loginbackend', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Check if user is admin (role = 1)
        if (Auth::user()->role == 1) {
            return redirect('/backend/beranda');
        }
        
        // If not admin, logout and redirect back with error
        Auth::logout();
        return back()->withErrors([
            'email' => 'Access denied. Admin access only.',
        ]);
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('backend.login.submit');

Route::get('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/home');
});

Route::prefix('backend')
    ->name('backend.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard
        Route::get('/beranda', [BerandaController::class, 'berandaBackend'])
            ->name('beranda');

        // CRUD Kategori
        Route::resource('kategori', KategoriController::class)->except(['show']);

        // CRUD Produk (BACKEND YANG BENAR)
        Route::resource('produk', BackendProdukController::class)->except(['show']);

        // CRUD Transaksi
        Route::resource('transaksi', TransaksiController::class)->only(['index', 'show', 'update', 'destroy']);

        // CRUD User Customer
        Route::resource('user-customer', UserCustomerController::class);

    });

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/backend/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/home');

})->name('backend.logout');