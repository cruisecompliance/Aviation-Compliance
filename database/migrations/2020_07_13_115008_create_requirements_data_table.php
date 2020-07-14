<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO: length, type and index
        Schema::create('requirements_data', function (Blueprint $table) {
            $table->id();
            $table->integer('rule_section')->unsigned();
            $table->string('rule_group');
            $table->string('rule_reference');
            $table->string('rule_title')->nullable();
            $table->string('rule_manual_reference')->nullable();
            $table->string('rule_chapter')->nullable();
            $table->timestamps();

            $table->foreignId('version_id');
            $table->foreign('version_id')->references('id')->on('requirements')->onDelete('cascade');

            $table->unique(['rule_reference','version_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requirements_data');
    }
}
