<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMenuCategoryRequest;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Models\Categories;
use App\Models\Menu;
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
	// store Categories
	public function storemMenu(AddMenuCategoryRequest $request)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistCategorySave($requestData);
        alert()->success('SuccessAlert', 'Record Created Successfully');
		activity()->log('Category Created');
        return response()->json(['message' => 'success'], 200);
    }
	
	// Categories update
	public function MenuUpdate(AddMenuCategoryRequest $request, Categories $category)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistCategoryUpdate($requestData, $category);
        alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Category updated');
        return response()->json(['message' => 'success'], 200);
    }
	public function activateMenu(Categories $category): RedirectResponse
    {
        $this->RestaurantService->activeCategory($category); 
        Alert::toast('Record Status changed Successfully ', 'success');
        activity()->log('Category status changed');
        return back();
    }
	// delete category
    public function destroyMenu(Categories $category)
    {
        //$category->delete();
		//return $category;
		$this->RestaurantService->destroyCategory($category); 
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Category deleted');
        return redirect()->back();
    }
}
