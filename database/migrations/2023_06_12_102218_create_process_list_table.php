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
        Schema::create('process_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('process_type_id')->default(0)->comment('Foreign key referencing the primary key of the process_types table');
            $table->unsignedInteger('org_id')->default(0)->comment('Foreign key referencing the primary key of the (organization/company/office) table');
            $table->bigInteger('ref_id')->default(0);
            $table->string('tracking_no', 25);
            $table->mediumText('json_object');
            $table->integer('process_user_desk_id')->default(0)->comment('Foreign key referencing the primary key of the process_user_desks table');
            $table->integer('process_status_id')->default(0)->comment('Foreign key referencing the primary key of the process_statuses table');
            $table->integer('user_id')->default(0)->comment('Foreign key referencing the primary key of the users table');
            $table->tinyInteger('read_status')->default(0);
            $table->text('remarks');
            $table->dateTime('locked_at')->nullable();
            $table->integer('locked_by')->default(0);
            $table->mediumText('previous_hash');
            $table->mediumText('hash_value');
            $table->timestamps();

            $table->index('ref_id');
            $table->index('process_type_id');
            $table->index('process_status_id');
            $table->index('user_id');
            $table->index('locked_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_list');
    }
};
