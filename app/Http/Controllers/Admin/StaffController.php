<?php

/**
 * Pages Class
 *
 * @package    PagesController 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Permissions;
use App\Models\Admin\Staffs;
use App\Models\Admin\Admins;
use Illuminate\Validation\Rule;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use App\Models\Admin\Orders;
use App\Models\Admin\Settings;
use App\Models\Admin\Staff;
use App\Models\Admin\StaffDocuments;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class StaffController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('staff', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$where = [];
    	if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . $search . '%';
    		$where['(
				staff.id LIKE ? or
				staff.first_name LIKE ? or
				staff.last_name LIKE ? or
				staff.aadhar_card_number LIKE ? or
				staff.phone_number LIKE ? or
				staff.email LIKE ? or
			 	owner.first_name LIKE ? or 
				owner.last_name LIKE ?)'] = [$search, $search, $search, $search];
    	}

    	if($request->get('created_on'))
    	{
    		$createdOn = $request->get('created_on');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['staff.created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['staff.created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($createdOn[1]))
    			];
    	}

    	if($request->get('admins'))
    	{
    		$admins = $request->get('admins');
    		$admins = $admins ? implode(',', $admins) : 0;
    		$where[] = 'staff.created_by IN ('.$admins.')';
    	}

    	$listing = Staff::getListing($request, $where);


    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/staff/listingLoop", 
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
			$filters = $this->filters($request);
	    	return view(
	    		"admin/staff/index", 
	    		[
	    			'listing' => $listing,
	    			'admins' => $filters['admins']
	    		]
	    	);
	    }
    }

    function filters(Request $request)
    {
		$admins = [];
		$adminIds = Staff::distinct()->whereNotNull('created_by')->pluck('created_by')->toArray();
		if($adminIds)
		{
	    	$admins = Admins::getAll(
	    		[
	    			'admins.id',
	    			'admins.first_name',
	    			'admins.last_name',
	    			'admins.status',
	    		],
	    		[
	    			'admins.id in ('.implode(',', $adminIds).')'
	    		],
	    		'concat(admins.first_name, admins.last_name) desc'
	    	);
	    }
    	return [
	    	'admins' => $admins
    	];
    }

    function add(Request $request)
    {
    	if(!Permissions::hasPermission('staff', 'create'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		unset($data['_token']);
    		$validator = Validator::make(
	            $request->toArray(),
	            [
					'first_name' => 'required',
					'last_name' => 'required',
					'email' => [
						'required',
						'email',
						Rule::unique('staff','email')->whereNull('deleted_at'),
					],
					'phone_number' => ['required','numeric','digits:10',],
					'image' => ['nullable'],
	            ]
	        );
	        if(!$validator->fails())
	        {
	        	$page = Staff::create($data);
	        	if($page)
	        	{
	        		$request->session()->flash('success', 'Staff created successfully.');
	        		return redirect()->route('admin.staff');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Staff could not be save. Please try again.');
		    		return redirect()->back()->withErrors($validator)->withInput();
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
		    }
		}

	    return view("admin/staff/add", [
	    		]);
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('staff', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
		$fromDate = now()->startOfMonth()->toDateString();
		$toDate = now()->endOfMonth()->toDateString();
		if ($request->filled('order_created')) {
			$customDateRange = $request->input('order_created');
			$fromDate = $customDateRange[0];
			$toDate = $customDateRange[1];
		}	
		if($fromDate && $toDate)
    	{
    		if(isset($fromDate) && !empty($fromDate))
    			$where['orders.created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($fromDate))
    			];
    		if(isset($toDate) && !empty($toDate))
    			$where['orders.created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($toDate))
    			];
    	}
    	$page = Staff::get($id);
		$where['staff_id'] = $id;
		if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . $search . '%';
    		$where['(
				orders.status LIKE ? or
				orders.id LIKE ? or
				orders.total_amount LIKE ?)'] = [$search, $search, $search];
    	}
		$listing = Orders::getListing($request,$where);
    	if($page)
    	{	if($request->ajax())
			{
				$html = view(
					"admin/staff/orders/listingLoop", 
					[
						'listing' => $listing,
						'currency' => Settings::get('currency_symbol'),
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
	    	return view("admin/staff/view", [
    			'page' => $page,
				'status' => Orders::getStaticData()['status'],
				'listing' => $listing
    		]);}
		}
		else
		{
			abort(404);
		}
    }

    

    function edit(Request $request, $id)
    {
    	if(!Permissions::hasPermission('staff', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$page = Staff::get($id);

    	if($page)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();
	    		$validator = Validator::make(
		            $request->toArray(),
		            [
						'first_name' => 'required',
						'last_name' => 'required',
						'email' => [
							'required',
							'email',
							Rule::unique('staff','email')->whereNull('deleted_at'),
						],
						'phone_number' => ['required','numeric','digits:10',],
						'image' => ['nullable'],
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	unset($data['_token']);
	        		
		        	/** IN CASE OF SINGLE UPLOAD **/
		        	if(isset($data['image']) && $data['image'])
		        	{
		        		$oldImage = $page->image;
		        	}
		        	else
		        	{
		        		unset($data['image']);
		        		
		        	}
		        	/** IN CASE OF SINGLE UPLOAD **/

		        	$categories = [];
		        	if(isset($data['category']) && $data['category']) {
		        		$categories = $data['category'];
		        	}
		        	unset($data['category']);

		        	if(Staff::modify($id, $data))
		        	{
		        		/** IN CASE OF SINGLE UPLOAD **/
		        		if(isset($oldImage) && $oldImage)
		        		{
		        			FileSystem::deleteFile($oldImage);
		        		}
		        		/** IN CASE OF SINGLE UPLOAD **/

		        		if(!empty($categories))
		        		{
		        			Staff::handleCategories($page->id, $categories);
		        		}

		        		$request->session()->flash('success', 'Staff updated successfully.');
		        		return redirect()->route('admin.staff');
		        	}
		        	else
		        	{
		        		$request->session()->flash('error', 'Staff could not be save. Please try again.');
			    		return redirect()->back()->withErrors($validator)->withInput();
		        	}
			    }
			    else
			    {
			    	$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			    }
			}

			return view("admin/staff/edit", [
    			'page' => $page
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('staff', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$admin = Staff::find($id);
    	if($admin->delete())
    	{
    		$request->session()->flash('success', 'Staff deleted successfully.');
    		return redirect()->route('admin.staff');
    	}
    	else
    	{
    		$request->session()->flash('error', 'Staff could not be delete.');
    		return redirect()->route('admin.staff');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('staff', 'update')) || ($action == 'delete' && !Permissions::hasPermission('staff', 'delete')) )
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'active':
    				Staff::modifyAll($ids, [
    					'status' => 1
    				]);
    				$message = count($ids) . ' records has been published.';
    			break;
    			case 'inactive':
    				Staff::modifyAll($ids, [
    					'status' => 0
    				]);
    				$message = count($ids) . ' records has been unpublished.';
    			break;
    			case 'delete':
    				Staff::removeAll($ids);
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

    function addDocument(Request $request, $id)
    {
        if(!Permissions::hasPermission('staff', 'create'))
        {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }
        $staff = Staff::findOrFail($id);
        $data = $request->toArray();
        unset($data['_token']);
        $validator = Validator::make(
            $request->toArray(),
            [
                'title' => ['required'],
                'file' =>['required', 'json'],          
            ]
        );
        if (!$validator->fails()) {
            if($staff){
                $data['staff_id'] = $staff->id;
                $staffDocument = StaffDocuments::create($data);
                if($staffDocument){
                    return Response()->json([
                        'status' => true,
                        'message' => 'Document added successfully.',
                        'id' => $id
                    ]);
                }
                else {
                    return Response()->json([
                        'status' => false,
                        'message' => 'Document could not be saved. Please try again.'
                    ], 400);
                }
            }
            else {
                return Response()->json([
                    'status' => false,
                    'message' => 'User not found.'
                ], 400);
            }   
        }   else {
            return Response()->json([
                'status' => false,
                'message' => current(current($validator->errors()->getMessages()))
            ], 400);
        }
    }

	function deleteDocument(Request $request, $staffId, $id, $index)
    {
        if(!Permissions::hasPermission('staff', 'delete'))
        {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
		}
		$staffDoc = StaffDocuments::find($id);
		if($staffDoc){
				$fileArray = json_decode($staffDoc->file, true);
				if (count($fileArray) === 1 && array_key_exists($index, $fileArray)) {
					$staffDoc->delete();
					$filePath = public_path($fileArray[$index]);
					if (File::exists($filePath)) {
						File::delete($filePath);
					}
					$request->session()->flash('success', 'Document and row deleted successfully.');
					return redirect()->route('admin.staff.view', ['id' => $staffId]);
				} elseif (array_key_exists($index, $fileArray)) {
					$filePath = public_path($fileArray[$index]);
					if (File::exists($filePath)) {
						File::delete($filePath);
					}
					unset($fileArray[$index]);
					$staffDoc->file = json_encode(array_values($fileArray));
					$staffDoc->save();
					$request->session()->flash('success', 'Document deleted successfully.');
					return redirect()->route('admin.staff.view', ['id' => $staffId]);
				} else {
					$request->session()->flash('error', 'Invalid index specified.');
					return redirect()->route('admin.staff.view', ['id' => $staffId]);
				}
			}
			else {
				$request->session()->flash('Document not found.');
				return redirect()->route('admin.staff.view',['id' => $staffId]);
		}
    }
}
