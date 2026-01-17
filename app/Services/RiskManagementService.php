<?php

namespace App\Services;

use App\Models\AiFeatureStore; // Import Model
use Illuminate\Support\Facades\Log;

class RiskManagementService
{
    /**
     * Phân tích rủi ro và GHI LOG vào Feature Store
     */
    public function assessOrderRisk(array $orderData, ?int $userId): array
    {
        $riskScore = 0.0;
        $reasons = [];

        // --- LOGIC TÍNH ĐIỂM (GIỮ NGUYÊN NHƯ CŨ) ---
        
        // 1. Identity Check
        if (is_null($userId)) {
            $riskScore += 0.2;
            $reasons[] = "Guest checkout";
        }

        // 2. Value Check
        $totalAmount = $orderData['total'] ?? 0;
        if ($totalAmount > 3000) {
            $riskScore += 0.4;
            $reasons[] = "High value order (> $3000)";
        } elseif ($totalAmount > 1000) {
            $riskScore += 0.1;
        }

        // 3. Time Check (2h - 5h sáng)
        $currentHour = now()->hour;
        if ($currentHour >= 2 && $currentHour <= 5) {
            $riskScore += 0.3;
            $reasons[] = "Suspicious time (2AM-5AM)";
        }

        // --- QUYẾT ĐỊNH ---
        $isBlocked = $riskScore >= 0.8;
        $label = $isBlocked ? 'block' : 'allow';

        // --- DATA COLLECTION (PHẦN MỚI) ---
        // Lưu ngay dữ liệu thô vào Feature Store để làm giàu dữ liệu cho AI
        try {
            $featureLog = AiFeatureStore::create([
                'total_amount' => $totalAmount,
                'ip_address'   => request()->ip(), // Lấy IP thật
                'risk_score'   => $riskScore,
                'reasons'      => $reasons, // Model sẽ tự cast sang JSON
                'label'        => $label,
                // order_id sẽ được cập nhật sau khi tạo Order thành công
            ]);
            
            // Lưu ID log để lát nữa update order_id
            $featureLogId = $featureLog->id;

        } catch (\Exception $e) {
            // Log lỗi nhưng KHÔNG chặn đơn hàng chỉ vì lỗi lưu log AI (Fail-open)
            Log::error("Failed to store AI features: " . $e->getMessage());
            $featureLogId = null;
        }

        if ($isBlocked) {
            Log::warning("Fraud Detected: Order blocked. Score: $riskScore");
        }

        return [
            'allowed' => !$isBlocked,
            'score'   => $riskScore,
            'reason'  => implode(', ', $reasons),
            'log_id'  => $featureLogId // Trả về ID để dùng tiếp
        ];
    }
}