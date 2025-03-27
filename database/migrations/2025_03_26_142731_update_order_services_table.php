<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up(): void
    {
        Schema::table('order_services', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('order_details')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::table('order_services', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn(['order_id', 'service_id', 'quantity', 'price', 'total_price']);
        });
    }
};
