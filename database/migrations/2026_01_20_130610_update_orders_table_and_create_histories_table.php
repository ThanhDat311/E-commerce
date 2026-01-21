<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_xx_xx_update_orders...php
  public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Chỉ thêm nếu cột chưa tồn tại
            if (!Schema::hasColumn('orders', 'shipping_carrier')) {
                $table->string('shipping_carrier')->nullable()->after('order_status');
            }

            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('shipping_carrier');
            }
            
            // Bạn đã có cột 'note' (cho khách), ta thêm 'admin_note' (cho nội bộ)
            if (!Schema::hasColumn('orders', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('updated_at');
            }
            
            // Các cột bạn đã có (payment_status, order_status, total...) -> BỎ QUA KHÔNG TẠO LẠI
        });

        // Tạo bảng lịch sử (giữ nguyên)
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Liên kết với bảng orders
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Liên kết với user thực hiện
            $table->string('action'); // Ví dụ: "Update Status"
            $table->text('description')->nullable(); // Chi tiết thay đổi
            $table->timestamps(); // create_at, updated_at
        });
    }
};
