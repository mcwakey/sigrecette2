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
        Schema::table('communes', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('name')->after('title')->nullable()->default('');;
            $table->string('region_name')->after('name')->nullable()->default('');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('title');
            $table->dropColumn('region_name');
        });
    }
};
