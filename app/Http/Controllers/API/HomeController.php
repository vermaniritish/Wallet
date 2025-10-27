<?php
/**
 * Home Class
 *
 * @package    HomeController
 
 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Admin\Settings;
use App\Libraries\General;
use App\Libraries\PositionStack;
use App\Models\API\SearchSugessions;
use App\Models\API\Products;
use App\Models\API\ApiAuth;
use App\Models\API\UsersMatches;
use App\Models\API\ProductCategories;
use App\Models\Admin\Offers;
use App\Models\Admin\ProductCategoryRelation;
use App\Models\Admin\SearchKeywords;
use Illuminate\Support\Facades\DB;
use App\Libraries\FileSystem;
use App\Models\Admin\OrderProductRelation;
use App\Models\Admin\Orders;
use App\Models\Admin\Users;
use App\Models\Admin\ProductSizeRelation;
use Illuminate\Support\Arr;

class HomeController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

	function productsListing(Request $request)
	{
		$where = ['products.status' => 1];
		if($request->get('school_id'))
			$where['products.school_id'] = $request->get('school_id');
		else
			$where[] = 'products.parent_id is null';
		
		$products = Products::getListing($request, $where);
		$items = $products->items();
		foreach($items as $k => $v)
		{
			$items[$k]->colors_count = ProductSizeRelation::select(['color_id'])->where('product_id', $v->id)->groupBy('color_id')->pluck('color_id')->toArray();
		}
		return Response()->json([
			'status' => true,
			'products' => $items,
			'page' => $products->currentPage(),
			'counter' => $products->perPage(),
			'count' => $products->total(),
			'maxPage' => ceil($products->total()/$products->perPage()),
			'paginationMessage' => "We found <strong class=\"text-brand\">{$products->total()}</strong> items for you!",
			'count' => $request->get('page') && $request->get('page') < 2 ? [
				'menCount' => Products::getCount($request, $where, "Male"),
				'womenCount' => Products::getCount($request, $where, "Female"),
				'kidsCount' => Products::getCount($request, $where, "Kids"),
				'unisexCount' => Products::getCount($request, $where, "Unisex"),
			]: null

		]);
	}

	function productDetails(Request $request, $slug)
	{
		$userId = ApiAuth::getLoginId();
		$product = Products::getBySlug($slug);
		if($product)
		{
			$product->description = nl2br($product->description);
			$product->user_image = $product->users && $product->users->image ? $product->users->image : null;
			$catIds = $product->categories()->pluck('category_id')->toArray();
			$similarProducts = [];
			if(!empty($catIds))
			{
				$productIds = ProductCategoryRelation::whereIn('category_id', $catIds)
					->where('product_id', '!=', $product->id)
					->groupBy('product_id')
					->pluck('product_id')
					->toArray();
				if(!empty($productIds))
				{
					$where = ["products.id in (".implode(',', array_unique($productIds)).")", "products.status" => 1, 'sold' => 0];
					$similarProducts = Products::getListing($request, $where);
					if($userId)
					{
						foreach ($similarProducts as $key => $value) {
							$similarProducts[$key]->match_id = UsersMatches::where('user_id', $userId)->where('product_id', $value->id)->pluck('id')->first();
							$similarProducts[$key]->user_image = FileSystem::getAllSizeImages($value->user_image);
						}
					}
				}
			}
			
			$respond = DB::select('SELECT AVG(TIMESTAMPDIFF(MINUTE, created, read_at)) as response_seconds from messages where to_id = ' . $product->user_id . ' and read_at is not null;');
			$respond = $respond && $respond[0] && $respond[0]->response_seconds ? $respond[0]->response_seconds : null;
			return Response()->json([
		    	'status' => true,
	    		'product' => $product,
	    		'similar_products' => $similarProducts,
	    		'respond' => $respond
		    ]);
		}
		else
		{
			return Response()->json([
		    	'status' => false,
	    		'message' => 'Not Found!'
		    ], 400);
		}
	}

	function createBooking(Request $request)
	{
		$data = $request->toArray();
		$validator = Validator::make($data, [
			'first_name'   => ['required'],
			'last_name'    => ['required'],
			'address'      => ['required'],
			'postalcode'   => ['required'],
			'cart'         => ['required', 'array'],
			'cart.*.quantity' => ['required', 'integer', 'min:1'],
		]);

		// ✅ If saveInfo = true, then email, phone, password are required
		$validator->sometimes(['email', 'phone', 'password'], 'required', function ($input) {
			return !empty($input->saveInfo) && $input->saveInfo === true;
		});

		// ✅ If ship_different_address = true, then shipping fields are required
		$validator->sometimes(['ship_fname', 'ship_address1', 'ship_state', 'ship_zip'], 'required', function ($input) {
			return !empty($input->ship_different_address) && $input->ship_different_address === true;
		});

		if(!$validator->fails())
		{
			$user = null;
			if($request->get('token')) {
				$userId = General::decrypt($request->get('token'));
				$user = Users::select(['id', 'email', 'phonenumber'])->where('id', $userId)->limit(1)->first();
			}

			if($request->get('saveInfo'))
			{
				$user = Users::select(['id', 'email', 'phonenumber'])->where(function($q) use($request) {
					return $q->where('email', 'LIKE', $request->get('email'))
							->orWhere('phonenumber', 'LIKE', $request->get('phone'));
				})->limit(1)->first();
				if($user){
					return Response()->json(['status' => false, 'message' => 'User having same email and phone is already registered with us. Please login to place order.']);
				}

				$password = $request->get('password');

	        	$user = [
	        		'first_name' => $request->get('first_name'),
	        		'last_name' => $request->get('last_name'),
	        		'email' => $request->get('email'),
	        		'password' => $request->get('password'),
	        		'token' => General::hash(64)
	        	];

	        	if($user = Users::create($user))
	        	{
        			$link = url('/email-verification/' . $user['token']);
        			$codes = [
        				'{first_name}' => $user->first_name,
        				'{last_name}' => $user->last_name,
        				'{email}' => $user->email,
        				'{password}' => $password,
        				'{verification_link}' => General::urlToAnchor($link)
        			];

        			General::sendTemplateEmail(
        				$user->email,
        				'registration',
        				$codes
        			);
				}
				else
				{
					return Response()->json(['status' => false, 'message' => 'Something went wrong. Please try again or contact us.']);
				}
			}

			$order = new Orders();
			$order->customer_id = $user ? $user->id : null;
			$order->first_name = $request->get('first_name');
			$order->last_name = $request->get('last_name');
			$order->company = $request->get('company');
			$order->customer_email = $request->get('email');
			$order->customer_phone = $request->get('phone');

			if($order->customer_email || $order->customer_phone)
			{
				if($request->ship_different_address)
				{
					$order->ship_address1 = $request->get('ship_address1');
					$order->ship_address2 = $request->get('ship_address2');
					$order->ship_city = $request->get('ship_city');
					$order->ship_company = $request->get('ship_company');
					$order->ship_fname = $request->get('ship_fname');
					$order->ship_lname = $request->get('ship_lname');
					$order->ship_state = $request->get('ship_state');
					$order->ship_zip = $request->get('ship_zip');
					$order->ship_different_address = 1;
				}

				$order->address = $data['address'];
				$order->area = ($data['address2'] ? $data['address2'] : null);
				$order->city = $data['city'];
				$order->postcode = $data['postalcode'];
				$order->coupon = isset($data['coupon']) && $data['coupon'] ? json_encode($data['coupon']) : null;
				$order->status = 'pending';
				$order->created = date('Y-m-d H:i:s');

				if($request->get('lastId'))
				{
					Orders::where('id', $request->get('orderId'))->where('status', 'pending')->where('paid', 0)->limit(1)->orderBy('id', 'desc')->delete();
				}
				if($order->save())
				{
					$order->prefix_id = 'PSW-'.(Settings::get('order_prefix') + $order->id);
					$order->save();

					$products = [];
					$discount = 0;
					$subtotal = 0;
					$margin = 0;
					$includeTravelCharges = 0;
					foreach($data['cart'] as $c)
					{
						$logo = isset($c['customization']) && $c['customization'] ? $c['customization'] : [];
						$products[] = [
							'order_id' => $order->id,
							'product_id' => $c['product_id'],
							'size_id' => $c['id'],
							'size_title' => $c['size_title'] . ' ' . $c['from_cm'] . ' - ' . $c['to_cm'],
							'sku_number' => isset($c['sku_number']) && $c['sku_number'] ? $c['sku_number'] : null,
							'color' => $c['color'],
							'product_title' => $c['title'],
							'product_description' => $c['size_title'] . ' ' . $c['from_cm'] . ' - ' . $c['to_cm']."\nChest:" . (isset($c['chest']) && $c['chest'] ? $c['chest'] : '') ."Waist: ".(isset($c['waist']) && $c['waist'] ? $c['waist'] : '')." Hip:".(isset($c['hip']) && $c['hip'] ? $c['hip'] : ''),
							'amount' => $c['price'],
							'quantity' => $c['quantity'],
							'logo_data' => json_encode($logo)
						];

						$subtotal += $this->offerPrice($c)['price'];
					}

					if($products)
					{
						OrderProductRelation::insert($products);
						
						$logoDetailing = $this->calculateLogoCost($data['cart']);
						// $oneTimeCost = $logoDetailing['haveLogo'] ? Settings::get('one_time_setup_cost') : 0;
						$oneTimeCost = 0;
						
						/* Delivery Case */
						// ----------------- Important
						// $freeDelivery = Settings::get('free_delivery');
						// $freeDelivery = $freeDelivery ? json_decode($freeDelivery, true) : null;
						// $order->free_delivery = $freeDelivery && $subtotal >= $freeDelivery['min_cart_price'] ? 1 : 0;
						// ----------------- Important
						$order->free_delivery = 0;
						$order->delivery_cost = isset($data['shipping']) && $data['shipping'] ? $data['shipping'] : 0;
						/* Delivery Case */
						$order->subtotal = $subtotal;	
						$order->logo_cost = $logoDetailing['cost'];
						$order->logo_discount = $logoDetailing['logoDiscount'];
						$order->logo_discount_applied = $logoDetailing['appliedDiscount'];
						$order->one_time_cost = $oneTimeCost;
						$order->tax_percentage = Settings::get('gst');
						$subtotal += $logoDetailing['cost'];
						if(isset($data['coupon']) && $data['coupon'] && $data['coupon']['is_percentage'] > 0 && $data['coupon']['amount'] > 0) {
							$discount = ($subtotal * $data['coupon']['amount'])/100;
						}
						elseif(isset($data['coupon']) && $data['coupon'] && $data['coupon']['amount'] > 0) {
							$discount = $data['coupon']['amount'] > $subtotal ? $subtotal : $data['coupon']['amount'];
						}
						$order->discount = $discount;
						$order->tax_percentage = Settings::get('gst');
						$order->tax = (($subtotal - $discount) * $order->tax_percentage) / 100;
						$order->total_amount = ($subtotal - $discount) + $order->tax + $order->delivery_cost;
						$order->save();
					}

					return Response()->json([
						'status' => true,
						'orderId' => $order->prefix_id,
						'amount' => $order->total_amount
					]);
				}
				else
				{
					return Response()->json([
						'status' => true
					]);
				}
			}
			else
			{
				return Response()
					->json([
						'status' => false,
						'clear' => true,
						'message' =>  'Please provide a valid email or phone number to make an order.'
					]);	
			}
		}
		else
		{
            return Response()
                ->json([
                    'status' => false,
                    'clear' => true,
                    'message' =>  current( current( $validator->errors()->getMessages() ) )
                ]);
		}
	}

	function addToCart(Request $request)
    {
        $data = $request->toArray();
		$cart = $data['cart'];
		foreach($cart as $k => $c)
		{
			$colorImages = Products::select(['color_images'])->where('id', $c['product_id'])->limit(1)->pluck('color_images')->first();
			$colorImages = $colorImages ? $colorImages : null;
			
			$offer = Offers::select(['type', 'quantity', 'offer_total_price', 'free_logo'])
				->where('product_id', $c['product_id'])
				->whereRaw('FIND_IN_SET(?, colors)', [$c['color_id']])
				->whereRaw('FIND_IN_SET(?, sizes)', [$c['size_title']])
				->where('status', 1)
				->orderBy('offers.type', 'asc')
				->get();
			if(isset($c['color_id']) && $c['color_id'] && $colorImages && isset($colorImages[$c['color_id']]) && $colorImages[$c['color_id']]){
				$cart[$k]['image'] = json_encode([$colorImages[$c['color_id']]['path']]);
			}
			$cart[$k]['offer'] = $offer ? $offer : null;	
			$cart[$k]['customization'] = isset($data['customization']) && $data['customization'] ? $data['customization'] : null;	
		}
		return Response()
			->json([
				'status' => true,
				'cart' => array_values($cart)
			]);
    }

	protected function offerPrice($item)
	{
		if(isset($item['offer']) && $item['offer'])
		{
			foreach($item['offer'] as $offer)
			{
				if($offer['type'] == 'case-2')
				{
					if(($offer['offer_total_price']*1) > 0 && $item['quantity'] == $offer['quantity'])
					{
						return ['price' => $offer['offer_total_price']*1, 'freeLogo' => 0, 'haveOffer' => true ];
					}
				}
				
				if($offer['type'] == 'case-3')
				{
					if($item['quantity'] == $offer['quantity'])
					{
						return ['price' => $item['quantity']*$item['price'], 'freeLogo' => ($offer['free_logo']*1), 'haveOffer' => false ];
					}
				}
			}
		}
		
		return ['price' => $item['quantity']*$item['price'], 'freeLogo'=> 0, 'haveOffer'=> false ];
	}

	protected function calculateLogoCost($cart)
	{
		$cost = 0;
		$haveLogo = 0;
		$appliedDiscount = 0;
		$logoDiscount = 0;
		foreach($cart as $c)
		{
			$totalCost = isset($c['customization']) && $c['customization'] ? array_sum(Arr::pluck($c['customization'], 'cost')) : 0;
			$cost += $totalCost > 0 ? $totalCost : 0;
		}
		return [
			'cost' => $cost,
			'haveLogo' => $haveLogo > 0 ? true : false,
			'logoDiscount' => $logoDiscount,
			'appliedDiscount' => $appliedDiscount
		];
	}
}