<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id()->from(100001);
            $table->string('invoice_no')->nullable();
            $table->string('order_no')->nullable();
            $table->string('nic')->nullable();
            $table->string('status')->default('DRAFT');
            $table->string('pay_status')->default('OWING');
            $table->string('delivery')->default('NOT DELIVERED');
            $table->date('delivery_date')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->double('qty')->nullable();
            $table->double('amount')->default(0);
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            // $table->unsignedBigInteger('taxpayer_taxable_id')->default(1);
            // $table->foreign('taxpayer_taxable_id')->references('id')->on('taxpayer_taxables');
            $table->timestamps();
        });
        
        //DB::statement("ALTER TABLE books AUTO_INCREMENT = 100000;");
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
