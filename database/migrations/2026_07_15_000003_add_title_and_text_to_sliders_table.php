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
        Schema::table('sliders', function (Blueprint $table) {
            if (! Schema::hasColumn('sliders', 'title')) {
                $table->longText('title')->nullable()->after('url');
            }

            if (! Schema::hasColumn('sliders', 'text')) {
                $table->longText('text')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (Schema::hasColumn('sliders', 'text')) {
                $table->dropColumn('text');
            }

            if (Schema::hasColumn('sliders', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};
