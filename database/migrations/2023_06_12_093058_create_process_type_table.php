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
        Schema::create('process_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->string('name_bn', 150);
            $table->string('group_name', 150);
            $table->string('app_table_name', 50)->nullable();
            $table->string('module_folder_name', 50)->nullable();
            $table->string('active_for_permissions', 1000)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('panel_color', 50)->default('bg-aqua');
            $table->string('icon_class', 50)->default('fa-list');
            $table->tinyInteger('order')->nullable();
            $table->timestamps();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_type');
    }
};
