<?php

use App\Models\Order;
use App\Services\CartService;
use App\Services\RiskManagementService;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

test('database rolls back order if creating order items fails', function () {
    /** @var \Tests\TestCase $this */ // FIX: Báo IDE biết ngữ cảnh là TestCase
    
    // 1. Arrange
    $this->mock(CartService::class, function ($mock) {
        $mock->shouldReceive('getCartDetails')->andReturn([
            'cartItems' => [
                ['id' => 1, 'name' => 'Item A', 'quantity' => 1, 'price' => 100, 'total' => 100],
                ['id' => 2, 'name' => 'Item B', 'quantity' => 1, 'price' => 200, 'total' => 200],
            ],
            'total' => 300
        ]);
    });

    $this->mock(RiskManagementService::class, function ($mock) {
        $mock->shouldReceive('assessOrderRisk')->andReturn(['allowed' => true, 'score' => 0, 'log_id' => null]);
    });

    // Mock Repository để gây lỗi
    $this->mock(OrderRepositoryInterface::class, function (MockInterface $mock) {
        // createOrder thành công
        $mock->shouldReceive('createOrder')
            ->once()
            ->andReturn(new Order(['id' => 123])); 

        // createOrderItem sẽ ném lỗi
        $mock->shouldReceive('createOrderItem')
            ->andThrow(new Exception('Simulated Database Error during Item Insert'));
    });

    // 2. Act
    try {
        $this->post(route('cart.placeOrder'), [
            'first_name' => 'Rollback',
            'last_name' => 'Tester',
            'email' => 'fail@test.com',
            'phone' => '111',
            'address' => 'Nowhere',
            'payment_method' => 'cod'
        ]);
    } catch (Exception $e) {
        // Bắt lỗi để test không dừng
    }

    // 3. Assert
    // Transaction Rollback thành công thì count phải bằng 0
    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseCount('order_items', 0);
});