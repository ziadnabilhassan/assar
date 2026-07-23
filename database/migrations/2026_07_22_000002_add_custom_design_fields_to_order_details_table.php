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
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreignId('design_id')->nullable()->after('product_id')->constrained('saved_designs')->nullOnDelete();
            $table->string('image_url')->nullable()->after('price');
            $table->string('preview_image_url')->nullable()->after('image_url');
            $table->boolean('is_custom_design')->default(false)->after('preview_image_url');
            $table->json('design_data')->nullable()->after('is_custom_design');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['design_id']);
            $table->dropColumn([
                'design_id',
                'image_url',
                'preview_image_url',
                'is_custom_design',
                'design_data',
            ]);
        });
    }
};
