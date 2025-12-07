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
use App\Models\Admin\GiftVoucher;
use App\Models\Admin\Admins;
use App\Models\Admin\BlogCategories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use Illuminate\Support\Facades\Storage;

class GiftVoucherController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('gift_voucher', 'listing'))
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
                code LIKE ? or
                sender_name LIKE ? or
                sender_email LIKE ? or
                sender_mobile LIKE ? or
                receiver_name LIKE ? or
                receiver_email LIKE ? or
                receiver_mobile LIKE ?
            )'] = [$search, $search, $search, $search, $search, $search, $search];
    	}

    	if($request->get('created_on'))
    	{
    		$createdOn = $request->get('created_on');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['gift_voucher.created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['gift_voucher.created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($createdOn[1]))
    			];
    	}

    	if($request->get('admins'))
    	{
    		$admins = $request->get('admins');
    		$admins = $admins ? implode(',', $admins) : 0;
    		$where[] = 'gift_voucher.created_by IN ('.$admins.')';
    	}

    	if($request->get('status') !== "" && $request->get('status') !== null)
    	{    		
    		$where['gift_voucher.status'] = $request->get('status');
    	}

    	$listing = GiftVoucher::getListing($request, $where);


    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/gift_vouchers/listingLoop", 
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
	    		"admin/gift_vouchers/index", 
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
		$adminIds = GiftVoucher::distinct()->whereNotNull('user_id')->pluck('user_id')->toArray();
		if($adminIds)
		{
	    	$admins = Users::getAll(
	    		[
	    			'users.id',
	    			'users.first_name',
	    			'users.last_name'
	    		],
	    		[
	    			'users.id in ('.implode(',', $adminIds).')'
	    		],
	    		'concat(users.first_name, users.last_name) desc'
	    	);
	    }
    	return [
	    	'admins' => $admins
    	];
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('gift_voucher', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$page = GiftVoucher::get($id);
    	if($page)
    	{
	    	return view("admin/gift_vouchers/view", [
    			'page' => $page
    		]);
		}
		else
		{
			abort(404);
		}
    }
}
