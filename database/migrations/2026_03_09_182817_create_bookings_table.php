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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('pharmacist_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('prescription')->nullable();
            $table->string('contact_method');
            $table->string('booking_type');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('booking_status')->default('pending');
            $table->string('country')->nullable();

            // Payment fields
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_id')->nullable();
            $table->decimal('payment_amount', 10, 2)->default(0.00);
            $table->string('payment_currency')->default('USD');
            $table->date('payment_date')->nullable();
            $table->time('payment_time')->nullable();

            // Google Calendar fields
            $table->string('google_event_id')->nullable();
            $table->string('meet_link')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
