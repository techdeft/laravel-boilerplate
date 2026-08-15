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
        Schema::table('delivery_zones', function (Blueprint $table) {
            $table->string('area')->nullable()->after('city')->comment('Specific neighborhood or sub-area');
            $table->decimal('special_surcharge', 12, 2)->default(0)->after('local_park_fee')->comment('Extra fee for hard-to-reach or special areas');
            $table->decimal('free_delivery_threshold', 12, 2)->nullable()->after('special_surcharge')->comment('Min cart total for free delivery in this zone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_zones', function (Blueprint $table) {
            $table->dropColumn(['area', 'special_surcharge', 'free_delivery_threshold']);
        });
    }
};
