<?php

use App\Http\Controllers\BackendController\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Backendcontroller\LogoController;
use App\Http\Controllers\BackendController\ProductController;
use App\Http\Controllers\BackendController\ProfileController;
use App\Http\Controllers\BackendController\UserController;
use App\Http\Controllers\FrontController\ProductController as FrontControllerProductController;
use App\Http\Controllers\ProfileController as ControllersProfileController;
use App\Http\Controllers\ShopController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/news', function () {
    return view('Frontend.news');
});
// Route::controller(fro::class)->group(function(){
//    Route::get('/','home');
//    Route::get('/shop','shop')->name('shop');


// });
Route::controller(FrontControllerProductController::class)->group(function(){
     Route::get('/','home');
        Route::get('/shop','shop')->name('shop');
        Route::get('/shopsubmit','index')->name('shopsubmit');
        Route::get('/admin/search-product', 'searchProduct')->name('product.search');
        Route::get('/product/{id}', 'show')->name('product.show');

});
Route::controller(UserController::class)->group(function(){
    Route::get('/login','login')->name('login');
    Route::get('/register','register')->name('register');
    Route::post('/signup-submit','signupSubmit')->name('signupSubmit');
    Route::post('/loginSubmit','loginSubmit')->name('loginSubmit');
    Route::get('/logout','logout')->name('logout');
});
Route::get('/admin/dashboard', [UserController::class, 'dashBoard'])
    ->middleware('auth')
    ->name('dashBoard');
Route::middleware(['auth'])->group(function(){
    Route::controller(ProductController::class)->group(function(){
        Route::get('/admin/add-product','addProduct')->name('addProduct');
        Route::post('/admin/add-product-submit','addProductSubmit')->name('addProductSubmit');
        Route::get('/admin/list-product','listProduct');
        Route::post('/admin/delete-product','deleteProduct')->name('deleteProduct');
        Route::get('/admin/edit-product/{product}','editProduct')->name('editProduct');

    });
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/admin/add-category','addCategory')->name('addCategory');
        Route::post('/admin/add-category-submit','addCateSubmit')->name('addCateSubmit');
        Route::get('/admin/list-category','viewCate')->name('viewCate');
        Route::get('/admin/edit-category/{category}','editCate')->name('editCategory');
        Route::post('/admin/delete-category','deleteCate')->name('deleteCate');
    });
    Route::controller(LogoController::class)->group(function(){
        Route::get('/admin/add-logo','addlogo')->name('addlogo');
        Route::post('/admin/addLogoSubmit','addLogoSubmit')->name('addLogoSubmit');
        Route::get('/admin/list-logo','listLogo')->name('listLogo');
        Route::post('/admin/delete-Logo', 'deleteLogo')->name('deleteLogo');
        Route::get('/admin/edit-logo/{logo}', 'editLogo')->name('editLogo');

    });
    Route::get('/profile/edit', [ControllersProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ControllersProfileController::class, 'update'])->name('profile.update');

});

/// 🛒 ក្រុម Route ខាងក្រៅ (ភ្ញៀវមិនទាន់ Login ក៏អាចមើល និងកុម្ម៉ង់ទិញបាន)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');

// 💡 ដំណោះស្រាយ៖ ត្រូវយក Route ទាំង ៣ នេះមកដាក់នៅខាងក្រៅក្រុម auth ដែរ
Route::post('/payment/cash', [PaymentController::class, 'cashOnDelivery'])->name('payment.cash');
Route::post('/payment/stripe', [PaymentController::class, 'stripePayment'])->name('payment.stripe');
Route::get('/payment/success/{orderNumber}', [PaymentController::class, 'success'])->name('payment.success');


// 🔒 ក្រុម Route ខាងក្នុង (ទុកសម្រាប់មុខងារណាដែលចាំបាច់ទាល់តែ Login ពិតប្រាកដ)
Route::middleware('auth')->group(function () {
    // អ្នកអាចដាក់ Route ផ្សេងៗដូចជា Dashboard, Profile, or Order History នៅទីនេះ...
});