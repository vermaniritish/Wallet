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
use App\Models\API\ApiAuth;
use App\Models\API\Products;
use App\Models\API\ProductCategories;
use App\Models\API\UsersWishlist;
use App\Models\API\ProductReports;
use App\Models\API\Orders;
use App\Models\Admin\Settings;

use App\Libraries\FileSystem;
use App\Libraries\DateTime;
use App\Models\Admin\LogoPrices;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class InventoryProductController extends BaseController
{
	/**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
	    $query = Products::with('categories');

	    // SEARCH
	    if ($request->filled('query')) {
	        $search = $request->get('query');

	        $query->where(function ($q) use ($search) {
	            $q->where('title', 'like', "%{$search}%")
	              ->orWhereHas('categories', function ($cat) use ($search) {
	                  $cat->where('product_category_relation.category_id', 'like', "%{$search}%");
	              });
	        });
	    }

	    // SORTING
	    $sortBy = $request->get('sortBy', 'id');
	    $direction = $request->get('direction', 'asc');

	    $query->orderBy($sortBy, $direction);

	    // PAGINATION
	    $perPage = $request->get('per_page', 10);
	    $products = $query->paginate($perPage);

	    return ProductsResource::collection($products);
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

}