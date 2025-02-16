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
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Settings;
use App\Models\Admin\Permissions;
use App\Models\Admin\AdminAuth;
use App\Libraries\General;
use App\Models\Admin\Offers;
use App\Models\Admin\Admins;
use App\Models\Admin\Products;
use App\Models\Admin\Sizes;
use App\Models\Admin\Colours;
use App\Models\Admin\BlogCategories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use Illuminate\Support\Facades\Storage;

class OffersController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('offers', 'listing'))
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
				offers.id LIKE ? or
				offers.title LIKE ? or
			 	owner.first_name LIKE ? or 
				owner.last_name LIKE ?)'] = [$search, $search, $search, $search];
    	}

    	if($request->get('created_on'))
    	{
    		$createdOn = $request->get('created_on');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['offers.created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['offers.created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($createdOn[1]))
    			];
    	}

    	if($request->get('admins'))
    	{
    		$admins = $request->get('admins');
    		$admins = $admins ? implode(',', $admins) : 0;
    		$where[] = 'offers.created_by IN ('.$admins.')';
    	}

    	if($request->get('status') !== "" && $request->get('status') !== null)
    	{    		
    		$where['offers.status'] = $request->get('status');
    	}

    	$listing = Offers::getListing($request, $where);


    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/offers/listingLoop", 
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
	    		"admin/offers/index", 
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
		$adminIds = Offers::distinct()->whereNotNull('created_by')->pluck('created_by')->toArray();
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
    	if(!Permissions::hasPermission('offers', 'create'))
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
	                'type' => ['required'],
	                'product_id' => ['required'],
	                'sizes' => ['required', 'array'],
	                'colors' => ['required', 'array'],
	                'quantity' => ['required', 'numeric', 'min:1'],
	                'offer_total_price' => [Rule::requiredIf(fn () => $request->type == 'case-2'), 'nullable', 'numeric', 'min:1'],
	                'free_logo' => [Rule::requiredIf(fn () => $request->type == 'case-3'), 'nullable', 'numeric', 'min:1'],
	            ]
	        );
	        if(!$validator->fails())
	        {
				$data['sizes'] = implode(',',$data['sizes']);
				$data['colors'] = implode(',',$data['colors']);
	        	$page = Offers::create($data);
	        	if($page)
	        	{
	        		$request->session()->flash('success', 'Offer created successfully.');
	        		return redirect()->route('admin.offers');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Offer could not be save. Please try again.');
		    		return redirect()->back()->withErrors($validator)->withInput();
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
		    }
		}

	    return view("admin/offers/add", [
				'products' => Products::select(['id', 'title', 'sku_number'])->orderBy('title', 'asc')->get(),
				'sizes' => Sizes::select(['size_title'])->orderBy('id', 'asc')->get(),
				'colors' => Colours::select(['id', 'title', 'color_code'])->orderBy('id', 'asc')->get()
	    	]);
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('offers', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$page = Offers::get($id);
    	if($page)
    	{
	    	return view("admin/offers/view", [
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
    	if(!Permissions::hasPermission('offers', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$page = Offers::get($id);

    	if($page)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();
				pr($data); die;
	    		$validator = Validator::make(
		            $request->toArray(),
		            [
						'title' => ['required'],
						'type' => ['required'],
						'product_id' => ['required'],
						'sizes' => ['required', 'array'],
						'colors' => ['required', 'array'],
						'quantity' => ['required', 'numeric', 'min:1'],
						'offer_total_price' => [Rule::requiredIf(fn () => $request->type == 'case-2'), 'nullable', 'numeric', 'min:1'],
						'free_logo' => [Rule::requiredIf(fn () => $request->type == 'case-3'), 'nullable', 'numeric', 'min:1'],
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	unset($data['_token']);
					$data['sizes'] = implode(',',$data['sizes']);
					$data['colors'] = implode(',',$data['colors']);
	        		
		        	if(Offers::modify($id, $data))
		        	{
		        		$request->session()->flash('success', 'Offer updated successfully.');
		        		return redirect()->route('admin.offers');
		        	}
		        	else
		        	{
		        		$request->session()->flash('error', 'Offer could not be save. Please try again.');
			    		return redirect()->back()->withErrors($validator)->withInput();
		        	}
			    }
			    else
			    {
			    	$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			    }
			}

			return view("admin/offers/edit", [
    			'page' => $page,
				'products' => Products::select(['id', 'title', 'sku_number'])->orderBy('title', 'asc')->get(),
				'sizes' => Sizes::select(['size_title'])->orderBy('id', 'asc')->get(),
				'colors' => Colours::select(['id', 'title', 'color_code'])->orderBy('id', 'asc')->get()
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('offers', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$admin = Offers::find($id);
    	if($admin->delete())
    	{
    		$request->session()->flash('success', 'Offer deleted successfully.');
    		return redirect()->route('admin.offers');
    	}
    	else
    	{
    		$request->session()->flash('error', 'Offer could not be delete.');
    		return redirect()->route('admin.offers');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('offers', 'update')) || ($action == 'delete' && !Permissions::hasPermission('offers', 'delete')) )
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'active':
    				Offers::modifyAll($ids, [
    					'status' => 1
    				]);
    				$message = count($ids) . ' records has been published.';
    			break;
    			case 'inactive':
    				Offers::modifyAll($ids, [
    					'status' => 0
    				]);
    				$message = count($ids) . ' records has been unpublished.';
    			break;
    			case 'delete':
    				Offers::removeAll($ids);
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
