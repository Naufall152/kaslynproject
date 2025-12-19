<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            // kode plan: basic / pro
            $table->string('key')->unique();

            // nama plan: Basic / Pro
            $table->string('name');

            // harga (Basic = 0, Pro = 50000)
            $table->unsignedInteger('price')->default(0);

            // durasi langganan (hari)
            $table->unsignedInteger('duration_days')->default(30);

            // status aktif / tidak
            $table->boolean('is_active')->default(true);

            // fitur dalam bentuk JSON
            $table->json('features')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
