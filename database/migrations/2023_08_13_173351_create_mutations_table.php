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
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->string('nid')->nullable();
            $table->string('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('name')->nullable();
            $table->integer('division')->nullable();
            $table->integer('district')->nullable();
            $table->integer('upzila')->nullable();
            $table->integer('khotian_type')->nullable();
            $table->string('khotian_no')->nullable();
            $table->string('dag_no')->nullable();
            $table->string('dolil_no')->nullable();
            $table->date('dolil_date')->nullable();
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
        Schema::dropIfExists('mutations');
    }
};
