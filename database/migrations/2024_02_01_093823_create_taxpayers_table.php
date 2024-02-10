<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxpayers', function (Blueprint $table) {
            $table->id();
		    $table->string('tnif')->unique()->nullable();
            $table->string('name');
            $table->string('gender');
            $table->string('id_type');
            $table->string('id_number');
            $table->string('mobilephone');
            $table->string('telephone');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('canton');
            $table->string('town');
            $table->string('erea');
            $table->string('address');
            $table->string('zone_id');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxpayers');
    }
};
