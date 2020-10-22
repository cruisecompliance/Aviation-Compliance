<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyFieldsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_fields_data', function (Blueprint $table) {
            $table->id();

            $table->string('rule_reference', 200)->index();
            $table->string('company_manual', 200)->nullable();
            $table->string('company_chapter', 200)->nullable();

            $table->foreignId('version_id');
            $table->foreign('version_id')->references('id')->on('company_fields')->onDelete('cascade');

//             $table->foreignId('company_id');
//             $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_fields_data');
    }
}
