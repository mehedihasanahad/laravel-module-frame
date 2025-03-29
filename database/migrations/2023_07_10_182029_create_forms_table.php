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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_type_id')->index();
            $table->tinyInteger('form_type')->comment('1 => add form, 2 => edit form');
            $table->tinyInteger('template_type')->nullable()
                ->comment('1 => Default,2 => Steps ');
            $table->tinyInteger('steps')->nullable()
                ->comment('Total number of steps');
            $table->longText('steps_name')->nullable()->comment('names of form steps in array format');
            $table->string('title');
            $table->json('form_data_json')->nullable()
                ->comment('{
                    ["key"=>"value"] pair,
                    keys are blade variable,
                    value is a raw sql query
                }');
            $table->string('method')->comment('only supports post,put,patch,delete');
            $table->string('action')->comment('form submit url');
            $table->string('enctype')->nullable();
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
        Schema::dropIfExists('forms');
    }
};
