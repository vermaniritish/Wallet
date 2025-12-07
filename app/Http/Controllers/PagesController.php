<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Admin\Orders;
use App\Models\Admin\Pages;
use App\Models\Admin\Users;
use App\Libraries\General;
use App\Models\Admin\ContactUs;
use App\Models\Admin\ProductCategories;
use App\Models\Admin\Shops;
use App\Models\Admin\Faqs;
use App\Models\Admin\OrderProductRelation;
use App\Models\Admin\ProductSubCategoryRelation;
use App\Models\Admin\Settings;
use App\Models\Admin\Addresses;
use App\Models\Admin\Schools;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class PagesController extends BaseController
{
    public function aboutUs(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'about-us')->limit(1)->first();
        return view('frontend.page', ['page' => $page]);
    }

    public function faqs(Request $request)
    {
        $faqs = Faqs::where('status', 1)->get();
        return view('frontend.faq', ['faqs' => $faqs]);
    }
    
    public function cart(Request $request) 
    {
        return view('frontend.cart', ['page' => null]);
    }

    public function checkout(Request $request) 
    {
        $user = $request->session()->get('user');
        $shops = Shops::select(['name'])->where('allow_pickup', 1)->where('status', 1)->get();
        return view('frontend.checkout.index', [
            'page' => null,
            'user' => $user,
            'address' => $user && $user->id ? Addresses::where('user_id', $user->id)->limit(1)->first() : null,
            'shops' => $shops,
            'settings' => [
                'shipping_cost_parcelforce' => Settings::get('shipping_cost_parcelforce'),
                'shipping_cost_dpd' => Settings::get('shipping_cost_dpd'),
                'shipping_parcelforce' => Settings::get('shipping_parcelforce'),
                'shipping_dpd' => Settings::get('shipping_dpd'),
            ]
        ]);
    }

    public function searchAddresses(Request $request)
    {
        $search = $request->get('search');
        $user = $request->session()->get('user');
        $addresses = Addresses::where('user_id', $user->id)
            ->where(function($q) use ($search) {
                return $q->orWhere('title', 'LIKE', '%'.$search.'%')
                    ->orWhere('address', 'LIKE', '%'.$search.'%')
                    ->orWhere('area', 'LIKE', '%'.$search.'%')
                    ->orWhere('city', 'LIKE', '%'.$search.'%')
                    ->orWhere('state', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();
        
        return Response()->json([
            'status' => true,
            'addresses' => $addresses
        ]);
    }

    public function searchSchools(Request $request)
    {
        $search = $request->get('search');
        $schools = Schools::select(['id', 'schooltype', 'name'])->where('status', 1)
            ->where(function($q) use ($search) {
                $search = explode(' ', $search);
                foreach($search as $a){
                    $q->orWhere('name', 'LIKE', '%'.$a.'%');
                }
                return $q;
            })
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($school) {
                $school->slug = Str::slug($school->name) . '-' . $school->id;
                return $school;
            });
        
        return Response()->json([
            'status' => true,
            'schools' => $schools
        ]);
    }

    public function myAccount(Request $request) 
    {
        $user = Users::find($request->session()->get('user')->id);
        return view('frontend.account.index', [
            'user' => $user,
            'screen' => 'dashboard'
        ]);
    }

    public function myOrders(Request $request) 
    {
        $user = Users::find($request->session()->get('user')->id);
        $orders = Orders::where(function($query) use ($user) {
            return $query->orWhere('customer_id', $user->id)
                    ->orWhere('customer_email', 'LIKE', $user->email);
        })->select([
            'orders.id', 'orders.prefix_id', 'orders.created', 'orders.status', 'orders.total_amount', 'orders.paid',
            DB::raw('GROUP_CONCAT(order_products.shipment_tracking) as shipment')
        ])
        ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        ->orderBy('id', 'desc')->groupBy('orders.id')->limit(3000)->get();
        return view('frontend.account.index', [
            'user' => $user,
            'orders' => $orders,
            'screen' => 'orders'
        ]);
    }

    public function editAccount(Request $request) 
    {
        $user = Users::find($request->session()->get('user')->id);
        
        if($request->isMethod('post'))
        {
            $data = $request->toArray();
            unset($data['_token']);
            $validator = Validator::make(
                $data,
                    [
                        'first_name' => 'required',
                        'last_name' => 'required',
                    ]
            );
            if(!$validator->fails())
            {
                if(Users::modify($user->id, $data))
                {
                    $user = Users::find($user->id);
                    $request->session()->put('user', $user);
                    $request->session()->flash( 'success', "Your profile has been updated.");
    		        return redirect()->back();
                }
                else
                {
                    $request->session()->flash( 'error', "Profile could not be saved." );
    		        return redirect()->back();
                }
            }
            else
            {
                $request->session()->flash( 'error', current(current($validator->errors())) );
    		    return redirect()->back();
            }
        }
        return view('frontend.account.index', [
            'user' => $user,
            'screen' => 'account'
        ]);
    }
    
    function contactUs(Request $request)
    {
        $shops = Shops::select(['name', 'address','postcode', 'lat', 'lng'])->where(['status' => 1])->get();
    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		unset($data['_token']);
    		$validator = Validator::make(
	            $request->toArray(),
	            [
	                'firstname' => ['required'],
					'number' => ['required'],
					'email' => ['required'],
					'subject' => ['required'],
					'message' => ['required'],
	            ]
	        );
	        if(!$validator->fails())
	        {
	        	$page = ContactUs::create($data);
	        	if($page)
	        	{
                    $userData = [
                        '{name}' => $data['firstname'],
                        '{email}' => $data['email'],
                    ];
                    General::sendTemplateEmail($data['email'], 'thank-you-for-contacting', $userData);
                    $adminData = [
                        '{name}' => $data['firstname'],
                        '{email}' => $data['email'],
                        '{number}' => $data['number'],
                        '{message}' => $data['message'],
                    ];
                    $adminEmail = Settings::get('admin_notification_email');
                    if($adminEmail)
                    {
                        General::sendTemplateEmail($adminEmail, 'admin-contact-us-request-received', $adminData);
                    }
	        		$request->session()->flash('success', 'Thankyou for contacting us. We will get back to you soon.');
    		        return redirect()->route('contactUs');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Contact Us request could not be send. Please try again.');
    		        return redirect()->route('contactUs');
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
		    }
		}
        return view('frontend.contactUs', [
            'shops' => $shops
        ]);
    }

    function invoice(Request $request, $id)
    {
        $order = Orders::where('prefix_id', $id)->limit(1)->first();
        if($order)
        {
            $where = ['order_products.order_id' => $order->id];
            $listing = OrderProductRelation::getListing($request, $where);
            $logo = Settings::get('logo');
            $html = view('frontend.invoice', ['order' => $order, 'listing' => $listing, 'logo' => $logo])->render();
            $mpdf = new \Mpdf\Mpdf([
                'tempDir' => public_path('/uploads'),
                'mode' => 'utf-8', 
                'orientation' => 'P',
                'format' => [210, 297],
                'setAutoTopMargin' => true,
                'margin_left' => 0,'margin_right' => 0,'margin_top' => 0,'margin_bottom' => 0,'margin_header' => 0,'margin_footer' => 0
            ]);
                
            $mpdf->WriteHTML($html);
            $mpdf->Output('Order-'.$order->prefix_id.'.pdf', 'D');
        }
        else
        {
            abort('404');
        }
    }

    function trackOrder(Request $request)
    {
        $data = $request->toArray();
        $user = Users::find($request->session()->get('user')->id);
        $order = null;
        if($data)
        {
            $order = Orders::select(['parcels', 'shipment_tracking', 'shipping_gateway'])
                ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
                ->where(function($q) use ($data) {
                    return $q->where('prefix_id', 'LIKE', trim($data['orderid']))
                        ->orWhere('orders.customer_email', 'LIKE', $data['email'])
                        ->orWhere('users.email', 'LIKE', $data['email']);
                })->limit(1)->first();
        }
        
        return view('frontend.account.index', [
            'user' => $user,
            'order' => $order,
            'screen' => 'track-order'
        ]);
    }

    function addresses(Request $request)
    {
        $data = $request->toArray();
        $user = Users::find($request->session()->get('user')->id);
        $address = Addresses::where('user_id', $user->id)->limit(1)->first();

        if($request->isMethod('post'))
        {
            $data = $request->toArray();
            unset($data['_token']);
            $validator = Validator::make(
                $data,
                    [
                        'title' => 'required',
                        'address' => 'required',
                        'area' => 'required',
                        'city' => 'required',
                        'postcode' => 'required',
                    ]
            );
            if(!$validator->fails())
            {
                if(Addresses::modify($address->id, $data))
                {
                    $request->session()->flash( 'success', "Address updated.");
    		        return redirect()->back();
                }
                else
                {
                    $request->session()->flash( 'error', "Address could not be saved." );
    		        return redirect()->back();
                }
            }
            else
            {
                $request->session()->flash( 'error', current(current($validator->errors())) );
    		    return redirect()->back();
            }
        }
        
        return view('frontend.account.index', [
            'user' => $user,
            'address' => $address,
            'screen' => 'address'
        ]);
    }

    function customPage(Request $request, $slug)
    {
        $page = Pages::where('slug', 'LIKE', $slug)->where('status', 1)->limit(1)->first();
        if($page)
            return view('frontend.page', ['page' => $page]);
        else
            abort(404);
    }


    function personalization(Request $request)
    {
        $categories = ProductCategories::select(['id', 'slug', 'image', 'title'])->where('status', 1)->get();
        return view('frontend.personalization', [
            'categories' => $categories
        ]);
    }

    function fetchSubCategories(Request $request, $id)
    {
        $subcategories = ProductSubCategoryRelation::distinct()->select(['sub_categories.id', 'sub_categories.category_id', 'product_categories.slug as cat_slug', 'sub_categories.slug', 'sub_categories.image', 'sub_categories.title'])
            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_sub_category_relation.category_id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'product_sub_category_relation.sub_category_id')
            ->where('product_sub_category_relation.category_id', $id)->whereNotNull('product_sub_category_relation.sub_category_id')
            ->get();

        return Response()->json([
            'status' => true,
            'subcategories' => $subcategories
        ]);
    }
}