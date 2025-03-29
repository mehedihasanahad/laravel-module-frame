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
        Schema::create('inputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->index()->comment('Form table id');;
            $table->unsignedBigInteger('component_id')->index()->comment('Component table id');;
            $table->string('label');
            $table->string('input_tag_name');
            $table->boolean('is_loop')->default(false);
            $table->string('loop_data')->nullable()->comment('stores the variable of looped data for blade looping');
            $table->json('attribute_bag')->comment('{
                    "type"=>"text",
                }');
            $table->json('validation')
                ->comment('{
                    "rules"=> ["required", "string"],
                    "message"=> "Required"
                 }');
            $table->string('model_namespace');
            $table->string('column_name');
            $table->integer('order');
            $table->integer('status');
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
        Schema::dropIfExists('inputs');
    }
};
