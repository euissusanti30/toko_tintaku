<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kategori;

/*
|--------------------------------------------------------------------------
| NIH BIAR ADA LOGIN GOOGLE NYA YAAAAAAAAAAA
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\GoogleController;

Route::get('/auth/google/login', [GoogleController::class, 'loginGoogle'])
    ->name('google.login');
Route::get('/auth/google/callback/login', [GoogleController::class, 'handleLogin']);

/*
|--------------------------------------------------------------------------
| GOOGLE REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/auth/google/register', [GoogleController::class, 'registerGoogle'])
    ->name('google.register');

Route::get('/auth/google/callback/register', [GoogleController::class, 'handleRegister']);
/*
|--------------------------------------------------------------------------
| AUTH CONTROLLER
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

Route::get('/', [FrontendProdukController::class, 'frontend'])
    ->name('home');

Route::get('/home', [FrontendProdukController::class, 'frontend'])
    ->name('home.page');

Route::get('/shop', [FrontendProdukController::class, 'shop'])
    ->name('shop');

Route::get('/detail/{id}', [FrontendProdukController::class, 'detail'])
    ->name('detail');

Route::get('/kategori/{id}', [FrontendProdukController::class, 'kategori'])
    ->name('kategori');

Route::get('/contact', function () {
    $kategori = Kategori::all();
    return view('v_contact.index', compact('kategori'));
});


/*
|--------------------------------------------------------------------------
| CART & CHECKOUT
|--------------------------------------------------------------------------
*/

Route::get('/cart', [FrontendProdukController::class, 'cart']);

Route::get('/add-cart/{id}', [FrontendProdukController::class, 'addCart'])
    ->middleware('auth');   

Route::post('/update-cart', [FrontendProdukController::class, 'updateCart']);

Route::get('/delete-cart/{id}', [FrontendProdukController::class, 'deleteCart']);

Route::get('/checkout', [FrontendProdukController::class, 'checkout']);

Route::post('/checkout-store', [FrontendProdukController::class, 'checkoutStore'])
    ->name('frontend.checkout.store');

Route::get('/invoice/{id}', [FrontendProdukController::class, 'invoice']);


/*
|--------------------------------------------------------------------------
| API ONGKIR
|--------------------------------------------------------------------------
*/

Route::get('/api/cities/{provinceId}', [FrontendProdukController::class, 'getCities']);

Route::get('/api/cities-search', [FrontendProdukController::class, 'searchCities']);

Route::post('/api/shipping-cost', [FrontendProdukController::class, 'getShippingCost']);


/*
|--------------------------------------------------------------------------
| BACKEND
|--------------------------------------------------------------------------
*/

Route::prefix('backend')
    ->name('backend.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // DASHBOARD
        Route::get('/beranda', [BerandaController::class, 'berandaBackend'])
            ->name('beranda');

        // KATEGORI
        Route::resource('kategori', KategoriController::class)
            ->except(['show']);

        // PRODUK
        Route::resource('produk', BackendProdukController::class)
            ->except(['show']);

        // TRANSAKSI
        Route::resource('transaksi', TransaksiController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        // USER CUSTOMER
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


/*
|--------------------------------------------------------------------------
| AUTH LARAVEL
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| DELETE ACCOUNT
|--------------------------------------------------------------------------
*/

Route::delete('/delete-account', function () {

    $user = Auth::user();

    User::find($user->id)->delete();

    Auth::logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect('/');

})->middleware('auth')->name('account.delete');