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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('design_id')->nullable()->constrained('saved_designs')->nullOnDelete();
            $table->string('name');
            $table->unsignedDecimal('price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('image_url')->nullable();
            $table->string('preview_image_url')->nullable();
            $table->boolean('is_custom_design')->default(false);
            $table->json('design_data')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
