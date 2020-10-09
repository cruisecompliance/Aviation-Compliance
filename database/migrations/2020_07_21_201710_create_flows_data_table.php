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
            $table->string('rule_group', 200);
            $table->string('rule_reference', 200);
            $table->string('rule_title', 200)->nullable();
            $table->string('rule_manual_reference',200)->nullable();
            $table->string('rule_chapter', 200)->nullable();

            // Company Structure
            $table->string('company_manual', 200)->nullable();
            $table->string('company_chapter', 200)->nullable();

            // Audit Structure
            $table->string('frequency', 200)->nullable();
            $table->string('month_quarter')->nullable(); // ToDO Date format

            $table->foreignId('auditor_id')->nullable();
            $table->foreign('auditor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreignId('auditee_id')->nullable();
            $table->foreign('auditee_id')->references('id')->on('users')->onDelete('set null');


            // Auditors Input
            $table->text('questions')->nullable();
            $table->text('finding')->nullable();
            $table->text('deviation_statement')->nullable();
            $table->text('evidence_reference')->nullable();
            $table->text('deviation_level')->nullable();
            $table->text('safety_level_before_action')->nullable();
            $table->date('due_date')->nullable();
            $table->text('repetitive_finding_ref_number')->nullable();

            // Auditee Input (NP)
            $table->foreignId('investigator_id')->nullable();
            $table->foreign('investigator_id')->references('id')->on('users')->onDelete('set null');

            $table->text('corrections')->nullable();
            $table->text('rootcause')->nullable();
            $table->text('corrective_actions_plan')->nullable();
            $table->text('preventive_actions')->nullable();
            $table->text('action_implemented_evidence')->nullable();
            $table->text('safety_level_after_action')->nullable();
            $table->date('effectiveness_review_date')->nullable();
            $table->date('response_date')->nullable();

            $table->date('extension_due_date')->nullable();
            $table->dateTime('closed_date')->nullable();

            $table->string('task_status')->default(RequrementStatus::CMM_Backlog);
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
