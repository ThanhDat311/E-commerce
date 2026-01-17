<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\RiskManagementService;
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

    // FIX LỖI 1: Thêm dấu ? vào trước int
    public function processCheckout(array $customerData, ?int $userId = null)
    {
        $cartData = $this->cartService->getCartDetails();
        $cartItems = $cartData['cartItems'];

        if (empty($cartItems)) {
            throw new Exception("Cart is empty");
        }

        // --- NEW: AI RISK CHECK (Bước kiểm tra an ninh) ---
        // Gộp data để check
        $dataToCheck = array_merge($customerData, ['total' => $cartData['total']]);

        $riskAnalysis = $this->riskService->assessOrderRisk($dataToCheck, $userId);

        if (!$riskAnalysis['allowed']) {
            throw new Exception("Security Alert: Transaction blocked... Reason: {$riskAnalysis['reason']}");
        }
        // --------------------------------------------------

        // 2. Bắt đầu Transaction
        DB::beginTransaction();

        try {

            $orderData = [
                'user_id' => $userId,
                'first_name' => $customerData['first_name'],
                'last_name' => $customerData['last_name'] ?? '',
                'email' => $customerData['email'],
                'phone' => $customerData['phone'],
                'address' => $customerData['address'],
                'note' => $customerData['note'] ?? null,
                'total' => $cartData['total'],
                'status' => 'pending',
                'payment_method' => 'cod'
            ];

            $order = $this->orderRepository->createOrder($orderData);

            foreach ($cartItems as $item) {
                $this->orderRepository->createOrderItem([
                    'order_id' => $order->id, // Model dùng property ->id, không phải method ->id()
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);
            }

            // --- NEW: CẬP NHẬT LOG AI ---
            // Nếu có log_id, hãy cập nhật order_id vào bảng ai_feature_store
            if (!empty($riskAnalysis['log_id'])) {
                AiFeatureStore::where('id', $riskAnalysis['log_id'])->update([
                    'order_id' => $order->id
                ]);
            }
            // -----------------------------

            DB::commit();
            $this->cartService->clearCart();
            return $order;
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Checkout Failed: " . $e->getMessage());
            throw $e;
        }
    }
}
