<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $col) {
            $col->id();
            $col->foreignId('user_id')->constrained()->cascadeOnDelete();
            $col->string('order_number')->unique();
            $col->decimal('subtotal', 12, 2);
            $col->decimal('delivery_fee', 12, 2)->default(0);
            $col->decimal('total_amount', 12, 2);
            $col->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $col->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $col->string('delivery_method')->default('home_delivery'); // home_delivery, local_park
            $col->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $col->text('notes')->nullable();
            $col->timestamps();
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
