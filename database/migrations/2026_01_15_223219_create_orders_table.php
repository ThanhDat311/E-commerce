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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // 1. Cho phép user_id null để hỗ trợ khách vãng lai (Guest)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // 2. Thay vì shipping_address_id, ta lưu cứng thông tin vào đây (Snapshot)
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->text('note')->nullable();
            
            // 3. Các trạng thái đơn hàng
            $table->string('order_status')->default('pending'); // pending, processing, completed, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->string('payment_method')->default('cod');   // cod, vnpay, momo
            
            // 4. Tổng tiền (Sửa tên cột cho khớp với Code: total -> total)
            // Trong migration cũ bạn để total_amount, nhưng Code Service đang gọi là 'total'.
            // Ta thống nhất dùng 'total' cho gọn, hoặc sửa code service. Ở đây mình sửa DB theo code.
            $table->decimal('total', 10, 2); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};