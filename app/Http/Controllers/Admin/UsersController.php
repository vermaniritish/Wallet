<?php

/**
 * Users Class
 *
 * @package    UsersController
 
 
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
use App\Models\Admin\Products;
use App\Libraries\General;
use App\Libraries\FileSystem;
use App\Models\Admin\OrderProductRelation;
use App\Models\Admin\Orders;
use App\Models\Admin\Users;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UsersController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('users', 'listing'))
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
    				date('Y-m-d 00:00:00', strtotime($lastLogin[0]))
    			];
    		if(isset($lastLogin[1]) && !empty($lastLogin[1]))
    			$where['last_login <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($lastLogin[1]))
    			];
    	}

    	if($request->get('created_on'))
    	{
    		$created = $request->get('created_on');
    		if(isset($created[0]) && !empty($created[0]))
    			$where['created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($created[0]))
    			];
    		if(isset($created[1]) && !empty($created[1]))
    			$where['created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($created[1]))
    			];
    	}

    	if($request->get('role'))
    	{
    		switch ($request->get('role')) {
    			case 'customer':
    				$where['seller'] = 0;
    			break;
    			case 'seller':
    				$where['seller'] = 1;
    			break;
    		}
    		
    	}

    	if($request->get('status'))
    	{
    		switch ($request->get('status')) {
    			case 'active':
    				$where['status'] = 1;
    			break;
    			case 'non_active':
    				$where['status'] = 0;
    			break;
    		}
    		
    	}

    	$listing = Users::getListing($request, $where);

    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/users/listingLoop", 
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
	    		"admin/users/index", 
	    		[
	    			'listing' => $listing,
	    		]
	    	);
	    }
    }

    function add(Request $request)
    {
    	if(!Permissions::hasPermission('users', 'create'))
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
	                	Rule::unique('users')->whereNull('deleted_at')
	                ],
	                'phonenumber' => 'unique:users,phonenumber',
	                'send_password_email' => 'required',
	                'password' => [
	                	'required',
					    'min:8',
	                ]
	            ]
	        );


	        if(!$validator->fails())
	        {
	        	$password = $data['password'];
	        	unset($data['_token']);
	        	unset($data['send_password_email']);
	        	$data['verified_at'] = date('Y-m-d H:i:s');
	        	$user = Users::create($data);
	        	if($user)
	        	{
	        		//Send Email
	        		if($sendPasswordEmail)
	        		{
	        			$codes = [
	        				'{first_name}' => $user->first_name,
	        				'{last_name}' => $user->last_name,
	        				'{email}' => $user->email,
	        				'{password}' => $password
	        			];

	        			General::sendTemplateEmail(
	        				$user->email, 
	        				'customer-admin-registration', 
	        				$codes
	        			);
	        		}

	        		$request->session()->flash('success', 'Customer created successfully.');
	        		return redirect()->route('admin.users');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Customer could not be save. Please try again.');
		    		return redirect()->back()->withErrors($validator)->withInput();
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
		    }
		}
	    
	    return view("admin/users/add", []);
    }

    function edit(Request $request, $id)
    {
    	if(!Permissions::hasPermission('users', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$user = Users::get($id);
    	if($user)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();

	    		/** Set random password in case send email button is on **/
	    		$sendPasswordEmail = isset($data['send_password_email']) && $data['send_password_email'] > 0 ? true : false;
	        	if($sendPasswordEmail)
	        	{
	        		$data['password'] = $password = Str::random(20);
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
		                	Rule::unique('users')->ignore($user->id)->whereNull('deleted_at')
		                ],
		                'password' => [
		                	'nullable',
						    'min:8',
		                ],
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	unset($data['_token']);
		        	unset($data['send_password_email']);

		        	$user = Users::modify($id, $data);
		        	if($user)
		        	{
		        		//Send Email
		        		if($sendPasswordEmail)
		        		{
		        			$codes = [
		        				'{first_name}' => $user->first_name,
		        				'{last_name}' => $user->last_name,
		        				'{email}' => $user->email,
		        				'{password}' => $password
		        			];

		        			General::sendTemplateEmail(
		        				$user->email, 
		        				'customer-admin-registration',
		        				$codes
		        			);
		        		}

		        		$request->session()->flash('success', 'User updated successfully.');
		        		return redirect()->route('admin.users');
		        	}
		        	else
		        	{
		        		$request->session()->flash('error', 'User could not be save. Please try again.');
			    		return redirect()->back()->withErrors($validator)->withInput();
		        	}
			    }
			    else
			    {
			    	$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			    }
			}

			return view("admin/users/edit", [
    			'user' => $user
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('users', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$user = Users::get($id);
    	if($user)
    	{
			if($request->get('search'))
			{
				$search = $request->get('search');
				$search = '%' . $search . '%';
				$where['(
					order_products.id LIKE ? or
					order_products.product_title LIKE ? or
					order_products.quantity LIKE ? or
					order_products.amount LIKE ?)'] = [$search, $search, $search, $search];
			}
			$listing = Orders::getListing($request, ['customer_id' => $id]);

	    	
			if($request->ajax())
	    	{
			    $html = view(
					"admin/users/orderedProducts/listingLoop", 
		    		[
		    			'listing' => $listing,
						'status' => Orders::getStaticData()['status'],
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
		    		"admin/users/view", 
		    		[
		    			'user' => $user,
		    			'listing' => $listing,
						'status' => Orders::getStaticData()['status'],
		    		]
		    	);
		    }
		}
		else
		{
			abort(404);
		}
    }

    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('users', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$user = Users::find($id);
    	if($user->delete())
    	{
    		$request->session()->flash('success', 'User deleted successfully.');
    		return redirect()->route('admin.users');
    	}
    	else
    	{
    		$request->session()->flash('error', 'User category could not be delete.');
    		return redirect()->route('admin.blogs.categories');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('users', 'update')) || ($action == 'delete' && !Permissions::hasPermission('users', 'delete')) ) 
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'active':
    				Users::modifyAll($ids, [
    					'status' => 1
    				]);
    				$message = count($ids) . ' records has been activate.';
    			break;
    			case 'inactive':
    				Users::modifyAll($ids, [
    					'status' => 0
    				]);
    				$message = count($ids) . ' records has been inactivate.';
    			break;
    			case 'delete':
    				Users::removeAll($ids);
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

    function updatePicture(Request $request)
    {
    	if(!Permissions::hasPermission('users', 'update'))
    	{
    		return Response()->json([
			    	'status' => 'error',
			    	'message' => 'Permission denied.'
			    ]);
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();

            $validator = Validator::make(
	            $request->toArray(),
	            [
	                'file' => 'mimes:jpg,jpeg,png,gif',
	            ]
	        );

	        if(!$validator->fails())
	        {
				$id = $data['id'];

		    	$user = Users::find($id);
		    	if($user)
		    	{
		    		$oldImage = $user->image;
		    		if($request->file('file')->isValid())
		    		{
		    			$file = FileSystem::uploadImage(
		    				$request->file('file'),
		    				'users'
		    			);

		    			if($file)
		    			{
		    				$user->image = $file;
		    				if($user->save())
		    				{
		    					$originalName = FileSystem::getFileNameFromPath($file);
		    					
		    					FileSystem::resizeImage($file, 'M-' . $originalName, "350*350");
		    					FileSystem::resizeImage($file, 'S-' . $originalName, "100*100");
		    					$picture = $user->getResizeImagesAttribute()['medium'];

		    					if($oldImage)
		    					{
		    						FileSystem::deleteFile($oldImage);
		    					}

		    					
		    					return Response()->json([
							    	'status' => 'success',
							    	'message' => 'Picture uploaded successfully.',
							    	'picture' => url($picture)
							    ]);		
		    				}
		    				else
		    				{
		    					FileSystem::deleteFile($file);
		    					return Response()->json([
							    	'status' => 'error',
							    	'message' => 'Picture could not be uploaded.'
							    ]);	
		    				}
		    				
		    			}
		    			else
		    			{
		    				return Response()->json([
						    	'status' => 'error',
						    	'message' => 'Picture could not be upload.'
						    ]);		
		    			}
		    		}
					else
					{
						return Response()->json([
					    	'status' => 'error',
					    	'message' => 'Picture could not be uploaded. Please try again.'
					    ]);
					}
				}
				else
				{
					return Response()->json([
				    	'status' => 'error',
				    	'message' => 'Admin member is missing.'
				    ]);
				}
			}
		}
		else
		{
			return Response()->json([
			    	'status' => 'error',
			    	'message' => 'Admin member is missing.'
			    ]);
		}
    }
}