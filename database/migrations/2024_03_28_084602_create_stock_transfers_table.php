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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('trans_no');
            $table->integer('trans_id')->nullable();
            //$table->string('trans_desc');
            $table->integer('qty')->default(0);
            $table->integer('start_no')->nullable();
            $table->integer('end_no')->nullable();
            $table->integer('last_no')->nullable();
            $table->string('trans_type')->default('DEMANDE');
            $table->string('type')->default('ACTIVE'); // DONE FOR REGISSEUR VERIFICATION // 'ARCHIVE' FOR RECEVEUR VERIFICATION
            $table->integer('code')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->unsignedBigInteger('by_user_id');
            $table->foreign('by_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('to_user_id');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('taxable_id');
            $table->foreign('taxable_id')->references('id')->on('taxables');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
