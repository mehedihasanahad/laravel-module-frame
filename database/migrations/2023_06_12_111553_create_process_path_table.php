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
        Schema::create('process_paths', function (Blueprint $table) {
            $table->id();
            $table->integer('process_type_id')->default(0)->comment('Foreign key referencing the primary key of the process_types table');
            $table->integer('desk_from')->default(0)->comment('Foreign key referencing the primary key of the process_user_desks table');
            $table->string('desk_to', 255)->default('0')->comment('Foreign key referencing the primary key of the process_user_desks table');
            $table->integer('status_from')->default(0)->comment('Foreign key referencing the primary key of the process_statuses table');
            $table->string('status_to', 255)->default('0')->comment('Foreign key referencing the primary key of the process_statuses table');
            $table->tinyInteger('file_attachment')->default(0);
            $table->tinyInteger('remarks')->default(0);
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->index('process_type_id');
            $table->index('desk_from');
            $table->index('status_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_path');
    }
};
