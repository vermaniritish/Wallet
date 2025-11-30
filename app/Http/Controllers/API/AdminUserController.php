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
use App\Models\API\Admins;
use App\Models\Admin\Settings;
use App\Libraries\FileSystem;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AdminUserController extends BaseController
{
	function index(Request $request)
	{
		$userId = AdminAuth::getLoginId();

		$where = [];
		if($request->get('search'))
        {
            $search = $request->get('search');
            $search = '%' . $search . '%';
            $where['(concat(first_name, "", last_name) LIKE ? or email LIKE ? or phonenumber LIKE ?)'] = [$search, $search, $search];
        }

		$listing = Admins::getApiListing($request, $where);
		return Response()->json([
	    	'status' => true,
	    	'listing' => $listing->items(),
	    ]);
	}
}