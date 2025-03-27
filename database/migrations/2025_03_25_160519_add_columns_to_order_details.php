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
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('customer_paid', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->nullable();
            $table->unsignedBigInteger('service_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn(['room_id', 'check_in_time', 'check_out_time', 'total_price', 'payment_method', 'customer_paid', 'change_amount']);
        });
    }
};
