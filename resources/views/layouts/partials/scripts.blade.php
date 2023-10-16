<!-- Vendor js -->
<script src="{{ asset('js/vendor.min.js') }}"></script>

<!-- Plugins js-->
<script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>


<script src="{{ asset('libs/axios/dist/axios.min.js') }}"></script>
{{--<script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>--}}

{{--<script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>--}}

<!-- Dashboar 1 init js-->
{{--<script src="{{ asset('js/pages/dashboard-1.init.js') }}"></script>--}}
<!-- App js-->
<script src="{{ asset('js/app.min.js') }}"></script>

<script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>

{{--<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>--}}
<script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
{{--<script src="{{ asset('js/pages/create-project.init.js') }}"></script>--}}

{{--<script>--}}
{{--    setTimeout(function(){--}}
{{--        window.location.reload();--}}
{{--    }, 90000);--}}
{{--</script>--}}
<script>
    function sendMarkRequest(id = null) {
        return $.ajax("{{ route('markNotification') }}", {
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id
            }
        });

                window.location.reload();
    }

    $(function () {
        $('.mark-as-read').click(function () {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                window.location.reload();
            });
        });
        $('#mark-all').click(function () {
            let request = sendMarkRequest();
            request.done(() => {
                $('div.alert').remove();
            })
        });
    });
</script>



