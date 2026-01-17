<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại liên kết với đơn hàng
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            
            // Khóa ngoại liên kết sản phẩm (để thống kê)
            $table->foreignId('product_id')->constrained();

            // --- CÁC CỘT SNAPSHOT (QUAN TRỌNG) ---
            // Lưu cứng tên sản phẩm tại thời điểm mua
            $table->string('product_name'); 
            
            $table->integer('quantity');
            
            // Đổi tên 'unit_price' -> 'price' để khớp với Code Service
            $table->decimal('price', 10, 2);
            
            // Thêm cột tổng tiền (quantity * price) để query báo cáo nhanh hơn
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};