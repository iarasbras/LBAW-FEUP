<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use App\Models\ShoppingCart;
use App\Models\Book;
use App\Models\Order;
use App\Observers\BookObserver;
use App\Observers\OrderObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registar observers
        Book::observe(BookObserver::class);
        Order::observe(OrderObserver::class);

        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;
            $userId = $user->getAuthIdentifier();
            $request = request();

            $sessionCart = $request->session()->get('cart', []);
            if (empty($sessionCart)) {
                
                $db = ShoppingCart::where('user_id', $userId)->get();
                $cart = [];
                foreach ($db as $r) { $cart[(string) $r->book_id] = (int) $r->quantity; }
                $request->session()->put('cart', $cart);
                return;
            }

            foreach ($sessionCart as $bookId => $qty) {
                $bookId = (int) $bookId;
                $qty = (int) $qty;
                $row = ShoppingCart::where('user_id', $userId)->where('book_id', $bookId)->first();
                if ($row) {
                    $row->quantity = (int) $row->quantity + $qty;
                    $row->save();
                } else {
                    ShoppingCart::create(['user_id' => $userId, 'book_id' => $bookId, 'quantity' => $qty]);
                }
            }

            
            $db = ShoppingCart::where('user_id', $userId)->get();
            $cart = [];
            foreach ($db as $r) { $cart[(string) $r->book_id] = (int) $r->quantity; }
            $request->session()->put('cart', $cart);

            
        });
    }
}
