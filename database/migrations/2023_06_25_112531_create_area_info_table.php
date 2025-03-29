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
        Schema::create('area_info', function (Blueprint $table) {
            $table->id('id');
            $table->string('area_nm')->nullable();
            $table->integer('pare_id')->index()->nullable();
            $table->tinyInteger('area_type')->nullable()->comment('1=Division,2=District,3=Thana/upzila');
            $table->string('area_nm_ban')->nullable();
            $table->integer('nid_area_code')->nullable();
            $table->integer('sb_dist_code')->nullable();
            $table->string('soundex_nm')->nullable();
            $table->integer('rjsc_id')->nullable();
            $table->string('rjsc_name')->nullable();
            $table->integer('app_limit')->nullable();
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
        Schema::dropIfExists('area_info');
    }
};
