<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Liên kết với bảng orders
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Liên kết với user thực hiện
            $table->string('action'); // Ví dụ: "Update Status"
            $table->text('description')->nullable(); // Chi tiết thay đổi
            $table->timestamps(); // create_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_histories');
    }
};
