<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->id()->from(10001);;
		    $table->string('tnif')->unique()->nullable();
            $table->string('name');
            $table->string('gender');
            $table->string('id_type');
            $table->string('id_number')->nullable();
            $table->string('mobilephone');
            $table->string('telephone')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('address')->nullable();

            $table->string('social_work')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('other_work')->nullable();
            
            $table->string('file_no')->nullable();
            $table->string('authorisation')->default("NO");
            $table->string('auth_reference')->nullable();
            $table->string('nif')->nullable();

            $table->unsignedBigInteger('town_id')->nullable();
            $table->foreign('town_id')->references('id')->on('towns');

            $table->unsignedBigInteger('erea_id')->nullable();
            $table->foreign('erea_id')->references('id')->on('ereas');

            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones');

            $table->string('email')->unique()->nullable();
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
