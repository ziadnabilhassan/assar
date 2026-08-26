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
            if (! Schema::hasColumn('orders', 'payment_provider')) {
                $table->string('payment_provider')->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_provider');
            }

            if (! Schema::hasColumn('orders', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('payment_status');
            }

            if (! Schema::hasColumn('orders', 'payment_proof_url')) {
                $table->string('payment_proof_url')->nullable()->after('payment_proof_path');
            }

            if (! Schema::hasColumn('orders', 'payment_admin_note')) {
                $table->text('payment_admin_note')->nullable()->after('payment_proof_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach ([
                'payment_admin_note',
                'payment_proof_url',
                'payment_proof_path',
                'payment_status',
                'payment_provider',
            ] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
