<?php

namespace App\Services;
use Artisan; 
//use App\Services\RestaurantServiceInterface;
use App\Models\Categories;
use App\Models\Menu;
use App\Models\Tables;
use App\Models\ServiceType;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;
use Stancl\Tenancy\Tenant;
use Illuminate\Http\Request;

class RestaurantService //implements RestaurantServiceInterface
{
	use FileUpload;
	public function __construct()
	{
       
	}
  
	public function persistCategorySave($request)
    {
		// save category
		DB::beginTransaction();

			Categories::create([
                'name' => $request['name'],
                'status' => 1,
            ]);

		DB::commit();
    }
	
	public function persistCategoryUpdate($request, $id)
    {
		// update category
		try {
			
			DB::beginTransaction();

				$categoryRecord = Categories::find($id);
				$categoryRecord->update($request->all());

			DB::commit();
		}
		catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }
	
	public function destroyCategory($category)
    {

        try {
			//die('do you hhbhbs');
			DB::beginTransaction();
			
				//$category = Categories::find($id);
				$category->delete();

            DB::commit();

        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    /**
     * @param $patient
     * @return void
     */
    public function activeCategory($category)
    {
		
        $category['status'] == 1 ? $status = 0 : $status = 1;
        $category['status'] = $status;
        $category->update();
    }
	// menu section
	public function persistMenuSave($request)
    {
		// save category
		DB::beginTransaction();

			$menuRecord = Menu::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'ingredients' => $request['ingredients'],
                'category_id' => $request['category_id'],
                'menu_type' => $request['menu_type'],
                'calories' => $request['calories'],
                'price' => $request['price'],
                'status' => 1,
            ]);
			
			// save image
			$this->uploadImage($request, 'image', 'Image', $menuRecord);
			// save video
			$this->uploadVideo($request, 'video', 'Video', $menuRecord);
			
		DB::commit();
    }
	
	public function persistMenuUpdate($request, $menu)
    {
		// update menu
		DB::beginTransaction();

			//$menuRecord = Menu::find($id->id);
			//$menu->update($request->all());

			$menu->name= $request['name'];
			$menu->description = $request['description'];
			$menu->ingredients= $request['ingredients'];
			$menu->category_id= $request['category_id'];
			$menu->menu_type= $request['menu_type'];
			$menu->calories= $request['calories'];
			$menu->price= $request['price'];
			$menu->update();
			// save image
			if (!empty($request['image'])) 
				$this->uploadImage($request, 'image', 'Image', $menu);
			// save video
			if (!empty($request['video']))
				$this->uploadVideo($request, 'video', 'Video', $menu);
			
		DB::commit();
    }
	
	public function destroyMenu($menu)
    {

        try {
			
			DB::beginTransaction();
			
				$menu->delete();

            DB::commit();

        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    /**
     * @param $patient
     * @return void
     */
    public function activeMenu($menu)
    {
		
        $menu['status'] == 1 ? $status = 0 : $status = 1;
        $menu['status'] = $status;
        $menu->update();
    }
	
	// table section
	public function persistTableSave($request)
    {
		// save category
		DB::beginTransaction();

			$tableRecord = Tables::create([
                'name' => $request['name'],
                'number_customer' => $request['number_customer'],
                'employee_id' => $request['employee_id'],
                'status' => 1,
            ]);
						
		DB::commit();
    }
	
	public function persistTableUpdate($request, $id)
    {
		// update Tables
		DB::beginTransaction();

			$tableRecord = Tables::find($id->id);
			
			$tableRecord->name= $request['name'];
			$tableRecord->number_customer = $request['number_customer'];
			$tableRecord->employee_id= $request['employee_id'];
			$tableRecord->update();
						
		DB::commit();
    }
	
	public function destroyTable($table)
    {

        try {
			
			DB::beginTransaction();
			
				$table->delete();

            DB::commit();

        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    /**
     * @param $patient
     * @return void
     */
    public function activeTable($table)
    {
        $table['status'] == 1 ? $status = 0 : $status = 1;
        $table['status'] = $status;
        $table->update();
    }
	
	// service section
	public function persistServiceSave($request)
    {
		// save ServiceType
		DB::beginTransaction();

			$tableRecord = ServiceType::create([
                'name' => $request['name'],
                'status' => 1,
            ]);
						
		DB::commit();
    }
	
	public function persistServiceUpdate($request, $id)
    {
		// update ServiceType
		DB::beginTransaction();

			$serviceRecord = ServiceType::find($id->id);
			
			$serviceRecord->name= $request['name'];
			$serviceRecord->update();
						
		DB::commit();
    }
	
	public function destroyService($service)
    {

        try {
			
			DB::beginTransaction();
			
				$service->delete();

            DB::commit();

        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    /**
     * @param $patient
     * @return void
     */
    public function activeService($service)
    {
        $service['status'] == 1 ? $status = 0 : $status = 1;
        $service['status'] = $status;
        $service->update();
    }
}