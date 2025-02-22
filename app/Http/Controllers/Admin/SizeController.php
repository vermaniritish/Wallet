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
use App\Models\Admin\Admins;
use Illuminate\Validation\Rule;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use App\Models\Admin\Colours;
use App\Models\Admin\Orders;
use App\Models\Admin\Settings;
use App\Models\Admin\Sizes;
use App\Models\Admin\StaffDocuments;
use Illuminate\Support\Facades\File;
use Intervention\Image\Size;

class SizeController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request, $slug = null)
    {
    	if(!Permissions::hasPermission('sizes', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

		$male = Sizes::where('type','Male')->select(['type','id','size_title','from_cm','to_cm','chest','waist','hip','length', 'vat'])->get();
		$female = Sizes::where('type','Female')->select(['type','id','size_title','from_cm','to_cm','chest','waist','hip','length', 'vat'])->get();
		$unisex = Sizes::where('type','Unisex')->select(['type','id','size_title','from_cm','to_cm','chest','waist','hip','length', 'vat'])->get();
		$kids = Sizes::where('type','Kids')->select(['type','id','size_title','from_cm','to_cm','chest','waist','hip','length', 'vat'])->get();
    	$where = [];
		$filters = $this->filters($request);
		return view(
			"admin/sizes/index", 
			[
				'male' => $male,
				'female' => $female,
				'unisex' => $unisex,
				'kids' => $kids,
				'admins' => $filters['admins'],
				'slug' => $slug ? $slug : 'men'
			]
		);
    }

    function filters(Request $request)
    {
		$admins = [];
		$adminIds = Sizes::distinct()->whereNotNull('created_by')->pluck('created_by')->toArray();
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
    	if(!Permissions::hasPermission('sizes', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
    	if($request->isMethod('post'))
    	{
			$data = $request->toArray();
    		unset($data['_token']);
			if (!empty($data)) {
				$validator = Validator::make($request->all(), [
					'type' => ['required', Rule::in(['Male','Female','Unisex', 'Kids'])]
				]);
				if(!$validator->fails())
				{
					foreach ($request->mens as $item) {
						$sizeId = null;
						if(isset($item['id']) && $item['id']) {
							$sizeId = Sizes::select(['id'])->where('id',$item['id'])->limit(1)->pluck('id')->first();
						}
						$item['type'] = $data['type'];
						if($sizeId)
							Sizes::modify($sizeId, $item);
						else
							Sizes::create($item);
					}
					$request->session()->flash('success', 'Size created successfully.');
					return redirect()->back();
				}
				else
				{
					$request->session()->flash('error', 'Please provide valid inputs.');
					return redirect()->back()->withErrors($validator)->withInput();
				}
			}
			else {
				$request->session()->flash('error', 'Please provide valid inputs.');
				return redirect()->back()->withErrors(['mens' => 'Data is empty.'])->withInput();
			}
		}

	    return view("admin/sizes/add", [
	    		]);
    }

    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('sizes', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$admin = Sizes::find($id);
    	if($admin->delete())
    	{
    		$request->session()->flash('success', 'Staff deleted successfully.');
    		return redirect()->route('admin.size');
    	}
    	else
    	{
    		$request->session()->flash('error', 'Staff could not be delete.');
    		return redirect()->route('admin.size');
    	}
    }

}
