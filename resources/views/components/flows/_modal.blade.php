@if(!empty($flow))
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
                                <li class="nav-item">
                                    <a href="#task_history" data-toggle="tab" aria-expanded="false" class="nav-link">
                                        History
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
                                <div class="tab-pane" id="task_history">
                                    <!-- history -->
                                    @include('components.flows._history')
                                    <!--/history -->
                                </div>
                            </div>
                        </div> <!-- end col -->

                    </div>
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

                // datepicker
                $(".month-picker").datepicker({
                    format: "mm.yyyy",
                    startView: "months",
                    minViewMode: "months",
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
                            $('.picker').datepicker('place');
                            $('.month-picker').datepicker('place');
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
                    var formData = new FormData(form[0]);

                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        // data: form.serialize(),
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.success) {
                                getModalData(); // reload form data
                                form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>'); // add success massage
                                form.find('textarea[name=comment]').val(''); // reset textarea message
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

                    $.get('/flows/' + {{ $flow->id }} +'/requirements/' + rule_reference + '/edit', function (data) {

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
                        $('#evidence_reference').val(''); // empty
                        $('#deviation_level').val(data.resource.deviation_level);
                        $('#safety_level_before_action').val(data.resource.safety_level_before_action);
                        $('#due_date').val(data.resource.due_date);
                        $('#repetitive_finding_ref_number').val(data.resource.repetitive_finding_ref_number);

                        $('#corrections').val(data.resource.corrections);
                        $('#rootcause').val(data.resource.rootcause);
                        $('#corrective_actions_plan').val(data.resource.corrective_actions_plan);
                        $('#preventive_actions').val(data.resource.preventive_actions);
                        $('#action_implemented_evidence').val(''); // empty
                        $('#safety_level_after_action').val(data.resource.safety_level_after_action);
                        $('#effectiveness_review_date').val(data.resource.effectiveness_review_date);
                        $('#response_date').val(data.resource.response_date);

                        $('#extension_due_date').val(data.resource.extension_due_date);
                        $('#closed_date').val(data.resource.closed_date);

                        // Finding / Observation - Field Dependence
                        if (data.resource.finding === 'None') {
                            $('#deviation_level').prop('readonly', true);
                        } else {
                            $('#deviation_level').prop('readonly', false);
                        }

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

                        // check if role has permission to change/read task (flowData)
                        if (!data.status_permission) {
                            $('#statuses-wrapper').hide();
                            $("#ItemForm :input").attr("disabled", true);
                        } else {
                            $('#statuses-wrapper').show();
                        }

                        // task owner
                        if (data.users) {
                            // remove option
                            $('#task_owner').find('option').remove().end().append('<option value="">...</option>').val();
                            // append option
                            $.each(data.users, function (key, user) {
                                $('#task_owner').append('<option value="' + user.id + '">' + user.name + '</option>');
                            });
                            // selected option
                            $('#task_owner option[value="' + data.resource.task_owner + '"]').prop('selected', true);
                        }

                        // set auditor - manual evidence reference link
                        if(data.resource.evidence_reference) {
                            var auditor_storage = "{{ url('') }}" + '/storage/auditor/' + data.resource.evidence_reference;
                            $('#evidence_reference_link').find('a').remove();
                            $('#evidence_reference_link').show().append('<a class="file-text" href=' + auditor_storage + ' target="_blank">' + data.resource.evidence_reference + '</a>'); // file link

                        } else {
                            $('#evidence_reference_link').find('a').remove();
                        }

                        // set investigator - action implemented evidence link
                        if(data.resource.action_implemented_evidence) {
                            var investigator_storage = "{{ url('') }}" + '/storage/investigator/' + data.resource.action_implemented_evidence;
                            $('#action_implemented_evidence_link').find('a').remove();
                            $('#action_implemented_evidence_link').show().append('<a class="file-text" href=' + investigator_storage + ' target="_blank">' + data.resource.action_implemented_evidence + '</a>'); // file link

                        } else {
                            $('#action_implemented_evidence_link').find('a').remove();
                        }


                        // get comment for task
                        getComments(); // ToDo

                        // get history for task
                        getHistory();

                        // show modal
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
                            if (data.comment) {
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

                    $.get('/flow/' + rule_id + '/comments', function (data) {

                        $('#comments').empty(); // ToDo

                        if (data.comments) {

                            $.each(data.comments, function (key, comment) {

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

                function getHistory(){
                    var rule_id = $('#rule_id').val();

                    $.get('/flows/' + rule_id + '/history', function (data) {

                        $('#history').empty();

                        if (data.diff) {
                            $.each(data.diff, function (key, diff) {
                                $.each(diff.fields, function (field, value) {

                                    var field_name = field.split("_").join(" ");
                                    var field_name = toTitleCase(field_name);

                                    $('#history').append(
                                        '<div class="media mt-3 p-1 comment-block">' +
                                        '<div class="media-body">' +
                                        '<h5 class="mt-0 mb-0 font-size-14">' +
                                        '<span class="float-right text-muted font-12">' + diff.created_at + '</span>' +
                                        diff.user +
                                        '<span class="text-muted font-14"> updated the </span>' +
                                        field_name +
                                        '</h5>' +
                                        '<div class="diff mt-1 mb-1">' +
                                        '<span style="background: #ffe9e9" class="deleted-line">' + value.old + '</span>'+
                                        '<span> <i class="fas fa-long-arrow-alt-right"></i> </span>'+
                                        '<span style="background: #e9ffe9" class="added-line">' + value.new + '</span>'+
                                        '</div>'+
                                        '</div>' +
                                        '</div>' +
                                        '<hr/>'
                                    );

                                });
                            });

                        }
                    });
                }

                function toTitleCase(str) {
                    return str.replace(/(?:^|\s)\w/g, function(match) {
                        return match.toUpperCase();
                    });
                }

            });// end function
        </script>
    @endpush
@endif
