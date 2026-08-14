<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get the current active cart for the authenticated user or guest.
     * @return \App\Models\Cart|null
     */
    public function getCart(): ?Cart
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            return $user->activeCart() ?? $user->carts()->create(['status' => 'active']);
        }

        // Guest Cart logic
        $sessionId = request()->cookie('guest_session_id') ?? session()->getId();

        $cart = Cart::where('session_id', $sessionId)
            ->where('status', 'active')
            ->first() ?? Cart::create([
                        'session_id' => $sessionId,
                        'status' => 'active'
                    ]);

        // Queue cookie so session ID persists across login/register redirects & session regeneration
        \Illuminate\Support\Facades\Cookie::queue('guest_session_id', $sessionId, 60 * 24 * 7);
        session(['guest_session_id' => $sessionId]);

        return $cart;
    }

    /**
     * Add an item to the cart.
     */
    public function addItem(Product $product, int $quantity = 1)
    {
        $cart = $this->getCart();
        if (!$cart)
            return false;

        /** @var \App\Models\CartItem|null $item */
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return true;
    }

    /**
     * Check if a product is in the cart.
     */
    public function isInCart(Product $product): bool
    {
        $cart = $this->getCart();
        if (!$cart)
            return false;

        return $cart->items()->where('product_id', $product->id)->exists();
    }

    /**
     * Merge guest cart into user cart after login or registration.
     */
    public function mergeGuestCart(?string $sessionId = null)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user)
            return;

        // Retrieve guest session ID from cookie, session, or passed parameter
        $guestSessionId = request()->cookie('guest_session_id')
            ?? session('guest_session_id')
            ?? $sessionId;

        if (!$guestSessionId)
            return;

        $guestCart = Cart::where('session_id', $guestSessionId)
            ->where('status', 'active')
            ->first();

        // Fallback: If not found by cookie, search by passed parameter if different
        if (!$guestCart && $sessionId && $sessionId !== $guestSessionId) {
            $guestCart = Cart::where('session_id', $sessionId)
                ->where('status', 'active')
                ->first();
        }

        if (!$guestCart)
            return;

        $userCart = $this->getCart();

        // If the guest cart is already the user's active cart, assign user_id directly
        if ($guestCart->id === $userCart->id) {
            $userCart->update(['user_id' => $user->id, 'session_id' => null]);
            \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('guest_session_id'));
            session()->forget('guest_session_id');
            return;
        }

        foreach ($guestCart->items as $item) {
            $existingItem = $userCart->items()->where('product_id', $item->product_id)->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $item->quantity);
                $item->delete();
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('guest_session_id'));
        session()->forget('guest_session_id');
    }

    /**
     * Update item quantity.
     */
    public function updateQuantity(int $itemId, int $quantity)
    {
        $cart = $this->getCart();
        if (!$cart)
            return false;

        /** @var \App\Models\CartItem|null $item */
        $item = $cart->items()->find($itemId);
        if (!$item)
            return false;

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $quantity]);
        }

        return true;
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $itemId)
    {
        $cart = $this->getCart();
        if (!$cart)
            return false;

        return $cart->items()->where('id', $itemId)->delete();
    }

    /**
     * Get cart item count.
     */
    public function getCount()
    {
        $cart = $this->getCart();
        return $cart ? $cart->items()->sum('quantity') : 0;
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->items()->delete();
        }
    }
}
