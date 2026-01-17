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

    /**
     * LẤY DỮ LIỆU GIỎ HÀNG (LIVE DATA TỪ DB)
     * Thay vì tin tưởng dữ liệu trong Session, ta dùng ID để lấy lại data mới nhất từ DB.
     */
    public function getCartDetails(): array
    {
        // 1. Lấy giỏ hàng thô từ Session (Chỉ chứa ID và Quantity là quan trọng nhất)
        // Cấu trúc Session cũ: [ 1 => [...], 2 => [...] ]
        $sessionCart = $this->getCart();
        
        // Lấy danh sách ID sản phẩm
        $productIds = array_keys($sessionCart);

        // 2. Query Database để lấy thông tin sản phẩm thật
        // Hàm findByIds này phải có trong ProductRepository (đã tạo ở bước trước)
        $products = $this->productRepository->findByIds($productIds);

        $cartItems = [];
        $subTotal = 0;
        $shipping = 3.00;

        // 3. Map dữ liệu từ DB vào Giỏ hàng
        foreach ($products as $product) {
            $id = $product->id;
            
            // Lấy số lượng từ session (nếu không có thì mặc định 1)
            $quantity = $sessionCart[$id]['quantity'] ?? 1;

            // Tính toán
            $lineTotal = $product->price * $quantity;
            $subTotal += $lineTotal;

            // Tạo item chuẩn để trả về View
            $cartItems[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'quantity' => $quantity,
                'price'    => $product->price,
                // [QUAN TRỌNG] Lấy ảnh từ DB -> img_url. Nếu null thì lấy ảnh mặc định
                'image'    => $product->image_url ?? 'img/product-1.png', 
                'model'    => $product->sku ?? 'N/A', // Lấy SKU mới nhất
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
        // Kiểm tra sản phẩm có tồn tại không
        $product = $this->productRepository->find($productId);
        if (!$product) {
            return ['status' => false, 'message' => 'Product not found!'];
        }

        $cart = $this->getCart();

        // Chỉ cần lưu ID và Quantity là đủ (Data khác sẽ lấy live ở getCartDetails)
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