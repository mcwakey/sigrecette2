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
        Schema::table('user_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('taxpayer_id')->index()->nullable();
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_logs', function (Blueprint $table) {
            $table->dropForeign(['taxpayer_id']);
            $table->dropColumn('taxpayer_id');
        });
    }
};
