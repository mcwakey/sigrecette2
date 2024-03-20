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
        Schema::table('taxpayer_taxables', function (Blueprint $table) {
            $table->string('bill_status')->default('NOT BILLED')->after('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxpayer_taxables', function (Blueprint $table) {
            $table->dropColumn('bill_status');
        });
    }
};
