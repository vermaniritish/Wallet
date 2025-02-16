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
use App\Models\Admin\Coupons;
use App\Models\Admin\Admins;
use App\Models\Admin\BlogCategories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use Illuminate\Support\Facades\Storage;

class CouponsController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('coupons', 'listing'))
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
				coupons.id LIKE ? or
				coupons.title LIKE ? or
			 	owner.first_name LIKE ? or 
				owner.last_name LIKE ?)'] = [$search, $search, $search, $search];
    	}

    	if($request->get('created_on'))
    	{
    		$createdOn = $request->get('created_on');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['coupons.created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['coupons.created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($createdOn[1]))
    			];
    	}

    	if($request->get('admins'))
    	{
    		$admins = $request->get('admins');
    		$admins = $admins ? implode(',', $admins) : 0;
    		$where[] = 'coupons.created_by IN ('.$admins.')';
    	}

    	if($request->get('status') !== "" && $request->get('status') !== null)
    	{    		
    		$where['coupons.status'] = $request->get('status');
    	}

    	$listing = Coupons::getListing($request, $where);


    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/coupons/listingLoop", 
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
	    		"admin/coupons/index", 
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
		$adminIds = Coupons::distinct()->whereNotNull('created_by')->pluck('created_by')->toArray();
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
    	if(!Permissions::hasPermission('coupons', 'create'))
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
	                'title' => ['required'],
					'coupon_code' => ['required', Rule::unique('coupons','coupon_code')->whereNull('deleted_at')],
					'max_use' => ['required', 'integer'],
					'end_date' => ['required', 'after_or_equal:today'],
	                'description' => 'nullable',
					'is_percentage' => ['required','boolean'],
					'amount' => ['required']
	            ]
	        );
	        if(!$validator->fails())
	        {
				$formattedDateTime = date('Y-m-d H:i:s', strtotime($request->get('end_date')));
				$data['end_date'] = $formattedDateTime;
	        	$page = Coupons::create($data);
	        	if($page)
	        	{
	        		$request->session()->flash('success', 'Coupon created successfully.');
	        		return redirect()->route('admin.coupons');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Coupon could not be save. Please try again.');
		    		return redirect()->back()->withErrors($validator)->withInput();
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
		    }
		}

	    return view("admin/coupons/add", [
	    		]);
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('coupons', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$page = Coupons::get($id);
    	if($page)
    	{
	    	return view("admin/coupons/view", [
    			'page' => $page
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function edit(Request $request, $id)
    {
    	if(!Permissions::hasPermission('coupons', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$page = Coupons::get($id);

    	if($page)
    	{
			if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();
	    		$validator = Validator::make(
		            $request->toArray(),
		            [
						'title' => ['required'],
						'coupon_code' => ['required', Rule::unique('coupons','coupon_code')->ignore($id)->whereNull('deleted_at')],
						'max_use' => ['required', 'integer'],
						'end_date' => ['required', 'after_or_equal:today'],
						'description' => 'nullable',
						'is_percentage' => ['required','boolean'],
						'amount' => ['required']
		            ]
		        );

		        if(!$validator->fails())
		        {
					$formattedDateTime = date('Y-m-d H:i:s', strtotime($request->get('end_date')));
					$data['end_date'] = $formattedDateTime;
		        	unset($data['_token']);
		        	if(Coupons::modify($id, $data))
		        	{
		        		$request->session()->flash('success', 'Coupon updated successfully.');
		        		return redirect()->route('admin.coupons');
		        	}
		        	else
		        	{
		        		$request->session()->flash('error', 'Coupon could not be save. Please try again.');
			    		return redirect()->back()->withErrors($validator)->withInput();
		        	}
			    }
			    else
			    {
			    	$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			    }
			}

			return view("admin/coupons/edit", [
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
    	if(!Permissions::hasPermission('coupons', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$admin = Coupons::find($id);
    	if($admin->delete())
    	{
    		$request->session()->flash('success', 'Coupon deleted successfully.');
    		return redirect()->route('admin.coupons');
    	}
    	else
    	{
    		$request->session()->flash('error', 'Coupon could not be delete.');
    		return redirect()->route('admin.coupons');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('coupons', 'update')) || ($action == 'delete' && !Permissions::hasPermission('coupons', 'delete')) )
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'active':
    				Coupons::modifyAll($ids, [
    					'status' => 1
    				]);
    				$message = count($ids) . ' records has been published.';
    			break;
    			case 'inactive':
    				Coupons::modifyAll($ids, [
    					'status' => 0
    				]);
    				$message = count($ids) . ' records has been unpublished.';
    			break;
    			case 'delete':
    				Coupons::removeAll($ids);
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

	function download(Request $request, $id)
    {
    	$page = Coupons::get($id);
		
    	if($page)
    	{
			$html = view(
				"admin/coupons/pdf", 
				[
					'page' => $page
				]
			)->render();
			$mpdf = new \Mpdf\Mpdf([
                'tempDir' => public_path('/uploads'),
                'mode' => 'utf-8', 
                'orientation' => 'P',
                'format' => [210, 297],
                'setAutoTopMargin' => true,
                'margin_left' => 0,'margin_right' => 0,'margin_top' => 0,'margin_bottom' => 0,'margin_header' => 0,'margin_footer' => 0
            ]);
			$mpdf->showImageErrors = true;
			$mpdf->WriteHTML($html);
            $mpdf->Output('Coupon-'.$page->id.'.pdf','D');
		}
		else
		{
			abort(404);
		}
    }
}
