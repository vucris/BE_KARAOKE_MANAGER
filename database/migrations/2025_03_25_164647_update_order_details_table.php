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
        Schema::table('order_details', function (Blueprint $table) {
            // Xóa ràng buộc khóa ngoại trước
            $table->dropForeign(['order_id']);

            // Sau đó mới xóa cột order_id
            $table->dropColumn('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Thêm lại cột order_id
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
        });
    }
};
