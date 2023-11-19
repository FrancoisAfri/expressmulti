<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMenuCategoryRequest;
use App\Http\Requests\AddMenuRequest;
use App\Http\Requests\AddTableRequest;
use App\Http\Requests\AddServiceRequest;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Models\Categories;
use App\Models\Menu;
use App\Models\Tables;
use App\Models\ServiceType;
use App\Models\HRPerson;
use App\Services\RestaurantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
	use BreadCrumpTrait, CompanyIdentityTrait;
	/**
     * @var RestaurantService
     */
    private $restaurantService;
    private $menuType;
	
	public function __construct(RestaurantService $restaurantService)
    {
        $this->RestaurantService = $restaurantService;
        $this->menuType = array(1 => 'Main Course', 2 => 'Starters', 3 => 'Desert');
    }
	
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
		 $data = $this->breadcrumb(
            'Restaurant',
            'Categories Management Page',
            'categories_view',
            'Restaurant Management',
            'Menu Categories'
        );
		
		$data['categories'] = Categories::getCategories();
		 
        return view('restaurant.categories')->with($data);
    }
	// store Categories
	public function storeCategory(AddMenuCategoryRequest $request)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistCategorySave($requestData);
        alert()->success('SuccessAlert', 'Record Created Successfully');
		activity()->log('Category Created');
        return response()->json(['message' => 'success'], 200);
    }
	
	// Categories update
	public function categoryUpdate(AddMenuCategoryRequest $request, Categories $category)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistCategoryUpdate($requestData, $category);
        alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Category updated');
        return response()->json(['message' => 'success'], 200);
    }
	public function activateCategory(Categories $category): RedirectResponse
    {
        $this->RestaurantService->activeCategory($category); 
        Alert::toast('Record Status changed Successfully ', 'success');
        activity()->log('Category status changed');
        return back();
    }
	// delete category
    public function destroyCategory(Categories $category)
    {
        //$category->delete();
		//return $category;
		$this->RestaurantService->destroyCategory($category); 
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Category deleted');
        return redirect()->back();
    }
	// menu
	public function menus()
    {
		 $data = $this->breadcrumb(
            'Restaurant',
            'Menus Management Page',
            'menus_view',
            'Menus Management',
            'Menu'
        );
		
		$data['categories'] = Categories::getCategories();
		$data['menus'] = Menu::getMenus();
		$data['menusArray'] = $this->menuType;
		 
        return view('restaurant.menus')->with($data);
    }
	// store Menu
	public function storeMenu(AddMenuRequest $request)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistMenuSave($request);
        alert()->success('SuccessAlert', 'Record Created Successfully');
		activity()->log('Menu Created');
        return response()->json(['message' => 'success'], 200);
    }
	
	// Menu update
	public function menuUpdate(AddMenuRequest $request, Menu $menu)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistMenuUpdate($requestData, $menu);
        alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Menu updated');
        return response()->json(['message' => 'success'], 200);
    }
	public function activateMenu(Menu $menu): RedirectResponse
    {
        $this->RestaurantService->activeMenu($menu); 
        Alert::toast('Record Status changed Successfully', 'success');
        activity()->log('Menu status changed');
        return back();
    }
	// delete Menu
    public function destroyMenu(Categories $menu)
    {
        //$menu->delete();
		//return $menu;
		$this->RestaurantService->destroyMenu($menu); 
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Menu deleted');
        return redirect()->back();
    }
	// seating plan
	
	public function plans()
    {
		 $data = $this->breadcrumb(
            'Restaurant',
            'Tables Management Page',
            'tables_view',
            'Tables Management',
            'Tables'
        );
		
		$data['users'] = HRPerson::getAllUsers();
		$data['tables'] = Tables::getTables();
		 
        return view('restaurant.tables')->with($data);
    }
	// store Tables
	public function storePlan(AddTableRequest $request)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistTableSave($request);
        alert()->success('SuccessAlert', 'Record Created Successfully');
		activity()->log('Table Created');
        return response()->json(['message' => 'success'], 200);
    }
	
	// Tables update
	public function planUpdate(AddTableRequest $request, Tables $table)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistTableUpdate($requestData, $table);
        alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Table updated');
        return response()->json(['message' => 'success'], 200);
    }
	public function activatePlan(Tables $plan): RedirectResponse
    {
        $this->RestaurantService->activeTable($plan); 
        Alert::toast('Record Status changed Successfully', 'success');
        activity()->log('Table status changed');
        return back();
    }
	// delete Tables
    public function destroyPlan(Tables $plan)
    {
		$this->RestaurantService->destroyTable($plan); 
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Table deleted');
        return redirect()->back();
    }
	
	// services section
	// seating plan
	
	public function services()
    {
		 $data = $this->breadcrumb(
            'Restaurant',
            'Tables Services Type Page',
            'service_type_view',
            'Services Type Management',
            'Services Type'
        );
		
		$data['services'] = ServiceType::getServices();
		 
        return view('restaurant.services')->with($data);
    }
	// store ServiceType
	public function storeService(AddServiceRequest $request)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistServiceSave($request);
        alert()->success('SuccessAlert', 'Record Created Successfully');
		activity()->log('Services Created');
        return response()->json(['message' => 'success'], 200);
    }
	
	// ServiceType update
	public function serviceUpdate(AddServiceRequest $request, ServiceType $service)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistServiceUpdate($requestData, $service);
        alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Services updated');
        return response()->json(['message' => 'success'], 200);
    }
	public function activateService(ServiceType $service): RedirectResponse
    {
        $this->RestaurantService->activeService($service); 
        Alert::toast('Record Status changed Successfully', 'success');
        activity()->log('Services status changed');
        return back();
    }
	// delete ServiceType
    public function destroyService(ServiceType $service)
    {
		$this->RestaurantService->destroyService($service); 
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Services deleted');
        return redirect()->back();
    }
	// delete ServiceType
    public function generateQrCode(Tables $table)
    {
		
		die('Qr Code');
		
		$this->RestaurantService->destroyService($service); 
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Services deleted');
        return redirect()->back();
    }
}
