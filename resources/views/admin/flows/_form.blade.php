<!-- modal -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

            </div>
            <div class="modal-body" id="">
                <form id="ItemForm" action="" name="ItemForm" class="form-horizontal" method="POST">

                    <input type="hidden" name="_method" id="_method" value="">

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
                        <input type="text" class="form-control" id="company_manual" name="company_manual" value="">
                    </div>
                    <div class="form-group">
                        <label for="company_chapter" class="control-label">Company Chapter</label>
                        <input type="text" class="form-control" id="company_chapter" name="company_chapter" value="">
                    </div>

                    <h2>Audit Structure</h2>
                    <div class="form-group">
                        <label for="frequency" class="control-label">Frequency</label>
                        <input type="text" class="form-control" id="frequency" name="frequency" value="">
                    </div>
                    <div class="form-group">
                        <label for="month_quarter" class="control-label">Month / Quarter</label>
                        <input type="text" class="form-control" id="month_quarter" name="month_quarter" value="">
                    </div>
                    <div class="form-group">
                        <label for="assigned_auditor" class="control-label">Assigned Auditor</label>
                        <select name="auditor_id" id="assigned_auditor" class="form-control">
                            <option value="">...</option>
                            @if($auditors->isNotEmpty())
                                @foreach($auditors as $auditor)
                                    <option value="{{ $auditor->id }}">{{ $auditor->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assigned_auditee" class="control-label">Assigned Auditee</label>
                        <select name="auditee_id" id="assigned_auditee" class="form-control">
                            <option value="">...</option>
                            @if($auditees->isNotEmpty())
                                @foreach($auditees as $auditee)
                                    <option value="{{ $auditee->id }}">{{ $auditee->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <h2>Auditors Input</h2>
                    <div class="form-group">
                        <label for="comments" class="control-label">Comments / Questions</label>
                        <input type="text" class="form-control" id="comments" name="comments" value="">
                    </div>
                    <div class="form-group">
                        <label for="finding" class="control-label">Finding / Observation</label>
                        <input type="text" class="form-control" id="finding" name="finding" value="">
                    </div>
                    <div class="form-group">
                        <label for="deviation_statement" class="control-label">Deviation Statement</label>
                        <input type="text" class="form-control" id="deviation_statement" name="deviation_statement" value="">
                    </div>
                    <div class="form-group">
                        <label for="evidence_reference" class="control-label">Manual / Evidence Reference</label>
                        <input type="text" class="form-control" id="evidence_reference" name="evidence_reference" value="">
                    </div>
                    <div class="form-group">
                        <label for="deviation_level" class="control-label">Deviation-Level</label>
                        <input type="text" class="form-control" id="deviation_level" name="deviation_level" value="">
                    </div>
                    <div class="form-group">
                        <label for="safety_level_before_action" class="control-label">Safety level before action</label>
                        <input type="text" class="form-control" id="safety_level_before_action" name="safety_level_before_action" value="">
                    </div>
                    <div class="form-group">
                        <label for="due_date" class="control-label">Due-Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="">
                    </div>
                    <div class="form-group">
                        <label for="repetitive_finding_ref_number" class="control-label">Repetitive Finding ref Number</label>
                        <input type="text" class="form-control" id="repetitive_finding_ref_number" name="repetitive_finding_ref_number" value="">
                    </div>

                    <h2>Auditee Input (NP)</h2>
                    <div class="form-group">
                        <label for="assigned_investigator" class="control-label">Assigned Investigator</label>
                        <select name="investigator_id" id="assigned_investigator" class="form-control">
                            <option value="">...</option>
                            @if($investigators->isNotEmpty())
                                @foreach($investigators as $investigator)
                                    <option value="{{ $investigator->id }}">{{ $investigator->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="corrections" class="control-label">Correction(s)</label>
                        <input type="text" class="form-control" id="corrections" name="corrections" value="">
                    </div>
                    <div class="form-group">
                        <label for="rootcause" class="control-label">Rootcause:
                            1. Why?
                            2. Why?
                            3. Why?
                            4. Why?
                            5. Why?
                        </label>
                        <textarea class="form-control" id="rootcause" name="rootcause" rows="3" placeholder=""></textarea>
                    </div>
                    <div class="form-group">
                        <label for="corrective_actions_plan" class="control-label">Corrective Action(s) Plan</label>
                        <input type="text" class="form-control" id="corrective_actions_plan" name="corrective_actions_plan" value="">
                    </div>
                    <div class="form-group">
                        <label for="preventive_actions" class="control-label">Preventive Action(s)</label>
                        <input type="text" class="form-control" id="preventive_actions" name="preventive_actions" value="">
                    </div>
                    <div class="form-group">
                        <label for="action_implemented_evidence" class="control-label">Action implemented evidence</label>
                        <input type="text" class="form-control" id="action_implemented_evidence" name="action_implemented_evidence" value="">
                    </div>
                    <div class="form-group">
                        <label for="safety_level_after_action" class="control-label">Safety level after action</label>
                        <input type="text" class="form-control" id="safety_level_after_action" name="safety_level_after_action" value="">
                    </div>
                    <div class="form-group">
                        <label for="effectiveness_review_date" class="control-label">Effectiveness Review date</label>
                        <input type="date" class="form-control" id="effectiveness_review_date" name="effectiveness_review_date" value="">
                    </div>
                    <div class="form-group">
                        <label for="response_date" class="control-label">Response date</label>
                        <input type="date" class="form-control" id="response_date" name="response_date" value="">
                    </div>

                    <div class="form-group">
                        <label for="extension_due_date" class="control-label">Extension Due-Date</label>
                        <input type="date" class="form-control" id="extension_due_date" name="extension_due_date" value="">
                    </div>
                    <div class="form-group">
                        <label for="closed_date" class="control-label">Closed date</label>
                        <input type="date" class="form-control" id="closed_date" name="closed_date" value="">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- /modal -->
@push('scripts')
    <script type="text/javascript">

        $(function () {

            // csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // open modal if window has hash (rule_reference)
            if(window.location.hash){
                getFormData();
            }

            // open modal if change hash in URL (rule_reference)
            $(window).on('hashchange', function() {
                getFormData();
            });

            // modal edit
            $('body').on('click', '.editItem', function () {
                getFormData();

            });

            // ajax create | save
            $('body').on('click', '#saveBtn', function (e) {
                e.preventDefault();

                resetForm();

                $("#ajaxModel").scrollTop(0);

                var form = $('#ItemForm');

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function (data) {
                        if (data.success) {
                            form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                            table.draw();
                        } else {
                            $.each(data.errors, function (input_name, input_error) {
                                $("#" + input_name).addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
                            });
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            // modal edit
            function getFormData() {

                // get hash (rule_rerference)
                var editURL = window.location.href;
                var rule_reference = editURL.substring(editURL.indexOf("#")+1);

                // perpare form data
                // var rule_reference = $(this).data('rule_reference');
                var action = "{{ route('admin.flows.requirements.update', $flow->id) }}";
                var method = "POST";

                resetForm();

                $.get('/admin/flows/' + {{ $flow->id }} + '/requirements/' + rule_reference + '/edit', function (data) {
                    $('#modelHeading').html("Edit - " + data.resource.rule_reference); // modal header
                    $('#ItemForm').attr('action', action); // form action
                    $('#_method').val(method); // form method
                    $('#saveBtn').html("Update"); // form button

                    $('#rule_group').val(data.resource.rule_group);
                    $('#rule_section').val(data.resource.rule_section);
                    $('#rule_reference').val(data.resource.rule_reference);
                    $('#rule_title').val(data.resource.rule_title);

                    $('#rule_manual_reference').val(data.resource.rule_manual_reference);
                    $('#rule_chapter').val(data.resource.rule_chapter);

                    $('#company_manual').val(data.resource.company_manual);
                    $('#company_chapter').val(data.resource.company_chapter);

                    $('#frequency').val(data.resource.frequency);
                    $('#month_quarter').val(data.resource.month_quarter);
                    if (data.auditor) {
                        $('#assigned_auditor option[value=' + data.auditor.id + ']').prop('selected', true); // form data - selected user company
                    }
                    if (data.auditee) {
                        $('#assigned_auditee option[value=' + data.auditee.id + ']').prop('selected', true); // form data - selected user company
                    }
                    $('#comments').val(data.resource.comments);
                    $('#finding').val(data.resource.finding);
                    $('#deviation_statement').val(data.resource.deviation_statement);
                    $('#evidence_reference').val(data.resource.evidence_reference);
                    $('#deviation_level').val(data.resource.deviation_level);
                    $('#safety_level_before_action').val(data.resource.safety_level_before_action);
                    $('#due_date').val(data.resource.due_date);
                    $('#repetitive_finding_ref_number').val(data.resource.repetitive_finding_ref_number);

                    if (data.investigator) {
                        $('#assigned_investigator option[value=' + data.investigator.id + ']').prop('selected', true); // form data - selected user company
                    }
                    $('#corrections').val(data.resource.corrections);
                    $('#rootcause').val(data.resource.rootcause);
                    $('#corrective_actions_plan').val(data.resource.corrective_actions_plan);
                    $('#preventive_actions').val(data.resource.preventive_actions);
                    $('#action_implemented_evidence').val(data.resource.action_implemented_evidence);
                    $('#safety_level_after_action').val(data.resource.safety_level_after_action);
                    $('#effectiveness_review_date').val(data.resource.effectiveness_review_date);
                    $('#response_date').val(data.resource.response_date);

                    $('#extension_due_date').val(data.resource.extension_due_date);
                    $('#closed_date').val(data.resource.closed_date);

                    $('#ajaxModel').modal('show');
                });
            }

            // reset form alert
            function resetForm() {
                var form = $('#ItemForm');
                $(".alert-success").remove();
                $(".text-danger").remove();
                form.find("input").removeClass('is-invalid');
                form.find("select").removeClass('is-invalid');
                form.find("date").removeClass('is-invalid');
                form.find("textarea").removeClass('is-invalid');
            }

        });// end function

    </script>
@endpush
