<?php

/**
 * Pages Class
 *
 * @package    PagesController 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Permissions;
use App\Models\Admin\Coupons;
use App\Models\Admin\Admins;
use App\Models\Admin\Shops;
use App\Models\Admin\BlogCategories;
use App\Models\Admin\Schools;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use App\Libraries\General;
use App\Models\Admin\Addresses;
use App\Models\Admin\AdminAuth;
use App\Models\Admin\OrderProductRelation;
use App\Models\Admin\Orders;
use App\Models\Admin\OrderStatusHistory;
use App\Models\Admin\ProductCategories;
use App\Models\Admin\Products;
use App\Models\Admin\Colours;
use App\Models\Admin\Sizes;
use App\Models\Admin\Brands;
use App\Models\Admin\Settings;
use App\Models\Admin\Staff;
use App\Models\Admin\Users;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class OrdersController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('orders', 'listing'))
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
				orders.prefix_id LIKE ? or
				orders.first_name LIKE ? or
				orders.last_name LIKE ? or
				orders.customer_email LIKE ? or
				orders.customer_phone LIKE ? or
				orders.company LIKE ? or
				orders.address LIKE ? or
				orders.status LIKE ? or
				orders.coupon_code LIKE ? or
			 	orders.total_amount LIKE ?)'] = [$search, $search, $search, $search, $search, $search, $search, $search, $search, $search];
    	}

    	if($request->get('coupon')) {
			$where['orders.coupon_code'] = $request->get('coupon');
		}

    	if($request->get('created_on'))
    	{
    		$createdOn = $request->get('created_on');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['orders.created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['orders.created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($createdOn[1]))
    			];
    	}

		if($request->get('booking_date'))
    	{
    		$createdOn = $request->get('booking_date');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['orders.booking_date >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['orders.booking_date <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($createdOn[1]))
    			];
    	}

    	if($request->get('admins'))
    	{
    		$admins = $request->get('admins');
    		$admins = $admins ? implode(',', $admins) : 0;
    		$where[] = 'orders.created_by IN ('.$admins.')';
    	}
		if($request->has('status') && $request->get('status')) 
		{
			$where['orders.status'] = $request->get('status');
		}

		if($request->get('source') == 'website') {
			$where[] = '(orders.shop_id is null)';
		}
		else if($request->get('source') == 'shop') {
			$where[] = '(orders.shop_id is not null)';
		}
		if($request->get('shipping') == 'parcelforce') {
			$where['( orders.shipping_gateway LIKE ? or orders.shipping_gateway LIKE ?)'] = ['%Parcel Force%', 'parcelforce'];
		}
		else if($request->get('shipping') == 'dpd') {
			$where['( orders.shipping_gateway LIKE ? or orders.shipping_gateway LIKE ?)'] = ['DPD', 'dpd'];
		}
		elseif($request->get('shipping')) {
			$where['( orders.shipping_gateway LIKE ?)'] = ['%'.$request->get('shipping').'%'];
		}
    	$listing = Orders::getListing($request, $where);
    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/orders/listingLoop", 
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
			$filters = $this->filters($request);
	    	return view(
	    		"admin/orders/index", 
	    		[
					'status' => Orders::getStaticData()['status'],
	    			'listing' => $listing,
	    			'admins' => $filters['admins'],
					'shops' => Shops::select(['id', 'name', 'status'])->orderBy('name', 'asc')->get(),
					'schools' => Schools::select(['id', 'schooltype', 'name', 'status'])->orderBy('name', 'asc')->get()
	    		]
	    	);
	    }
    }

    function filters(Request $request)
    {
		$admins = [];
		$adminIds = Orders::distinct()->whereNotNull('created_by')->pluck('created_by')->toArray();
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
	    	'admins' => $admins,
    	];
    }

    function add(Request $request)
    {
    	if(!Permissions::hasPermission('orders', 'create'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		unset($data['_token']);
            $data['productsData'] = json_decode($data['productsData'], true);
			$productData = [];
			$productData = $data['productsData'];
    		$validator = Validator::make(
	            $data,
	            [
					'customer_id' => ['required', Rule::exists(User::class,'id')],
					'product_id' => ['required', 'array'],
    				'product_id.*' => ['required', Rule::exists(Products::class, 'id')->where(function ($query) {
						$query->where('status', 1)->whereNull('deleted_at');
					})],
					'booking_date' => ['required', 'date'],
					'booking_time' => ['required', 'after_or_equal:today'],
					'manual_address' => ['nullable','boolean'],
					'address' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:255'],
					// 'state' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:40'],
					// 'city' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:30'],
					// 'area' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:40'],
					'address_id' => ['exclude_if:manual_address,true','required_if:manual_address,false',Rule::exists(Addresses::class,'id')],
					// 'payment_type' => ['required'], 
					'coupon_code_id' => ['nullable', Rule::exists(Coupons::class, 'id')->where(function ($query) {
						$query->where('status', 1)->whereNull('deleted_at');
					})],
					'subtotal' => ['required', 'numeric'],
					'discount' => ['required', 'numeric'],
					'tax' => ['required', 'numeric'],
					'total_amount' => ['required', 'numeric'],
					'productsData' => ['required', 'array'],
					'productsData.*.id' => ['required', Rule::exists(Products::class, 'id')->where(function ($query) {
						$query->where('status', 1)->whereNull('deleted_at');
					})],
					'productsData.*.quantity' => ['required', 'integer', 'min:1'],
	            ]
	        );
	        if(!$validator->fails())
	        {   
				unset($data['product_id']);
				unset($data['productsData']);
				$formattedDateTime = date('Y-m-d H:i:s', strtotime($request->get('booking_date')));
				$data['booking_date'] = $formattedDateTime;
				$data['created_by_admin'] = true;
				$customerId = $request->input('customer_id');
				$user = User::find($customerId);
				if($user){
					$data['customer_name'] = $user->first_name . ' ' . $user->last_name; 
				}
				if (!$data['manual_address']) {
					$address = Addresses::where('id', $data['address_id'])->first();
					if($address){
						$data['address'] = $address->address; 
						$data['city'] = $address->city; 
						$data['state'] = $address->state; 
						$data['area'] = $address->area; 
						$input['latitude'] = $address->latitude;
						$input['longitude'] = $address->longitude;
					}
				}
	        	$order = Orders::create($data);
	        	if($order)
	        	{
					$order_prefix = (int)Settings::get('order_prefix');
					$data['prefix_id'] = $order->id + $order_prefix;
					Orders::modify($order->id,$data);
					if (!empty($productData)) {
						Orders::handleProducts($order->id, $productData);
					}
					$request->session()->flash('success', trans('ORDER_CREATED'));
					return Response()->json([
						'status' => true,
						'message' => trans('ORDER_CREATED'),
						'id' => $order->id
					]);
	        	}
	        	else
	        	{
					return Response()->json([
						'status' => false,
						'message' => 'Order could not be saved. Please try again.'
					], 400);
	        	}
		    }
		    else
		    {
				return Response()->json([
					'status' => false,
					'message' => current(current($validator->errors()->getMessages()))
				], 400);
		    }
		}
		$users = Users::getAll(
			[
				'users.id',
				'users.first_name',
				'users.last_name',
				'users.status',
				'users.phonenumber',
			],
			[
			],
			'concat(users.first_name, users.last_name) desc'
		);
		$productCategories = ProductCategories::with(['products' => function ($query) {
			$query->where('status', 1);
		}])
		->where('status', 1)
		->get(['id', 'title']);
		
	    $address = Addresses::getAll(
			[
				'addresses.id',
				'addresses.address',
				'addresses.city',
				'addresses.area',
				'addresses.state',
			],
			[
			]
		);
		$coupons = Coupons::getAll(
			[
				'coupons.id',
				'coupons.title',
				'coupons.is_percentage',
				'coupons.amount',
			],
			[
				'status' => 1, 
			]
		);
	    return view("admin/orders/add", [
			'users' => $users,
			'productCategories' => $productCategories,
			'address' => $address,
			'coupons' => $coupons,
			'paymentType' => Orders::getStaticData()['paymentType'],
			'tax_percentage' => Settings::get('tax_percentage'),
			'cgst' => Settings::get('cgst'),
			'sgst' => Settings::get('sgst')
	    ]);
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('orders', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
    	$page = Orders::get($id);
		$where = ['order_products.order_id' => $id];
		$listing = OrderProductRelation::getListing($request, $where);
		$staff = Staff::getAll(
			[
				'staff.id',
				'staff.first_name',
				'staff.last_name',
				'staff.status',
			],
			[
			],
			'concat(staff.first_name, staff.last_name) desc'
		);
    	if($page)
    	{
			if($request->ajax())
			{
				$html = view(
					"admin/orders/orderedProducts/listingLoop", 
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
				return view("admin/orders/view", [
					'page' => $page,
					'status' => Orders::getStaticData()['status'],
					'history' => OrderStatusHistory::where('order_id', $id)->orderBy('created', 'desc')->get(),
					'listing' => $listing,
					'staff' => $staff
				]);
			}
		}
		else
		{
			abort(404);
		}
    }

	function download(Request $request, $id)
    {
		$id = Orders::select(['id'])->where(function($q) use ($id) {
			return $q->where('id', $id)->orWhere('prefix_id', $id);
		})->limit(1)->pluck('id')->first();
    	if($id)
		{
			$page = Orders::get($id);
			$where = ['order_products.order_id' => $id];
			$listing = OrderProductRelation::getListing($request, $where);
			$html = view(
				"admin/orders/pdf", 
				[
					'page' => $page,
					'listing' => $listing,
					'logo' => Settings::get('logo')
				]
			)->render();
			$mpdf = new \Mpdf\Mpdf([
				'tempDir' => public_path('/uploads'),
				'mode' => 'utf-8',
				'format' => 'A4',
				'orientation' => 'P',
				'margin_left' => 10,
				'margin_right' => 10,
				'margin_top' => 10,
				'margin_bottom' => 10,
			]);
			$mpdf->showImageErrors = true;
			$mpdf->WriteHTML($html);
            $mpdf->Output('Order-'.$page->prefix_id.'.pdf','I');
		}
		else
		{
			abort(404);
		}
    }

	function bulkExport(Request $request)
    {
		if($request->get('d'))
		{
			$dates = explode('-', $request->get('d'));
			if(count($dates) == 2)
			{
				$page = Orders::where('created' , '>=', date( 'Y-m-d 00:00:00', strtotime( str_replace('/', '-', trim($dates[0]) ) ) ) )
					->where('created' , '<=', date( 'Y-m-d 23:59:59', strtotime( str_replace('/', '-', trim($dates[1]) ) ) ))
					->orderBy('id', 'asc')
					->limit(5000)->get();
				$pdfHtml = '';
				if($page->count() > 0)
				{
					$mpdf = new \Mpdf\Mpdf([
						'tempDir' => public_path('/uploads/mpdf'),
						'mode' => 'utf-8',
						'orientation' => 'P',
						'format' => [210, 297],
						'setAutoTopMargin' => true,
						'margin_left' => 10, 'margin_right' => 10, 'margin_top' => 10, 'margin_bottom' => 10
					]);
					
					foreach($page as $index => $order)
					{
						$where = ['order_products.order_id' => $order->id];
						$listing = OrderProductRelation::getListing($request, $where);
						$html = view(
							"admin/orders/pdf", 
							[
								'page' => $order,
								'listing' => $listing
							]
						)->render();

						if ($index > 0) {
							$mpdf->AddPage();
						}
						$mpdf->WriteHTML($html);
					}
					$mpdf->Output('Orders '.$request->get('d').'.pdf','I');
				}
			}
			
		}
		abort(404);
    }

    function edit(Request $request, $id)
    {
    	if(!Permissions::hasPermission('orders', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
    	$page = Orders::get($id);
    	if($page)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();
				unset($data['_token']);
				$data['productsData'] = json_decode($data['productsData'], true);
				$productData = [];
				$productData = $data['productsData'];
	    		$validator = Validator::make(
		            $data,
					[
						'customer_id' => ['required', Rule::exists(User::class,'id')],
						'product_id' => ['required', 'array'],
						'product_id.*' => ['required', Rule::exists(Products::class, 'id')->where(function ($query) {
							$query->where('status', 1)->whereNull('deleted_at');
						})],
						'booking_date' => ['required', 'date'],
						'booking_time' => ['required', 'after_or_equal:today'],
						'manual_address' => ['nullable','boolean'],
						'address' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:255'],
						// 'state' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:40'],
						// 'city' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:30'],
						// 'area' => ['exclude_if:manual_address,false','required_if:manual_address,true','string','max:40'],
						'address_id' => ['exclude_if:manual_address,true','required_if:manual_address,false',Rule::exists(Addresses::class,'id')],
						// 'payment_type' => ['required'], 
						'coupon_code_id' => ['nullable', Rule::exists(Coupons::class, 'id')->where(function ($query) {
							$query->where('status', 1)->whereNull('deleted_at');
						})],
						'subtotal' => ['required', 'numeric'],
						'discount' => ['required', 'numeric'],
						'tax' => ['required', 'numeric'],
						'total_amount' => ['required', 'numeric'],
						'productsData' => ['required', 'array'],
						'productsData.*.id' => ['required', Rule::exists(Products::class, 'id')->where(function ($query) {
							$query->where('status', 1)->whereNull('deleted_at');
						})],
						'productsData.*.quantity' => ['required', 'integer', 'min:1'],
					]
		        );
		        if(!$validator->fails())
				{  
					unset($data['product_id']);
					unset($data['productsData']);
					$formattedDateTime = date('Y-m-d H:i:s', strtotime($request->get('booking_date')));
					$data['booking_date'] = $formattedDateTime;
					$data['created_by_admin'] = true;
					$customerId = $request->input('customer_id');
					$user = User::find($customerId);
					if($user){
						$data['customer_name'] = $user->first_name . ' ' . $user->last_name; 
					}
					if (!$data['manual_address']) {
						$address = Addresses::where('id', $data['address_id'])->first();
						if($address){
							$data['address'] = $address->address; 
							$data['city'] = $address->city; 
							$data['state'] = $address->state; 
							$data['area'] = $address->area; 
							$input['latitude'] = $address->latitude;
							$input['longitude'] = $address->longitude;
						}
					}
					$order = Orders::modify($id,$data);
					if($order)
					{
						if (!empty($productData)) {
							Orders::handleProducts($order->id, $productData);
						}
						$request->session()->flash('success', trans('ORDER_UPDATED'));
						return Response()->json([
							'status' => true,
							'message' => trans('ORDER_UPDATED'),
							'id' => $order->id
						]);
					}
					else
					{
						return Response()->json([
							'status' => false,
							'message' => 'Order could not be saved. Please try again.'
						], 400);
					}
				}
			    else
			    {
					return Response()->json([
						'status' => false,
						'message' => current(current($validator->errors()->getMessages()))
					], 400);
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
			$productCategories = ProductCategories::with(['products' => function ($query) {
				$query->where('status', 1);
			}])
			->where('status', 1)
			->get(['id', 'title']);
			$address = Addresses::getAll(
				[
					'addresses.id',
					'addresses.address',
					'addresses.city',
					'addresses.area',
					'addresses.state',
				],
				[
				]
			);
			$coupons = Coupons::getAll(
				[
					'coupons.id',
					'coupons.title',
					'coupons.is_percentage',
					'coupons.amount',
				],
				[
					'status' => 1, 
				]
			);
			$staff = Staff::getAll(
				[
					'staff.id',
					'staff.first_name',
					'staff.last_name',
					'staff.status',
				],
				[
				],
				'concat(staff.first_name, staff.last_name) desc'
			);
			$cgst = Settings::get('cgst');
			$sgst = Settings::get('sgst');
			return view("admin/orders/add", [
    			'page' => $page,
				'users' => $users,
				'productCategories' => $productCategories,
				'address' => $address,
				'coupons' => $coupons,
				'paymentType' => Orders::getStaticData()['paymentType'],
				'staff' => $staff,
				'cgst' => $cgst,
				'sgst' => $sgst
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('orders', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$admin = Orders::find($id);
    	if($admin->delete())
    	{
    		$request->session()->flash('success', 'Order deleted successfully.');
    		return redirect()->route('admin.orders');
    	}
    	else
    	{
    		$request->session()->flash('error', 'Order could not be delete.');
    		return redirect()->route('admin.orders');
    	}
    }

	function bulkActions(Request $request, $action)
	  {
	    if (($action != 'delete' && !Permissions::hasPermission('diary_pages', 'update')) || ($action == 'delete' && !Permissions::hasPermission('diary_pages', 'delete'))) {
	      $request->session()->flash('error', 'Permission denied.');
	      return redirect()->route('admin.dashboard');
	    }
	    $ids = $request->get('ids');
	    if (is_array($ids) && !empty($ids)) {
			switch ($action) {
				case 'delete':
					Orders::whereIn('id', $ids)->delete();
					$message = count($ids) . ' records have been deleted.';
					break;
				default:
					$diaryPage = new Orders();
					$statusLabel = Orders::getStaticData()['status'][$action]['label'];
					
					if ($statusLabel !== null) {
						foreach ($ids as $diaryPageId) {
							Orders::modify($diaryPageId, [
								'status' => $action,
								'status_by' => AdminAuth::getLoginId(),
							]);
							$diaryPage->logStatusHistory($action, $diaryPageId);

							$order = Orders::select(['id', 'customer_id', 'prefix_id', 'status'])->where('id', $diaryPageId)->limit(1)->first();
							if($order)
							{
								
								
							}
						}
						$message = count($ids) . ' records status have been marked as ' . $statusLabel . '.';
					} else {
						$message = 'Invalid action.';
					}
				break;
			}
	      $request->session()->flash('success', $message);
	      return Response()->json([
	        'status' => 'success',
	        'message' => $message,
	      ], 200);
	    } else {
	      return Response()->json([
	        'status' => 'error',
	        'message' => 'Please select atleast one record.',
	      ], 200);
	    }
	  }

	function switchStatus(Request $request, $field, $id)
	{
		if (!Permissions::hasPermission('orders', 'update')) {
		$request->session()->flash('error', 'Permission denied.');
		return redirect()->route('admin.dashboard');
		}
		$data = $request->toArray();
		$validator = Validator::make(
		$request->toArray(),
		[
			'flag' => 'required'
		]
		);
		if (!$validator->fails()) {
		$order = Orders::find($id);
		if($order){
			$updated = $order->updateStatusAndLogHistory($field, $request->get('flag'));
			$order = Orders::find($id);
			$phone = preg_replace('/\D/', '', $order->customer_phone);
			if(!$phone && $order->customer && $order->customer->phonenumber)
			{
				$phone = preg_replace('/\D/', '', $order->customer->phonenumber);
			}
			if($phone){
				$sent = \App\Libraries\SMSGateway::send($phone, str_replace('{order_id}', $order->prefix_id , Orders::getStatuses($order->status)['sms_message']));
			}

			$codes = [
				'{order_id}' => $order->prefix_id,
				'{subject}' => Orders::getStatuses($order->status)['message'] . ' - ' . $order->prefix_id, 
				'{status_message}' => str_replace('{order_id}', $order->prefix_id , Orders::getStatuses($order->status)['sms_message']), 
			];
			$email = $order->email ? $order->email : ($order->customer ? $order->customer->phonenumber : null);
			General::sendTemplateEmail($email, 'order-status-change', $codes);
		}
		if ($updated) {
			return Response()->json([
			'status' => 'success',
			'message' => 'Record updated successfully.'
			]);
		} else {
			return Response()->json([
			'status' => 'error',
			'message' => 'Record could not be update.'
			]);
		}
		} else {
		return Response()->json([
			'status' => 'error',
			'message' => 'Record could not be update.'
		]);
		}
	}
	
	public function getStatuses()
	{
		return response()->json(Orders::getStaticData()['status']);
	}

	public function getAddress($customerId)
	{
		$addresses = Addresses::select(['id','address'])->whereUserId($customerId)->get();
	
		return response()->json([
			'status' => true,
			'addresses' => $addresses,
		]);
	}

	function selectStaff(Request $request, $id)
	{
		if (!Permissions::hasPermission('orders', 'update')) {
			$request->session()->flash('error', 'Permission denied.');
			return redirect()->route('admin.dashboard');
		}
	
		$data = $request->toArray();
		$validator = Validator::make(
			$request->toArray(),
			[
				'staff_id' => ['required', Rule::exists(Staff::class, 'id')],
			]
		);
		
		if (!$validator->fails()) {
			$order = Orders::find($id);
			if ($order) {
				$oldStaffId = $order->staff_id;
				if(!$oldStaffId){
					$updated = Orders::where('id', $id)->update([
						'staff_id' => $data['staff_id'],
					]);
					$order = $order->fresh();
					
				} else{
					$updated = Orders::where('id', $id)->update([
						'staff_id' => $data['staff_id'],
					]);
					$order = $order->fresh();
					
					if ($oldStaffId != $data['staff_id']) {
						$oldStaff = Staff::find($oldStaffId);
						if ($oldStaff) {
							// General::sendTemplateEmail($oldStaff->email, 'order-unassigned', $codes);
							// General::sendTemplateEmail($order->customer->email, 'staff-reassigned', $codes);
						}
						$newStaff = Staff::find($data['staff_id']);
						// if ($newStaff) {
						// 	General::sendTemplateEmail($newStaff->email, 'staff-reassigned', $codes);
						// }
					}
				}
				if ($updated) {
					$order->logStaffHistory($order->staff_id, $id);
					$request->session()->flash('success', 'Staff assigned successfully.');
					
				} else {
					$request->session()->flash('error', 'Staff could not be assigned successfully.');
					
				}
			} 
			else {
				$request->session()->flash('error', trans('ORDER_NOT_FOUND'));
			}
		} else {
			$request->session()->flash('error', 'Please provide valid inputs.');
		}
		return redirect()->route('admin.orders.view', ['id' => $order->id]);
	}

	function updateField(Request $request, $id)
	{
		if (!Permissions::hasPermission('orders', 'update')) {
		$request->session()->flash('error', 'Permission denied.');
		return redirect()->route('admin.dashboard');
		}
		$data = $request->toArray();
		$validator = Validator::make(
		$request->toArray(),
		[
			'fieldName' => 'required',
			'value' => 'required',
		]
		);
		if (!$validator->fails()) {
		$order = Orders::find($id);
			if($order){
				$updated = $order->updateFieldAndLogHistory($request->get('fieldName'), $request->get('value'));
				if ($updated) {
					return Response()->json([
					'status' => true,
					'message' => 'Record updated successfully.'
					],200);
				} else {
					return Response()->json([
					'status' => false,
					'message' => 'Record could not be update.'
					],200);
				}
			}
		} else {
		return Response()->json([
			'status' => false,
			'message' => 'Record could not be update.'
		]);
		}
	}

	public function ship(Request $request, $id)
	{
		if($request->get('ship'))
		{
			$shipOption = $request->get('options');
			$PFELENDPOINT = "https://expresslink.parcelforce.net/ws";
			$PFELUSERNAME = "EL_WDMEOQK";
			$PFELPWD = "zzj033se";
			$PFELWDMONLINEURL = "https://www.parcelforce.net/";
			$PFELWDMONLINEUSERNAME = "WDMEOQK";
			$PFELWDMONLINEPWD = "7v5cqsc6";
			
			$PFWSDLNAMESPACE = "http://www.parcelforce.net/ws/ship/V14";
			$PFELDPTNO = "1";
			$PFELDPTNAME = "Pinders Schoolwear";
			$PFELCONTRCTNO = "K271462";
			$PFELACCOUNTNO = "PIN003001";
			$date = date('Y-m-d');

			$order = Orders::find($id);
			
			$address = substr($order->address, 0, 40);
			$xml = <<<EOL
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v14="http://www.parcelforce.net/ws/ship/v14">
			<soapenv:Header/>
			<soapenv:Body>
				<v14:CreateShipmentRequest>
					<v14:Authentication>
						<v14:UserName>$PFELUSERNAME</v14:UserName>
						<v14:Password>$PFELPWD</v14:Password>
					</v14:Authentication>
					<v14:RequestedShipment>
						<v14:DepartmentId>$PFELDPTNO</v14:DepartmentId>
						<v14:ShipmentType>DELIVERY</v14:ShipmentType>
						<v14:ContractNumber>$PFELCONTRCTNO</v14:ContractNumber>
						<v14:ServiceCode>$shipOption</v14:ServiceCode>
						<v14:ShippingDate>$date</v14:ShippingDate>
						<v14:RecipientContact>
						<v14:BusinessName>{$order->company}</v14:BusinessName>
						<v14:ContactName>{$order->first_name} {$order->last_name}</v14:ContactName>
						<v14:EmailAddress>{$order->customer_email}</v14:EmailAddress>
						<v14:Telephone></v14:Telephone>
						<v14:MobilePhone>$order->customer_phone</v14:MobilePhone>
						<v14:Notifications>
							<v14:NotificationType>EMAIL</v14:NotificationType>
							<v14:NotificationType>EMAILATTEMPTDELIVERY</v14:NotificationType>
							<v14:NotificationType>SMSDAYOFDESPATCH</v14:NotificationType>
							<v14:NotificationType>SMSSTARTOFDELIVERY</v14:NotificationType>
							<v14:NotificationType>SMSATTEMPTDELIVERY</v14:NotificationType>
						</v14:Notifications>
						</v14:RecipientContact>
						<v14:RecipientAddress>
						<v14:AddressLine1>{$address}</v14:AddressLine1>
						<v14:AddressLine2>{$order->area}</v14:AddressLine2>
						<v14:AddressLine3></v14:AddressLine3>
						<v14:Town>{$order->city}</v14:Town>
						<v14:Postcode>{$order->postcode}</v14:Postcode>
						<v14:Country>GB</v14:Country>
						</v14:RecipientAddress>
						<v14:TotalNumberOfParcels>{$request->get('parcel')}</v14:TotalNumberOfParcels>
						<v14:Enhancement>
						<v14:SaturdayDeliveryRequired>false</v14:SaturdayDeliveryRequired>
						</v14:Enhancement>
						<v14:ReferenceNumber1>SW-{$order->prefix_id}</v14:ReferenceNumber1>
						<v14:SpecialInstructions1></v14:SpecialInstructions1>
						<v14:ConsignmentHandling>false</v14:ConsignmentHandling>
					</v14:RequestedShipment>
				</v14:CreateShipmentRequest>
			</soapenv:Body>
			</soapenv:Envelope>
EOL;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
			curl_setopt($ch, CURLOPT_URL, $PFELENDPOINT);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$data = curl_exec($ch);
			if (curl_errno($ch)) {
				throw new \Exception(curl_error($ch));
			}
			curl_close($ch);

			$XMLReader = new \XMLReader();
			$XMLReader->XML($data);
			$XMLReader->read();
			$XMLReader->read();

			$xml_shipment_info = $XMLReader->readInnerXml();
			$XMLReader->close();

			$xml_label = simplexml_load_string($xml_shipment_info);
			$json_label = json_encode($xml_label);
			$jsonArrayLabel = json_decode($json_label, TRUE);
			if( isset($jsonArrayLabel['CompletedShipmentInfo']['CompletedShipments']['CompletedShipment']['ShipmentNumber']) && $jsonArrayLabel['CompletedShipmentInfo']['CompletedShipments']['CompletedShipment']['ShipmentNumber'] )
			{
				$trackingNumber = $jsonArrayLabel['CompletedShipmentInfo']['CompletedShipments']['CompletedShipment']['ShipmentNumber'];
				$order->status = 'shipped';
				$order->parcels = $request->get('parcel');
				$order->shipment_tracking = $trackingNumber;
				$order->shipping_gateway = 'parcelforce';
				$order->save();

				$message = "Your order $order->prefix_id is completed and dispatched. Please track your order with $trackingNumber on this link [tracking link]. Thank you. <a href=\"https://www.pindersworkwear.com\">pindersworkwear.com</a>";
				$phone = preg_replace('/\D/', '', $order->customer_phone);
				if(!$phone && $order->customer && $order->customer->phonenumber)
				{
					$phone = preg_replace('/\D/', '', $order->customer->phonenumber);
				}
				if($phone){
					$sent = \App\Libraries\SMSGateway::send($phone, $message);
				}
				return Response()->json([
					'status' => true,
					'message' => "Shipping information submitted to service provider.",
					'trackingNumber' => $trackingNumber,
					'parcels' => $request->get('parcel')
				]);
			}
			else if($jsonArrayLabel && isset($jsonArrayLabel['Alerts']) && isset($jsonArrayLabel['Alerts']['Alert']))
			{
				$errors = Arr::pluck($jsonArrayLabel['Alerts']['Alert'], 'Message');
				$errors = array_values(array_filter($errors));

				$order->status = 'shipped';
				$order->shipment_tracking = '123456';
				$order->shipping_gateway = 'parcelforce';
				$order->delivery_at = 'Post';
				$order->parcels = $request->get('parcel');
				$order->delivery_cost = Settings::get('shipping_cost_parcelforce');
				$order->total_amount = $order->subtotal + $order->logo_cost - $order->discount + $order->delivery_cost + $order->tax + $order->logo_tax; 
				$order->save();

				return Response()->json([
					'status' => true,
					'message' => "Shipping information submitted to service provider.",
					'trackingNumber' => '123456',
					'parcels' => $order->parcels,
					'cost' => $order->delivery_cost
				]);

				return Response()->json([
					'status' => false,
					'message' => $errors ? implode("<br />", $errors) : 'Shipment could not be submitted to provider. Please try again or contact us.'
				]);
			}

			
		}
		else
		{
			return Response()->json([
				'status' => false,
				'message' => 'Please select atlerase one record to ship.'
			]);
		}
	} 

    public function exportOrders()
    {
        // Retrieve unique SKU numbers
        $skuNumbers = Products::select(['id', 'title', 'sku_number'])->orderBy('products.sku_number');

        $statuses = Orders::getStatuses();
		$categories = ProductCategories::select(['id', 'title'])->orderBy('title', 'asc')->get();
		$brands = Brands::select(['id', 'title'])->orderBy('title', 'asc')->get();
            
        $sizes = Sizes::select(['id','size_title', 'type'])->orderBy('size_title')->get();
		$colors = Colours::select(['id', 'title', 'code'])->orderBy('title')->get();

        // Return the view with all necessary data
        return view("admin/orders/export", [
            'skuNumbers' => $skuNumbers,
            'statuses' => $statuses,
            'categories' => $categories,
            'brands' => $brands,
            'sizes' => $sizes,
            'colors' => $colors,
        ]);
    }

	public function exportLogos()
    {
        // Retrieve unique SKU numbers
        $skuNumbers = Products::select(['id', 'title', 'sku_number'])->orderBy('products.sku_number');

        $statuses = Orders::getStatuses();
		$categories = ProductCategories::select(['id', 'title'])->orderBy('title', 'asc')->get();
		$brands = Brands::select(['id', 'title'])->orderBy('title', 'asc')->get();
            
        $sizes = Sizes::select(['id','size_title'])->orderBy('size_title')->get();
		$colors = Colours::select(['id', 'title', 'code'])->orderBy('title')->get();

        // Return the view with all necessary data
        return view("admin/orders/exportLogos", [
            'skuNumbers' => $skuNumbers,
            'statuses' => $statuses,
            'categories' => $categories,
            'brands' => $brands,
            'sizes' => $sizes,
            'colors' => $colors,
        ]);
    }

    function export(Request $request)
    {
        $data = $request->toArray();

        // Start building the query
        $query = OrderProductRelation::join('products', 'order_products.product_id', '=', 'products.id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->leftJoin('brands', 'order_products.brands_id', '=', 'brands.id')
            ->select([
                'products.sku_number as sku',
                'products.title as product_title',
                'order_products.id as id',
                'order_products.color',
                'order_products.size_title as size',
                'orders.created as selling_date',
                'orders.prefix_id as invoiceID',
                'orders.status',
               'product_categories.title as category',
               'brands.title as brand',
                DB::raw("IFNULL(JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')), null) as logo"),
				// 'products.purchase_price',
				'order_products.quantity',
				'order_products.amount as selling_price',
				DB::raw('(order_products.quantity * order_products.amount) as sale_price'),
				'order_products.logo_data',
				DB::raw('(order_products.quantity * order_products.amount) as total_price'),
            ]);

        if (!empty($data['skuNumber'])) {
            $query->whereIn('order_products.product_id', $data['skuNumber']);
        }

        if (!empty($data['colors'])) {
            $c = $data['colors'];
            $query->where(function($q) use ($c){
				foreach($c as $v){
					$q->orWhere('order_products.color', 'LIKE', $v);
				}
				return $q;
			});
        }

        if (!empty($data['sizes'])) {
            $query->whereIn('order_products.size_id', $data['sizes']);
        }

        if (!empty($data['statuses'])) {
            $query->whereIn('orders.status', $data['statuses']);
        }

        if (!empty($data['categories'])) {
            $query->whereIn('products.category_id', $data['categories']);
        }

        if (!empty($data['brands'])) {
			$brands = $data['brands'];
			$query->where(function($q) use ($brands) {
				foreach($brands as $b)
				{
					$q->orWhereRaw('FIND_IN_SET(?, order_products.brands_id)', [$b]);
				}
				return $query;
			});
        }

        if (!empty($data['selling_date_from']) && !empty($data['selling_date_to'])) {
            $fromDate = min($data['selling_date_from'], $data['selling_date_to']);
            $toDate = max($data['selling_date_from'], $data['selling_date_to']);
            $query->whereBetween(DB::raw('DATE(orders.created)'), [$fromDate, $toDate]);
        }

		if($request->has('without_logo') && $data['without_logo'] != '')
		{
			if($data['without_logo'] == '1')
				$query->whereRaw("(JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')) is null or JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')) = 'null')");
			else
				$query->whereRaw("(JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')) is not null and JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')) != 'null')");
		}

        $listing = $query->orderBy('order_products.id', 'desc')->get();
        // Set headers for CSV download
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=orders_export.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Generate CSV
        $callback = function () use ($listing) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['SKU', 'Name', 'Color', 'Size', 'Selling Date', 'Invoice No', 'Category', 'Brand','Logo','Status',
                                    'Quantity', 'Selling Price', 'Sub Total', 'Logo Price', 'Total Price']);

            foreach ($listing as $row) 
			{
				$logoPrice = null;
				$logoData = $row->logo_data ? (substr($row->logo_data, 0, 1) == '{' ? json_decode('['.$row->logo_data.']') : json_decode($row->logo_data)) : null;
				if($logoData)
				{
					foreach($logoData as $ld)
					{
						if(!($ld->text || $ld->image || $ld->category || $ld->postion)) continue;
						if(isset($ld->price) && $ld->price > 0) {
							$logoPrice += $ld->price * $row->quantity;
						}
					}
				}
                fputcsv($file, [
                    $row->sku ?? '',
                    $row->product_title ?? '',
                    $row->color ?? '',
                    $row->size ?? '',
                    $row->selling_date ?? '',
                    $row->invoiceID ?? '',
                    isset($row->category) ? $row->category :'',
                    // $row->invoice_no ?? '',
                    $row->brand ?? '',
                    isset($row->logo) ? $row->logo :'',
                    Ucwords($row->status ?? ''),
                    // number_format($row->purchase_price ?? 0, 2),
                    number_format($row->quantity ?? 0, 2),
                    number_format($row->selling_price ?? 0, 2),
                    number_format($row->sale_price ?? 0, 2),
                    $logoPrice ? number_format($logoPrice ?? 0, 2) : '',
                    number_format($row->total_price+($logoPrice ? $logoPrice : 0) ?? 0, 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

	function exportLogoReport(Request $request)
    {
        $data = $request->toArray();

        // Start building the query
        $query = OrderProductRelation::join('products', 'order_products.product_id', '=', 'products.id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->leftJoin('brands', 'order_products.brands_id', '=', 'brands.id')
            ->select([
                'products.sku_number as sku',
                'products.title as product_title',
                'order_products.id as id',
                'order_products.color',
                'order_products.size_title as size',
                'orders.created as selling_date',
                'orders.prefix_id as invoiceID',
                'orders.status',
               'product_categories.title as category',
               'brands.title as brand',
                DB::raw("IFNULL(JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')), null) as logo"),
				// 'products.purchase_price',
				'order_products.quantity',
				'order_products.amount as selling_price',
				DB::raw('(order_products.quantity * order_products.amount) as sale_price'),
				'order_products.logo_data',
				DB::raw('(order_products.quantity * order_products.amount) as total_price'),
            ]);

        if (!empty($data['skuNumber'])) {
            $query->whereIn('order_products.product_id', $data['skuNumber']);
        }

        if (!empty($data['colors'])) {
            $c = $data['colors'];
            $query->where(function($q) use ($c){
				foreach($c as $v){
					$q->orWhere('order_products.color', 'LIKE', $v);
				}
				return $q;
			});
        }

        if (!empty($data['sizes'])) {
            $query->whereIn('order_products.size_id', $data['sizes']);
        }

        if (!empty($data['statuses'])) {
            $query->whereIn('orders.status', $data['statuses']);
        }

        if (!empty($data['categories'])) {
            $query->whereIn('products.category_id', $data['categories']);
        }

        if (!empty($data['brands'])) {
			$brands = $data['brands'];
			$query->where(function($q) use ($brands) {
				foreach($brands as $b)
				{
					$q->orWhereRaw('FIND_IN_SET(?, order_products.brands_id)', [$b]);
				}
				return $query;
			});
        }

        if (!empty($data['selling_date_from']) && !empty($data['selling_date_to'])) {
            $fromDate = min($data['selling_date_from'], $data['selling_date_to']);
            $toDate = max($data['selling_date_from'], $data['selling_date_to']);
            $query->whereBetween(DB::raw('DATE(orders.created)'), [$fromDate, $toDate]);
        }

		$query->whereRaw("(JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')) is not null and JSON_UNQUOTE(JSON_EXTRACT(order_products.logo_data, '$[0].category')) != 'null')");

		
        $listing = $query->orderBy('order_products.id', 'desc')->get();

        // Set headers for CSV download
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=orders_export.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Generate CSV
        $callback = function () use ($listing) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['SKU', 'Name', 'Color', 'Size', 'Selling Date', 'Invoice No', 'Category', 'Brand','Order Status', 'Logo Type', 'Logo Position',
                                    'Quantity', 'Logo Price', 'Total Price']);

            foreach ($listing as $row) 
			{
				$logoPrice = null;
				$logoData = $row->logo_data ? (substr($row->logo_data, 0, 1) == '{' ? json_decode('['.$row->logo_data.']') : json_decode($row->logo_data)) : null;
				if($logoData)
				{
					foreach($logoData as $ld)
					{
						if(!($ld->text || $ld->image || $ld->category || $ld->postion)) continue;
						fputcsv($file, [
							$row->sku ?? '',
							$row->product_title ?? '',
							$row->color ?? '',
							$row->size ?? '',
							$row->selling_date ?? '',
							$row->invoiceID ?? '',
							isset($row->category) ? $row->category :'',
							// $row->invoice_no ?? '',
							$row->brand ?? '',
							Ucwords($row->status ?? ''),
							$ld->category,
							$ld->postion,
							$row->quantity,
							number_format($ld->price ?? 0, 2),
							number_format(($row->quantity*$ld->price) ?? 0, 2)
						]);
					}
				}
                
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
