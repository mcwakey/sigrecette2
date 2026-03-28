<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->text('message');
            $table->string('provider')->nullable();
            $table->string('status')->default('pending');
            $table->string('external_id')->nullable();
            $table->json('meta')->nullable();
            $table->morphs('loggable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
