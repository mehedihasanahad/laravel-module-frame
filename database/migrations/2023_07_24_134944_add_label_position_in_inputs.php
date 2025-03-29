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
        Schema::table('inputs', function (Blueprint $table) {
            $table->tinyInteger('label_position')->default(1)->after('label')->comment('1=>Upper,2=>side');
            $table->tinyInteger('width')->default(1)->after('label_position')->comment('1=>Default,2=>Full Row');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inputs', function (Blueprint $table) {
            $table->dropColumn(['label_position','width']);
        });
    }
};
