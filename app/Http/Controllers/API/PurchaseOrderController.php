<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\AdminAuth;
use App\Models\API\PurchaseOrder;
use App\Models\Admin\Settings;
use App\Libraries\FileSystem;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends BaseController
{
	function index(Request $request)
	{
		$userId = AdminAuth::getLoginId();

		// $where['purchase_orders.user_id'] = $userId;
		$where = [];
		if($request->get('search'))
        {
            $search = $request->get('search');
            $search = '%' . $search . '%';
            $where['purchase_orders.title LIKE ? or purchase_orders.phone LIKE ?'] = [$search, $search];
        }

		$listing = PurchaseOrder::getApiListing($request, $where);
		return Response()->json([
	    	'status' => true,
	    	'listing' => $listing->items(),
	    ]);
	}

	public function createPurchaseOrder(Request $request)
	{
	    // VALIDATION
	    $validator = Validator::make($request->all(), [
	        'supplier_name'  => 'required|string|max:255',
	        'supplier_email' => 'required|email|max:255',
	        'supplier_phone' => 'required|regex:/^[0-9]{10}$/',
	        'order_date' => 'required|date',
	        'expected_delivery_date' => 'required|date|after_or_equal:order_date',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => $validator->errors()->first()
	        ], 422);
	    }

	    $adminId = AdminAuth::getLoginId();

	    // CREATE PURCHASE ORDER
	    $purchaseOrder = PurchaseOrder::create([
	        'supplier_name'            => $request->supplier_name,
	        'supplier_email'           => $request->supplier_email,
	        'supplier_phone'           => $request->supplier_phone,
	        'order_date'               => $request->order_date,
	        'status'				   => $request->status,
	        'expected_delivery_date'   => $request->expected_delivery_date,
	        'created_by'               => $adminId,
	    ]);

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order created successfully',
	        'data' => $purchaseOrder
	    ]);
	}


	public function updatePurchaseOrder(Request $request, $id)
	{
	    $validator = Validator::make($request->all(), [
	        'supplier_name'  => 'required|string|max:255',
	        'supplier_email' => 'required|email|max:255',
	        'supplier_phone' => 'required|regex:/^[0-9]{10}$/',
	        'order_date' => 'required|date',
	        'expected_delivery_date' => 'required|date|after_or_equal:order_date',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => $validator->errors()->first()
	        ], 422);
	    }

	    $purchaseOrder = PurchaseOrder::find($id);

	    if (!$purchaseOrder) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Purchase order not found'
	        ], 404);
	    }

	    $adminId = AdminAuth::getLoginId();
	    $purchaseOrder->supplier_name          = $request->supplier_name;
	    $purchaseOrder->supplier_email 		   = $request->supplier_email;
	    $purchaseOrder->order_date             = $request->order_date;
	    $purchaseOrder->status                 = $request->status;
	    $purchaseOrder->expected_delivery_date = $request->expected_delivery_date;
	    $purchaseOrder->created_by             = $adminId;
	    $purchaseOrder->save();

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order updated successfully',
	        'data' => $purchaseOrder
	    ]);
	}

	public function viewPurchaseOrder(Request $request, $id)
	{
	    $purchaseOrder = PurchaseOrder::find($id);

	    if (!$purchaseOrder) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Purchase order not found',
	        ], 404);
	    }

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order details fetched successfully',
	        'data' => $purchaseOrder
	    ]);
	}

	public function deletePurchaseOrder(Request $request, $id)
	{
	    $purchaseOrder = PurchaseOrder::find($id);

	    if (!$purchaseOrder) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Purchase order not found',
	        ], 404);
	    }

	    // Delete purchaseOrder
	    $purchaseOrder->delete();

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order deleted successfully',
	    ]);
	}

}