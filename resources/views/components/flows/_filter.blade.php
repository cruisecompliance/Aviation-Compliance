<form id="filterForm" action="{{ route(Route::currentRouteName(), $flow->id) }}" name="FilterForm" class="form-horizontal">

    <div class="d-flex flex-row form-row">
        <div class="form-group p-1">
            <!-- filter list -->
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-variant"></i> Filters <i class="mdi mdi-chevron-down"></i></button>

                <div class="dropdown-menu" id="filter_list" style="max-height:250px; overflow-x: scroll;">
                    {{-- <a class="dropdown-item" href=""></a>--}}
                </div>
            </div>
            <!-- /filter list -->
        </div>
        <div class="form-group p-1">
            {{--            <select name="rule_reference" id="filter_tasks" class="form-control float-left" data-toggle="select2" data-placeholder="Rule Reference" onchange="submit()">--}}
            {{--                <option></option>--}}
            {{--            </select>--}}

            <input type="text" id="filter_tasks" class="form-control" name="rule_reference" placeholder="Rule Reference" autocomplete="off">
            {{--            <div id="tasks_list" style="display: none"></div>--}}

            {{--                        <style>--}}
            {{--                            #tasks_list{--}}
            {{--                                background: #fff;--}}
            {{--                                border: 1px solid #f0f0f0;--}}
            {{--                                font-size: 14px;--}}
            {{--                                position: absolute;--}}
            {{--                                z-index: 1;--}}
            {{--                                width: 177px;--}}
            {{--                                /*height: 250px;*/--}}
            {{--                                /*overflow-x: scroll;*/--}}
            {{--                            }--}}
            {{--                            #tasks_list a {--}}
            {{--                                padding: 6px 12px;--}}
            {{--                                display: block;--}}
            {{--                                color: #6c757d;--}}
            {{--                            }--}}
            {{--                            #tasks_list a:hover {--}}
            {{--                                background-color: #6658dd;--}}
            {{--                                color: white;--}}
            {{--                            }--}}
            {{--                        </style>--}}


        </div>
        <div class="form-group p-1">
            <select name="rule_section" id="filter_sections" class="form-control" data-toggle="select2" data-placeholder="Rule Section">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <select name="assignee" id="filter_users" class="form-control" data-toggle="select2" data-placeholder="Assignee">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <select name="status" id="filter_statuses" class="form-control" data-toggle="select2" data-placeholder="Status">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <select name="finding" id="filter_finding" class="form-control" data-toggle="select2" data-placeholder="F/O/N">
                <option></option>
            </select>
        </div>
        <div class="form-group p-1">
            <button type="submit" class="btn btn-primary" hidden></button>
            <button type="button" id="show-filter-modal" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save</button>
            <button type="button" id="delete-filter" class="btn btn-danger">Delete</button>
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
                        <input type="text" name="rule_reference" value="" hidden>
                        <input type="text" name="rule_section" value="" hidden>
                        <input type="text" name="assignee" value="" hidden>
                        <input type="text" name="status" value="" hidden>
                        <input type="text" name="finding" value="" hidden>
                        {{--                        @foreach(request()->query() as $query => $value)--}}
                        {{--                            <input type="text" name="{{ $query }}" value="{{ $value }}" hidden>--}}
                        {{--                        @endforeach--}}
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

                // // rule_reference input
                // $.each(data.tasks, function (key, task) {
                // $('#filter_tasks').append('<option value="' + task + '">' + task + '</option>');
                //     // $('#tasks_list').append('<option value="' + task + '">' + task + '</option>');
                // });

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

                if (route.indexOf('kanban') > -1) {
                    $.each(data.kanbanStatuses, function (key, status) {
                        $('#filter_statuses').append('<option value="' + status + '">' + status + '</option>');
                    });
                } else {
                    $.each(data.tableStatuses, function (key, status) {
                        $('#filter_statuses').append('<option value="' + status + '">' + status + '</option>');
                    });
                }

                // finding F/O/N input
                var finding_options = ['Finding', 'Observation', 'None'];
                $.each(finding_options, function (key, finding_option) {
                    $('#filter_finding').append('<option value="' + finding_option + '">' + finding_option + '</option>');
                });

                // filter list active link
                $('#filter_list a[title="{{ request()->filter_name }}"]').addClass('active');

                // rule_reference selected option
                $('#filter_tasks').val("{{ request()->rule_reference }}");

                // rule_section selected option
                $('#filter_sections option[value="{{ request()->rule_section }}"]').prop('selected', true);

                // assignee selected option
                $('#filter_users option[value="{{ request()->assignee }}"]').prop('selected', true);

                // statuses selected option
                $('#filter_statuses option[value="{{ request()->status }}"]').prop('selected', true);

                // finding selected option
                $('#filter_finding option[value="{{ request()->finding }}"]').prop('selected', true);

                // draw dataTable
                $('#basic-datatable').DataTable().draw();
                // load kanban data
                refreshKanban();
            });

            // select2
            $('[data-toggle="select2"]').select2({
                allowClear: true,
                // tags: true,
            });

            // rule_reference (search)
            $('#filter_tasks').keyup(throttle(filterSearch));

            // rule section (search)
            $('#filter_sections').change(throttle(filterSearch));

            // assignee (search)
            $('#filter_users').change(throttle(filterSearch));

            // status (search)
            $('#filter_statuses').change(throttle(filterSearch));

            // finding - F/O/N (search)
            $('#filter_finding').change(throttle(filterSearch));


            {{--$('#filter_tasks').focusout(function () {--}}
            {{--    $('#tasks_list').hide();--}}
            {{--});--}}

            // show filter modal form
            $("#show-filter-modal").click(function () {
                resetForm();

                // form for save filter
                var form = $('#FilterModalForm');

                // reset form for save filter
                form[0].reset();

                var filterFormInputs = $('#filterForm').serializeArray();

                // press data in form for save filter
                $.each(filterFormInputs, function (key, value) {
                    form.find('input[name=' + value.name + ']').val(value.value);
                });

                // var filterModalForm = $('#filter_name');
                // filterModalForm.find('input[name=filter_name]').empty();
                $('#filter-modal').modal('show');
            });

            // delete filter
            $("#delete-filter").click(function () {

                var filter_name = "{{ (request()->filter_name) ?? NULL }}";

                if (filter_name) {

                    confirm("Do you really want to delete filter - '" + filter_name + "'?");

                    $.ajax({
                        type: "DELETE",
                        url: '{{ route('components.flows.filters.destroy', (request()->filter_name)) ?? NULL }}',
                        success: function (data) {
                            console.log(data);
                            window.location.replace("{{ url()->current() }}");
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }




                {{--    var Item_id = $(this).data("id");--}}
                {{--    confirm("Are You sure want to delete ?");--}}

                {{--    $.ajax({--}}
                {{--        type: "DELETE",--}}
                {{--        url: "{{ route('ajaxItems.store') }}"+'/'+Item_id,--}}
                {{--        success: function (data) {--}}
                {{--            table.draw();--}}
                {{--        },--}}
                {{--        error: function (data) {--}}
                {{--            console.log('Error:', data);--}}
                {{--        }--}}
                {{--    });--}}

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

        function filterSearch() {

            // var rule_reference = $(this).val();
            var route = "{{ Route::currentRouteName() }}";

            // reload (kanban and table)
            if (route.indexOf('kanban') > -1) {
                // $('#load').load(encodeURI(url) + ' #load');
                refreshKanban();
            } else {
                $('#basic-datatable').DataTable().draw();
            }

        }

        function refreshKanban() {

            var form = $('#filterForm');
            var formInputs = form.serializeArray();
            var firstFormInput = formInputs.shift();

                {{--var url = '/flows/' + {{ $flow->id }} +'/kanban/list';--}}
            var url = "{{ route('components.flows.kanban.list', $flow->id) }}";
            var url_param = url + '?' + firstFormInput.name + '=' + firstFormInput.value;

            $.each(formInputs, function (key, value) {
                url_param = url_param + '&' + value.name + '=' + value.value;
            });

            var request = $.get(url_param); // make request
            var container = $('#load');

            // container.addClass('loading'); // add loading class (optional)

            request.done(function (data) { // success
                container.html(data.html);
            });
            // request.always(function() {
            //     container.removeClass('loading');
            // });
        }

        // throttle search
        function throttle(f, delay) {
            var timer = null;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = window.setTimeout(function () {
                    f.apply(context, args);
                }, delay || 1000);
            };
        }


    </script>
@endpush
