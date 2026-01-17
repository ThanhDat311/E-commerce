<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_feature_store', function (Blueprint $table) {
            $table->id();
            
            // 1. Cho phép null để hỗ trợ nhiều loại log khác nhau
            $table->foreignId('auth_log_id')->nullable()->constrained('auth_logs')->nullOnDelete();
            
            // 2. Thêm order_id để link với đơn hàng (Quan trọng cho bài toán của chúng ta)
            // Lưu ý: bảng orders phải được tạo trước bảng này. 
            // Nếu thứ tự migration sai, hãy bỏ 'constrained' hoặc đảm bảo thứ tự file timestamp.
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            // 3. Các feature (đặc trưng) đầu vào của AI
            $table->decimal('total_amount', 10, 2)->nullable(); // Giá trị đơn hàng
            $table->string('ip_address')->nullable();
            
            // Các điểm số thành phần (nếu sau này AI trả về chi tiết)
            $table->float('velocity_check')->nullable();
            $table->float('ip_reputation_score')->nullable();
            
            // 4. Kết quả đánh giá cuối cùng
            $table->float('risk_score')->default(0); // Điểm rủi ro tổng (0.0 -> 1.0)
            $table->json('reasons')->nullable(); // Lưu lý do chặn dưới dạng JSON (Vd: ["High Value", "Midnight Order"])
            
            $table->string('label')->nullable(); // 'allow', 'block', 'review'
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_feature_store');
    }
};