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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('onrecoveryprint')->after('reduce_amount')->nullable()->default(false);
            $table->boolean('ondistributionprint')->nullable()->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('onrecoveryprint');
                $table->dropColumn('ondistributionprint');

            });
    }
};
