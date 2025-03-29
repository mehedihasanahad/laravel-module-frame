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
        Schema::create('process_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('process_type_id');
            $table->unsignedBigInteger('ref_id');
            $table->unsignedBigInteger('process_desk_id');
            $table->unsignedBigInteger('process_status_id');
            $table->string('file', 100);
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_documents');
    }
};
