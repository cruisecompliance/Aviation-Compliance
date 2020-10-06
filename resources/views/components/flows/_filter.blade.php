<form id="filterForm" action="{{ route(Route::currentRouteName(), $flow->id) }}" name="FilterForm" class="form-horizontal">

    <div class="d-flex flex-row form-row">
        <div class="form-group p-1">
            <!-- filter list -->
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-variant"></i> Filters <i class="mdi mdi-chevron-down"></i></button>

                <div class="dropdown-menu" id="filter_list">
                    {{-- <a class="dropdown-item" href=""></a>--}}
                </div>
            </div>
            <!-- /filter list -->
        </div>
        <div class="form-group p-1">
            <select name="rule_reference" id="filter_tasks" class="form-control float-left" data-toggle="select2" data-placeholder="Rule Reference" onchange="submit()">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <select name="rule_section" id="filter_sections" class="form-control" data-toggle="select2" data-placeholder="Rule Section" onchange="submit()">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <select name="assignee" id="filter_users" class="form-control" data-toggle="select2" data-placeholder="Assignee" onchange="submit()">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <select name="status" id="filter_statuses" class="form-control" data-toggle="select2" data-placeholder="Status" onchange="submit()">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <button type="submit" class="btn btn-primary" hidden></button>
            <button type="button" id="show-filter-modal" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save</button>
        </div>
    </div>

</form>

<!-- modal save filter -->
<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Save Filter</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="FilterModalForm" action="{{ route('components.flows.filters.store', $flow->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Filter name*</label>
                        <input type="text" id="filter_name" class="form-control" name="name" value="" required>
                        <input type="text" name="route" value="{{ route(Route::currentRouteName(), $flow->id) }}" hidden>
                        @foreach(request()->query() as $query => $value)
                            <input type="text" name="{{ $query }}" value="{{ $value }}" hidden>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="FilterSubmit">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /modal save filter -->

@push('scripts')
    <script type="text/javascript">

        $(document).ready(function () {

            // get filter form data
            $.get("{{ route('components.flows.filters.show', $flow->id) }}", function (data) {

                // filter list
                if (jQuery.isEmptyObject(data.filters)) {
                    $('#filter_list').hide();
                } else {
                    $.each(data.filters, function (key, filter) {
                        $('#filter_list').append('<a href="{{ (request()->is('admin/*')) ? route(Route::currentRouteName(), $flow->id) : route(Route::currentRouteName()) }}?' + filter.params + '" class="dropdown-item" id="filter_link" title="' + filter.name + '">' + filter.name + '</a>');
                    });
                }

                // rule_reference input
                $.each(data.tasks, function (key, task) {
                    $('#filter_tasks').append('<option value="' + task + '">' + task + '</option>');
                });

                // rule_section input
                $.each(data.sections, function (key, sections) {
                    $('#filter_sections').append('<option value="' + sections + '">' + sections + '</option>');
                });

                // assignee input
                $.each(data.users, function (key, user) {
                    $('#filter_users').append('<option value="' + user.id + '">' + user.name + '</option>');
                });

                // statuses input (kanban and table)
                var route = "{{ Route::currentRouteName() }}";

                if(route.indexOf('kanban') > -1){
                    $.each(data.kanbanStatuses, function (key, status) {
                        $('#filter_statuses').append('<option value="' + status + '">' + status + '</option>');
                    });
                } else {
                    $.each(data.tableStatuses, function (key, status) {
                        $('#filter_statuses').append('<option value="' + status + '">' + status + '</option>');
                    });
                }

                // filter list active link
                $('#filter_list a[title="{{ request()->filter_name }}"]').addClass('active');

                // rule_reference selected option
                $('#filter_tasks option[value="{{ request()->rule_reference }}"]').prop('selected', true);

                // rule_section selected option
                $('#filter_sections option[value="{{ request()->rule_section }}"]').prop('selected', true);

                // assignee selected option
                $('#filter_users option[value="{{ request()->assignee }}"]').prop('selected', true);

                // statuses selected option
                $('#filter_statuses option[value="{{ request()->status }}"]').prop('selected', true);


            });

            // select2
            $('[data-toggle="select2"]').select2({
                allowClear: true,
            });

            // show filter modal form
            $("#show-filter-modal").click(function () {
                resetForm();
                $('#filter_name').val('');
                $('#filter-modal').modal('show');
            });

            // save filter
            $('body').on('click', '#FilterSubmit', function (e) {
                e.preventDefault();

                resetForm();

                var form = $('#FilterModalForm');

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function (data) {
                        if (data.success) {
                            window.location.replace(data.redirect);
                        } else {
                            $.each(data.errors, function (input_name, input_error) {
                                // $("#" + input_name).addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
                                $("#filter_name").addClass('is-invalid').after('<span class="text-danger">' + input_error + '</br></span>');
                            });
                        }
                    }
                });

            });

        });

        // reset form alert
        function resetForm() {
            var form = $('#FilterModalForm');
            $(".alert-success").remove();
            $(".text-danger").remove();
            form.find("input").removeClass('is-invalid');
            form.find("select").removeClass('is-invalid');
            form.find("date").removeClass('is-invalid');
            form.find("textarea").removeClass('is-invalid');
        }

        // select submit (on change)
        function submit() {
            $('#filterForm').submit();
        }

    </script>
@endpush
