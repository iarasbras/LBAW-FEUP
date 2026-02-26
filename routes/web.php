<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SellerBookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\DeleteAccountController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\PlatformInformationController;

Route::get('/', [PlatformInformationController::class, 'home'])->name('home');

Route::get('/about-us', [PlatformInformationController::class, 'about'])->name('about_us');

// Catálogo
Route::controller(CatalogController::class)->group(function () {
    Route::get('/catalog', 'index')->name('catalog.index');
    Route::get('/catalog/{book}', 'show')->name('catalog.show');
    Route::post('/catalog/{book}/review', 'storeReview')->name('catalog.review')->middleware('auth');
    Route::delete('/books/{book}/review', [CatalogController::class, 'destroyReview'])->name('catalog.review.destroy');
});

// Carrinho
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/add', 'add')->name('cart.add');
    Route::get('/cart/checkout', 'checkout')->name('cart.checkout');
    Route::post('/cart/complete', 'complete')->name('cart.complete');
    Route::post('/cart/update', 'update')->name('cart.update');
    Route::post('/cart/remove', 'remove')->name('cart.remove');
    Route::post('/cart/save-delivery', 'saveDeliveryInfo')->name('cart.save-delivery');
    Route::get('/order/{order}', 'confirmation')->name('order.show');
});

Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'authenticate');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register');
    });

    // Recuperação de Password
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('/password/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('/password/email', 'sendResetLinkEmail')->name('password.email');
    });
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('/password/reset', 'reset')->name('password.update');
    });
});

Route::middleware('auth')->group(function () {
    
    // Logout (User Normal)
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Perfil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::post('/profile', 'update')->name('profile.update');
    });

    // Encomendas
    Route::get('/profile/orders', [OrderController::class, 'history'])->name('profile.orders');

    // Wishlist
    Route::controller(WishlistController::class)->group(function () {
        Route::post('/wishlist/{book}/toggle', 'toggle')->name('wishlist.toggle');
        Route::get('/wishlist/{book}/check', 'check')->name('wishlist.check');
    });

    // Notificações
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'index')->name('notifications.index');
        Route::get('/notifications/unread', 'unread')->name('notifications.unread');
        Route::post('/notifications/{id}/read', 'markAsRead')->name('notifications.read');
        Route::post('/notifications/read-all', 'markAllAsRead')->name('notifications.readAll');
    });

    // Eliminação de conta
    Route::controller(DeleteAccountController::class)->group(function () {
        Route::delete('/delete-account','destroy')->name('delete-account.destroy');
    });

    // --- ÁREA DE VENDEDOR ---
    Route::prefix('seller')->name('seller.')->controller(SellerBookController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/books/create', 'create')->name('books.create');
        Route::post('/books', 'store')->name('books.store');
        Route::get('/books/{book}/edit', 'edit')->name('books.edit');
        Route::put('/books/{book}', 'update')->name('books.update');
        Route::delete('/books/{book}', 'destroy')->name('books.destroy');
        Route::get('/sales-history', 'ordersHistory')->name('orders.history');
    });
});

// --- ADMIN ---
Route::prefix('admin')->name('admin.')->group(function () {

    // 1. Login de Admin (Guest Admin)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('login'); 
        Route::post('/login', [LoginController::class, 'adminLogin'])->name('login.submit');
    });

    // 2. Painel Protegido (Auth Admin)
    Route::middleware('auth:admin')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', function() { return redirect()->route('admin.dashboard'); });

        // Logout Admin
        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

        // Gestão de Livros
        Route::controller(AdminBookController::class)->group(function () {
            Route::get('/books/create', 'create')->name('books.create');
            Route::post('/books', 'store')->name('books.store');
            Route::get('/books/{book}/edit', 'edit')->name('books.edit');
            Route::put('/books/{book}', 'update')->name('books.update');
            Route::delete('/books/{book}', 'destroy')->name('books.destroy');
        });

        // Gestão de Utilizadores
        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::get('/users/create', 'create')->name('users.create');
            Route::post('/users', 'store')->name('users.store');
            Route::get('/users/{user}', 'show')->name('users.show');
            Route::get('/users/{user}/purchase-history', 'purchaseHistory')->name('users.purchase-history');
            Route::get('/users/{user}/edit', 'edit')->name('users.edit');
            Route::put('/users/{user}', 'update')->name('users.update');
            Route::patch('/users/{user}/toggle-block', 'toggleBlock')->name('users.toggle-block');
            Route::delete('/users/{user}/delete', 'delete')->name('users.delete');
        });

        // Gestão de Categorias
        Route::controller(AdminCategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('categories.index');
            Route::get('/categories/create', 'create')->name('categories.create');
            Route::post('/categories', 'store')->name('categories.store');
            Route::get('/categories/{category}/edit', 'edit')->name('categories.edit');
            Route::put('/categories/{category}', 'update')->name('categories.update');
            Route::delete('/categories/{category}', 'destroy')->name('categories.destroy');
        });

        // Gestão de Informação da Plataforma
        Route::controller(PlatformInformationController::class)->group(function () {
            Route::get('/platform-info/edit', 'showEditForm')->name('platform-info.edit');
            Route::post('/platform-info', 'edit')->name('platform-info.update');
        });
    });
});