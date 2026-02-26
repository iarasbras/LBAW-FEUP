<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Seller;

class UserController extends Controller
{
    /**
     * List users with optional search, status and role filters.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'all');
        $role = $request->input('role', 'all'); 

        $users = User::query()
            ->with('sellerAccount')
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . strtolower($search) . '%';
                $query->where(function ($subQuery) use ($like, $search) {
                    $subQuery
                        ->whereRaw('LOWER(username) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$like]);

                    if (is_numeric($search)) {
                        $subQuery->orWhere('user_id', (int) $search);
                    }
                });
            })
            ->when($status === 'blocked', fn($query) => $query->where('is_blocked', true))
            ->when($status === 'active', fn($query) => $query->where('is_blocked', false)->where('is_active', true))
            ->when($status === 'deleted', fn($query) => $query->where('is_active', false))
            ->when($role === 'seller', fn($query) => $query->whereHas('sellerAccount'))
            ->when($role === 'client', fn($query) => $query->whereDoesntHave('sellerAccount'))
            ->orderBy('username')
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.show', [
            'users' => $users,
            'search' => $search,
            'status' => $status,
            'role' => $role,
        ]);
    }

    /**
     * Show the form to create a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Persist a new user and optionally create seller entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:250', 'unique:users,username'],
            'email' => ['required', 'email', 'max:250', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_blocked' => ['nullable', 'boolean'],
            'is_seller' => ['required', 'boolean'], 
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'is_blocked' => (bool) ($validated['is_blocked'] ?? false),
            ]);

            if ($validated['is_seller']) {
                DB::table('seller')->insert(['seller_id' => $user->user_id]);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->withSuccess('Utilizador criado com sucesso.');
    }

    /**
     * Show user purchase history.
     */
    public function purchaseHistory(User $user): View
    {
        $orders = DB::table('orders as o')
            ->leftJoin('order_book as ob', 'o.order_id', '=', 'ob.order_id')
            ->leftJoin('book as b', 'ob.book_id', '=', 'b.book_id')
            ->where('o.user_id', $user->user_id)
            ->select('o.order_id', 'o.total_price', 'o.date', 'o.payment_id', 'b.name as title', 'ob.quantity')
            ->orderBy('o.order_id', 'desc')
            ->get();
        
        // Agrupar por order
        $ordersMap = [];
        foreach ($orders as $row) {
            if (!isset($ordersMap[$row->order_id])) {
                $ordersMap[$row->order_id] = [
                    'order_id' => $row->order_id,
                    'total_price' => $row->total_price,
                    'date' => $row->date,
                    'status' => $row->payment_id ? 'Completado' : 'Pendente',
                    'books' => []
                ];
            }
            if ($row->title) {
                $ordersMap[$row->order_id]['books'][] = [
                    'title' => $row->title,
                    'quantity' => $row->quantity,
                ];
            }
        }
        
        return view('admin.users.purchase-history', [
            'user' => $user,
            'orders' => array_values($ordersMap),
        ]);
    }

    /**
     * Show the edit form.
     */
    public function edit(User $user): View
    {
        $user->load('sellerAccount');
        
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update an existing user and manage seller table entry.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'is_blocked' => 'required|boolean', 
            'is_seller' => 'required|boolean',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'is_blocked' => $validated['is_blocked'],
            ]);

            if ($validated['is_seller']) {
                DB::table('seller')->updateOrInsert(['seller_id' => $user->user_id]);
            } else {
                DB::table('seller')->where('seller_id', $user->user_id)->delete();
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilizador atualizado com sucesso!');
    }

    /**
     * Toggle the blocked status for a user via AJAX.
     */
    public function toggleBlock(Request $request, User $user) 
    {
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_blocked' => $user->is_blocked,
                'message' => $user->is_blocked ? 'Utilizador bloqueado.' : 'Utilizador desbloqueado.'
            ]);
        }

        return back()->withSuccess(
            $user->is_blocked ? 'Utilizador bloqueado.' : 'Utilizador desbloqueado.'
        );
    }

    public function delete(User $user) {
        if ($user->profile_img_url) {
            Storage::disk('public')->delete($user->profile_img_url);
            $user->profile_img_url = null;
        }

        $userId = $user->getKey();
        $timestamp = now()->timestamp;
        $user->username = "deleted-{$userId}-{$timestamp}";
        $user->email = "deleted-{$userId}-{$timestamp}@liberato.com";
        $user->password_hash = Str::random(60);
        $user->is_active = false;

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'A conta ' . $userId . ' foi eliminada com sucesso.');
    }
}