<?php
/**
 * Products Class
 *
 * @package    ProductsController
 
 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\ProductsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\AdminAuth;
use App\Models\API\WareHouse;
use App\Models\Admin\Settings;
use App\Libraries\FileSystem;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class WareHouseController extends BaseController
{
	function index(Request $request)
	{
		$userId = AdminAuth::getLoginId();

		$where['ware_houses.user_id'] = $userId;
		if($request->get('search'))
        {
            $search = $request->get('search');
            $search = '%' . $search . '%';
            $where['ware_houses.title LIKE ? or ware_houses.phone LIKE ?'] = [$search, $search];
        }

		$listing = WareHouse::getApiListing($request, $where);
		return Response()->json([
	    	'status' => true,
	    	'listing' => $listing->items(),
	    ]);
	}

	public function createSupplier(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'title' => 'required|string|max:255',
	        'manager_name' => 'required|string|max:255',
	        'phone' => 'required',
	        'email' => 'required|email',
	        'address' => 'required|string',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => $validator->errors()->first()
	        ], 422);
	    }

	    $adminId = AdminAuth::getLoginId(); // ← get admin id

	    $supplier = WareHouse::create([
	        'title'        => $request->title,
	        'manager_name' => $request->manager_name,
	        'phone'        => $request->phone,
	        'email'        => $request->email,
	        'address'      => $request->address,
	        'created_by'   => $adminId,
	    ]);

	    return response()->json([
	        'status' => true,
	        'message' => 'Supplier created successfully',
	        'data' => $supplier
	    ]);
	}

	public function updateSupplier(Request $request, $id)
	{
	    $validator = Validator::make($request->all(), [
	        'title' => 'required|string|max:255',
	        'manager_name' => 'required|string|max:255',
	        'phone' => 'required',
	        'email' => 'required|email',
	        'address' => 'required|string',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => $validator->errors()->first()
	        ], 422);
	    }

	    $warehouse = WareHouse::find($id);

	    if (!$warehouse) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Ware house not found'
	        ], 404);
	    }

	    $adminId = AdminAuth::getLoginId();

	    // Manual update (No fillable needed)
	    $warehouse->title        = $request->title;
	    $warehouse->manager_name = $request->manager_name;
	    $warehouse->phone        = $request->phone;
	    $warehouse->email        = $request->email;
	    $warehouse->address      = $request->address;
	    $warehouse->created_by   = $adminId;

	    $warehouse->save();

	    return response()->json([
	        'status' => true,
	        'message' => 'Ware house updated successfully',
	        'data' => $warehouse
	    ]);
	}

}