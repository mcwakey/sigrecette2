<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_invoices_status_created');
            $table->index('taxpayer_id', 'idx_invoices_taxpayer');
            $table->index('pay_status', 'idx_invoices_pay_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index(['invoice_id', 'status'], 'idx_payments_invoice_status');
            $table->index('taxpayer_id', 'idx_payments_taxpayer');
        });

        Schema::table('taxpayers', function (Blueprint $table) {
            $table->index(['zone_id', 'type'], 'idx_taxpayers_zone_type');
            $table->index('category_id', 'idx_taxpayers_category');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_status_created');
            $table->dropIndex('idx_invoices_taxpayer');
            $table->dropIndex('idx_invoices_pay_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_invoice_status');
            $table->dropIndex('idx_payments_taxpayer');
        });

        Schema::table('taxpayers', function (Blueprint $table) {
            $table->dropIndex('idx_taxpayers_zone_type');
            $table->dropIndex('idx_taxpayers_category');
        });
    }
};
