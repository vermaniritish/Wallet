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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\API\AdminAuth;
use App\Models\API\Supplier;

use App\Libraries\FileSystem;
use App\Libraries\DateTime;
use App\Models\Admin\LogoPrices;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SupplierController extends BaseController
{
	function index(Request $request)
	{
		$userId = AdminAuth::getLoginId();

		$where = [];
		if($request->get('search'))
        {
            $search = $request->get('search');
            $search = '%' . $search . '%';
            $where['suppliers.name LIKE ?'] = [$search];
        }

		$listing = Supplier::getApiListing($request, $where);
		return Response()->json([
	    	'status' => true,
	    	'listing' => $listing->items(),
	    ]);
	}

	public function view(string $id)
    {
        $check_brand = Products::whereId($id)->first();
        if (!$check_brand) {
            return $this->error(trans('PRODUCT_NOT_FOUND'), Response::HTTP_NOT_FOUND);
        }

        return $this->success(new ProductsResource($check_brand), Response::HTTP_OK);
    }

    public function delete(string $id)
	{
	    $product = Products::find($id);

	    if (!$product) {
	        return $this->error(trans('PRODUCT_NOT_FOUND'), Response::HTTP_NOT_FOUND);
	    }

	    $product->delete();

	    return $this->success(['message' => trans('PRODUCT_DELETED_SUCCESSFULLY')], Response::HTTP_OK);
	}

	public function getSupplierSearch(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'supplier_name' => 'required',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => current(current($validator->errors()->getMessages()))
	        ], 400);
	    }

	    $name = $request->supplier_name;

	    // Find product by name
	    $supplier = Supplier::where('name', 'LIKE', "%{$name}%")->first();

	    if (!$supplier) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Supplier not found with this name.'
	        ], 404);
	    }
	    return response()->json([
	        'status' => true,
	        'message' => 'Supplier colors fetched successfully.',
	        'data' => [
	            'supplier' => $supplier
	        ]
	    ]);
	}

}