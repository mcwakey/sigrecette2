<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->boolean('mobile_payment_enabled')->default(false)->after('carry_forward_previous_year');
            $table->boolean('sms_enabled')->default(false)->after('mobile_payment_enabled');
            $table->string('default_payment_provider')->nullable()->after('sms_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->dropColumn(['mobile_payment_enabled', 'sms_enabled', 'default_payment_provider']);
        });
    }
};
