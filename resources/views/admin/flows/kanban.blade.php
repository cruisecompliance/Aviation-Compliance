@extends('layouts.admin')

@section('content')

    <div class="content">

        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.flows.index') }}">Flows</a></li>
                                <li class="breadcrumb-item active">Kanban</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- page content -->
            <div class="row">
                <!-- BLOCK 1 -->
                <div class="col-lg-4">
                    <div class="card-box">
                        <h4 class="header-title mb-3">Upcoming</h4>
                        <ul class="sortable-list tasklist list-unstyled" id="upcoming" data-list="{{ RequrementStatus::UPCOMING }}">
                            @if(!empty($flowData[RequrementStatus::UPCOMING]))
                                @foreach($flowData[RequrementStatus::UPCOMING] as $item)
                                    <li id="task{{ $item->id }}" data-id="{{ $item->id }}">
{{--                                        <span class="badge bg-soft-danger text-danger float-right">High</span>--}}
                                        <h5 class="mt-0"><a href="javascript: void(0)" data-toggle="tooltip" data-rule_reference="{{ $item->rule_reference }}" data-original-title="Edit" class="editItem text-dark">{{ $item->rule_reference }}</a></h5>
                                        <p>{{ $item->rule_title }}</p>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="font-13 mt-2 mb-0"><i class="mdi mdi-calendar"></i> {{ ($item->due_date) ? $item->due_date->format('d.m.Y') : '-' }}</p>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-right">
                                                    <a href="javascript: void(0);" class="text-muted">
                                                        <div>Auditor: {{ ($item->auditor->name) ?? '-' }}</div>
                                                        <div>Auditee: {{ ($item->auditee->name) ?? '-' }}</div>
                                                        <div>Investigator: {{ ($item->investigator->name) ?? '-' }}</div>
{{--                                                        <img src="{{ asset('images/users/user-1.jpg') }}" alt="task-user" class="avatar-sm img-thumbnail rounded-circle">--}}
                                                    </a>
{{--                                                    <a href="javascript: void(0);" class="text-muted">--}}
{{--                                                        <img src="{{ asset('images/users/user-3.jpg') }}" alt="task-user" class="avatar-sm img-thumbnail rounded-circle">--}}
{{--                                                    </a>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div> <!-- end col -->
                <!-- /BLOCK 1-->
                <!-- BLOCK 2 -->
                <div class="col-lg-4">
                    <div class="card-box">
                        <h4 class="header-title mb-3">In Progress</h4>
                        <ul class="sortable-list tasklist list-unstyled" id="inprogress" data-list="{{ RequrementStatus::IN_PROGRESS }}">
                            @if(!empty($flowData[RequrementStatus::IN_PROGRESS]))
                                @foreach($flowData[RequrementStatus::IN_PROGRESS] as $item)
                                    <li id="task{{ $item->id }}" data-id="{{ $item->id }}">
{{--                                        <span class="badge bg-soft-warning text-warning float-right">Medium</span>--}}
                                        <h5 class="mt-0"><a href="javascript: void(0)" data-toggle="tooltip" data-rule_reference="{{ $item->rule_reference }}" data-original-title="Edit" class="editItem text-dark">{{ $item->rule_reference }}</a></h5>
                                        <p>{{ $item->rule_title }}</p>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="font-13 mt-2 mb-0"><i class="mdi mdi-calendar"></i> {{ ($item->due_date) ? $item->due_date->format('d.m.Y') : '-' }}</p>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-right">
                                                    <a href="javascript: void(0);" class="text-muted">
                                                        <div>Auditor: {{ ($item->auditor->name) ?? '-' }}</div>
                                                        <div>Auditee: {{ ($item->auditee->name) ?? '-' }}</div>
                                                        <div>Investigator: {{ ($item->investigator->name) ?? '-' }}</div>
                                                        {{--                                                        <img src="{{ asset('images/users/user-8.jpg') }}" alt="task-user" class="avatar-sm img-thumbnail rounded-circle">--}}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div> <!-- end card-box -->
                </div> <!-- end col -->
                <!-- /BLOCK 2-->
                <!-- BLOCK 3-->
                <div class="col-lg-4">
                    <div class="card-box">
                        <h4 class="header-title mb-3">Completed</h4>
                        <ul class="sortable-list tasklist list-unstyled" id="completed" data-list="{{ RequrementStatus::COMPLETED }}">
                            @if(!empty($flowData[RequrementStatus::COMPLETED]))
                                @foreach($flowData[RequrementStatus::COMPLETED] as $item)
                                    <li id="task{{ $item->id }}" data-id="{{ $item->id }}">
{{--                                        <span class="badge bg-soft-success text-success float-right">Low</span>--}}
                                        <h5 class="mt-0"><a href="javascript: void(0)" data-toggle="tooltip" data-rule_reference="{{ $item->rule_reference }}" data-original-title="Edit" class="editItem text-dark">{{ $item->rule_reference }}</a></h5>
                                        <p>{{ $item->rule_title }}</p>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="font-13 mt-2 mb-0"><i class="mdi mdi-calendar"></i> {{ ($item->due_date) ? $item->due_date->format('d.m.Y') : '-' }}</p>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-right">
                                                    <a href="javascript: void(0);" class="text-muted">
                                                        <div>Auditor: {{ ($item->auditor->name) ?? '-' }}</div>
                                                        <div>Auditee: {{ ($item->auditee->name) ?? '-' }}</div>
                                                        <div>Investigator: {{ ($item->investigator->name) ?? '-' }}</div>
                                                        {{--                                                        <img src="{{ asset('images/users/user-3.jpg') }}" alt="task-user" class="avatar-sm img-thumbnail rounded-circle">--}}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div> <!-- end card-box -->
                </div> <!-- end col -->
                <!-- /BLOCK 3-->
            </div>
            <!-- /page content -->

        </div>

    </div>

    @include('admin.flows._form')

    @push('scripts')
        <script type="text/javascript">

            !function ($) {
                "use strict";

                var KanbanBoard = function () {
                    this.$body = $("body")
                };

                //initializing various charts and components
                KanbanBoard.prototype.init = function () {
                    $('.tasklist').each(function () {
                        Sortable.create($(this)[0], {
                            group: 'shared',
                            animation: 150,
                            ghostClass: 'bg-ghost',
                            sort: false, //
                            disabled: false, //
                            dataIdAttr: 'data-id',
                            onEnd: function (evt) {
                                var item_el = evt.item;  // dragged HTMLElement
                                var item_id = item_el.dataset.id;

                                var list_to = evt.to;    // target list
                                var list_name = list_to.dataset.list;

                                // csrf token
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                $.ajax({
                                    url: '/admin/flows/' + {{ $flow->id }} + '/kanban/status', // KanbanController@changeStatus
                                    type: "POST",
                                    data: {item_id: item_id, list_name: list_name},
                                    success: function (data) {
                                        console.log(data);
                                    },
                                    error: function (data) {
                                        console.log('Error:', data);
                                    }
                                });
                                // var listFrom = evt.from;  // previous list
                                // var data_id = Sortable.utils.is(listFrom, "#upcoming");
                                // var oldIndex = evt.oldIndex;  // element's old index within old parent
                                // var newIndex = evt.newIndex;  // element's new index within new parent
                                // var oldDraggableIndex = evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
                                // var newDraggableIndex = evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
                                // var clone = evt.clone // the clone element
                                // var pullMode = evt.pullMode;  // when item is in another sortable: `"clone"` if cloning, `true` if moving
                            },
                        });

                    });
                },

                    //init KanbanBoard
                    $.KanbanBoard = new KanbanBoard, $.KanbanBoard.Constructor =
                    KanbanBoard

            }(window.jQuery),

                //initializing KanbanBoard
                function ($) {
                    "use strict";
                    $.KanbanBoard.init()
                }(window.jQuery);

        </script>
    @endpush
@endsection
