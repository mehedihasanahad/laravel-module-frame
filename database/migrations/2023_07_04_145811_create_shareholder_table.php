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
        Schema::create('shareholders', function (Blueprint $table) {
            $table->id();
            $table->integer('app_id')->nullable();
            $table->integer('process_type_id')->nullable();
            $table->string('name');
            $table->integer('nationality')->nullable();
            $table->string('nid', 50)->nullable();
            $table->string('passport', 50)->nullable();
            $table->date('dob')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('image')->nullable();
            $table->integer('no_of_share')->nullable();
            $table->integer('share_value')->nullable();
            $table->integer('share_percent')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shareholder');
    }
};
