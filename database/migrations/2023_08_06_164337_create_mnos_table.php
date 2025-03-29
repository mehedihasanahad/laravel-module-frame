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
        Schema::create('mnos', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_type')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('op_office_district')->nullable();
            $table->string('op_office_thana')->nullable();
            $table->string('op_office_address')->nullable();
            $table->string('op_office_address2')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('applicant_mobile')->nullable();
            $table->string('applicant_email')->nullable();
            $table->string('applicant_telephone')->nullable();
            $table->string('applicant_district')->nullable();
            $table->string('applicant_thana')->nullable();
            $table->string('applicant_address')->nullable();
            $table->string('applicant_address2')->nullable();
            $table->string('type_of_isp_licenses')->nullable();
            $table->string('total_no_of_share')->nullable();
            $table->string('total_share_value')->nullable();
            $table->string('accept_terms')->nullable();
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
        Schema::dropIfExists('mnos');
    }
};
