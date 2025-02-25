<?php
/**
 * Settings Class
 *
 * @package    SettingsController


 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Libraries\General;
use App\Models\Admin\Settings;
use App\Models\Admin\AdminAuth;
use App\Models\Admin\Permissions;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Libraries\FileSystem;

class SettingsController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
		if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.settings');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();

    		$validator = Validator::make(
	            $request->toArray(),
	            [
	                'company_name' => 'required',
	                'company_address' => 'required',
	                'pagination_method' => 'required',
	                'admin_second_auth_factor' => 'required',
	                'currency_code' => 'required',
	                'currency_symbol' => 'required',
					'tax_percentage' => 'required',
	                'admin_notification_email' => [
	                	'required',
	                	'email'
	                ]
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	$logo = null;
	        	if(isset($data['logo']) && $data['logo'])
	        	{
	        		$logo = $data['logo'];
	        	}

	        	$favicon = null;
	        	if(isset($data['favicon']) && $data['favicon'])
	        	{
	        		$favicon = $data['favicon'];
	        	}


	        	// if(isset($data['ignore_keywords']) && $data['ignore_keywords'])
	        	// {
	        	// 	$favicon = $data['favicon'];
	        	// }

	        	unset($data['logo']);
	        	unset($data['favicon']);
	        	unset($data['_token']);

	        	foreach ($data as $key => $value) {
	        		Settings::put($key, $value);
	        	}

	        	if($logo)
	        	{
	        		if(Settings::put('logo', $logo))
	        		{
		        		$originalName = FileSystem::getFileNameFromPath($logo);
		        		FileSystem::resizeImage($logo, $originalName, '400*120');
		        	}
	        	}

	        	if($favicon)
	        	{
	        		if(Settings::put('favicon', $favicon))
	        		{
		        		$originalName = FileSystem::getFileNameFromPath($favicon);
		        		FileSystem::resizeImage($favicon, $originalName, '50*50');
		        	}
	        	}

        		$request->session()->flash('success', 'Settings updated successfully.');
        		return redirect()->route('admin.settings');
			}
			else
			{
				$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			}
		}

		return view("admin/settings/index", []);
	}

	function recaptcha(Request $request)
    {
		if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.settings');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		$validator = Validator::make(
	            $request->toArray(),
	            [
	                'admin_recaptcha' => 'required',
	                'recaptcha_key' => 'required',
	                'recaptcha_secret' => 'required'
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	unset($data['_token']);
	        	foreach ($data as $key => $value) {
	        		Settings::put($key, $value);
	        	}

        		$request->session()->flash('success', 'Recaptcha settings updated.');
        		return redirect()->route('admin.settings');
			}
			else
			{
				$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			}
		}
		else
		{
			abort(404);
		}
	}

	function email(Request $request)
    {
		if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.settings');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		$validator = Validator::make(
	            $request->toArray(),
	            [
	                'email_method' => 'required',
	                'from_email' => 'required',
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	unset($data['_token']);

	        	$password = null;
	        	if(isset($data['smtp_password']) && $data['smtp_password'])
	        	{
	        		$password = $data['smtp_password'];
	        	}
	        	unset($data['smtp_password']);

	        	foreach($data as $key => $value) {
	        		Settings::put($key, $value);
	        	}

	        	if(isset($password) && $password)
	        	{
	        		$password = General::encrypt($password);
	        		Settings::put('smtp_password', $password);
	        	}

        		$request->session()->flash('success', 'Password updated successfully.');
        		return redirect()->route('admin.settings');
			}
			else
			{
				$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
			}
		}
		else
		{
			abort(404);
		}
	}

	function dateTimeFormats(Request $request)
	{
		if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		$validator = Validator::make(
	            $request->toArray(),
	            [
	                'date_format' => 'required',
	                'time_format' => 'required'
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	unset($data['_token']);

	        	foreach ($data as $key => $value) {
	        		Settings::put($key, $value);
	        	}

	        	$request->session()->flash('success', 'Date and time format updated.');
        		return redirect()->route('admin.settings');
			}
			else
			{
				$request->session()->flash('error', 'Please provide valid inputs.');
			    return redirect()->back()->withErrors($validator)->withInput();
			}
		}
		else
		{
			abort(404);
		}
	}

	function home(Request $request)
    {
		if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.settings');
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();

    		$validator = Validator::make(
	            $request->toArray(),
	            [
	                'home_page_text' => 'required',
	                'home_page_search_text' => 'required',
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	$banner = null;
	        	if(isset($data['home_page_banner']) && $data['home_page_banner'])
	        	{
	        		$banner = $data['home_page_banner'];
	        	}

	        	unset($data['home_page_banner']);
	        	unset($data['_token']);

	        	foreach ($data as $key => $value) {
	        		Settings::put($key, $value);
	        	}

	        	if($banner)
	        	{
	        		if(Settings::put('home_page_banner', $banner))
	        		{
		        		$originalName = FileSystem::getFileNameFromPath($banner);
		        		FileSystem::resizeImage($banner, $originalName, '612*378');
		        	}
	        	}

        		$request->session()->flash('success', 'Settings updated successfully.');
        		return redirect()->route('admin.settings.home');
			}
			else
			{
				$request->session()->flash('error', 'Please provide valid inputs.');
			    return redirect()->back()->withErrors($validator)->withInput();
			}
		}

		return view("admin/settings/home", []);
	}

	function footerLinks(Request $request)
    {
		if(!AdminAuth::isAdmin())
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.settings');
    	}

    	if($request->isMethod('post'))
    	{
    		$allowed = ['blog_link', 'privacy_policy_link', 'terms_of_service_link', 'support_link'];
	    	if($request->has($allowed))
	    	{
	    		$data = $request->toArray();

	    		$validator = Validator::make(
		            $request->toArray(),
		            [
		                'blog_link' => 'required',
		                'privacy_policy_link' => 'required',
		                'terms_of_service_link' => 'required',
		                'support_link' => 'required'
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	foreach ($data as $key => $value) {
		        		Settings::put($key, $value);
		        	}

	        		$request->session()->flash('success', 'Settings updated successfully.');
	        		return redirect()->route('admin.settings.footerLinks');
				}
				else
				{
					$request->session()->flash('error', 'Please provide valid inputs.');
				    return redirect()->back()->withErrors($validator)->withInput();
				}
			}
			else
		    {
			    $request->session()->flash('error', 'Some of inputs are invalid in request.');
				return redirect()->back()->withInput();
		    }
		}


		return view("admin/settings/footerLinks", []);
	}

	function freeDelivery(Request $request)
    {
		if(!Permissions::hasPermission('offers', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->back();
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		$validator = Validator::make(
	            $request->toArray(),
	            [
					'free_delivery' => 'required',
					'min_cart_price' => [Rule::requiredIf(fn () => $request->free_delivery > 0), 'nullable', 'numeric', 'min:1'],
	            ]
	        );
	        if(!$validator->fails())
	        {
	        	unset($data['_token']);
	        	Settings::put('free_delivery', $request->free_delivery ? json_encode([
					'min_cart_price' => $request->min_cart_price
				]) : null);

        		$request->session()->flash('success', 'Free delivery offer updated.');
        		return redirect()->back();
			}
			else
			{
				$request->session()->flash('error', current( current( $validator->errors()->getMessages() ) ));
			    return redirect()->back()->withErrors($validator)->withInput();
			}
		}
		else
		{
			abort(404);
		}
	}

	function freeLogo(Request $request)
    {
		if(!Permissions::hasPermission('offers', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->back();
    	}

    	if($request->isMethod('post'))
    	{
    		$data = $request->toArray();
    		$validator = Validator::make(
	            $request->toArray(),
	            [
					'free_logo' => 'required',
					'quantity' => [Rule::requiredIf(fn () => $request->quantity > 0), 'nullable', 'min:1'],
					'min_cart_price' => [Rule::requiredIf(fn () => $request->free_logo > 0), 'nullable', 'numeric', 'min:1'],
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	unset($data['_token']);
	        	Settings::put('free_logo_discount', $request->free_logo ? json_encode([
					'min_cart_price' => $request->min_cart_price,
					'quantity' => $request->quantity
				]) : null);

        		$request->session()->flash('success', 'Free delivery offer updated.');
        		return redirect()->back();
			}
			else
			{
				$request->session()->flash('error', current( current( $validator->errors()->getMessages() ) ));
			    return redirect()->back()->withErrors($validator)->withInput();
			}
		}
		else
		{
			abort(404);
		}
	}

    public function oneTimeSetupCost(Request $request)
    {
        $data = $request->toArray();
        $validator = Validator::make(
            $request->toArray(),
            [
                'one_time_setup_cost' => 'required|numeric',
            ]
        );

        if(!$validator->fails())
        {
            Settings::put('one_time_setup_cost',$data['one_time_setup_cost']);
            $request->session()->flash('success', 'One time setup cost updated.');
            return redirect()->back();

        }else{
            $request->session()->flash('error', current( current( $validator->errors()->getMessages() ) ));
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
