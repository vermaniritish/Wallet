<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\AdminAuth;
use App\Models\API\PurchaseOrderItem;
use App\Models\Admin\Settings;
use App\Libraries\FileSystem;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PurchaseOrderItemController extends BaseController
{
	function index(Request $request)
	{
		$userId = AdminAuth::getLoginId();

		// $where['purchase_order_items.user_id'] = $userId;
		$where = [];
		// if($request->get('search'))
        // {
        //     $search = $request->get('search');
        //     $search = '%' . $search . '%';
        //     $where['purchase_order_items.title LIKE ? or purchase_order_items.phone LIKE ?'] = [$search, $search];
        // }

		$listing = PurchaseOrderItem::getApiListing($request, $where);
		return Response()->json([
	    	'status' => true,
	    	'listing' => $listing->items(),
	    ]);
	}

	public function createPurchaseOrder(Request $request)
	{
	    // VALIDATION
	    $validator = Validator::make($request->all(), [
	        'product_variant_id'  => 'required',
	        'quantity' => 'required',
	        'unit_price' => 'required',
	        'total_price' => 'required'
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => $validator->errors()->first()
	        ], 422);
	    }

	    $adminId = AdminAuth::getLoginId();

	    // CREATE PURCHASE ORDER
	    $purchaseOrderItem = PurchaseOrderItem::create([
	        'product_variant_id'            => $request->product_variant_id,
	        'quantity'           			=> $request->quantity,
	        'unit_price'           			=> $request->unit_price,
	        'total_price'               	=> $request->total_price,
	        'created_by'               		=> $adminId,
	    ]);

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order created successfully',
	        'data' => $purchaseOrderItem
	    ]);
	}


	public function updatePurchaseOrder(Request $request, $id)
	{
	    $validator = Validator::make($request->all(), [
	        'product_variant_id'  => 'required',
	        'quantity' => 'required',
	        'unit_price' => 'required',
	        'total_price' => 'required',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => $validator->errors()->first()
	        ], 422);
	    }

	    $purchaseOrderItem = PurchaseOrderItem::find($id);

	    if (!$purchaseOrderItem) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Purchase order not found'
	        ], 404);
	    }

	    $adminId = AdminAuth::getLoginId();
	    $purchaseOrderItem->product_variant_id     = $request->product_variant_id;
	    $purchaseOrderItem->quantity 		   	   = $request->quantity;
	    $purchaseOrderItem->unit_price             = $request->unit_price;
	    $purchaseOrderItem->total_price            = $request->total_price;
	    $purchaseOrderItem->created_by             = $adminId;
	    $purchaseOrderItem->save();

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order updated successfully',
	        'data' => $purchaseOrderItem
	    ]);
	}

	public function viewPurchaseOrder(Request $request, $id)
	{
	    $purchaseOrderItem = PurchaseOrderItem::find($id);

	    if (!$purchaseOrderItem) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Purchase order not found',
	        ], 404);
	    }

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order details fetched successfully',
	        'data' => $purchaseOrderItem
	    ]);
	}

	public function deletePurchaseOrder(Request $request, $id)
	{
	    $purchaseOrderItem = PurchaseOrderItem::find($id);

	    if (!$purchaseOrderItem) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Purchase order not found',
	        ], 404);
	    }

	    // Delete purchaseOrder
	    $purchaseOrderItem->delete();

	    return response()->json([
	        'status' => true,
	        'message' => 'Purchase order deleted successfully',
	    ]);
	}

}