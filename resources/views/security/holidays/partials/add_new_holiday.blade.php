<div class="modal fade" id="add-new-holiday-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-holiday-form">
                {{ csrf_field() }}

                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Add New Holiday</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="modal-body p-4">
                        <div class="form-group">
                            <label for="name"> Name</label>
                            <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Module name" required>
                        </div>

                        <div class="form-group">
                            <label for="name">  Day</label>
                            <input type="text" id="humanfd-datepicker" name="day"
                                   class="form-control" placeholder="October 9, 2018">
                        </div>

                        <div class="form-group">
                            <label for="name"> Countries</label>
                            <select id="country_id" name="country_id"
                                    class="form-control" required="">
                                <option value="0">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" >{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close
                        </button>
                        <button type="button" id="add-holiday" class="btn btn-success waves-effect waves-light">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


