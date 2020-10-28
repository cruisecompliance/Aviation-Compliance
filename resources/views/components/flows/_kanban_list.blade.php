@foreach(RequirementStatus::kanbanStatuses() as $status)
    <div class="col-3 pl-1 pr-1" style="max-width: 20%">
        <div class="card-box p-2">
            <h4 class="header-title mb-2 pl-2">{{ $status }}</h4>
            <ul class="sortable-list tasklist list-unstyled" id="upcoming" data-list="{{ $status }}">

                @if(!empty($kanbanData[$status]))
                    @foreach($kanbanData[$status] as $item)
                        <li id="task{{ $item->id }}" data-id="{{ $item->id }}" class="pt-2 pb-1">

                            <div class="row">
                                <div class="col text-left">
                                    <h5 class="mt-0"><a href="#{{ $item->rule_reference }}" data-rule_reference="{{ $item->rule_reference }}" class="editItem text-dark">{{ $item->rule_reference }}</a></h5>
                                </div>
                                <div class="col-auto text-right font-13">

                                    <div class="badge @if($item->finding == "None") bg-soft-success text-success @else bg-soft-warning text-warning @endif">
                                        <div data-toggle="tooltip" data-placement="top" title="F/O/N">
                                            {{--{{ substr($item->finding, 0, 1) }}--}}
                                            {{ $item->finding }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-left pt-2">
                                        <i class="mdi mdi-calendar"></i>
                                        <span data-toggle="tooltip" data-placement="top" title="Due Date">{{ ($item->due_date) ? $item->due_date : '-' }}</span>
                                </div>
                                <div class="col-auto text-right">
                                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($item->owner->email))) }}?s=20" alt="task-user" class="avatar-sm img-thumbnail rounded-circle" data-toggle="tooltip" data-placement="top" title="{{ $item->owner->name }}">
                                </div>
                            </div>

                        </li>
                    @endforeach
                @endif

            </ul>
        </div>
    </div>
@endforeach
