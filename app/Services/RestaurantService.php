<?php

namespace App\Services;
use Artisan;
use App\Models\Categories;
use App\Models\Menu;
use App\Models\Tables;
use App\Models\ServiceType;
use App\Models\OrdersServices;
use App\Models\EventsServices;
use App\Models\OrdersHistory;
use App\Models\OrdersProducts;
use App\Models\TableScans;
use App\Models\CloseRequests;
use App\Models\Orders;
use App\Models\MenuType;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;
use Stancl\Tenancy\Tenant;
use Illuminate\Http\Request;
use App\Events\NewRecordAdded;
use App\Events\PlaySoundAndRefreshWidget;

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
    }
	
	public function persistMenuUpdate($request, $menu)
    {
		// update menu
		$menu->name= $request['name'];
		$menu->description = $request['description'];
		$menu->ingredients= $request['ingredients'];
		$menu->category_id= $request['category_id'];
		$menu->menu_type= $request['menu_type'];
		$menu->calories= $request['calories'];
		$menu->price= $request['price'];
		$menu->update();
    }
	
	public function destroyMenu($menu)
    {
		$menu->delete();
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
			
			$serviceRecord->name = $request['name'];
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
	
	// Menu type
	public function persistMenuTypeSave($request)
    {
		// save MenuType
		DB::beginTransaction();

			$tableRecord = MenuType::create([
                'name' => $request['name'],
                'sequence' => !empty($request['sequence']) ? $request['sequence'] : 0,
                'status' => 1,
            ]);
						
		DB::commit();
    }
	
	public function persistMenuTypeUpdate($request, $id)
    {
		// update MenuType
		DB::beginTransaction();

			$serviceRecord = MenuType::find($id->id);
			
			$serviceRecord->name = $request['name'];
			$serviceRecord->sequence = !empty($request['sequence']) ? $request['sequence'] : 0;
			$serviceRecord->update();
						
		DB::commit();
    }
	
	public function destroyMenuType($menuType)
    {

        try {
			
			DB::beginTransaction();
			
				$menuType->delete();

            DB::commit();

        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    /**
     * @param $patient
     * @return void
     */
    public function activeMenuType($menuType)
    {
        $menuType['status'] == 1 ? $status = 0 : $status = 1;
        $menuType['status'] = $status;
        $menuType->update();
    }
	
	// request for service
	public function requestService($table, $service)
    {
       
		// get table last scanned
		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();

		if (!empty($scanned->status) &&  $scanned->status === 1)
		{
			// save request
			//DB::beginTransaction();
				
				$comment = "$service->name requested on table: $table->name";
				$action = "$service->name Service Requesed" ;
				$request = OrdersServices::create([
					'service_id' => $service->id,
					'comment' => $comment,
					'table_id' => $table->id,
					'scan_id' => $scanned->id,
					'status' => 1,
				]);
				///'action', 'comment', 'table_id', 'order_id'
				$history = OrdersHistory::create([
					'action' => $action,
					'table_id' => $table->id,
				]);
				// save services requestService
				$EventsServices = EventsServices::create([
						'scan_id' => $scanned->id,
						'table_id' => $table->id,
						'service_type' => 1,
						'waiter' => $table->employee_id,
						'requested_time' => time(),
						'service' => "$service->name",
						'item_id' => $request->id,
						'status' => 1,
					]);
							
			return $EventsServices;
		}
		
    }
	// close table
	public function closeTable($table)
    {
		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();

        // add code to close all open orders and service request
		// close all order
		$orders = Orders::getOderByTable($table->id, $scanned->id);
		foreach ($orders as $order)
		{
			$order->status = 2;
			$order->update();
			// update order products.
			foreach ($order->products as $product)
			{
				$product->status = 2;
				$product->update();
			}
		}
		// close all service requests
		DB::table('events_services')
            ->where('table_id', $table->id)
            ->where('scan_id', $scanned->id)
            ->update(['status' => 2, 'completed_time' => time()]); 
			
		// close all normal services
		DB::table('orders_services')
            ->where('table_id', $table->id)
            ->where('scan_id', $scanned->id)
            ->update(['status' => 2]);
			
		// close table
		$scanned->status = 2;
        $scanned->closed_time = time();
        $scanned->update();
    }
	// close table
	public function closeService($service)
    {
        $service->status = 2;
        $service->completed_time = time();
        $service->update();  
		// close event table
		if (!empty($service->item_id))
		{
			$request = OrdersServices::where('id', $service->item_id)->first();
			$request->status = 2;
			$request->update();
		}
    }
	// close table
	public function closeRequest($close)
    {
        $close->status = 2;
		$close->completed_time = time();
        $close->update();
		
		// close event table
		if (!empty($close->item_id))
		{
			$request = CloseRequests::where('id', $close->item_id)->first();
			$request->status = 2;
			$request->update();
		}
		// add code to close all open orders and service request
		// close all order
		$orders = Orders::getOderByTable($close->table_id, $close->scan_id);
		foreach ($orders as $order)
		{
			$order->status = 2;
			$order->update();
			// update order products.
			foreach ($order->products as $product)
			{
				$product->status = 2;
				$product->update();
			}
		}
		// close all service requests
		DB::table('events_services')
            ->where('table_id', $close->table_id)
            ->where('scan_id', $close->scan_id)
            ->update(['status' => 2, 'completed_time' => time()]); 
			
		// close all normal services
		DB::table('orders_services')
            ->where('table_id', $close->table_id)
            ->where('scan_id', $close->scan_id)
            ->update(['status' => 2]);
		
		// close table 
		$scanned = TableScans::where('id', $close->scan_id)->first();
        $scanned->status = 2;
        $scanned->closed_time = time();
        $scanned->update();
    }
	// close table
	public function closeDeniedRequest($close)
    {
        $close->status = 2;
		$close->completed_time = time();
        $close->update();

		// close event table
		if (!empty($close->item_id))
		{
			$request = CloseRequests::where('id', $close->item_id)->first();
			$request->status = 2;
			$request->update();
		}
    }
	// close table
	public function closeOrders($order)
    {
        $order->status = 2;
		$order->completed_time = time();
        $order->update();
    }
	// delete order
	public function deleteOrders($order)
    {
		
		//get order model
		$orders = Orders::where('id',$order->item_id);
		//get all order products
		$OrdersProducts = OrdersProducts::where('order_id',$order->item_id);
		foreach($OrdersProducts as $product) {
			$product->delete();
		}
		// delete order
		$orders->delete();
		// delete event service
		$order->delete();
    }
	// get user ip address
	/*public function getUserIpAddress(Request $request)
    {
        $ipAddress = $request->ip();
        if (empty($ipAddress)) {
            $ipAddress = $request->server('REMOTE_ADDR');
        }
        return $ipAddress;
    }*/
}