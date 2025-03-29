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
        if (!Schema::hasTable('process_user_desks')) {
            Schema::create('process_user_desks', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 100);
                $table->tinyInteger('status')->comment('0=inactive, 1=active');
                $table->timestamps();
                $table->unsignedInteger('created_by');
                $table->unsignedInteger('updated_by');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_user_desk');
    }
};
