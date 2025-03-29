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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->index()->comment('Form table id');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Parent component id');
            $table->string('title');
            $table->boolean('is_loop')->default(false);
            $table->tinyInteger('template_type')->nullable()
                ->comment('1=>2 Column Grid,2=> 4 Column Grid, 3=>Tabular Form');
            $table->integer('order');
            $table->integer('step_no')->nullable();
            $table->tinyInteger('status')->comment('0 => Inactive, 1 => Active');
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
        Schema::dropIfExists('components');
    }
};
