<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('network')->nullable()->after('provider');
        });

        Schema::table('mobile_payment_transactions', function (Blueprint $table) {
            $table->string('network')->nullable()->after('provider');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('network');
        });

        Schema::table('mobile_payment_transactions', function (Blueprint $table) {
            $table->dropColumn('network');
        });
    }
};
