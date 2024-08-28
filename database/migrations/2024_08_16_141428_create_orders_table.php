<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 10, 2);
            $table->string('invoice');
            $table->string('payment_type'); // e.g., 'card', 'cash on delivery'
            $table->string('payment_status'); // e.g., 'paid', 'pending', 'failed'
            $table->string('order_status'); // e.g., 'processing', 'shipped', 'delivered'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('location_lat', 10, 7);
            $table->decimal('location_lng', 10, 7);
            $table->timestamps();
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');
    }
};
