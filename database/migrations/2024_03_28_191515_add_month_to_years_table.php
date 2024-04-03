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
        Schema::table('years', function (Blueprint $table) {
            $table->string('current_month')->after('name')->nullable()->default('01');
            $table->boolean('auto_switch')->after('current_month')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('years', function (Blueprint $table) {
            $table->dropColumn(['current_month', 'auto_switch']);
        });
    }
};
