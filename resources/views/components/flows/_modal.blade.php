<!-- modal -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xl-12">

                        <ul class="nav nav-tabs nav-bordered">
                            <li class="nav-item">
                                <a href="#task_details" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                    Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#task_comments" data-toggle="tab" aria-expanded="true" class="nav-link">
                                    Comments
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="task_details">
                                <!-- form -->
                            @include('components.flows._form')
                            <!-- /form -->
                            </div>
                            <div class="tab-pane" id="task_comments">
                                <!-- comments -->
                            @include('components.flows._comments')
                            <!--/comments -->
                            </div>
                        </div>
                    </div> <!-- end col -->

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

                // datepicker
                $(".picker").datepicker({
                    format: 'dd.mm.yyyy',
                    startDate: '+0d',
                    enableOnReadonly: false,
                    autoclose: true,
                });
                // datepicker modal scroll
                var t;
                $(document).on(
                    'DOMMouseScroll mousewheel scroll',
                    '#ajaxModel',
                    function () {
                        window.clearTimeout(t);
                        t = window.setTimeout(function () {
                            $('.picker').datepicker('place')
                        });
                    }
                );

                // open modal if window has hash (rule_reference)
                if (window.location.hash) {
                    getModalData();
                }

                // open modal if change hash in URL (rule_reference)
                $(window).on('hashchange', function () {
                    getModalData();
                });

                // modal edit
                $('body').on('click', '.editItem', function () {
                    getModalData();

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
                                getModalData(); // reload form data
                                form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>'); // add success massage
                                $('#basic-datatable').DataTable().draw(); // draw in datatable
                                $('#load').load(location.href + ' #load'); // reload kanban board
                            } else {
                                $.each(data.errors, function (input_name, input_error) {
                                    var errors = input_error.join('<br/>');
                                    $("#" + input_name).addClass('is-invalid').after('<span class="text-danger">' + errors + '<br/></span>');
                                });
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });


                // modal edit
                function getModalData() {

                    // get hash (rule_rerference)
                    var editURL = window.location.href;
                    var rule_reference = editURL.substring(editURL.indexOf("#") + 1);

                    // prepare form data
                    // var rule_reference = $(this).data('rule_reference');
                    var action = "{{ route('components.flows.requirements.update', $flow->id) }}";
                    var method = "POST";
                    resetForm();

                    $.get('/flows/' + {{ $flow->id }} + '/requirements/' + rule_reference + '/edit', function (data) {

                        $('#modelHeading').html("Edit - " + data.resource.rule_reference); // modal header
                        $('#ItemForm').attr('action', action); // form action
                        $('#_method').val(method); // form method
                        $('#rule_id').val(data.resource.id); // hidden rule_reference id
                        $('#saveBtn').html("Update"); // form button

                        $('#requirements_rule').val(data.resource.rule_reference);

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

                        $('#questions').val(data.resource.questions);
                        $('#finding').val(data.resource.finding);
                        $('#deviation_statement').val(data.resource.deviation_statement);
                        $('#evidence_reference').val(data.resource.evidence_reference);
                        $('#deviation_level').val(data.resource.deviation_level);
                        $('#safety_level_before_action').val(data.resource.safety_level_before_action);
                        $('#due_date').val(data.resource.due_date);
                        $('#repetitive_finding_ref_number').val(data.resource.repetitive_finding_ref_number);

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

                        // statuses list (add select option)
                        if (data.transitions) {
                            // merge task status and status transitions
                            var statuses = data.transitions.concat(data.resource.task_status);

                            // remove option
                            $('#task_status').find('option').remove().val();
                            // $('#task_status').find('option').remove().end().append('<option value="">...</option>').val();

                            // append option
                            $.each(statuses, function (key, value) {
                                $('#task_status').append('<option value="' + value + '">' + value + '</option>');
                            });

                            // selected option
                            $('#task_status option[value="' + data.resource.task_status + '"]').prop('selected', true);

                        }

                        // statuses role permission
                        if (!data.status_permission) {
                            $('#statuses-wrapper').hide();
                        } else {
                            $('#statuses-wrapper').show();
                        }

                        // auditors input
                        if (data.auditors) {
                            // remove option
                            $('#assigned_auditor').find('option').remove().end().append('<option value="">...</option>').val();
                            // append option
                            $.each(data.auditors, function (key, auditor) {
                                $('#assigned_auditor').append('<option value="' + auditor.id + '">' + auditor.name + '</option>');
                            });
                            // selected option
                            $('#assigned_auditor option[value="' + data.resource.auditor_id + '"]').prop('selected', true);
                        }

                        // auditee input
                        if (data.auditees) {
                            // remove option
                            $('#assigned_auditee').find('option').remove().end().append('<option value="">...</option>').val();
                            // append option
                            $.each(data.auditees, function (key, auditee) {
                                $('#assigned_auditee').append('<option value="' + auditee.id + '">' + auditee.name + '</option>');
                            });
                            // selected option
                            $('#assigned_auditee option[value="' + data.resource.auditee_id + '"]').prop('selected', true);
                        }

                        // investigator input
                        if (data.investigators) {
                            // $('#assigned_investigator option[value=' + data.investigator.id + ']').prop('selected', true); // form data - selected user company
                            // remove option
                            $('#assigned_investigator').find('option').remove().end().append('<option value="">...</option>').val();
                            // append option
                            $.each(data.investigators, function (key, investigator) {
                                $('#assigned_investigator').append('<option value="' + investigator.id + '">' + investigator.name + '</option>');
                            });
                            // selected option
                            $('#assigned_investigator option[value="' + data.resource.investigator_id + '"]').prop('selected', true);
                        }

                        getComments(); // ToDo

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

                // comment save
                $('#CommentSubmit').click(function (e) {

                    e.preventDefault();

                    var rule_id = $('#rule_id').val();
                    var form = $('#CommentForm');
                    form.find('input[name="rule_id"]').val(rule_id);

                    // reset form textarea
                    $(".text-danger").remove();
                    form.find("textarea").removeClass('is-invalid');

                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        success: function (data) {
                            if(data.comment) {
                                // comments
                                getComments();
                                // reset form textarea
                                form.find('textarea[name="message"]').val('');
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

                // get comments
                function getComments() {

                    var rule_id = $('#rule_id').val();
                    console.log(rule_id);

                    $.get('/flow/' + rule_id + '/comments', function (data) {

                        $('#comments').empty(); // ToDo

                        if (data.comments) {
                            // console.table(data.comments);

                            $.each(data.comments, function (key, comment) {
                                // console.log(comment.message);
                                $('#comments').append(
                                    '<div class="media mt-3 p-1 comment-block">' +
                                    '<div class="media-body">' +
                                    '<h5 class="mt-0 mb-0 font-size-14">' +
                                    '<span class="float-right text-muted font-12">' + comment.created_at + '</span>' +
                                    comment.user.name +
                                    '</h5>' +
                                    '<p class="mt-1 mb-0 text-muted">' +
                                    comment.message +
                                    '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '<hr/>'
                                );
                            });

                        }
                    });
                }

            });// end function
        </script>
@endpush
