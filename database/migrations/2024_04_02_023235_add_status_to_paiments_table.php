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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('status')->after('remaining_amount')->nullable()->default('DRAFT');;
            $table->unsignedBigInteger('r_user_id')->index()->nullable();
            $table->foreign('r_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('r_user_id');
        });
    }
};
