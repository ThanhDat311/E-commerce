<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Product;

class CheckoutTest extends TestCase
{
    use WithFaker; // Sử dụng để fake dữ liệu (tên, email, sđt)
    use RefreshDatabase;

    /**
     * Test case: User cannot access checkout page if cart is empty.
     * Logic: Should redirect to Shop page.
     */
    public function test_guest_cannot_access_checkout_with_empty_cart()
    {
        // 1. Arrange: Ensure session is empty
        session(['cart' => []]);

        // 2. Act: Try to access checkout route
        $response = $this->get(route('cart.checkout'));

        // 3. Assert: Check redirection to 'shop' route
        $response->assertStatus(302);
        $response->assertRedirect(route('shop'));
    }

    /**
     * Test case: User can access checkout page with items in cart.
     * Logic: Should return status 200 and display correct calculations.
     */
    public function test_guest_can_access_checkout_with_items()
    {
        // 1. Arrange: Mock cart data in session
        $cartData = [
            1 => [
                'id' => 1,
                'name' => 'Test Product',
                'price' => 100,
                'quantity' => 2, // Subtotal = 200
                'image' => 'img/test.png'
            ]
        ];
        session(['cart' => $cartData]);

        // 2. Act
        $response = $this->get(route('cart.checkout'));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertViewIs('checkout');

        // Verify view data calculation: Subtotal (200) + Shipping (3) = 203
        $response->assertViewHas('subTotal', 200);
        $response->assertViewHas('total', 203);
    }

    /**
     * Test case: Validation fails when required fields are missing.
     * Logic: Should redirect back with errors.
     */
    public function test_place_order_requires_validation()
    {
        // 1. Arrange: Cart has items
        session(['cart' => [1 => ['id' => 1, 'price' => 10, 'quantity' => 1]]]);

        // 2. Act: Submit empty data
        $response = $this->post(route('cart.placeOrder'), []);

        // 3. Assert: Check validation errors for required fields
        $response->assertSessionHasErrors(['first_name', 'phone', 'email', 'address']);
    }

    /**
     * Test case: Order is placed successfully with valid data.
     * Logic: Cart should be cleared, and user redirected to success page.
     */
    public function test_place_order_successfully()
    {
        // 1. Arrange: Tạo dữ liệu THẬT trong DB
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category' // Nếu model Category có fillable slug
        ]);

        // FIX: Dùng đúng tên cột khớp với Model & Database mới sửa
        $product = Product::create([
            'name'           => 'Real Product',
            'sku'            => 'TEST-SKU-001', // <-- Bắt buộc phải có
            'price'          => 100,
            'stock_quantity' => 50,             // <-- Dùng stock_quantity
            'category_id'    => $category->id,
            'description'    => 'Test desc',
            'image_url'      => 'img/test.png'  // <-- Dùng image_url
        ]);

        // 2. Mock Session Giỏ hàng
        // Lưu ý: Session cart vẫn dùng key cũ ('quantity', 'image') nếu CartService của bạn code như vậy.
        // Session không liên quan trực tiếp đến DB nên giữ nguyên logic cũ của CartService.
        session(['cart' => [
            $product->id => [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => 1,            // Trong Session vẫn gọi là quantity (số lượng mua)
                'image'    => 'img/test.png'
            ]
        ]]);

        // Fake form data
        $formData = [
            'first_name'     => $this->faker->firstName,
            'last_name'      => $this->faker->lastName,
            'email'          => $this->faker->email,
            'phone'          => '0901234567',
            'address'        => $this->faker->address,
            'payment_method' => 'cod', 
        ];

        // 3. Act
        $response = $this->post(route('cart.placeOrder'), $formData);

        // 4. Assert
        $response->assertRedirect(route('cart.orderSuccess'));
        $response->assertSessionHas('success', 'Đơn hàng đã được đặt thành công!');
        $this->assertEmpty(session('cart'));
    }
}
