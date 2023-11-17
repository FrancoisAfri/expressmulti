<?php

namespace App\Services;
use Artisan; 
//use App\Services\RestaurantServiceInterface;
use App\Models\Categories;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;
use Stancl\Tenancy\Tenant;
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
}