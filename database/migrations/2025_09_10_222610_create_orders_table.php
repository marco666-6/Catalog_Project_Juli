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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('order_date');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('installment_plan', ['full_payment', '3_months', '6_months', '12_months'])->default('full_payment');
            $table->enum('payment_method', ['bank_transfer', 'credit_card', 'cash', 'other'])->default('bank_transfer');
            $table->float('total_price', 15);
            $table->text('confirmation')->nullable();
            $table->string('payment_proof')->nullable();
            $table->string('handover_proof')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};