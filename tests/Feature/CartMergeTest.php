<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Tests\TestCase;

class CartMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_is_merged_on_login()
    {
        $product = Product::create([
            'name' => 'Paracetamol',
            'slug' => 'paracetamol',
            'price' => 500,
            'stock' => 50,
            'category' => 'Medicine',
            'brand' => 'Panadol',
            'is_synced' => false,
        ]);

        $cartService = app(CartService::class);
        $cartService->addItem($product, 2);

        $guestSessionId = session()->getId();

        $user = User::factory()->create(['uuid' => (string) \Illuminate\Support\Str::uuid()]);

        // Simulate login event with guest session cookie set
        $this->withUnencryptedCookie('guest_session_id', $guestSessionId)
            ->actingAs($user);

        Event::dispatch(new Login('web', $user, false));

        // Verify cart merged to user
        $userCart = $cartService->getCart();
        $this->assertEquals($user->id, $userCart->user_id);
        $this->assertEquals(2, $cartService->getCount());
    }

    public function test_guest_cart_is_merged_on_registration()
    {
        $product = Product::create([
            'name' => 'Vitamin C',
            'slug' => 'vitamin-c',
            'price' => 1200,
            'stock' => 30,
            'category' => 'Vitamins',
            'brand' => 'Emzor',
            'is_synced' => false,
        ]);

        $cartService = app(CartService::class);
        $cartService->addItem($product, 3);

        $guestSessionId = session()->getId();

        $user = User::factory()->create(['uuid' => (string) \Illuminate\Support\Str::uuid()]);

        $this->withUnencryptedCookie('guest_session_id', $guestSessionId)
            ->actingAs($user);

        Event::dispatch(new Registered($user));

        $userCart = $cartService->getCart();
        $this->assertEquals($user->id, $userCart->user_id);
        $this->assertEquals(3, $cartService->getCount());
    }
}
