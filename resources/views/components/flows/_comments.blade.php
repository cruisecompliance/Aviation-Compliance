<!-- comments -->
<div class="row mt-3">
    <div class="col">
{{--        <h5 class="mb-2 font-size-16">Comments</h5>--}}

        <div id="comments"></div>

{{--        <!-- comment block -->--}}
{{--        <div class="media mt-3 p-1 comment-block">--}}
{{--            <img src="{{ asset('/images/users/user-9.jpg') }}"--}}
{{--                 class="mr-2 rounded-circle" height="36" alt="Arya Stark"/>--}}
{{--            <div class="media-body">--}}
{{--                <h5 class="mt-0 mb-0 font-size-14">--}}
{{--                    <span class="float-right text-muted font-12">4:30am</span>--}}
{{--                    Arya Stark--}}
{{--                </h5>--}}
{{--                <p class="mt-1 mb-0 text-muted">--}}
{{--                    Should I review the last 3 years legal documents as--}}
{{--                    well?--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <hr/>--}}
{{--        <!-- /comment block -->--}}

{{--        <!-- comment block -->--}}
{{--        <div class="media mt-2 p-1">--}}
{{--            <img src="{{ asset('/images/users/user-5.jpg') }}"--}}
{{--                 class="mr-2 rounded-circle" height="36" alt="Dominc B"/>--}}
{{--            <div class="media-body">--}}
{{--                <h5 class="mt-0 mb-0 font-size-14">--}}
{{--                    <span class="float-right text-muted font-12">3:30am</span>--}}
{{--                    Gary Somya--}}
{{--                </h5>--}}
{{--                <p class="mt-1 mb-0 text-muted">--}}
{{--                    @Arya FYI..I have created some general guidelines last--}}
{{--                    year.--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <hr/>--}}
{{--        <!-- /comment block -->--}}

    </div>
</div>
<!-- /comments -->

<!-- comment form-->
<div class="row mt-2">
    <div class="col">
        <div class="border rounded">

            <form id="CommentForm" name="CommentForm" action="{{ route('components.flows.comments.store', $flow->id) }}" method="POST">
                @csrf
                <textarea name="comment" rows="3" class="form-control border-0 resize-none" placeholder="Your comment...."></textarea>
                <input type="hidden" name="rule_id" value="">
                <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                    <div>
                        {{--                        <a href="#" class="btn btn-sm px-2 font-16 btn-light"><i class="mdi mdi-cloud-upload-outline"></i></a>--}}
                        {{--                        <a href="#" class="btn btn-sm px-2 font-16 btn-light"><i class="mdi mdi-at"></i></a>--}}
                    </div>
                    <button type="submit" id="CommentSubmit" form="CommentForm" class="btn btn-sm btn-success"><i class="mdi mdi-send mr-1"></i>Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- /comment form-->

@push('scripts')
    <script type="text/javascript">

        // $('ItemForm').ready(function () {
        //
        // });// end function

    </script>
@endpush
