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
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->id();
            $table->string('req_no');
            $table->integer('req_id')->nullable();
            $table->string('req_desc');
            $table->integer('qty')->default(0);
            $table->integer('start_no')->nullable();
            $table->integer('end_no')->nullable();
            $table->string('req_type')->default('DEMANDE');
            $table->string('type')->default('ACTIVE'); // 'ARCHIVE' FOR DONE ONES
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('stock_requests');
    }
};
