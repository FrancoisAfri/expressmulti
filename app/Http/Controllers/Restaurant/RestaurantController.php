<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMenuCategoryRequest;
use App\Http\Requests\AddMenuRequest;
use App\Http\Requests\AddTableRequest;
use App\Http\Requests\AssingnEmployeeRequest;
use App\Http\Requests\AddServiceRequest;
use App\Http\Requests\MenuTypeRequest;
use App\Http\Requests\RestaurantSettingsRequest;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Models\Categories;
use App\Models\modules;
use App\Models\EventsServices;
use App\Models\RestaurantSetup;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\Tables;
use App\Models\ServiceType;
use App\Models\HRPerson;
use App\Services\RestaurantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RestaurantController extends Controller
{
	use BreadCrumpTrait, CompanyIdentityTrait;
	/**
     * @var RestaurantService
     */
    private $restaurantService;
   // private $menuType;
	
	public function __construct(RestaurantService $restaurantService)
    {
        $this->RestaurantService = $restaurantService;
        //$this->menuType = array(1 => 'Main Course', 2 => 'Starters', 3 => 'Desert');
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
	 // check new request for main dashboard
    public function create()
    {
        $value = ['table_updated' => 0];
		$tableCreated = DB::table('events_sessions_check')
            ->select('session_check')->where('session_check',1 )->first();
		if (!empty($tableCreated->session_check) && $tableCreated->session_check == 1)
		{
			$value = ['table_updated' => true];
			DB::table('events_sessions_check')->truncate();
		}
        return response()->json($value);
    }  
	// check new request for terminal
	public function checkTerminal()
    {
        $value = ['table_updated' => 0];
		$tableCreated = DB::table('events_check_terminal')
            ->select('session_check')->where('session_check',1 )->first();
		if (!empty($tableCreated->session_check) && $tableCreated->session_check == 1)
		{
			$value = ['table_updated' => true];
			DB::table('events_check_terminal')->truncate();
		}
        return response()->json($value);
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
            '/restaurant/category',
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
		// assign variable
		$name = !empty($request['name']) ? $request['name'] : '';
		// save  menu
		$category = Categories::create([
                'name' => $name,
                'status' => 1,
            ]);
		//Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('Images/categories/', $fileName);
                //Update file name in the database
                $category->image = $fileName;
                $category->update();
            }
        }
        //$this->RestaurantService->persistCategorySave($requestData);
        alert()->success('SuccessAlert', 'Category Created Successfully');
		activity()->log('Category Created');
        return response()->json(['message' => 'success'], 200);
    }
	// edit category
	public function categoryEdit(Categories $category)
    {
		 $data = $this->breadcrumb(
            'Restaurant',
            'Categories Management Page',
            '/category',
            'Categories Management',
            'Categories'
        );
		
	
		$data['category'] = $category;
		 
        return view('restaurant.category_edit')->with($data);
    }
	// Categories update
	public function categoryUpdate(AddMenuCategoryRequest $request, Categories $category)
    {
        $requestData = $request->validationData();
		// assign variable
		$name = !empty($request['name']) ? $request['name'] : '';
		
		// update  menu
		$category->name= $name;
		$category->update();
		
		//Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('Images/categories', $fileName);
                //Update file name in the database
                $category->image = $fileName;
                $category->update();
            }
        }
		alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Category updated');
		return redirect()->route('categories.view');
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
            '/restaurant/menu',
            'Menus Management',
            'Menu'
        );
		$categories = Categories::getCategories();
		$menus = Menu::getAllMenus();
		$menusTypes = MenuType::getMenuTypes();
		//return $menus;
		$data['categories'] = $categories;
		$data['menus'] = $menus;
		$data['menusTypes'] = $menusTypes;
		 
        return view('restaurant.menus')->with($data);
    }
	// edit menu
	public function menuEdit(Menu $menu)
    {
		 $data = $this->breadcrumb(
            'Restaurant',
            'Menus Management Page',
            '/restaurant/menu',
            'Menus Management',
            'Menu'
        );
		$categories = Categories::getCategories();
		$menus = Menu::getAllMenus();
		$menusTypes = MenuType::getMenuTypes();
		$data['categories'] = $categories;
		$data['menu'] = $menu;
		$data['menusTypes'] = $menusTypes;
		 
        return view('restaurant.menu_edit')->with($data);
    }
	// store Menu
	public function storeMenu(AddMenuRequest $request)
    {
        $requestData = $request->validationData();
		// assign variable
		$name = !empty($request['name']) ? $request['name'] : '';
		$description = !empty($request['description']) ? $request['description'] : '';
		$ingredients = !empty($request['ingredients']) ? $request['ingredients'] : '';
		$category_id = !empty($request['category_id']) ? $request['category_id'] : 0;
		$menu_type = !empty($request['menu_type']) ? $request['menu_type'] : 0;
		$calories = !empty($request['calories']) ? $request['calories'] : 0;
		$price = !empty($request['price']) ? $request['price'] : 0;
		$sequence = !empty($request['sequence']) ? $request['sequence'] : 0;
		$video = !empty($request['video']) ? $request['video'] : '';
		// save  menu
		$menuRecord = new Menu();
        $menuRecord->name = $name;
        $menuRecord->description = $description;
        $menuRecord->ingredients = $ingredients;
        $menuRecord->category_id = $category_id;
        $menuRecord->menu_type = $menu_type;
        $menuRecord->calories = $calories;
        $menuRecord->price = $price;
        $menuRecord->status = 1;
        $menuRecord->sequence = $sequence;
        $menuRecord->video = $video;
        $menuRecord->save();
		// save video
		/*if ($request->hasFile('video')) {
			$video_name = $request->file('video');
			$File_ex = $video_name->extension();
			$filePath = 'com_vid' . ' ' . str_random(16) . '.' . $File_ex;
			$size = $request->file('video')->getSize();

			$isFileUploaded = Storage::disk('public')->put('videos/menus/' . $filePath,
				file_get_contents($request->file('video')));

			// File URL to access the video in frontend
			$url = Storage::disk('public')->url($filePath);

			// save vidoe details into the database
			$menuRecord->video = $filePath;
			$menuRecord->update();
		}*/
		//Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('images/menus', $fileName);
                //Update file name in the database
                $menuRecord->image = $fileName;
                $menuRecord->update();
            }
        }

        alert()->success('SuccessAlert', 'Menu Created Successfully');
		activity()->log('Menu Created');
        return response()->json(['message' => 'success'], 200);
    }
	
	// Menu update
	public function menuUpdate(AddMenuRequest $request, Menu $menu)
    {
		//return $menu;
		$requestData = $request->validationData();
		// assign variable
		$name = !empty($request['name']) ? $request['name'] : '';
		$description = !empty($request['description']) ? $request['description'] : '';
		$ingredients = !empty($request['ingredients']) ? $request['ingredients'] : '';
		$category_id = !empty($request['category_id']) ? $request['category_id'] : 0;
		$menu_type = !empty($request['menu_type']) ? $request['menu_type'] : 0;
		$calories = !empty($request['calories']) ? $request['calories'] : 0;
		$price = !empty($request['price']) ? $request['price'] : 0;
		$sequence = !empty($request['sequence']) ? $request['sequence'] : 0;
		$video = !empty($request['video']) ? $request['video'] : '';
		// update  menu
		$menu->name= $name;
		$menu->description = $description;
		$menu->ingredients= $ingredients;
		$menu->category_id= $category_id;
		$menu->menu_type= $menu_type;
		$menu->calories= $calories;
		$menu->price= $price;
		$menu->sequence = $sequence;
		$menu->video = $video;
		$menu->update();
		
		// save video
		/*if ($request->hasFile('video')) {
			$video_name = $request->file('video');
			$File_ex = $video_name->extension();
			$filePath = 'com_vid' . ' ' . str_random(16) . '.' . $File_ex;
			$size = $request->file('video')->getSize();

			$isFileUploaded = Storage::disk('public')->put('Video/' . $filePath,
				file_get_contents($request->file('videos/menus')));

			// File URL to access the video in frontend
			$url = Storage::disk('public')->url($filePath);

			// save vidoe details into the database
			$menu->video = $filePath;
			$menu->update();
		}*/
		//Upload Image picture
        if ($request->hasFile('image')) {
			
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('images/menus', $fileName);
                //Update file name in the database
				//die('ssssssseeeeeeeimage');
                $menu->image = $fileName;
                $menu->update();
            }
        }

        //$this->RestaurantService->persistMenuUpdate($requestData, $menu);
        alert()->success('SuccessAlert', 'Menu Updated Successfully');
		activity()->log('Menu updated');
        //return response()->json(['message' => 'success'], 200);
		return redirect()->route('menus.view');
    }
	public function activateMenu(Menu $menu): RedirectResponse
    {
        $this->RestaurantService->activeMenu($menu); 
        Alert::toast('Record Status changed Successfully', 'success');
        activity()->log('Menu status changed');
        return back();
    }
	// delete Menu
    public function destroyMenu(Menu $menu)
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
            '/restaurant/seating_plan',
            'Tables Management',
            'Tables'
        );
		$url = config('app.url') . '/restaurant/scan' ;
		$data['users'] = HRPerson::getAllUsers();
		$data['tables'] = Tables::getTables();
		$data['current_url'] = $url;
		 
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
            '/restaurant/service_type',
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

	// assign employee
	// store Tables
	public function assignEmployee(AssingnEmployeeRequest $request, Tables $table)
    {
        $requestData = $request->validationData();
		// assign employee
		$table->employee_id= $request['employee_id'];
		$table->update();
		// send alert
        alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Table Updated');
        return response()->json(['message' => 'success'], 200);
    }
	/// menu type
	public function menuType()
    {
		 $data = $this->breadcrumb(
            'Restaurant',
            'Menu Type Page',
            '/restaurant/menu-type',
            'Menu Type Management',
            'Menu Type'
        );
		
		$data['menuTypes'] = MenuType::getMenuTypes();
		 
        return view('restaurant.menu_type')->with($data);
    }
	// store ServiceType
	public function storeMenuType(MenuTypeRequest $request)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistMenuTypeSave($request);
        alert()->success('SuccessAlert', 'Record Created Successfully');
		activity()->log('Menu Type Created');
        return response()->json(['message' => 'success'], 200);
    }
	
	// MenuType update
	public function menuTypeUpdate(MenuTypeRequest $request, MenuType $type)
    {
        $requestData = $request->validationData();
        $this->RestaurantService->persistMenuTypeUpdate($requestData, $type);
        alert()->success('SuccessAlert', 'Record Updated Successfully');
		activity()->log('Menu Type updated');
        return response()->json(['message' => 'success'], 200);
    }
	public function activateMenuType(MenuType $type): RedirectResponse
    {
		//return $type;
        $this->RestaurantService->activeMenuType($type); 
        Alert::toast('Record Status changed Successfully', 'success');
        activity()->log('Menu Type status changed');
        return back();
    }
	// delete MenuType
    public function destroyMenuType(MenuType $type)
    {
		$this->RestaurantService->destroyMenuType($type); 
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Menu Type deleted');
        return redirect()->back();
    }
	// setup
    public function setup()
    {
		$setup = RestaurantSetup::where('id',1)->first();
		//return $setup ;
		$data = $this->breadcrumb(
            'Restaurant',
            'Setup Page',
            '/restaurant/setup',
            'Setup Management',
            'Setup'
        );
		
		$data['setup'] = RestaurantSetup::where('id',1)->first();
		 
        return view('restaurant.setup')->with($data);
    }
	// store setup
	// store ServiceType
	public function storeSetup(RestaurantSettingsRequest $request)
    {
		
        $requestData = $request->validationData();
		$setupID = !empty($request['setup_id']) ? $request['setup_id'] : 0;
		$setup = RestaurantSetup::where('id',$setupID)->first();
		if (!empty($setup->id))
		{ 
			$setup->colour_one = $request['colour_one']; 
			$setup->colour_two = $request['colour_two'];
			$setup->colour_three = $request['colour_three'];
			$setup->mins_one = $request['mins_one'];
			$setup->mins_two = $request['mins_two'];
			$setup->mins_three = $request['mins_three'];
			$setup->update();
			
			activity()->log('Setup Updated');
		}
		else
		{
			RestaurantSetup::create([
                'colour_one' => $request['colour_one'],
                'colour_two' => $request['colour_two'],
                'colour_three' => $request['colour_three'],
                'mins_one' => $request['mins_one'],
                'mins_two' => $request['mins_two'],
                'mins_three' => $request['mins_three'],
            ]);
			activity()->log('Setup Created');
		}
        alert()->success('SuccessAlert', 'Record Created Successfully');
		return back();
    }
	// donwload code
	public function printQrCode(Tables $table)
    {
		if ($table->status == 1)
		{
			// Generate QR code content with the table name
			$url = config('app.url') . '/restaurant/scan/' . $table->id;
			$qrCodeContent = "Table: $table->name\nURL: $url";
			$tableName = $table->name;
			// Generate QR code
			//$url = config('app.url') . '/restaurant/seating_plan/' . $table->id;
			$qrCode = QrCode::size(200)->generate($url);

			// Set headers for download
			/*$headers = [
				'Content-Type' => 'image/png',
				'Content-Disposition' => 'attachment; filename="qr_code.png"',
			];

			// Return response with QR code image for download
			return response($qrCode, 200, $headers);*/
			// Generate PDF
			activity()->log('Qr code donwloaded');
			$pdf = new Dompdf();
			$pdf->loadHtml("<img src='data:image/png;base64," . base64_encode($qrCode) . "' alt='QR Code'><br>Table Name: $tableName");
			$pdf->setPaper('A4');
			$pdf->render();

			// Download PDF
			return $pdf->stream("$tableName qr_code.pdf");
			// Save QR code image to storage
			

		}
		else 
		{
			Alert::toast('You can not download this qr code because this table is not active', 'warning');
			return redirect()->back();
		}
        
    }
	// showterminal
	public function showTerminal()
    {
		
		$services = EventsServices::getRequests();
		$setup = RestaurantSetup::where('id',1)->first();
		// get this year and month
		$year = date('Y');
		$month = date('m');
		$data['activeModules'] = modules::where('active', 1)->get();
        $data['normal'] = !empty($setup->colour_one) ? $setup->colour_one : '';
        $data['moderate'] = !empty($setup->colour_two) ? $setup->colour_two : '';
        $data['critical'] = !empty($setup->colour_three) ? $setup->colour_three : '';
        $data['normal_mins'] = !empty($setup->mins_one) ? $setup->mins_one : '';
        $data['moderate_mins'] = !empty($setup->mins_two) ? $setup->mins_two : '';
        $data['critical_mins'] = !empty($setup->mins_three) ? $setup->mins_three : '';
		$data['services'] = $services;
		$data['resquest_type'] = EventsServices::SERVICES_SELECT;
		$data['users'] = HRPerson::getAllUsers();
		$data['tables'] = Tables::getTablesScans();
		return view('restaurant.terminal')->with($data);
    }

	/*
	use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function downloadQRCode($tableId)
    {
        // Get the table name
        $tableName = Table::find($tableId)->name; // Assuming you have a Table model and a 'name' attribute

        // Generate QR code content with the table name
        $url = config('app.url') . '/restaurant/seating_plan/' . $tableId;
        $qrCodeContent = "Table: $tableName\nURL: $url";

        // Generate QR code
        $qrCode = QrCode::size(200)->generate($qrCodeContent);

        // Set headers for download
        $headers = [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr_code.png"',
        ];

        // Return response with QR code image for download
        return response($qrCode, 200, $headers);
    }
}
*/
/*
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeController extends Controller
{
    public function downloadQRCode($tableId)
    {
        // Get the table name
        $tableName = Table::find($tableId)->name; // Assuming you have a Table model and a 'name' attribute

        // Generate QR code content with the table name
        $url = config('app.url') . '/restaurant/seating_plan/' . $tableId;
        $qrCodeContent = "Table: $tableName\nURL: $url";

        // Generate QR code
        $qrCode = QrCode::size(200)->generate($qrCodeContent);

        // Save QR code image to storage
        $filename = 'qr_code_' . $tableId . '.png';
        Storage::put('public/qr_codes/' . $filename, $qrCode);

        // Generate URL for the stored QR code image
        $qrCodeUrl = Storage::url('public/qr_codes/' . $filename);

        // Return response with QR code image for download
        return response()->download(storage_path('app/public/qr_codes/' . $filename), $filename);
    }
}


*/
}
