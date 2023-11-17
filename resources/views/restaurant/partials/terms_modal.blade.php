<div id="terms-conditions-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel"
     aria-hidden="true">
    {{--    <div class="modal-dialog modal-full-width">--}}
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        {{--        <div class="modal-dialog modal-lg">--}}
        <div class="modal-content">
            <div class="modal-header">
{{--                <h4 class="modal-title" id="fullWidthModalLabel">Terms and Condtions</h4>--}}
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">

                <div id="invalid-input-alert"></div>
                <div id="success-alert"></div>

                <div class="text-center mt-2 mb-4">
                    <h4 class="modal-title" id="fullWidthModalLabel">Terms and Condtions</h4>
                    <a href="index.html" class="text-success">
                        <span><img src="{{  $logo }}" alt="" height="40"></span>
                    </a>
                </div>

                <form class="needs-validation" novalidate method="Post" name="terms-conditions-form">
                    {{ csrf_field() }}
                    <hr>
                    <p> {{ $terms_and_conditions ?? '' }}</p>`

                    <input type="hidden" id="user_id" name="user_id" value="{{ $patient->id }}">
                    <div class="p-2 bg-light d-flex justify-content-between align-items-center">

                        <div id="invalid-input-alert"></div>
                        <div id="success-alert"></div>

                        <div>
                            <div class="checkbox checkbox-blue mb-3 text-sm-left">
                                {{--                                <input id="checkbox6a" type="checkbox">--}}
                                <input class="form-check-input" type="checkbox" name="is_accepted" value="1"
                                       id="checkbox" data-id="{{$patient->is_accepted == 1 ? 'checked' : '' }} ">
                                {{--                                <input type="checkbox" id="checkbox6a" name="is_accepted" {{ $patient->is_accepted == 1 ? 'checked': '' }}/>--}}
                                <label for="checkbox6a">
                                    Accept Terms and Conditions
                                </label>
                                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                                {{--                        <button type="button" class="btn btn-sm btn-primary" >Save</button>--}}
                            </div>

                        </div>
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-sm btn-primary" id="save_terms">Save</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
