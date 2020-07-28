<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlowsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flows_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('flow_id');
            $table->foreign('flow_id')->references('id')->on('flows')->onDelete('cascade');

//            $table->foreignId('requirement_data_id');
//            $table->foreign('requirement_data_id')->references('id')->on('requirements_data')->onDelete('cascade');

            // European Rule
            $table->integer('rule_section')->unsigned();
            $table->string('rule_group');
            $table->string('rule_reference');
            $table->string('rule_title')->nullable();
            $table->string('rule_manual_reference')->nullable();
            $table->string('rule_chapter')->nullable();

            // Company Structure
            $table->string('company_manual')->nullable();
            $table->string('company_chapter')->nullable();

            // Audit Structure
            $table->string('frequency')->nullable();
            $table->string('month_quarter')->nullable();
            $table->string('assigned_auditor')->nullable(); // ToDo
            $table->string('assigned_auditee')->nullable(); //ToDo

            // Auditors Input
            $table->string('comments')->nullable();
            $table->string('finding')->nullable();
            $table->string('deviation_statement')->nullable();
            $table->string('evidence_reference')->nullable();
            $table->string('deviation_level')->nullable();
            $table->string('safety_level_before_action')->nullable();
            $table->date('due_date')->nullable();
            $table->string('repetitive_finding_ref_number')->nullable();

            // Auditee Input (NP)
            $table->string('assigned_investigator')->nullable();
            $table->string('corrections')->nullable();
            $table->string('rootcause')->nullable();
            $table->string('corrective_actions_plan')->nullable();
            $table->string('preventive_actions')->nullable();
            $table->string('action_implemented_evidence')->nullable();
            $table->string('safety_level_after_action')->nullable();
            $table->date('effectiveness_review_date')->nullable();
            $table->date('response_date')->nullable();

            $table->date('extension_due_date')->nullable();
            $table->date('closed_date')->nullable();

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
        Schema::dropIfExists('flows_data');
    }
}
