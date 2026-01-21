<?php

use App\Models\User;
use App\Services\CartService;
use App\Services\RiskManagementService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can checkout successfully', function () {
    /** @var \Tests\TestCase $this */ 
    $this->withoutExceptionHandling();

    // 1. Arrange
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    
    // Sử dụng helper function thay vì $this->mockCartData
    $mockData = getMockCartData();

    $this->mock(CartService::class, function ($mock) use ($mockData) {
        $mock->shouldReceive('getCartDetails')->andReturn($mockData);
        $mock->shouldReceive('clearCart')->once();
    });

    $this->mock(RiskManagementService::class, function ($mock) {
        $mock->shouldReceive('assessOrderRisk')->andReturn([
            'allowed' => true,
            'score' => 0.1,
            'reason' => '',
            'log_id' => 1
        ]);
    });

    // 2. Act
    $response = $this->actingAs($user)
        ->post(route('cart.placeOrder'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Street',
            'payment_method' => 'cod'
        ]);

    // 3. Assert
    $response->assertRedirect(route('cart.orderSuccess'));
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'email' => 'john@example.com',
        'order_status' => 'pending'
    ]);
});

test('guest user can checkout successfully', function () {
    /** @var \Tests\TestCase $this */

    $mockData = getMockCartData();

    $this->mock(CartService::class, function ($mock) use ($mockData) {
        $mock->shouldReceive('getCartDetails')->andReturn($mockData);
        $mock->shouldReceive('clearCart')->once();
    });

    $this->mock(RiskManagementService::class, function ($mock) {
        $mock->shouldReceive('assessOrderRisk')->andReturn([
            'allowed' => true, 
            'score' => 0.2,
            'reason' => 'Guest',
            'log_id' => null
        ]);
    });

    $response = $this->post(route('cart.placeOrder'), [
        'first_name' => 'Guest',
        'last_name'  => 'User',
        'email' => 'guest@example.com',
        'phone' => '0987654321',
        'address' => 'Guest House',
        'payment_method' => 'cod'
    ]);

    $response->assertRedirect(route('cart.orderSuccess'));
    $this->assertDatabaseHas('orders', [
        'user_id' => null,
        'email' => 'guest@example.com',
    ]);
});

test('checkout is blocked if cart is empty', function () {
    /** @var \Tests\TestCase $this */

    $this->mock(CartService::class, function ($mock) {
        $mock->shouldReceive('getCartDetails')->andReturn([
            'cartItems' => [],
            'total' => 0
        ]);
    });

    $response = $this->post(route('cart.placeOrder'), [
        'first_name' => 'Tester',
        'email' => 'test@test.com',
        'phone' => '123',
        'address' => 'HCM'
    ]);

    // Tùy vào cách controller xử lý lỗi (redirect back hay trả về view lỗi)
    // Nếu controller throw Exception, Pest sẽ fail trừ khi dùng ->withoutExceptionHandling()
    // Giả sử controller redirect back with errors:
    $response->assertSessionHasErrors(); 
    $this->assertDatabaseCount('orders', 0);
});

test('checkout is blocked if risk score is high', function () {
    /** @var \Tests\TestCase $this */
    
    $mockData = getMockCartData();

    $this->mock(CartService::class, function ($mock) use ($mockData) {
        $mock->shouldReceive('getCartDetails')->andReturn($mockData);
    });

    $this->mock(RiskManagementService::class, function ($mock) {
        $mock->shouldReceive('assessOrderRisk')->andReturn([
            'allowed' => false,
            'score' => 0.9,
            'reason' => 'High Risk Detected',
            'log_id' => 99
        ]);
    });

    $response = $this->post(route('cart.placeOrder'), [
        'first_name' => 'Hacker',
        'email' => 'hacker@darkweb.com',
        'phone' => '000',
        'address' => 'Unknown'
    ]);

    $this->assertDatabaseCount('orders', 0);
});

// --- HELPER FUNCTION (Nằm ngoài test cases) ---
function getMockCartData(): array {
    return [
        'cartItems' => [
            [
                'id' => 1,
                'name' => 'Test Product',
                'quantity' => 1,
                'price' => 100,
                'total' => 100
            ]
        ],
        'total' => 100
    ];
}