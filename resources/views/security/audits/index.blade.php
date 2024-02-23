@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

@section('content')

    @section('content_data')
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <h4 class="header-title mb-4">Modules</h4>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 data-table"
                           id="tickets-table">
                        <div class="row">
                            <div class="col-sm-2 pull-right">
                                <div class="form-group">
                                    <label for="user">USER</label>
                                    <input type="text" class="form-control" id="user"
                                           name="user" placeholder=" ">
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                            <div class="col-sm-2 pull-right">
                                <div class="form-group">
                                    <label for="gender"> Table </label>
                                    <select id="log_name" name="log_name" class="form-control">
                                        <option value="">...</option>
                                        @foreach(\App\Services\GlobalHelperService::getAvailableModels() as $role)
                                            <option value="{{ $role }}">{{ $role}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 float-sm-right">
                                <div class="form-group">
                                    <label for="heard"> LOG TYPE </label>
                                    <select id="marital_status" name="marital_status"
                                            class="form-control">
                                        <option value="">...</option>
                                        <option value="create">create</option>
                                        <option value="edit">edit</option>
                                        <option value="delete">delete</option>
                                        <option value="login">login</option>
                                        <option value="lockout">lockout</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 float-sm-right">
                                <div class="form-group">
                                    <label for="heard"> Date Range </label>
                                    <input type="text" id="range-datepicker" name="date_range" class="form-control">
                                </div>
                            </div>
                        </div>
                        <thead>
                        <tr>
                            <th></th>
                            <th>Log Name</th>
                            <th>Description</th>
                            <th>Table Affected</th>
                            <th>Causer</th>
							<th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div><!-- end col -->
        </div>
    @endsection
@stop
@section('page_script')

    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script
        src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script
        src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>


    <script type="text/javascript">



        $(function () {

            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('audits.index') }}",
                    data: function (d) {
                        d.log_name = $('#log_name').val(),
                            d.date_range = $('#range-datepicker').val(),
                            d.user = $('input[type="user"]').val()

                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'log_name', name: 'log_name'},
                    {data: 'description', name: 'description'},
                    {data: 'causer_type', name: 'causer_type'},
                    {data: 'firstname', name: 'firstname'},
                    {data: 'created_at', name: 'created_at'},

                ]
            });
            $('#log_name').change(function () {
                table.draw();
            });

            $('#user').change(function () {
                table.draw();
            });

            $('#range-datepicker').change(function () {
                table.draw();
            });

            window.alert = function() {};
        });
    </script>
@stop
