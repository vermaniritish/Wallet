<?php
/**
 * API Auth Class
 *
 * @package    AuthController
 
 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Admin\Settings;
use App\Models\API\Admins;
use App\Models\API\AdminAuth;
use App\Models\API\Users;
use App\Models\API\ApiAuth;
use App\Models\API\UsersWishlist;
use App\Models\API\Products;
use App\Libraries\General;
use App\Libraries\Facebook;
use App\Libraries\Google;

class AuthController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

	function authState(Request $request)
	{
		$data = $request->toArray();
		$token = isset($data['token']) && $data['token'] ? $data['token'] : null;
        $user = Users::select(['id', 'first_name', 'phonenumber'])->where('token', $token)
            ->where('token_expiry', '>', date('Y-m-d H:i'))
            ->whereNotNull('token_expiry')
            ->where('status', 1)
            ->limit(1)
            ->first();
        if ($user) {
            return Response()->json(['status' => true]);
        }
		else {
			return Response()->json(['status' => false]);
		}
	}

	// function signup(Request $request)
	// {
	// 	$allowed = ['email', 'password', 'first_name', 'last_name', 'device_id', 'device_type', 'device_name', 'fcm_token'];
    // 	if($request->has($allowed))
    // 	{
    // 		$validator = Validator::make(
	//             $request->toArray(),
	//             [
	//             	'first_name' => 'required',
	//                 'device_type' => 'required',
	//                 'device_id' => Rule::requiredIf(function () use ($request) {
	// 			        return $request->get('device_type') != 'web';
	// 			    }),
	// 			    'device_name' => Rule::requiredIf(function () use ($request) {
	// 			        return $request->get('device_type') != 'web';
	// 			    }),
	// 			    'fcm_token' => Rule::requiredIf(function () use ($request) {
	// 			        return $request->get('device_type') != 'web';
	// 			    }),
	// 			    'email' => [
	// 			    	'required',
	// 			    	'email',
	// 			    	Rule::unique('users')->whereNull('deleted_at'),
	// 			    ],
	//                 'password' => [
	//                 	'required',
	// 				    'min:8',
	//                 ],
	//             ]
	//         );

	//         if(!$validator->fails())
	//         {
	//         	$password = $request->get('password');

	//         	$user = [
	//         		'first_name' => $request->get('first_name'),
	//         		'last_name' => $request->get('last_name'),
	//         		'email' => $request->get('email'),
	//         		'password' => $request->get('password'),
	//         		'token' => General::hash(64)
	//         	];

	//         	if($user = Users::create($user))
	//         	{
    //     			$link = Settings::get('website_url') . '/email-verification/' . $user['token'];
    //     			$codes = [
    //     				'{first_name}' => $user->first_name,
    //     				'{last_name}' => $user->last_name,
    //     				'{email}' => $user->email,
    //     				'{password}' => $password,
    //     				'{verification_link}' => General::urlToAnchor($link)
    //     			];

    //     			General::sendTemplateEmail(
    //     				$user->email,
    //     				'registratadmin,
    //     				$codes
    //     			);

    //     			if(Settings::get('direct_login_after_registration'))
    //     			{
    //     				$user = ApiAuth::makeLoginSession($request, $user);
    //     				if($user)
    //     				{
    //     admin	return Response()->json([
	// 					    	'status' => true,
	// 					  adminmessage' => 'Registration successfully! An email having verification link has sent to your email address. Please verify.',
	// 					    	'user' => $user
	// 		admin   ]);
    //     admin}
    //     adminelse
    //     				{
    //     					return Response()->json([
	// 					    	'status' => false,
	// 					    	'message' => 'Unable to register new user. Please try again.'
	// 					  admin 400);
    //     				}
    //     			}
    //     			else
    //     			{
    //     				return Response()->json([
	// 				    	'status' => true,
	// 				   adminessage' =>admingistration successfully! An email having verification link has sent to your email address. Please verify.',
	// 				    ]);
    //     			}
	//         	}
	//         	else
	//         	{
	//         		return Response()->json([
	// 			 admin'status' => false,
	// 			    	'message' => 'Unable to register new user. Please try again.'
	// 			    ], 400);
	//         	}
	// 	    }
	// 	    else
	// 	    {
	// 	    	return Response()->json([
	// 		    	'status' => false,
	// 		    	'message' => current( current( $validator->errors()->getMessages() ) )
	// 		    ], 400);
	// 	    }
	//     }
	//     else
	//     {
	//     	return Response()->json([
	// 	    	'status' => false,
	// 	    	'message' => 'Some of inputs are invalid in request.',
	// 	    ], 400);
	//     }
	// }

	function signup(Request $request)
	{
		// pr($request->all()); die;
		$allowed = ['email', 'password', 'first_name', 'last_name', 'device_id', 'device_type', 'device_name', 'fcm_token'];
    	if($request->has($allowed))
    	{
    		$validator = Validator::make(
	            $request->toArray(),
	            [
	            	'first_name' => 'required',
	                'device_type' => 'required',
	                'device_id' => Rule::requiredIf(function () use ($request) {
				        return $request->get('device_type') != 'web';
				    }),
				    'device_name' => Rule::requiredIf(function () use ($request) {
				        return $request->get('device_type') != 'web';
				    }),
				    'fcm_token' => Rule::requiredIf(function () use ($request) {
				        return $request->get('device_type') != 'web';
				    }),
				    'email' => [
				    	'required',
				    	'email',
				    	Rule::unique('users')->whereNull('deleted_at'),
				    ],
	                'password' => [
	                	'required',
					    'min:8',
	                ],
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	$password = $request->get('password');

	        	$admin = [
	        		'first_name' => $request->get('first_name'),
	        		'last_name' => $request->get('last_name'),
	        		'email' => $request->get('email'),
	        		'password' => $request->get('password'),
	        		'token' => General::hash(64)
	        	];

	        	if($admin = Admin::create($admin))
	        	{
        			$link = Settings::get('website_url') . '/email-verification/' . $admin['token'];
        			$codes = [
        				'{first_name}' => $admin->first_name,
        				'{last_name}' => $admin->last_name,
        				'{email}' => $admin->email,
        				'{password}' => $password,
        				'{verification_link}' => General::urlToAnchor($link)
        			];

        			General::sendTemplateEmail(
        				$admin->email,
        				'registration',
        				$codes
        			);

        			if(Settings::get('direct_login_after_registration'))
        			{
        				$admin = ApiAuth::makeLoginSession($request, $admin);
        				if($admin)
        				{
        					return Response()->json([
						    	'status' => true,
						    	'message' => 'Registration successfully! An email having verification link has sent to your email address. Please verify.',
						    	'admin' => $admin
						    ]);
        				}
        				else
        				{
        					return Response()->json([
						    	'status' => false,
						    	'message' => 'Unable to register new user. Please try again.'
						    ], 400);
        				}
        			}
        			else
        			{
        				return Response()->json([
					    	'status' => true,
					    	'message' => 'Registration successfully! An email having verification link has sent to your email address. Please verify.',
					    ]);
        			}
	        	}
	        	else
	        	{
	        		return Response()->json([
				    	'status' => false,
				    	'message' => 'Unable to register new user. Please try again.'
				    ], 400);
	        	}
		    }
		    else
		    {
		    	return Response()->json([
			    	'status' => false,
			    	'message' => current( current( $validator->errors()->getMessages() ) )
			    ], 400);
		    }
	    }
	    else
	    {
	    	return Response()->json([
		    	'status' => false,
		    	'message' => 'Some of inputs are invalid in request.',
		    ], 400);
	    }
	}

	public function login(Request $request)
	{
	    if (!$request->isMethod('post')) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Invalid request method.'
	        ], 405);
	    }

	    // Validate request inputs
	    $validator = Validator::make($request->all(), [
	        'email'    => 'required|email',
	        'password' => 'required'
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'message' => $validator->errors()->first()
	        ], 400);
	    }

	    // Attempt login
	    $user = AdminAuth::attemptLogin($request);

	    if (!$user) {
	        return response()->json([
	            'status' => false,
	            'message' => "The credentials you've entered don't match any account."
	        ], 400);
	    }

	    // Make login session or token
	    $sessionUser = AdminAuth::makeLoginSession($request, $user);

	    if (!$sessionUser) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Session could not be established. Please try again.'
	        ], 500);
	    }

	    // ⭐ Successful API Login Response
	    return response()->json([
	        'status'  => true,
	        'message' => 'Login successful.',
	        'data'    => $sessionUser  // yaha aap token ya user detail bhi bhej sakte ho
	    ], 200);
	}

    function secondAuth(Request $request, $token)
    {
    	$user = ApiAuth::getRow([
			'token LIKE ?' => [
				$token
			],
			'status' => 1
		]);

		if(!$user)
		{
			return Response()->json([
			    	'status' => false,
			    	'message' => 'User is missing.',
			    ], 400);
		}

    	if($request->get('otp'))
    	{
    		$otp = $request->get('otp');
			if($user->otp == $otp)
			{
				$user->otp = null;
				$user->token = General::hash(64);
				$user->token_expiry = date('Y-m-d', strtotime('+1 Month'));
				if($user->save())
				{
					if($user)
		        	{
		        		return Response()->json([
					    	'status' => true,
					    	'message' => 'Login successfully',
					    	'token' => $user->token,
							'user' => [
								'name' => $user->first_name,
								'address' => $user->address,
								'token' => $user->token
							]
					    ]);
		        	}
		        	else
		        	{
		        		return Response()->json([
					    	'status' => false,
					    	'message' => 'Session could not be establised. Please try again.',
					    ]);
		        	}
				}
				else
				{
					return Response()->json([
				    	'status' => false,
				    	'message' => 'User could not be saved. Please try again.',
				    ]);
				}
			}
			else
			{
				return Response()->json([
				    	'status' => false,
				    	'message' => 'Your OTP is incorrect.',
				    ]);
			}
    	}
    	else
    	{
    		return Response()->json([
			    	'status' => false,
			    	'message' => 'Please enter your one time password.',
			    ]);
    	}
    }

    function socialLogin(Request $request)
    {	
    	$allowed = ['first_name', 'last_name', 'email', 'social_id', 'social_type', 'social_email', 'device_id', 'device_type', 'device_name', 'fcm_token', 'token'];
    	if($request->has($allowed))
    	{
    		$validator = Validator::make(
	            $request->toArray(),
	            [
	            	'first_name' => 'required',
	            	'social_id' => 'required',
	            	'social_type' => 'required',
	                // 'email' => 'email',
	                'device_type' => 'required',
	                'token' => 'required',
	                'device_id' => Rule::requiredIf(function () use ($request) {
				        return $request->get('device_type') != 'web';
				    }),
				    'device_name' => Rule::requiredIf(function () use ($request) {
				        return $request->get('device_type') != 'web';
				    }),
				    'fcm_token' => Rule::requiredIf(function () use ($request) {
				        return $request->get('device_type') != 'web';
				    }),
	            ]
	        );

	        if(!$validator->fails())
	        {
	        	$data = $request->toArray();
	        	$verify = null;
	        	$post = [];
	        	if($data['social_type'] == 'facebook')
	        	{
	        		$verify = Facebook::verifyLogin($data['token']);
	        	}
	        	elseif($data['social_type'] == 'google')
	        	{
	        		$verify = Google::verifyLogin($data['token']);
	        	}

	        	if($verify)
		        {
		        	$user = ApiAuth::where("{$data['social_type']}_id", $request->get('social_id'))
							->where('status', 1)
							->first();
			    	if($user) 
			        {
			        	$user = ApiAuth::makeLoginSession($request, $user);
			        	if($user)
			        	{
			        		$user->wishlist = UsersWishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
			        		return Response()->json([
						    	'status' => true,
						    	'message' => 'Login successfully',
						    	'user' => $user
						    ]);
			        	}
			        	else
			        	{
			        		return Response()->json([
						    	'status' => false,
						    	'message' => 'Session could not be establised. Please try again.',
						    ], 400);
			        	}
			        }
			        else 
			        {
			        	if($data['social_type'] == 'facebook')
			        	{
			        		$info = Facebook::getInfo($data['token']);
			        		$data['social_email'] = $info && $info->getId() ? $info->getEmail() : null;
			        	}

			        	$post = [
			        		'first_name' => $data['first_name'],
			        		'last_name' => $data['last_name'],
			        		'email' => $data['social_email'],
			        		'verified_at' => date('Y-m-d H:i:s')
			        	];

			        	if(isset($data['social_type']) && $data['social_type'] == 'facebook')
			        	{
			        		$post['facebook_id'] = $data['social_id'];
			        	}
			        	elseif(isset($data['social_type']) && $data['social_type'] == 'google')
			        	{
			        		$post['google_id'] = $data['social_id'];
			        		$post['google_email'] = $data['social_email'];
			        	}
			        	$post['verified_at'] = 1;
			        	
			        	$user = null;
			        	if($data['social_email'])
			        	{
			        		$exist = Users::where('email', 'LIKE', $data['social_email'])->pluck('id')->first();
			        		if($exist)
			        		{
			        			unset($post['first_name']);
			        			unset($post['last_name']);
			        			$user = Users::modify($exist, $post);
			        		}
			        		else
			        		{
			        			$user = Users::create($post);
			        		}
			        	}
			        	else
			        	{
			        		$user = Users::create($post);
			        	}

			        	if($user)
			        	{
			        		$user = ApiAuth::find($user->id);
				        	$user = ApiAuth::makeLoginSession($request, $user);
				        	if($user)
				        	{
				        		if($request->get('wishlist_product'))
			        			{
			        				$exist = UsersWishlist::select(['id'])
			        						->where('product_id', $request->get('wishlist_product'))
			        						->where('user_id', $user->id)
			        						->first();
			        				if(!$exist)
			        				{
			        					UsersWishlist::create($request->get('wishlist_product'), $user->id);
			        				}
			        			}

				        		$user->wishlist = UsersWishlist::where('user_id', $user->id)->pluck('product_id')->toArray();

				        		return Response()->json([
							    	'status' => true,
							    	'message' => 'Login successfully',
							    	'user' => $user
							    ]);
				        	}
				        	else
				        	{
				        		return Response()->json([
							    	'status' => false,
							    	'message' => 'Session could not be establised. Please try again.',
							    ], 400);
				        	}
				        }
				        else
				        {
				        	return Response()->json([
						    	'status' => false,
						    	'message' => 'Session could not be establised. Please try again.',
						    ], 400);
				        }
			        }
			    }
			    else
		        {
		        	return Response()->json([
					    	'status' => false,
					    	'message' => 'Could not able to login. Please try again.',
					    ], 400);
		        }
		    }
		    else
		    {
		    	return Response()->json([
			    	'status' => false,
			    	'message' => current( current( $validator->errors()->getMessages() ) )
			    ], 400);
		    }
	    }
	    else
	    {
	    	return Response()->json([
		    	'status' => false,
		    	'message' => 'Some of inputs are invalid in request.',
		    ], 400);
	    }
    }

    function forgotPassword(Request $request)
    {
    	$allowed = ['email'];
    	if($request->has($allowed))
	    {
	    	if($request->get('email'))
	    	{
	    		$email = $request->get('email');
	    		$user = Admins::getRow([
	    			'email LIKE ?' => [$email],
	    			'status' => 1
	    		]);

	    		if($user)
	    		{
	    			$user->token = General::hash(64);
	    			if($user->save())
	    			{
	    				$codes = [
	        				'{first_name}' => $user->first_name,
	        				'{last_name}' => $user->last_name,
	        				'{email}' => $user->email,
	        				'{recovery_link}' => Settings::get('website_url') . '/recover-password/' . $user->token
	        			];

	        			// General::sendTemplateEmail(
	        			// 	$user->email,
	        			// 	'forgot-password',
	        			// 	$codes
	        			// );

	    				return Response()->json([
						    	'status' => true,
						    	'message' => 'We have sent you a recovery link on your email. Please follow the email.'
						    ]);
	    			}
	    			else
	    			{
	    				return Response()->json([
						    	'status' => false,
						    	'message' => 'Something went wrong. Please try again.',
						    ], 400);
	    			}
			    	
	    		}
	    		else
	    		{
	    			return Response()->json([
					    	'status' => false,
					    	'message' => 'Email is not register with us.',
					    ], 400);
	    		}
	    	}
	    	else
	    	{
	    		return Response()->json([
				    	'status' => false,
				    	'message' => 'Please enter your register email to recover password.',
				    ], 400);
	    	}
	    }
	    else
	    {
	    	return Response()->json([
		    	'status' => false,
		    	'message' => 'Some of inputs are invalid in request.',
		    ], 400);
	    }
    }

    function recoverPassword(Request $request, $hash)
    {
    	$user = Users::getRow([
    			'token like ?' => [$hash]
    		]);

    	if($user)
    	{
    		if($request->isMethod('post'))
	    	{
		    	$allowed = ['new_password', 'confirm_password'];
		    	if($request->has($allowed))
			    {
			    	
		    		$data = $request->toArray();

		            $validator = Validator::make(
			            $request->toArray(),
			            [
			                'new_password' => [
			                	'required',
							    'min:8'
			                ],
			                'confirm_password' => [
			                	'required',
							    'min:8'
			                ]
			            ]
			        );

			        if(!$validator->fails())
			        {
		        		if($data['new_password'] && $data['confirm_password'] && $data['new_password'] == $data['confirm_password'])
		        		{
		        			$user->password = $data['new_password'];
		        			$user->token = null;
		        			if($user->save())
		        			{
			    				return Response()->json([
							    	'status' => true,
							    	'message' => 'Password updated successfully. Login with new credentials to proceed.'
							    ], 200);
		        			}
		        			else
		        			{
		        				return Response()->json([
							    	'status' => false,
							    	'message' => 'New password could be updated.'
							    ], 400);
		        			}
		        		}
		        		else
		        		{
		        			return Response()->json([
							    	'status' => false,
							    	'message' => 'Password did not match.'
							    ], 400);
		        		}
				    }
				    else
				    {
				    	return Response()->json([
						    	'status' => false,
						    	'message' => current( current( $validator->errors()->getMessages() ) )
						    ], 400);
				    }
				
				}
				else
				{
					return Response()->json([
				    	'status' => false,
				    	'message' => 'Some of inputs are invalid in request.',
				    ], 400);
				}
			}

			return Response()->json([
			    	'status' => true,
			    	'hash' => $hash,
			    ]);
		}
		else
		{
			return Response()->json([
			    	'status' => false,
			    	'message' => 'Please try again to reset your password. This link has expired.',
			    ], 400);
		}
    }

    function emailVerification(Request $request, $hash, $email = null)
    {
    	$user = Admins::getRow([
				'token like ?' => [$hash]
			]);

		if($user)
		{
			// $user->token = null;
			$user->verified_at = date('Y-m-d H:i:s');
			
			if($email)
			{
				$email = General::decrypt($email);
				if($email)
				{
					$user->email = $email;
				}
				else
				{
					return Response()->json([
				    	'status' => false,
				    	'message' => 'Email could not be changed. Something went wrong.'
				    ], 400);
				}
			}

			if($user->save())
			{
				return Response()->json([
			    	'status' => true,
			    	'message' => 'Your email verification has been completed.'
			    ], 200);
			}
			else
			{
				return Response()->json([
			    	'status' => false,
			    	'message' => 'Email could not be verified. The link is expired or used.'
			    ], 400);
			}
		}
		else
		{
			return Response()->json([
			    	'status' => false,
			    	'message' => 'Email could not be verified. The link is expired or used.',
			    ], 400);
		}
    }

    function checkEmailExist(Request $request)
    {
    	$userId = ApiAuth::getLoginId();
    	$exist = false;
    	if($request->get('email'))
    	{
    		$exist = Users::select(['email'])
    			->where('id', '!=', $userId)
    			->where('email', 'LIKE', $request->get('email'))
    			->first();
    		$exist = !empty($exist) ? true : false;
    	}

    	return Response()->json([
		    	'status' => true,
		    	'exist' => $exist
		    ]);
    }

    function logout(Request $request)
    {
    	ApiAuth::logout();
    	return Response()->json([
		    	'status' => true
		    ]);
    }
}
