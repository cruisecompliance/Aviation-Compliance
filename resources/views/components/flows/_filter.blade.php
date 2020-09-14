<form id="filterForm" action="{{ route(Route::currentRouteName(), $flow->id) }}" name="FilterForm" class="form-horizontal">
    <div class="form-row">

        <div class="form-group col-lg-2" style="min-width: 120px">
            <!-- filter list -->
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" style="width: 120px;" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-variant"></i> Filters <i class="mdi mdi-chevron-down"></i></button>

                <div class="dropdown-menu">
                    @if($filters->isNotEmpty())
                        @foreach($filters as $filter)
                            <a class="dropdown-item {{ (request()->filter_name == $filter->name) ? 'btn-block' : '' }}" href="{{ route(Route::currentRouteName(), $flow->id) }}?{{ $filter->params }}">{{ $filter->name }}</a>
                        @endforeach
                    @endif
                </div>
            </div>
            <!-- /filter list -->
        </div>
        <div class="form-group col-lg-3">

            <select name="rule_reference" class="form-control float-left" data-toggle="select2" data-placeholder="Rule Reference" onchange="select()">
                <option></option>
                @if($flowData->isNotEmpty())
                    @foreach($flowData as $item)
                        <option value="{{ $item->rule_reference }}" {{ (request()->rule_reference == $item->rule_reference) ? 'selected' : '' }}>{{ $item->rule_reference }} </option>
                    @endforeach
                @endif
            </select>

        </div>
        <div class="form-group col-lg-2">
            <select name="rule_section" class="form-control" data-toggle="select2" data-placeholder="Rule Section" onchange="select()">
                <option></option>
                @if($flowData->isNotEmpty())
                    @foreach($flowData->pluck('rule_section')->unique() as $rule_section)
                        <option value="{{ $rule_section }}" {{ (request()->rule_section == $rule_section) ? 'selected' : '' }}>{{ $rule_section }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-lg-2">
            <select name="assignee" class="form-control" data-toggle="select2" data-placeholder="Assignee" onchange="select()">
                <option></option>
                @if(collect($users)->isNotEmpty())
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}" {{ (request()->assignee == $user['id']) ? 'selected' : '' }}>{{ $user['name'] }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-lg-2">
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
            <form action="{{ route('admin.flows.filters.store', $flow->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Filter name*</label>
                        <input type="text" class="form-control" name="name" value="" required>
                        <input type="text" name="route" value="{{ route(Route::currentRouteName(), $flow->id) }}" hidden>
                        @foreach(request()->query() as $query => $value)
                            <input type="text" name="{{ $query }}" value="{{ $value }}" hidden>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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

            // select2
            $('[data-toggle="select2"]').select2({
                allowClear: true,
            });

            // save filter (modal form)
            $(function () {
                $("#show-filter-modal").click(function () {
                    $('#filter-modal').modal('show');
                });

            });

            //
            // $.get('action', function (data) {
            //
            // });

        });

        // select submit
        function select() {
            $('#filterForm').submit();
        }

    </script>
@endpush
