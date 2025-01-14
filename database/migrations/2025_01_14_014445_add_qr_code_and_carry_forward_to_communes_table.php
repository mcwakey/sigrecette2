<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->boolean('qr_code_enabled')->default(false)->after('url');
            $table->boolean('carry_forward_previous_year')->default(false)->after('qr_code_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->dropColumn(['qr_code_enabled', 'carry_forward_previous_year']);
        });
    }
};
