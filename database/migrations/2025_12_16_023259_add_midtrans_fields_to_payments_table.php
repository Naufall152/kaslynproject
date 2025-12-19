<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // token snap yang dipakai front-end (snap popup / qris)
            $table->string('snap_token')->nullable()->after('order_id');

            // opsional tapi disarankan untuk log midtrans
            $table->string('payment_type')->nullable()->after('status');
            $table->json('raw_response')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['snap_token','payment_type','raw_response']);
        });
    }
};
