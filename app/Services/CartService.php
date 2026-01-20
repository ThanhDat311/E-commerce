<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getCart(): array
    {
        return Session::get('cart', []);
    }

    public function getCartDetails(): array
    {
        $sessionCart = $this->getCart();
        $productIds = array_keys($sessionCart);

        // Nếu giỏ hàng trống, return sớm để tránh lỗi query
        if (empty($productIds)) {
            return [
                'cartItems' => [],
                'subTotal'  => 0,
                'shipping'  => 0,
                'total'     => 0
            ];
        }

        $products = $this->productRepository->findByIds($productIds);

        $cartItems = [];
        $subTotal = 0;
        $shipping = 3.00;

       foreach ($products as $product) {
            $id = $product->id;
            
            $quantity = $sessionCart[$id]['quantity'] ?? 1;

            $lineTotal = $product->price * $quantity;
            $subTotal += $lineTotal;

            // [FIXED] PHẢI DÙNG $cartItems[$id] (Dùng ID làm Key)
            // Thay vì $cartItems[] (Dùng số thứ tự 0,1,2 làm Key)
            $cartItems[$id] = [ 
                'id'       => $product->id,
                'name'     => $product->name,
                'quantity' => $quantity,
                'price'    => $product->price,
                'image'    => $product->image_url ?? 'img/product-1.png', 
                'model'    => $product->sku ?? 'N/A',
            ];
        }

        return [
            'cartItems' => $cartItems,
            'subTotal'  => $subTotal,
            'shipping'  => $shipping,
            'total'     => $subTotal + $shipping
        ];
    }

    public function addToCart(int $productId, int $quantity = 1): array
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            return ['status' => false, 'message' => 'Product not found!'];
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
           $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);

        return ['status' => true, 'message' => 'Product added to cart successfully!'];
    }

    public function removeFromCart(int $id): void
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
    }
    
    public function clearCart(): void
    {
        Session::forget('cart');
    }
}