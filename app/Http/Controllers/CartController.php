<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\ShoppingCart;
use App\Models\Order;
use App\Models\OrderBook;
use App\Models\Payment;
use App\Models\PaymentApprovedNotification;

class CartController extends Controller
{
    /**
     * Show cart contents.
     */
    public function index(Request $request)
    {
        if ($redirect = $this->denyAdminAccess()) {
            return $redirect;
        }

        // Se autenticado, lê da BD e sincroniza a sessão
        if (Auth::check()) {
            if (Auth::user()->is_blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sua conta encontra-se bloqueada.',
                ], 403);
            }
            $dbRows = ShoppingCart::where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($dbRows as $row) {
                $cart[(string) $row->book_id] = (int) $row->quantity;
            }
            $request->session()->put('cart', $cart);
        } else {
            $cart = $request->session()->get('cart', []);
        }

        $bookIds = array_keys($cart);

        // Carregar detalhes dos livros
        $books = Book::whereIn('book_id', $bookIds)->get()->keyBy(function ($b) {
            return (string) $b->book_id;
        });

        return view('pages.cart', compact('cart', 'books'));
    }

    /**
     * Add a book to the cart.
     */
    public function add(Request $request)
    {
        // Bloquear Admin com resposta JSON correta se for AJAX
        if ($redirect = $this->denyAdminAccess()) {
            return ($request->ajax() || $request->wantsJson())
                ? response()->json(['success' => false, 'message' => 'Admins não compram.'], 403)
                : $redirect;
        }

        $data = $request->validate([
            'book_id' => 'required|integer|exists:book,book_id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $id = (int) $data['book_id'];
        $quantity = (int) ($data['quantity'] ?? 1);

        if (Auth::check()) {
            if (Auth::user()->is_blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sua conta encontra-se bloqueada.',
                ], 403);
            }
            // Lógica Base de Dados
            $row = ShoppingCart::where('user_id', Auth::id())->where('book_id', $id)->first();
            if ($row) {
                $row->quantity = (int) $row->quantity + $quantity;
                $row->save();
            } else {
                ShoppingCart::create([
                    'user_id' => Auth::id(),
                    'book_id' => $id,
                    'quantity' => $quantity,
                ]);
            }

            // Sincronizar Sessão
            $db = ShoppingCart::where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($db as $r) {
                $cart[(string) $r->book_id] = (int) $r->quantity;
            }
            $request->session()->put('cart', $cart);

        } else {
            // Lógica Sessão (Guest)
            $idStr = (string) $id;
            $cart = $request->session()->get('cart', []);

            if (isset($cart[$idStr])) {
                $cart[$idStr] += $quantity;
            } else {
                $cart[$idStr] = $quantity;
            }

            $request->session()->put('cart', $cart);
        }

        // --- RESPOSTA (JSON para AJAX, Redirect para Normal) ---
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Livro adicionado com sucesso!',
                'cartCount' => array_sum($request->session()->get('cart', []))
            ]);
        }

        return redirect()->back()->with('success', 'Livro adicionado ao carrinho.');
    }

    /**
     * Update quantity.
     */
    public function update(Request $request)
    {
        if ($redirect = $this->denyAdminAccess()) {
            return $redirect;
        }

        $data = $request->validate([
            'book_id' => 'required|integer|exists:book,book_id',
            'quantity' => 'required|integer|min:0',
        ]);

        $id = (int) $data['book_id'];
        $quantity = (int) $data['quantity'];

        if (Auth::check()) {
            if (Auth::user()->is_blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sua conta encontra-se bloqueada.',
                ], 403);
            }
            $row = ShoppingCart::where('user_id', Auth::id())->where('book_id', $id)->first();
            if ($quantity <= 0) {
                if ($row) { $row->delete(); }
            } else {
                if ($row) {
                    $row->quantity = $quantity;
                    $row->save();
                } else {
                    ShoppingCart::create([
                        'user_id' => Auth::id(), 'book_id' => $id, 'quantity' => $quantity,
                    ]);
                }
            }

            // Sync session
            $db = ShoppingCart::where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($db as $r) { $cart[(string) $r->book_id] = (int) $r->quantity; }
            $request->session()->put('cart', $cart);

        } else {
            $idStr = (string) $id;
            $cart = $request->session()->get('cart', []);

            if ($quantity <= 0) {
                unset($cart[$idStr]);
            } else {
                $cart[$idStr] = $quantity;
            }
            $request->session()->put('cart', $cart);
        }

        // Resposta AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $stats = $this->getCartTotalAndCount($request);
            
            // Calcular subtotal da linha específica
            $book = Book::find($id); 
            $subtotal = $book ? ($book->price * $quantity) : 0;

            return response()->json([
                'success' => true,
                'cartCount' => $stats['count'],
                'newSubtotal' => number_format($subtotal, 2, ',', ' ') . ' €',
                'newTotal' => number_format($stats['total'], 2, ',', ' ') . ' €',
                'message' => 'Carrinho atualizado'
            ]);
        }

        return redirect()->back()->with('success', 'Carrinho atualizado.');
    }

    /**
     * Remove item.
     */
    public function remove(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|integer|exists:book,book_id',
        ]);
        $id = (int) $data['book_id'];

        if (Auth::check()) {
            if (Auth::user()->is_blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sua conta encontra-se bloqueada.',
                ], 403);
            }
            ShoppingCart::where('user_id', Auth::id())->where('book_id', $id)->delete();
            
            // Sync session manual para garantir consistência imediata
            $db = ShoppingCart::where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($db as $r) { $cart[(string) $r->book_id] = (int) $r->quantity; }
            $request->session()->put('cart', $cart);

        } else {
            $idStr = (string) $id;
            $cart = $request->session()->get('cart', []);
            if (isset($cart[$idStr])) {
                unset($cart[$idStr]);
                $request->session()->put('cart', $cart);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            $stats = $this->getCartTotalAndCount($request);

            return response()->json([
                'success' => true,
                'cartCount' => $stats['count'],
                'newTotal' => number_format($stats['total'], 2, ',', ' ') . ' €',
                'isEmpty' => $stats['count'] === 0
            ]);
        }

        return redirect()->back()->with('success', 'Livro removido.');
    }

    public function checkout(Request $request)
    {
        if ($redirect = $this->denyAdminAccess()) { return $redirect; }

        if (Auth::check()) {
            if (Auth::user()->is_blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sua conta encontra-se bloqueada.',
                ], 403);
            }
            $db = ShoppingCart::where('user_id', Auth::id())->get();
            $cart = [];
            $bookIds = [];
            foreach ($db as $r) { $cart[(string) $r->book_id] = (int) $r->quantity; $bookIds[] = $r->book_id; }
        } else {
            $cart = $request->session()->get('cart', []);
            $bookIds = array_keys($cart);
        }

        $books = Book::whereIn('book_id', $bookIds)->get()->keyBy(function ($b) {
            return (string) $b->book_id;
        });

        $total = 0;
        foreach ($cart as $id => $qty) {
            $book = $books[$id] ?? null;
            if ($book) {
                $total += $book->price * $qty;
            }
        }

        $selectedPayment = $request->query('payment_method', old('payment_method', null));

        return view('pages.checkout', compact('cart', 'books', 'total', 'selectedPayment'));
    }

    public function complete(Request $request)
    {
        if ($redirect = $this->denyAdminAccess()) { return $redirect; }

        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Por favor inicia sessão para completar a compra.');
        }
        
        if (Auth::user()->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'A sua conta encontra-se bloqueada.',
            ], 403);
        }

        $userId = Auth::id();
        
        $data = $request->validate([
            'payment_method' => 'required|string|in:MBWay,Card,Paypal',
            'mbway_phone'    => 'nullable|string|required_if:payment_method,MBWay',
            'card_number'    => 'nullable|string|required_if:payment_method,Card',
            'card_cvc'       => 'nullable|string|required_if:payment_method,Card',
            'paypal_email'   => 'nullable|email|required_if:payment_method,Paypal',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'address'        => 'required|string|max:255',
            'postal_code'    => 'required|string|max:20',
        ]);

        $cartRows = ShoppingCart::where('user_id', $userId)->get();
        if ($cartRows->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'O seu carrinho está vazio na base de dados.');
        }

        try {
            return DB::transaction(function () use ($cartRows, $userId, $data) {
                $total = 0;
                $itemsToCreate = [];

                foreach ($cartRows as $row) {
                    $book = Book::find($row->book_id);
                    if (!$book || $book->available_stock < $row->quantity) {
                        throw new \Exception("Stock insuficiente para o livro: " . ($book->name ?? 'ID '.$row->book_id));
                    }
                    $total += (float) $book->price * (int) $row->quantity;
                    
                    // Decrementar stock
                    $book->decrement('available_stock', $row->quantity);

                    $itemsToCreate[] = [
                        'book_id' => $book->book_id,
                        'unit_price_at_purchase' => $book->price,
                        'quantity' => $row->quantity,
                    ];
                }

                $payment = Payment::create([
                    'payment_method' => $data['payment_method'],
                    'amount' => $total,
                ]);

                $order = Order::create([
                    'user_id'     => $userId,
                    'payment_id' => $payment->payment_id,
                    'total_price' => $total, 
                    'date'       => now(),
                ]);

                foreach ($itemsToCreate as $item) {
                    $item['order_id'] = $order->order_id;
                    OrderBook::create($item);
                }

                // Criar notificação de pagamento aprovado (BD exige 'message' not null)
                $totalFmt = number_format($total, 2, ',', ' ');
                PaymentApprovedNotification::create([
                    'user_id' => $userId,
                    'order_id' => $order->order_id,
                    'date' => now(),
                    'message' => "Pagamento aprovado! Encomenda #{$order->order_id} ({$totalFmt} €) processada.",
                ]);

                ShoppingCart::where('user_id', $userId)->delete();
                session()->put('cart', []);

                return redirect()->route('order.show', $order->order_id)
                                 ->with('success', 'Encomenda #' . $order->order_id . ' processada!');
            });

        } catch (\Exception $e) {
            Log::error('Erro no Checkout: ' . $e->getMessage());
            return redirect()->route('cart.index')->withErrors(['checkout' => 'Erro: ' . $e->getMessage()]);
        }
    }

    public function confirmation(Request $request, $orderId)
    {
        if ($redirect = $this->denyAdminAccess()) { return $redirect; }
        if (!Auth::check()) { return redirect()->route('login'); }
        if (Auth::user()->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'A sua conta encontra-se bloqueada.',
            ], 403);
        }

        $order = Order::with(['books', 'payment'])
            ->where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $bookIds = $order->books->pluck('book_id')->all();
        $books = Book::whereIn('book_id', $bookIds)->get()->keyBy(fn ($b) => (string) $b->book_id);

        return view('pages.order_confirmation', compact('order', 'books'));
    }

    protected function denyAdminAccess(): ?RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()
                ->route('admin.dashboard')
                ->withErrors(['cart' => 'Administradores não podem comprar produtos.']);
        }
        return null;
    }

    private function getCartTotalAndCount(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->is_blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sua conta encontra-se bloqueada.',
                ], 403);
            }
            $db = ShoppingCart::where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($db as $r) { 
                $cart[(string) $r->book_id] = (int) $r->quantity; 
            }
        } else {
            $cart = $request->session()->get('cart', []);
        }

        if (empty($cart)) {
            return ['count' => 0, 'total' => 0];
        }

        $bookIds = array_keys($cart);
        $books = Book::whereIn('book_id', $bookIds)->get()->keyBy(function ($b) {
            return (string) $b->book_id;
        });

        $total = 0;
        foreach ($cart as $id => $qty) {
            $book = $books[$id] ?? null;
            if ($book) {
                $total += $book->price * $qty;
            }
        }

        return [
            'count' => array_sum($cart),
            'total' => $total
        ];
    }

    public function saveDeliveryInfo(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ]);

        // Guardar na sessão
        $request->session()->put('delivery_info', $data);

        return response()->json(['success' => true]);
    }
}