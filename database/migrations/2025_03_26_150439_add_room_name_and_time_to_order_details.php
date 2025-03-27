<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('room_name')->nullable()->after('room_id'); // Cho phép NULL
            $table->string('total_time_used')->nullable()->after('check_out_time'); // Cho phép NULL
        });
    }

    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['room_name', 'total_time_used']);
        });
    }
};
