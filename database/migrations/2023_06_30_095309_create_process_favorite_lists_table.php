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
        Schema::create('process_favorite_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('process_list_id')->unsigned();
            $table->tinyInteger('is_archive')->default(0)->comment('0 = Not archived; 1 = Archived');
            $table->tinyInteger('status')->default(1)->comment('0 = Inactive; 1 = Active');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
            $table->timestamps();
            $table->index('user_id');
            $table->index('process_list_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_favorite_lists');
    }
};
