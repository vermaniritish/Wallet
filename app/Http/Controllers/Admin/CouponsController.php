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
use App\Libraries\General;
use App\Models\Admin\Settings;
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
					'number_of_coupons' => 'required|integer|min:1',
	                'title' => ['required'],
					'coupon_code' => ['required', 'regex:/^[A-Za-z0-9]*[0-9]$/', Rule::unique('coupons','coupon_code')->whereNull('deleted_at')],
					'max_use' => ['required', 'integer'],
					'end_date' => 'required|date|after_or_equal:today',
	                'description' => 'nullable',
					'is_percentage' => ['required','boolean'],
					'amount' => ['required']
	            ]
	        );
	        if(!$validator->fails())
	        {
				$noCoupons = $request->get('number_of_coupons');
				unset($data['number_of_coupons']);
				$formattedDateTime = date('Y-m-d H:i:s', strtotime($request->get('end_date')));
				$data['end_date'] = $formattedDateTime;
				$code = $request->coupon_code;
				$inserted = 0;
				$data['uuid'] = General::hash();
				for($i = 0; $i < $noCoupons; $i++)
				{
					if($i > 0)
					{
						$alpha = preg_replace('/[0-9]/', '', $code);
						$numeric = preg_replace('/[^0-9]/', '', $code);
						$num = $numeric !== '' ? intval($numeric) : 0;
						$num++;
						$newNumeric = str_pad($num, strlen($numeric), '0', STR_PAD_LEFT);
						$newCode = $alpha . $newNumeric;
						$code = $newCode;
					}
					$data['coupon_code'] = $code;
	        		$page = Coupons::create($data);
					$inserted++;
				}

				$request->session()->flash('success', $inserted . ' coupons created.');
				return redirect()->route('admin.coupons');
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
			$coupons = Coupons::select(['coupon_code'])
				->where('uuid', $page->uuid)
				->orderBy('coupon_code', 'asc')
				->pluck('coupon_code')
				->toArray();

	    	return view("admin/coupons/view", [
    			'page' => $page,
				'coupons' => $coupons
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
    	$page = Coupons::find($id);
    	if($page)
    	{
			$coupons = Coupons::where('uuid', $page->uuid)
				->orderBy('coupon_code', 'asc')
				->get();

			$mpdf = new \Mpdf\Mpdf([
				'tempDir' => public_path('/uploads'),
				'mode' => 'utf-8',
				'orientation' => 'P',
				'format' => [210, 297],
				'margin_left' => 0,
				'margin_right' => 0,
				'margin_top' => 0,
				'margin_bottom' => 0,
			]);

			
			$footerHtml = '
			<table width="750" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td style="padding:20px 30px;font-family: Arial, Helvetica, sans-serif;font-size: 13px;color: #666666;">
						Pinders Schoolwear Ltd<br />
						TEL; 0114 2513275<br />
						www.pindersschoolwear.com<br />
					</td>
					<td style="text-align:right;">
						<div align="right">
							<img src="https://pindersschoolwear.com/image/emaillogo2.jpg" />
						</div>
					</td>
				</tr>
			</table>';

			$mpdf->SetHTMLFooter($footerHtml);

			$mpdf->showImageErrors = true;
			$logo = Settings::get('logo');
			foreach ($coupons as $index => $page) {

				// Pass coupon data to blade
				$html = view('admin.coupons.pdf', [
					'page' => $page,
					'logo' => $logo
				])->render();

				// Add new page for all except first page
				if ($index > 0) {
					$mpdf->AddPage();
				}

				$mpdf->WriteHTML($html);
			}

			$mpdf->Output($page->coupon_code . ' - Coupons.pdf', 'I');
		}
		else
		{
			abort(404);
		}
    }
}
