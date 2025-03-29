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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('caption', 2295)->nullable();
            $table->string('email_subject', 2295)->nullable();
            $table->text('email_content')->nullable();
            $table->tinyInteger('email_active_status')->nullable();
            $table->string('email_cc', 2295)->nullable();
            $table->string('sms_content', 2295)->nullable();
            $table->tinyInteger('sms_active_status')->nullable();
            $table->tinyInteger('is_archive')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templet');
    }
};
