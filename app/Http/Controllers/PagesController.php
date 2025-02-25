<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Admin\Orders;
use App\Models\Admin\Pages;
use App\Models\Admin\Users;
use App\Libraries\General;
use App\Models\Admin\ContactUs;
use App\Models\Admin\OrderProductRelation;
use App\Models\Admin\Settings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class PagesController extends BaseController
{
    public function aboutUs(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'about-us')->limit(1)->first();
        return view('frontend.page', ['page' => $page]);
    }

    public function faqs(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'faqs')->limit(1)->first();
        return view('frontend.page', ['page' => $page]);
    }
    
    public function termsConditions(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'terms-conditions')->limit(1)->first();
        return view('frontend.page', ['page' => $page]);
    }

    public function privacyPolicy(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'privacy-policy')->limit(1)->first();
        return view('frontend.page', ['page' => $page]);
    }

    public function deliveryInformation(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'delivery-information')->limit(1)->first();
        return view('frontend.page', ['page' => $page]);
    }

    public function returnPolicy(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'return-policy')->limit(1)->first();
        return view('frontend.page', ['page' => $page]);
    }

    public function cart(Request $request) 
    {
        return view('frontend.cart', ['page' => null]);
    }

    public function checkout(Request $request) 
    {
        $user = $request->session()->get('user');
        return view('frontend.checkout.index', [
            'page' => null,
            'user' => $user
        ]);
    }

    public function myAccount(Request $request) 
    {
        $user = Users::find($request->session()->get('user')->id);
        return view('frontend.account.index', [
            'user' => $user
        ]);
    }

    public function myOrders(Request $request) 
    {
        $user = Users::find($request->session()->get('user')->id);
        $orders = Orders::where(function($query) use ($user) {
            return $query->orWhere('customer_id', $user->id)
                    ->orWhere('email', 'LIKE', $user->email);
        })->select([
            'orders.id', 'orders.prefix_id', 'orders.created', 'orders.status', 'orders.total_amount', 'orders.paid',
            DB::raw('GROUP_CONCAT(order_products.shipment_tracking) as shipment')
        ])
        ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        ->orderBy('id', 'desc')->groupBy('orders.id')->limit(3000)->get();
        return view('frontend.account.orders', [
            'user' => $user,
            'orders' => $orders
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
                        'name' => 'required',
                        'address' => 'required',
                    ]
            );
            if(!$validator->fails())
            {
                $data['first_name'] = $data['name'];
                unset($data['name']);
                if(Users::modify($user->id, $data))
                {
                    $user = Users::find($user->id);
                    $request->session()->put('user', $user);
                    $request->session()->flash( 'success', "Your profile has been updated. Thank You!" );
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

        return view('frontend.account.edit', [
            'user' => $user
        ]);
    }
    
    function contactUs(Request $request)
    {
        $page = Pages::where('slug', 'LIKE', 'contact-us')->limit(1)->first();
    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		unset($data['_token']);
    		$validator = Validator::make(
	            $request->toArray(),
	            [
	                'firstname' => ['required'],
	                'lastname' => 'required',
					'number' => ['required'],
					'email' => ['required'],
					'message' => ['required'],
	            ]
	        );
	        if(!$validator->fails())
	        {
	        	$page = ContactUs::create($data);
	        	if($page)
	        	{
                    $userData = [
                        '{first_name}' => $data['firstname'],
                        '{last_name}' => $data['lastname'],
                        '{email}' => $data['email'],
                    ];
                    General::sendTemplateEmail($data['email'], 'thank-you-for-contacting', $userData);
                    $adminData = [
                        '{first_name}' => $data['firstname'],
                        '{last_name}' => $data['lastname'],
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
        return view('frontend.contactUs', ['page' => $page]);
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
}