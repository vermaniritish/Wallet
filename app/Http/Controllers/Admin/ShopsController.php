<?php
/**
 * Shops Class
 *
 * @package    ShopsController
 
 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Settings;
use App\Models\Admin\Permissions;
use App\Models\Admin\AdminAuth;
use App\Libraries\General;
use App\Models\Admin\Shops;
use App\Models\Admin\Users;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ShopsController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('shops', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$where = [];
    	if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . $search . '%';
    		$where['(concat(user.first_name, "", user.last_name) LIKE ? or name LIKE ? or postcode LIKE ?)'] = [$search, $search, $search];
    	}

        if($request->get('created_on'))
        {
            $createdOn = $request->get('created_on');
            if(isset($createdOn[0]) && !empty($createdOn[0]))
                $where['shops.created >= ?'] = [
                    date('Y-m-d 00:00:00', strtotime($createdOn[0]))
                ];
            if(isset($createdOn[1]) && !empty($createdOn[1]))
                $where['shops.created <= ?'] = [
                    date('Y-m-d 23:59:59', strtotime($createdOn[1]))
                ];
        }

        if($request->get('user_id'))
        {
            $where[] = 'shops.user_id in (' . implode(',', $request->get('user_id')) . ')';
        }

    	if($request->get('status'))
    	{
    		switch ($request->get('status')) {
    			case 'active':
    				$where['shops.status'] = 1;
    			break;
    			case 'non_active':
    				$where['shops.status'] = 0;
    			break;
    		}
    		
    	}
    	$listing = Shops::getListing($request, $where);


    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/shops/listingLoop", 
	    		[
	    			'listing' => $listing
	    		]
	    	)->render();

		    return Response()->json([
		    	'status' => 'success',
	            'html' => $html,
	            'page' => $listing->currentPage(),
	            'counter' => $listing->perPage(),
	            'count' => $listing->total(),
	            'pagination_counter' => $listing->currentPage() * $listing->perPage()
	        ], 200);
		}
		else
		{
            /** Filter Data **/
            $filters = $this->filters($request);
            /** Filter Data **/

	    	return view(
	    		"admin/shops/index", 
	    		[
	    			'listing' => $listing,
                    'users' => $filters['users']
	    		]
	    	);
	    }
    }

    function filters(Request $request)
    {
        $users = [];
        $userIds = Shops::distinct()->whereNotNull('user_id')->pluck('user_id')->toArray();
        if($userIds)
        {
            $users = Users::getAll(
                [
                    'users.id',
                    'users.first_name',
                    'users.last_name',
                    'users.status',
                ],
                [
                    'users.id in ('.implode(',', $userIds).')'
                ],
                'concat(users.first_name, users.last_name) desc'
            );
        }

        return [
            'users' => $users
        ];
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('shops', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$shop = Shops::get($id);
    	if($shop)
    	{
	    	return view("admin/shops/view", [
    			'shop' => $shop
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function add(Request $request)
    {
    	if(!Permissions::hasPermission('shops', 'create'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();

    		$validator = Validator::make(
	            $data,
	            [
	                'name' => 'required',
	                'postcode' => 'required',
	                'address' => 'required',
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	unset($data['_token']);

	        	$shop = Shops::create($data);
	        	if($shop)
	        	{
	        		$request->session()->flash('success', 'Shop created successfully.');
	        		return redirect()->route('admin.shops');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Shop could not be save. Please try again.');
		    		return redirect()->back()->withErrors($validator)->withInput();
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
		    }
		}

		$users = Users::getAll(
	    		[
	    			'users.id',
	    			'users.first_name',
                    'users.last_name',
	    			'users.status',
	    		],
	    		[
	    		],
	    		'concat(users.first_name, users.last_name) desc'
	    	);
	    
	    return view("admin/shops/add", [
	    			'permissions' => Permissions::all(),'users' => $users
	    		]);
    }

    function edit(Request $request, $id)
    {
    	if(!Permissions::hasPermission('shops', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$shop = Shops::get($id);
    	if($shop)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();

	    		
	    		$validator = Validator::make(
		            $data,
		            [
		                'name' => 'required',
		                'postcode' => 'required',
		                'address' => 'required',
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	unset($data['_token']);
		        	
		        	$shop = Shops::modify($id, $data);
		        	if($shop)
		        	{
		        		$request->session()->flash('success', 'Shop updated successfully.');
		        		return redirect()->route('admin.shops');
		        	}
		        	else
		        	{
		        		$request->session()->flash('error', 'Shop could not be save. Please try again.');
			    		return redirect()->back()->withErrors($validator)->withInput();
		        	}
			    }
			    else
			    {
			    	$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			    }
			}

			$users = Users::getAll(
	    		[
	    			'users.id',
	    			'users.first_name',
                    'users.last_name',
	    			'users.status',
	    		],
	    		[
	    		],
	    		'users.first_name desc'
	    	);

			return view("admin/shops/edit", [
    			'shop' => $shop,
    			'users' => $users
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('shops', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$shop = Shops::find($id);
    	if($shop->delete())
    	{
    		$request->session()->flash('success', 'Shop deleted successfully.');
    		return redirect()->route('admin.shops');
    	}
    	else
    	{
    		$request->session()->flash('error', 'Shop could not be delete.');
    		return redirect()->route('admin.shops');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('shops', 'update')) || ($action == 'delete' && !Permissions::hasPermission('shops', 'delete')) ) 
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'active':
    				Shops::modifyAll($ids, [
    					'status' => 1
    				]);
    				$message = count($ids) . ' records has been activate.';
    			break;
    			case 'inactive':
    				Shops::modifyAll($ids, [
    					'status' => 0
    				]);
    				$message = count($ids) . ' records has been inactivate.';
    			break;
    			case 'delete':
    				Shops::removeAll($ids);
    				$message = count($ids) . ' records has been deleted.';
    			break;
    		}

    		$request->session()->flash('success', $message);

    		return Response()->json([
    			'status' => 'success',
	            'message' => $message,
	        ], 200);		
    	}
    	else
    	{
    		return Response()->json([
    			'status' => 'error',
	            'message' => 'Please select atleast one record.',
	        ], 200);	
    	}
    }
}