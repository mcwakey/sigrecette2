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

            $table->string('file_no')->nullable();

            //$table->string('social_work')->nullable();

            //$table->string('category_work')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            //$table->foreign('category_id')->references('id')->on('categories');

            //$table->string('activity_id')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            //$table->foreign('activity_id')->references('id')->on('activities');

            $table->string('other_work')->nullable();

            $table->string('authorisation')->default("NO");
            $table->string('auth_reference')->nullable();
            $table->string('nif')->nullable();

            //$table->string('canton_id');
            //$table->unsignedBigInteger('canton')->default(1);
            //$table->foreign('canton')->references('id')->on('cantons');

            //$table->string('town');
            $table->unsignedBigInteger('town_id')->nullable();
            $table->foreign('town_id')->references('id')->on('towns');

            // $table->string('erea');
            $table->unsignedBigInteger('erea_id')->nullable();
            $table->foreign('erea_id')->references('id')->on('ereas');

            //$table->string('zone_id');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones');

            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            //$table->unsignedBigInteger('activity_id')->nullable();
            // $table->foreign('activity_id')->references('id')->on('activities');
            $table->rememberToken();
            $table->timestamps();
        });

        //DB::statement("ALTER TABLE books AUTO_INCREMENT = 10000;");
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
