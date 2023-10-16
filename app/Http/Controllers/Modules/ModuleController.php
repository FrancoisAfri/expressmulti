<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\modules;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ModuleRequest;
use App\Services\Modules\ModuleService;
use Illuminate\Http\Response;
use RealRashid\SweetAlert\Facades\Alert;

class ModuleController extends Controller
{
    use BreadCrumpTrait;

    private $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;

    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        /**
         * breadcrumb trait
         */
        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'module',
            'Settings',
            'Modules'
        );

        $data['modules'] = modules::getAllModules();
        return view('security.modules.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator($request->all(), [
            'name' => 'required',
            'code_name' => 'required',
            'path' => 'required',
            'font_awesome' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->moduleService->createModule($request);
        alert()->success('SuccessAlert', 'Record Created Successfully');
        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    public function update(Request $request, $module)
    {
        $validator = validator($request->all(), [
            'name' => 'required',
            'code_name' => 'required',
            'path' => 'required',
            'font_awesome' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->moduleService->editModule($request, $module);
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param modules $mod
     * @return RedirectResponse
     */
    public function activateModule(modules $mod): RedirectResponse
    {
        $this->moduleService->ActivateModule($mod);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }
}
