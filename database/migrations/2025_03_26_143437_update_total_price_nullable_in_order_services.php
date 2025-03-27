<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('order_services', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('order_services', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->nullable(false)->change();
        });
    }
};
