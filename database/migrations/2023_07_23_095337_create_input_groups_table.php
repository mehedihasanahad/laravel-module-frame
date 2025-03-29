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
        Schema::create('input_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->index()->nullable();
            $table->unsignedBigInteger('component_id')->index()->nullable();
            $table->tinyInteger('step_no')->nullable();
            $table->string('label');

            $table->timestamps();
            $table->integer('created_by')->index()->nullable();
            $table->integer('updated_by')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('input_groups');
    }
};
