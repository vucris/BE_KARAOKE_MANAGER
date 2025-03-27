<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'room_price')) {
                $table->decimal('room_price', 10, 2)->after('room_id')->default(0);
            }
            if (!Schema::hasColumn('order_details', 'service_price')) {
                $table->decimal('service_price', 10, 2)->after('room_price')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'room_price')) {
                $table->dropColumn('room_price');
            }
            if (Schema::hasColumn('order_details', 'service_price')) {
                $table->dropColumn('service_price');
            }
        });
    }
};
