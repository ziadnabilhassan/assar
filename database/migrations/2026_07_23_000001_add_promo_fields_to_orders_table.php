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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'coupon')) {
                $table->string('coupon')->nullable()->after('total');
            }

            if (! Schema::hasColumn('orders', 'discount')) {
                $table->string('discount')->nullable()->after('coupon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'discount')) {
                $table->dropColumn('discount');
            }

            if (Schema::hasColumn('orders', 'coupon')) {
                $table->dropColumn('coupon');
            }
        });
    }
};
