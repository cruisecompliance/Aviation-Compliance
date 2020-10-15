@if(!empty($flow))
    <button type="button" id="show-multiple-modal" class="btn btn-primary">Assign</button>

    <!-- modal multiple -->
    <div class="modal fade" id="multiple-modal" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Assign</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="MultipleModalForm" action="{{ route('components.flows.multiple.requirements.update', $flow->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="tasks_id" value="" hidden>
                        </div>
                        <div class="form-group">
                            <label for="task_owner" class="control-label">Task Owner</label>
                            <select name="task_owner" class="form-control">
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month_quarter" class="control-label">Month / Quarter</label>
                            <input type="text" class="form-control month-picker" name="month_quarter" value="" placeholder="mm.yyyy" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="due_date" class="control-label">Due-Date</label>
                            <input type="text" class="form-control picker" name="due_date" value="" placeholder="dd.mm.yyyy" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="task_status" class="control-label">Status</label>
                            <select name="task_status" class="form-control">
{{--                                <option></option>--}}
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="MultipleSubmit">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal multiple -->


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

                // show multiple modal form
                $("#show-multiple-modal").click(function () {
                    // e.preventDefault();

                    // reset form data
                    resetAssignFormAlert();

                    //
                    resetAssignFormFields();

                    // set selected rows in hidden input in form
                    var assignForm = $('#MultipleModalForm');
                    var rows_selected = $('#basic-datatable').DataTable().column(0).checkboxes.selected();

                    assignForm.find('input[name=tasks_id]').val(rows_selected.join(","));
                    assignForm.find('input[name=tasks_selected]').val(rows_selected.length);

                    // set form data
                    $.get("{{ route('components.flows.multiple.requirements.edit', $flow->id) }}", function (data) {

                        // task owner select
                        $.each(data.users, function (key, user) {
                            assignForm.find('select[name=task_owner]').append('<option value="' + user.id + '">' + user.name + '</option>');
                        });

                        // status select
                        $.each(data.statuses, function (key, status) {
                            assignForm.find('select[name=task_status]').append('<option value="' + status + '">' + status + '</option>');
                        });

                    });

                    // show form
                    $('#multiple-modal').modal('show');
                });



                // submit multiple modal form
                $('body').on('click', '#MultipleSubmit', function (e) {
                    e.preventDefault();

                    resetAssignFormAlert();

                    var form = $('#MultipleModalForm');

                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        success: function (data) {
                            if (data.success) {
                                form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                                $('#basic-datatable').DataTable().column(0).checkboxes.deselect();
                                $('#basic-datatable').DataTable().draw();
                            } else {
                                $.each(data.errors, function (input_name, input_error) {
                                    form.find('input[name=' + input_name + ']').addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
                                    form.find('select[name=' + input_name + ']').addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
                                });
                            }
                        }
                    });

                });

                function resetAssignFormAlert() {
                    var form = $('#MultipleModalForm');
                    $(".alert-success").remove();
                    $(".text-danger").remove();
                    form.find("input").removeClass('is-invalid');
                    form.find("select").removeClass('is-invalid');
                }

                function resetAssignFormFields() {
                    var form = $('#MultipleModalForm');
                    form.find('select').find('option').remove();
                    form.find('select[name=task_owner]').find('option').remove().end().append('<option value="">...</option>').val();
                    form.find('input').val('');
                }
            });

        </script>
    @endpush
@endif


{{--// Task Owner--}}

{{--// Month/Quarter--}}

{{--// Due Date--}}

{{--// Status--}}
