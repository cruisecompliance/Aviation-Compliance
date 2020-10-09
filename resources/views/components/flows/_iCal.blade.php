{{-- flow calendar $flow->hash --}}
<div class="p-1">
    <button id="iCalButton" class="btn btn-primary float-right" data-toggle="tooltip" data-placement="top" title="Copy to clipboard"><i class="mdi mdi-calendar-range"></i></button>
    <input type="text" id="iCalInput" class="form-control float-right mr-1" style="width: 150px; display: none;" value="{{ url("/calendar/$flow->hash") }}">
</div>
@push('scripts')
    <script type="text/javascript">

        // Copy to clipboard (link to iCal)
        $(function () {
            $("#iCalButton").click(function () {

                // copy links from input
                var copyText = document.getElementById("iCalInput");

                copyText.select();
                copyText.setSelectionRange(0, 99999); // for mobile devices

                document.execCommand("copy");

                // show input link
                $("#iCalInput").show();

                // change tooltip title
                $('#iCalButton').tooltip('hide')
                // .attr('data-original-title', "Copied: " + copyText.value)
                    .attr('data-original-title', "Copied to clipboard")
                    .tooltip('show');
            });

        });
    </script>
@endpush
