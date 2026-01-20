<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AiFeatureStore;
use Exception;

class OrderService
{
    protected $orderRepository;
    protected $cartService;
    protected $riskService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartService $cartService,
        RiskManagementService $riskService 
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
        $this->riskService = $riskService;
    }

    public function processCheckout(array $customerData, ?int $userId = null)
    {
        // 1. Lấy dữ liệu giỏ hàng live từ DB để đảm bảo giá cả chính xác
        $cartData = $this->cartService->getCartDetails();
        $cartItems = $cartData['cartItems'];

        if (empty($cartItems)) {
            throw new Exception("Cart is empty");
        }

        // --- CHECK RỦI RO (AI RISK MANAGEMENT) ---
        $dataToCheck = array_merge($customerData, ['total' => $cartData['total']]);
        
        // Gọi service check rủi ro
        $riskAnalysis = $this->riskService->assessOrderRisk($dataToCheck, $userId);

        // Nếu bị chặn thì throw Exception ngay
        if (!$riskAnalysis['allowed']) {
            throw new Exception("Security Alert: Transaction blocked. Reason: {$riskAnalysis['reason']}");
        }
        // -----------------------------------------

        DB::beginTransaction();

        try {
            // Chuẩn bị data để tạo Order
            $orderData = [
                'user_id'        => $userId,
                'first_name'     => $customerData['first_name'],
                'last_name'      => $customerData['last_name'] ?? '',
                'email'          => $customerData['email'],
                'phone'          => $customerData['phone'],
                'address'        => $customerData['address'],
                'note'           => $customerData['note'] ?? null,
                'total'          => $cartData['total'],
                
                // [FIXED] Sửa 'status' thành 'order_status' để khớp với Model & DB
                'order_status'   => 'pending', 
                
                'payment_method' => $customerData['payment_method'] ?? 'cod', // Lấy từ form hoặc mặc định COD
                'payment_status' => 'unpaid' // Nên set rõ ràng
            ];

            // Tạo Order Master
            $order = $this->orderRepository->createOrder($orderData);

            // Tạo Order Items (Chi tiết đơn hàng)
            foreach ($cartItems as $item) {
                $this->orderRepository->createOrderItem([
                    'order_id'     => $order->id,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                    'total'        => $item['price'] * $item['quantity']
                ]);
            }

            // --- CẬP NHẬT LOG AI (Gắn order_id vào log đã ghi trước đó) ---
            if (!empty($riskAnalysis['log_id'])) {
                AiFeatureStore::where('id', $riskAnalysis['log_id'])->update([
                    'order_id' => $order->id
                ]);
            }
            // -------------------------------------------------------------

            DB::commit();
            
            // Xóa giỏ hàng sau khi đặt thành công
            $this->cartService->clearCart();
            
            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Checkout Failed: " . $e->getMessage());
            throw $e; // Ném lỗi ra để Controller bắt được và hiển thị cho user
        }
    }
}