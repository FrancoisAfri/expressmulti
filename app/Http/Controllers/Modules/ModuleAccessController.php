<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\HRPerson;
use App\Models\module_access;
use App\Models\modules;
use App\Models\User;
use App\Services\Modules\ModuleService;
use App\Traits\BreadCrumpTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleAccessController extends Controller
{

    use BreadCrumpTrait;

    /**
     * @var ModuleService
     */
    private $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'users-access',
            'Settings',
            'Users Access'
        );

        $data['modules'] = modules::getAllModulesOrderedByName();
        $data['moduleAccess'] =  module_access::getAllModuleAccess();
        return view('security.user-access.index')->with($data);
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $this->moduleService->persistUserAccessRights($request);
        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show($id, ModuleService $moduleService)
    {

        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'users-access',
            'Settings',
            'Users Access'
        );

        $data['module'] = modules::getModuleByUuid($id);
        $data['users'] = $moduleService->getAllUsersByModuleAccess();

        return view('security.user-access.assign_access')->with($data);
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
     * @param Request $request
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
