<?php

/**
 * Admins Class
 *
 * @package    AdminsController
 
 
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
use App\Models\Admin\Shops;
use App\Libraries\General;
use App\Models\Admin\Admins;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminsController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$where = [];
    	if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . $search . '%';
    		$where['(concat(first_name, "", last_name) LIKE ? or email LIKE ? or phonenumber LIKE ?)'] = [$search, $search, $search];
    	}

    	if($request->get('last_login'))
    	{
    		$lastLogin = $request->get('last_login');
    		if(isset($lastLogin[0]) && !empty($lastLogin[0]))
    			$where['last_login >= ?'] = [
    				date('Y-m-d', strtotime($lastLogin[0]))
    			];
    		if(isset($lastLogin[1]) && !empty($lastLogin[1]))
    			$where['last_login <= ?'] = [
    				date('Y-m-d', strtotime($lastLogin[1]))
    			];
    	}

    	if($request->get('admins'))
    	{
    		switch ($request->get('admins')) {
    			case 'admin':
    				$where['is_admin'] = 0;
    			break;
    			case 'super_admin':
    				$where['is_admin'] = 1;
    			break;
    		}
    		
    	}

    	$listing = Admins::getListing($request, $where);


    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/admins/listingLoop", 
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
	    	return view(
	    		"admin/admins/index", 
	    		[
	    			'listing' => $listing,
	    		]
	    	);
	    }
    }

    function add(Request $request)
    {
    	if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();

    		/** Set random password in case send email button is on **/
    		$sendPasswordEmail = isset($data['send_password_email']) && $data['send_password_email'] > 0 ? true : false;
        	if($sendPasswordEmail)
        	{
        		$data['password'] = Str::random(20);
        	}

        	/** Set random password in case send email button is on **/
    		$validator = Validator::make(
	            $data,
	            [
	                'first_name' => 'required',
	                'last_name' => 'required',
	                'email' => [
	                	'required',
	                	'email',
	                	Rule::unique('admins')->whereNull('deleted_at')
	                ],
	                'send_password_email' => 'required',
	                'is_admin' => 'required',
	                'password' => [
	                	'required',
					    'min:8',
	                ],
	                'permissions' => [
	                	Rule::requiredIf(function () use ($request) {
					        return $request->get('is_admin') === null || $request->get('is_admin') == '0';
					    })
	                ],
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	$password = $data['password'];
	        	$isAdmin = isset($data['is_admin']) && $data['is_admin'] > 0 ? true : false;
	        	$permissions = isset($data['permissions']) && $data['permissions'] ? $data['permissions'] : [];
	        	unset($data['permissions']);
	        	unset($data['_token']);
	        	unset($data['send_password_email']);
	        	$data['shop_id'] = json_encode($request->shop_id);

	        	$admin = Admins::create($data);
	        	if($admin)
	        	{
	        		//Send Email
	        		if($sendPasswordEmail)
	        		{
	        			$codes = [
	        				'{first_name}' => $admin->first_name,
	        				'{last_name}' => $admin->last_name,
	        				'{email}' => $admin->email,
	        				'{password}' => $password
	        			];

	        			General::sendTemplateEmail(
	        				$admin->email, 
	        				'admin-registration', 
	        				$codes
	        			);
	        		}

	        		//Make Permissions
	        		if(!$isAdmin && !empty($permissions))
	        		{
        				Permissions::savePermissions(
	        					$admin->id,
	        					$permissions
	        				);
	        		}

	        		$request->session()->flash('success', 'Admin created successfully.');
	        		return redirect()->route('admin.admins');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Admin could not be save. Please try again.');
		    		return redirect()->back()->withErrors($validator)->withInput();
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
		    }
		}
	    $shops = Shops::getAll(['shops.id','shops.name','shops.slug','shops.image']);
	    return view("admin/admins/add", [
	    			'permissions' => Permissions::all(),
	    			'shops'       => $shops
	    		]);
    }

    function edit(Request $request, $id)
    {
    	if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$admin = Admins::get($id);
    	if($admin)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();

	    		/** Set random password in case send email button is on **/
	    		$sendPasswordEmail = isset($data['send_password_email']) && $data['send_password_email'] > 0 ? true : false;
	        	if($sendPasswordEmail)
	        	{
	        		$data['password']  = $password = Str::random(20);
	        	}
	        	elseif(!isset($data['password']) || !$data['password'])
	        	{
	        		unset($data['password']);
	        	}

	        	/** Set random password in case send email button is on **/
	    		$validator = Validator::make(
		            $data,
		            [
		                'first_name' => 'required',
		                'last_name' => 'required',
		                'email' => [
		                	'required',
		                	'email',
		                	Rule::unique('admins')->ignore($admin->id)->whereNull('deleted_at'),
		                ],
		                'is_admin' => 'required',
		                'password' => [
		                	'nullable',
						    'min:8',
		                ],
		                'permissions' => [
		                	Rule::requiredIf(function () use ($request) {
						        return $request->get('is_admin') === null || $request->get('is_admin') == '0';
						    })
		                ],
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	$isAdmin = isset($data['is_admin']) && $data['is_admin'] > 0 ? true : false;
		        	$permissions = isset($data['permissions']) && $data['permissions'] ? $data['permissions'] : [];
		        	
		        	unset($data['permissions']);
		        	unset($data['_token']);
		        	unset($data['send_password_email']);
		        	$data['shop_id'] = json_encode($request->shop_id);

		        	$admin = Admins::modify($id, $data);
		        	if($admin)
		        	{
		        		//Send Email
		        		if($sendPasswordEmail)
		        		{
		        			$codes = [
		        				'{first_name}' => $admin->first_name,
		        				'{last_name}' => $admin->last_name,
		        				'{email}' => $admin->email,
		        				'{password}' => $password
		        			];

		        			General::sendTemplateEmail(
		        				$admin->email, 
		        				'admin-registration', 
		        				$codes
		        			);
		        		}

		        		//Make Permissions
		        		if(!$isAdmin && !empty($permissions))
		        		{
	        				Permissions::savePermissions(
		        					$admin->id,
		        					$permissions
		        				);
		        		}

		        		$request->session()->flash('success', 'Admin updated successfully.');
		        		return redirect()->route('admin.admins');
		        	}
		        	else
		        	{
		        		$request->session()->flash('error', 'Admin could not be save. Please try again.');
			    		return redirect()->back()->withErrors($validator)->withInput();
		        	}
			    }
			    else
			    {
			    	$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			    }
			}
			$shops = Shops::getAll(['shops.id','shops.name','shops.slug','shops.image']);
			return view("admin/admins/edit", [
				'adminPermissions' => Permissions::getUserPermissions($admin->id),
    			'permissions' => Permissions::all(),
    			'admin' => $admin,
    			'shops' => $shops
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function delete(Request $request, $id)
    {
    	if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$admin = Admins::find($id);
    	if($admin->delete())
    	{
    		$request->session()->flash('success', 'Admin deleted successfully.');
    		return redirect()->route('admin.admins');
    	}
    	else
    	{
    		$request->session()->flash('error', 'Admin could not be delete.');
    		return redirect()->route('admin.admins');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if(!AdminAuth::isAdmin()) 
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'active':
    				Admins::modifyAll($ids, [
    					'status' => 1
    				]);
    				$message = count($ids) . ' records has been activate.';
    			break;
    			case 'inactive':
    				Admins::modifyAll($ids, [
    					'status' => 0
    				]);
    				$message = count($ids) . ' records has been inactivate.';
    			break;
    			case 'delete':
    				Admins::removeAll($ids);
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
