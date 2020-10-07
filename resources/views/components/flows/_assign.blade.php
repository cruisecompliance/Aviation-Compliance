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
                <form id="MultipleModalForm" action="{{ route('components.flows.requirements.multiple', $flow->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="tasks_id" value="" hidden>
                    </div>
                    <div class="form-group">
                        <label for="due_date" class="control-label">Due-Date</label>
                        <input type="text" class="form-control picker" name="due_date" value="" placeholder="dd.mm.yyyy">
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


