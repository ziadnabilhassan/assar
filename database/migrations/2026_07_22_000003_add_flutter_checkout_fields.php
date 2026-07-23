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
        Schema::table('saved_designs', function (Blueprint $table) {
            $table->string('preview_image_url')->nullable()->after('preview_image');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('saved_designs', function (Blueprint $table) {
            $table->dropColumn('preview_image_url');
        });
    }
};
