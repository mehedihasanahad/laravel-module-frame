<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email', 100);
            $table->string('password');
            $table->integer('user_group_id')->nullable();
            $table->tinyInteger('is_approved')->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0->inactive | 1->active | -1:suspend');
            $table->string('photo')->nullable();
            $table->string('national_id', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('upzila_id')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('nid', 17)->nullable();
            $table->integer('login_otp')->default(0);
            $table->string('auth_token')->nullable();
            $table->dateTime('otp_expire_time')->nullable();
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
