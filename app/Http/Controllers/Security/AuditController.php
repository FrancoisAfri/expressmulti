<?php

namespace App\Http\Controllers\Security;

use App\DataTables\ProvinceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\User;
use App\Services\GlobalHelperService;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use App\DataTables\AuditDataTable;
use Spatie\Activitylog\Models\Activity;
use function Symfony\Component\String\u;

class AuditController extends Controller
{
    use BreadCrumpTrait;

    public function index(Request $request)
    {



        $date_range = "2022-12-01 to 2022-12-26";
        $dates = explode("to", $date_range);


        $dates1 = $dates[0];
        $dates12 = $dates[1];

        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'audits',
            'Settings',
            'Security Audits'
        );


        if ($request->ajax()) {



            $data = Activity::select(
                'activity_log.*',
                'hr_people.first_name as firstname')
                ->leftJoin(
                    'hr_people',
                    'activity_log.causer_id',
                    '=', 'hr_people.id'
                );
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {

                    if ($request->get('log_name')) {
                        $instance->where('subject_type', $request->get('log_name'));
                    }

                    if (!empty($request->get('date_range'))) {
                        $dates = explode("to", $request->get('date_range'));
                        $startDate = $dates[0];
                        $endDate = $dates[1];
                        $instance->whereBetween('activity_log.created_at', [$startDate, $endDate]);
                    }

                    if (!empty($request->get('user'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('user');
                            $w->orWhere('causer_id', 'LIKE', "%$search%");
                        });
                    }

//                    if (!empty($request->get('search'))) {
//                        $instance->where(function($w) use($request){
//                            $search = $request->get('search');
//                            $w->orWhere('causer_id', 'LIKE', "%$search%")
//                                ->orWhere('log_name', 'LIKE', "%$search%");
//                        });
//                    }
                })
                ->addIndexColumn()
                ->make(true);
        }


        return view('security.audits.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
