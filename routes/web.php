<?php

use App\Http\Controllers\Admin\AdminProductsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Payment\PaymentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// home screen 
Route::get('/', [ProductsController::class, 'index'])->name('/');
// add product to cart
Route::get('addProductToCart/{id}', [ProductsController::class, 'addProductToCart'])->name('addToCart');
// delete product from cart
Route::get('deleteProductFromCart/{id}', [ProductsController::class, 'deleteProductFromCart'])->name('deleteFromCart');
// show cart items
Route::get('/cart', [ProductsController::class, 'showCart'])->name('cart');
// men
Route::get('/products/men', [ProductsController::class, 'menProducts'])->name('menProducts');
// women
Route::get('/products/women', [ProductsController::class, 'womenProducts'])->name('womenProducts');
// search url
Route::get('/search', [ProductsController::class, 'search'])->name('searchProducts');
// increase the product quantity
Route::get('/product/increaseSingleProduct/{id}', [ProductsController::class, 'increaseSingleProduct'])->name('increaseSingleProduct');
// decrease the product quantity
Route::get('/product/decreaseSingleProduct/{id}', [ProductsController::class, 'decreaseSingleProduct'])->name('decreaseSingleProduct');
// create an order
Route::post('/product/createOrder', [ProductsController::class, 'createOrder'])->name('createOrder');
// checkout page
Route::get('/product/checkoutProducts', [ProductsController::class, 'checkoutProducts'])->name('checkoutProducts');
// payment page
Route::get('/payment/paymentPage', [PaymentsController::class, 'showPaymentPage'])->name('showPaymentPage');
// handle receipt page
Route::get('/payment/receipt/{paypalPaymentID}/{paypalPayerID}', [PaymentsController::class, 'showPaymentReceipt'])->name('showPaymentReceipt');

// user authentication
Auth::routes();
Route::get('/logout', function(){
     Auth::logout();
     return view('auth.login');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
// admin panel
Route::get('/admin/products', [AdminProductsController::class, 'index'])->name('adminDisplayProducts')->middleware('restrictToAdmin');
// display edit product form
Route::get('/admin/editProductForm/{id}', [AdminProductsController::class, 'displayEditProductForm'])->name('adminDisplayEditProductForm');
// display edit product image form
Route::get('/admin/editProductImageForm/{id}', [AdminProductsController::class, 'displayEditProductImageForm'])->name('adminDisplayEditProductImageForm');
// update product image
Route::post('/admin/updateProductImage/{id}', [AdminProductsController::class, 'updateProductImage'])->name('adminUpdateProductImage');
// update product form
Route::post('/admin/updateProductForm/{id}', [AdminProductsController::class, 'updateProductForm'])->name('adminUpdateProductForm');
// display add product form
Route::get('/admin/AddProduct', [AdminProductsController::class, 'displayAddProduct'])->name('adminAddProduct');
// add product data to the database
Route::post('/admin/addProductForm', [AdminProductsController::class, 'addProduct'])->name('adminAddProductForm');
// remove a product admin side
Route::get('/admin/deleteProduct/{id}', [AdminProductsController::class, 'deleteProduct'])->name('adminDeleteProduct');