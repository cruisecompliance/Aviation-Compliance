@extends('layouts.user')

@section('content')


    <div class="content">

        <div class="container-fluid overflow-auto pl-1 pr-1">

            @if(!empty($flow))
                <div class="row">
                    <div class="col-7">
                        <h4 class="page-title mt-3">{{ $flow->title }}</h4>
                        <div class="text-muted font-13">Requirements: <span class="font-weight-bold">version {{ $flow->requirement->id }}</span></div>
                        <div class="text-muted font-13 mt-1">{{ $flow->description }}</div>
                    </div>
                    <div class="col-5 text-right pt-3">
                        <a href="{{ route('user.flows.table.index', ['rule_reference' => '', 'rule_section' => '', 'assignee' => Auth::user()->id, 'status' => '', 'finding' => '']) }}" class="btn btn-success btn-sm mr-1">Table View</a>
                    </div>
                </div>

                <!-- page content -->

                <!-- toolbar -->
                @include('components.flows._toolbar')
            <!-- /toolbar -->

                <!-- kanban board -->
                <div class="container-fluid overflow-auto pl-1 pr-1 disable-scrollbars">
                    <div class="row flex-nowrap" id="load">
                        <!-- start block -->
                        @include('components.flows._kanban_list')
                        <!-- /end block -->
                    </div>
                </div>
                <!-- /kanban board -->

                <!-- /page content -->
            @else
            <!-- page content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <p>The flow has not been created yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /page content -->

            @endif
        </div>

    </div>

    <!-- modal form -->
    @include('components.flows._modal')
    <!-- /modal form -->

    @push('scripts')
        <script type="text/javascript">

            // Kanban
            !function ($) {
                "use strict";

                var KanbanBoard = function () {
                    this.$body = $("body")
                };

                // load kanban data
                refreshKanban();

                //initializing various charts and components
                KanbanBoard.prototype.init = function () {
                    $('.tasklist').each(function () {
                        Sortable.create($(this)[0], {
                            group: 'shared',
                            animation: 150,
                            ghostClass: 'bg-ghost',
                            sort: false, // To disable sorting: set sort to false
                            disabled: true, // Disables the sortable if set to true.
                            // put: false, // Do not allow items to be put into this list
                            dataIdAttr: 'data-id',

                            onChoose: function (evt) {
                                var item_el = evt.item;  // dragged HTMLElement
                                var item_id = item_el.dataset.id;

                                var list_to = evt.to;    // target list
                                var list_name = list_to.dataset.list;

                                {{--var status_transitions = "{{ RequrementStatus::statusTransitions() }}";--}}
                                {{--console.log('choose', status_transition);--}}
                            },
                            onStart: function (evt) {
                            },
                            onEnd: function (evt) {
                                var item_el = evt.item;  // dragged HTMLElement
                                var item_id = item_el.dataset.id;

                                var list_to = evt.to;    // target list
                                var list_name = list_to.dataset.list;
                                console.log('end', list_to);

                                // csrf token
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                $.ajax({
                                    url: '/user/flows/kanban/status', // KanbanController@changeStatus
                                    type: "POST",
                                    data: {item_id: item_id, list_name: list_name},
                                    success: function (data) {
                                        // console.log(data);
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
