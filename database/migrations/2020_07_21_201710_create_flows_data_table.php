<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RequrementStatus;

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
//            $table->string('auditor_id')->nullable(); // ToDo:: assigned_auditor
//            $table->string('auditee_id')->nullable(); //ToDo:: assigned_auditee

            $table->foreignId('auditor_id')->nullable();
            $table->foreign('auditor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreignId('auditee_id')->nullable();
            $table->foreign('auditee_id')->references('id')->on('users')->onDelete('set null');


            // Auditors Input
            $table->string('comments')->nullable();
            $table->string('finding')->nullable();
            $table->string('deviation_statement')->nullable();
            $table->string('evidence_reference')->nullable();
            $table->string('deviation_level')->nullable();
            $table->string('safety_level_before_action')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('repetitive_finding_ref_number')->nullable();

            // Auditee Input (NP)
//            $table->string('investigator_id')->nullable(); // ToDo:: assigned_investigator
            $table->foreignId('investigator_id')->nullable();
            $table->foreign('investigator_id')->references('id')->on('users')->onDelete('set null');

            $table->string('corrections')->nullable();
            $table->string('rootcause')->nullable();
            $table->string('corrective_actions_plan')->nullable();
            $table->string('preventive_actions')->nullable();
            $table->string('action_implemented_evidence')->nullable();
            $table->string('safety_level_after_action')->nullable();
            $table->dateTime('effectiveness_review_date')->nullable();
            $table->dateTime('response_date')->nullable();

            $table->dateTime('extension_due_date')->nullable();
            $table->dateTime('closed_date')->nullable();

            $table->string('status')->default(RequrementStatus::UPCOMING);
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
