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
        Schema::create('process_statuses', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('process_type_id')->comment('Foreign key referencing the primary key of the process_types table');
            $table->string('status_name', 150);
            $table->string('color', 100);
            $table->integer('status')->default(0)->comment('0=inactive, 1=active');
            $table->integer('addon_status')->default(0)->comment('0=inactive, 1=active');
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->index('process_type_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_status');
    }
};
