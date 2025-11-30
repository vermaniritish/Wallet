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
	
    function add(Request $request)
	{	
		$userId = AdminAuth::getLoginId(); 
        // if($userId)
        // {		
			$allowed = ['title','manager_name','phone','email','address'];
			if($request->has($allowed))
			{ 
				$data = $request->toArray();
				$validator = Validator::make(
		            $request->toArray(),
		            [
						'title'        => 'required',
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	$data['user_id'] = $userId;
	        		if($info = WareHouse::createApi($data))
		        	{
                        $getWareHouse = WareHouse::getWareHouse($info->id);
						return Response()->json([
					    	'status' 	=> true,
					    	'message' 	=> 'Address saved successfully',
					    	'data' 		=> $getWareHouse
					    ]);	
		        	}
		        	else
		        	{
		        		return Response()->json([
					    	'status' 	=> false,
					    	'message' 	=> 'Unable to save address. Please try again.'
					    ], 400);
		        	}
		        }    	
			    else
			    {
			    	return Response()->json([
				    	'status' 	=> false,
				    	'message' 	=> current( current( $validator->errors()->getMessages() ) )
				    ], 400);
			    }
		    }
		    else
		    {
		    	return Response()->json([
			    	'status' 	=> false,
			    	'message' 	=> 'Some of inputs are invalid in request.',
			    ], 400);
		    }
		// }
		// else
		// {
		// 	return Response()->json([
		//     	'status' 	=> false,
		//     	'message' 	=> 'User not found.',
		//     ], 400);
		// }
	}
}