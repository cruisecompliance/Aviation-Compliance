<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO: lenght and type
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('rule_section')->nullable()->unsigned();
            $table->string('rule_group')->nullable();
            $table->string('rule_reference')->nullable();
            $table->string('rule_title')->nullable();
            $table->string('rule_manual_reference')->nullable();
            $table->string('rule_chapter')->nullable();
            $table->timestamps();

            $table->foreignId('version_id');
            $table->foreign('version_id')->references('id')->on('requirement_versions')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requirements');
    }
}
