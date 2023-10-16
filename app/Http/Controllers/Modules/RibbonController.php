<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRibbonRequest;
use App\Models\module_ribbons;
use App\Models\modules;
use App\Services\Modules\ModuleService;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RibbonController extends Controller
{

    private $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;

    }

    use BreadCrumpTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param ModuleRibbonRequest $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {



        $validator = validator($request->all(), [
            'ribbon_name' => 'required',
            'description' => 'required',
            'ribbon_path' => 'required',
            'font_awesome' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->moduleService->createRibbon($request);
        Alert::toast('Record Created Successfully ', 'success');
        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

       $module =  modules::getModuleByUuid($id);

        if ($module->active == 1) {
            $module->load('moduleRibbon');

            $data = $this->breadcrumb(
                'Settings Modules',
                'Admin page for security related settings',
                'users-access',
                'Settings',
                'Module Ribbon'
            );

            $data['modules'] = $module;
            $data['moduleRibbon'] = module_ribbons::getAllModuleRibbons($module->id);
            $data['userRights'] =  $userRights = module_ribbons::userRights();

            return view('security.ribbons.index')->with($data);
        }
        else return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param $ribbon
     * @return JsonResponse
     */
    public function update(Request $request,  $ribbon ): JsonResponse
    {
        $Ribbon = module_ribbons::find($ribbon);
        $Ribbon->update($request->all());
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param module_ribbons $ribbon
     * @return RedirectResponse
     */
    public function activateRibbon(module_ribbons $ribbon){
        $this->moduleService->ActivateRibbon($ribbon);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }
}
