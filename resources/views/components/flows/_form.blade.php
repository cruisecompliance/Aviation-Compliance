<form id="ItemForm" action="" name="ItemForm" class="form-horizontal" method="POST">

    <input type="hidden" name="_method" id="_method" value="">
    <input type="hidden" name="requirements_rule" id="requirements_rule" value="">
    <input type="hidden" name="rule_id" id="rule_id" value="">

    <div class="form-group">
        <label for="rule_section" class="control-label">Sec #</label>
        <input type="text" class="form-control" id="rule_section" name="rule_section" value="" readonly>
    </div>

    <h2>European Rule </h2>
    <div class="form-group">
        <label for="rule_group" class="control-label">IR/AMC/GM</label>
        <input type="text" class="form-control" id="rule_group" name="rule_group" value="" readonly>
    </div>
    <div class="form-group">
        <label for="rule_reference" class="control-label">Rule Reference</label>
        <input type="text" class="form-control" id="rule_reference" name="rule_reference" value="" readonly>
    </div>
    <div class="form-group">
        <label for="rule_title" class="control-label">Rule Title</label>
        <input type="text" class="form-control" id="rule_title" name="rule_title" value="" readonly>
    </div>
    <div class="form-group">
        <label for="rule_manual_reference" class="control-label">AMC3 ORO.MLR.100 Manual Reference</label>
        <input type="text" class="form-control" id="rule_manual_reference" name="rule_manual_reference" value="" readonly>
    </div>
    <div class="form-group">
        <label for="rule_chapter" class="control-label">AMC3 ORO.MLR.100 Chapter</label>
        <input type="text" class="form-control" id="rule_chapter" name="rule_chapter" value="" readonly>
    </div>

    <h2>Company Structure</h2>
    <div class="form-group">
        <label for="company_manual" class="control-label">Company Manual</label>
        <input type="text" class="form-control" id="company_manual" name="company_manual" value="" @cannot(PermissionName::EDIT_COMPANY_MANUAL) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="company_chapter" class="control-label">Company Chapter</label>
        <input type="text" class="form-control" id="company_chapter" name="company_chapter" value="" @cannot(PermissionName::EDIT_COMPANY_CHAPTER) readonly @endcan>
    </div>


    <h2>Audit Structure</h2>
    <div class="form-group">
        <label for="frequency" class="control-label">Frequency</label>
        <select name="frequency" id="frequency" class="form-control" @cannot(PermissionName::EDIT_FREQUENCY) readonly @endcan>
            <option value="">...</option>
            <option value="Annual">Annual</option>
            <option value="Performance">Performance</option>
        </select>
    </div>
    <div class="form-group">
        <label for="month_quarter" class="control-label">Month / Quarter</label>
            <input type="text" class="form-control month-picker" id="month_quarter" name="month_quarter" value="" placeholder="mm.yyyy" autocomplete="off" @cannot(PermissionName::EDIT_MONTH_QUARTER) readonly @endcan>
    </div>

    <h2>Auditors Input</h2>
    <div class="form-group">
        <label for="questions" class="control-label">Comments / Questions</label>
        <textarea class="form-control" id="questions" name="questions" rows="3" placeholder="" @cannot(PermissionName::EDIT_QUESTIONS) readonly @endcan></textarea>
    </div>
    <div class="form-group">
        <label for="finding" class="control-label">Finding / Observation</label>
        <select name="finding" id="finding" class="form-control" @cannot(PermissionName::EDIT_FINDING) readonly @endcan>
            <option value="Finding">Finding</option>
            <option value="Observation">Observation</option>
            <option value="None" selected>None</option>
        </select>
    </div>
    <div class="form-group">
        <label for="deviation_statement" class="control-label">Deviation Statement</label>
        <input type="text" class="form-control" id="deviation_statement" name="deviation_statement" value="" @cannot(PermissionName::EDIT_DEVIATION_STATEMENT) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="evidence_reference" class="control-label">Manual / Evidence Reference</label>
        <input type="text" class="form-control" id="evidence_reference" name="evidence_reference" value="" @cannot(PermissionName::EDIT_EVIDENCE_REFERENCE) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="deviation_level" class="control-label">Deviation-Level</label>
        <input type="text" class="form-control" id="deviation_level" name="deviation_level" value="" @cannot(PermissionName::EDIT_DEVIATION_LEVEL) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="safety_level_before_action" class="control-label">Safety level before action</label>
        <input type="text" class="form-control" id="safety_level_before_action" name="safety_level_before_action" value="" @cannot(PermissionName::EDIT_SAFETY_LEVEL_BEFORE_ACTION) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="due_date" class="control-label">Due-Date</label>
        <input type="text" class="form-control picker" id="due_date" name="due_date" value="" placeholder="dd.mm.yyyy" @cannot(PermissionName::EDIT_DUE_DATE) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="repetitive_finding_ref_number" class="control-label">Repetitive Finding ref Number</label>
        <input type="text" class="form-control" id="repetitive_finding_ref_number" name="repetitive_finding_ref_number" value="" @cannot(PermissionName::EDIT_REPETITIVE_FINDING_REF_NUMBER) readonly @endcan>
    </div>

    <h2>Auditee Input (NP)</h2>
    <div class="form-group">
        <label for="corrections" class="control-label">Correction(s)</label>
        <textarea class="form-control" id="corrections" name="corrections" rows="3" placeholder="" @cannot(PermissionName::EDIT_CORRECTIONS) readonly @endcan></textarea>
    </div>
    <div class="form-group">
        <label for="rootcause" class="control-label">Rootcause:
            1. Why?
            2. Why?
            3. Why?
            4. Why?
            5. Why?
        </label>
        <textarea class="form-control" id="rootcause" name="rootcause" rows="3" placeholder="" @cannot(PermissionName::EDIT_ROOTCASE) readonly @endcan></textarea>
    </div>
    <div class="form-group">
        <label for="corrective_actions_plan" class="control-label">Corrective Action(s) Plan</label>
        <textarea class="form-control" id="corrective_actions_plan" name="corrective_actions_plan" rows="3" placeholder="" @cannot(PermissionName::EDIT_CORRECTIVE_ACTIONS_PLAN) readonly @endcan></textarea>
    </div>
    <div class="form-group">
        <label for="preventive_actions" class="control-label">Preventive Action(s)</label>
        <textarea class="form-control" id="preventive_actions" name="preventive_actions" rows="3" placeholder="" @cannot(PermissionName::EDIT_PREVENTIVE_ACTIONS) readonly @endcan></textarea>
    </div>
    <div class="form-group">
        <label for="action_implemented_evidence" class="control-label">Action implemented evidence</label>
        <textarea class="form-control" id="action_implemented_evidence" name="action_implemented_evidence" rows="3" placeholder="" @cannot(PermissionName::EDIT_ACTION_IMPLEMENTED_EVIDENCE) readonly @endcan></textarea>
    </div>
    <div class="form-group">
        <label for="safety_level_after_action" class="control-label">Safety level after action</label>
        <textarea class="form-control" id="safety_level_after_action" name="safety_level_after_action" rows="3" placeholder="" @cannot(PermissionName::EDIT_SAFETY_LEVEL_AFTER_ACTION) readonly @endcan></textarea>
    </div>
    <div class="form-group">
        <label for="effectiveness_review_date" class="control-label">Effectiveness Review date</label>
        <input type="text" class="form-control picker" id="effectiveness_review_date" name="effectiveness_review_date" value="" placeholder="dd.mm.yyyy" @cannot(PermissionName::EDIT_EFFECTIVENESS_REVIEW_DATE) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="response_date" class="control-label">Response date</label>
        <input type="text" class="form-control picker" id="response_date" name="response_date" value="" placeholder="dd.mm.yyyy" @cannot(PermissionName::EDIT_RESPONSE_DATE) readonly @endcan>
    </div>
    <div class="form-group">
        <label for="extension_due_date" class="control-label">Extension Due-Date</label>
        <input type="text" class="form-control picker" id="extension_due_date" name="extension_due_date" value="" placeholder="dd.mm.yyyy" @cannot(PermissionName::EDIT_EXTENSION_DUE_DATE) readonly @endcan>
    </div>

    <div class="form-group">
        <label for="closed_date" class="control-label">Closed date</label>
        <input type="text" class="form-control picker" id="closed_date" name="closed_date" value="" placeholder="dd.mm.yyyy" @cannot(PermissionName::EDIT_CLOSED_DATE) readonly @endcan>
    </div>

    <div class="form-group">
        <label for="task_owner" class="control-label">Task Owner</label>
        <select name="task_owner" id="task_owner" class="form-control">
            <option value="">...</option>
        </select>
    </div>

    <!-- status -->
    <div class="form-group" id="statuses-wrapper">
        <label for="task_status" class="control-label">Status</label>
        <select name="task_status" id="task_status" class="form-control">
            <option value="">...</option>
        </select>
    </div>
    <!-- /status -->

    <!-- comment -->
    <div class="form-group">
        <label for="comment" class="control-label">Comment</label>
        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Your comment...."></textarea>
    </div>
    <!-- /comment -->

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>

</form>

