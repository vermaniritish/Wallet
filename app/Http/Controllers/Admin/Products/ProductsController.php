<?php

/**
 * Products Class
 *
 * @package    ProductsController
 
 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Settings;
use App\Models\Admin\Permissions;
use App\Models\Admin\AdminAuth;
use App\Libraries\General;
use App\Models\Admin\Products;
use App\Models\Admin\ProductCategories;
use App\Models\Admin\ProductCategoryRelation;
use App\Models\Admin\Admins;
use App\Models\Admin\Shops;
use App\Models\Admin\Users;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use App\Models\Admin\BrandProducts;
use App\Models\Admin\Brands;
use App\Models\Admin\Colours;
use App\Models\Admin\ProductSizeRelation;
use App\Models\Admin\ProductSubCategories;
use App\Models\Admin\ProductSubCategoryRelation;
use App\Models\Admin\Sizes;

class ProductsController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('products', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$where = [];
    	if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . $search . '%';
    		$where['(products.title LIKE ? or shop_owner.first_name LIKE ? or shop_owner.last_name LIKE ? or products.address LIKE ? or products.postcode LIKE ?)'] = [$search, $search, $search, $search, $search];
    	}

    	if($request->get('created_on'))
    	{
    		$createdOn = $request->get('created_on');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['products.created >= ?'] = [
    				date('Y-m-d 00:00:00', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['products.created <= ?'] = [
    				date('Y-m-d 23:59:59', strtotime($createdOn[1]))
    			];
    	}
    	
    	// if($request->get('shop_id') && !empty(array_filter($request->get('shop_id'))) )
    	// {
    	// 	$shops = $request->get('shop_id');
    	// 	$shops = $shops ? implode(',', $shops) : 0;
    	// 	$where[] = 'products.shop_id IN ('.$shops.')';
    	// }

    	if($request->get('category'))
    	{
    		$ids = ProductSubCategoryRelation::distinct()->whereIn('sub_category_id', $request->get('category'))->pluck('product_id')->toArray();
    		$ids = !empty($ids) ? implode(',', $ids) : '0';
    		$where[] = 'products.id IN ('.$ids.')';
    	}

		if($request->get('brand'))
    	{
    		$ids = BrandProducts::distinct()->whereIn('brand_id', $request->get('brand'))->pluck('product_id')->toArray();
    		$ids = !empty($ids) ? implode(',', $ids) : '0';
    		$where[] = 'products.id IN ('.$ids.')';
    	}

    	if($request->get('status'))
    	{
    		switch ($request->get('status')) {
    			case 'active':
    				$where['products.status'] = 1;
    			break;
    			case 'non_active':
    				$where['products.status'] = 0;
    			break;
    		}	
    	}

		$where['products.is_uniform'] = 0;
    	$listing = Products::getListing($request, $where);

    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/products/listingLoop", 
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
			$filters = $this->filters();
	    	return view(
	    		"admin/products/index", 
	    		[
	    			'listing' => $listing,
	    			'categories' => $filters['categories'],
					'brands' => $filters['brands'],
	    		]
	    	);
	    }
    }

    function filters()
    {
    	$catIds = ProductSubCategoryRelation::distinct()
		->groupBy('product_sub_category_relation.sub_category_id')
		->pluck('product_sub_category_relation.sub_category_id')->toArray();
    	$categories = [];
    	if($catIds)
    	{
			$categories = ProductCategories::getAllCategorySubCategory($catIds);
		}
    
		$brands = Brands::getAll(
			[
				'brands.id',
				'brands.title'
			],
			[
			],
			'brands.title desc'
		);

    	return [
    		'categories' => $categories,
			'brands' => $brands
    	];
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('products', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$product = Products::get($id);
		$where = ['product_sizes.product_id' => $id];
		if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . $search . '%';
    		$where['(
				product_sizes.id LIKE ? or
				product_sizes.size_title LIKE ? or
				product_sizes.from_cm LIKE ? or
				product_sizes.price LIKE ? or
				product_sizes.to_cm LIKE ?)'] = [$search, $search, $search, $search, $search];
    	}
		$listing = ProductSizeRelation::getListing($request, $where);
    	if($product)
    	{
			if($request->ajax())
			{
				$html = view(
					"admin/products/productSizes/listingLoop", 
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
				return view("admin/products/view", [
					'product' => $product,
					'listing' => $listing
				]);
			}
		}
		else
		{
			abort(404);
		}
    }

    function add(Request $request)
    {
    	if(!Permissions::hasPermission('products', 'create'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	if($request->isMethod('post'))
    	{
			$data = $request->toArray();
    		unset($data['_token']);
			$sizeData = [];
			$colors = [];
			$subCategory = [];
			$brands = [];
			if(isset($data['sizeData']) && $data['sizeData']) {
				$data['sizeData'] = json_decode($data['sizeData'], true);
				$sizeData = $data['sizeData'];
			}
			if (isset($data['tags']) && $data['tags']) {
				$data['tags'] = explode(',', $data['tags']);
			}
			if (isset($data['color_id']) && $data['color_id']) {
				$colors = $data['color_id'];
			}
			if(isset($data['sub_category']) && $data['sub_category']) {
				$subCategory = $data['sub_category'];
			}
			if(isset($data['brand']) && $data['brand']) {
				$brands = $data['brand'];
			}
    		$validator = Validator::make(
	            $data,
	            [
	                'title' => 'required',
	                'description' => 'nullable',
					'price' => ['required', 'numeric', 'min:0'],
					'sale_price' => ['nullable', 'numeric', 'min:0'],
	                'category' => ['required', Rule::exists(ProductCategories::class,'id')],
	                'brand' => 'required',
					'tags' => ['nullable', 'array'],
					'tags.*' => ['string','max:20',],
					'color_id' => ['nullable', 'array'],
					'color_id.*' => ['distinct','required', Rule::exists(Colours::class,'id')],
					'gender' => ['required', Rule::in(['Male','Female','Unisex'])],
					'sku_number' => ['required', Rule::unique('products')],
					'sizeData' => ['required', 'array']
	            ]
	        );

	        if(!$validator->fails())
	        {
				unset($data['size']);
				unset($data['sizeData']);
				unset($data['color_id']);
	        	unset($data['brand']);
	        	unset($data['sub_category']);
		
				$data['category_id'] = $data['category'];
				unset($data['category']);
	        	$product = Products::create($data);
	        	if($product)
	        	{
					if(!empty($colors))
	        		{
						Products::handleColors($product->id, $colors);
	        		}
					if (!empty($sizeData)) {
						Products::handleSizes($product->id, $sizeData);
					}
					if(!empty($brands))
	        		{
	        			Products::handleBrands($product->id, $brands);
	        		}
					if(!empty($subCategory))
	        		{
	        			Products::handleSubCategory($product->id, $subCategory);
	        		}
					$request->session()->flash('success', "Product created successfully.");
					return Response()->json([
						'status' => true,
						'message' => "Product created successfully.",
						'id' => $product->id
					]);
	        	}
	        	else
	        	{
					return Response()->json([
						'status' => false,
						'message' => 'Product could not be saved. Please try again.'
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
	    
	    $categories = ProductCategories::getAll(
	    		[
	    			'product_categories.id',
	    			'product_categories.title'
	    		],
	    	    [
					'status' => 1,
				],
	    		'product_categories.title desc'
	    	);

		$brands = Brands::getAll(
	    		[
	    			'brands.id',
	    			'brands.title'
	    		],
	    	    [
					'status' => 1, 
				],
	    		'brands.title desc'
	    	);

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
		
		$colors = Colours::getAll(
	    		[
	    			'colours.id',
	    			'colours.color_code',
	    			'colours.title',
	    		],
	    	    [
				],
	    		'colours.color_code desc'
	    	);

		$sizes = Sizes::getAll(
	    		[
	    			'sizes.id',
	    			'sizes.type',
	    			'sizes.size_title',
	    			'sizes.from_cm',
	    			'sizes.to_cm',
	    		],
	    	    [
				],
	    		'sizes.size_title desc'
	    	);
	    return view("admin/products/add", [
	    			'categories' => $categories,
	    			'users' => $users,
					'brands' => $brands,
					'colors' => $colors,
					'sizes' => $sizes
	    		]);
    }

    function edit(Request $request, $id)
    {
    	if(!Permissions::hasPermission('products', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
    	$product = Products::get($id);
    	if($product)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();
				$sizeData = [];
				$subCategory= [];
				$brands = [];
				$colors = [];
				if(isset($data['sizeData']) && $data['sizeData']) {
					$data['sizeData'] = json_decode($data['sizeData'], true);
					$sizeData = $data['sizeData'];
				}
				if (isset($data['tags']) && $data['tags']) {
					$data['tags'] = explode(',', $data['tags']);
				}
				if(isset($data['sub_category']) && $data['sub_category']) {
	        		$subCategory = $data['sub_category'];
	        	}
				if(isset($data['color_id']) && $data['color_id']) {
	        		$colors = $data['color_id'];
	        	}
				if(isset($data['brand']) && $data['brand']) {
					$brands = $data['brand'];
				}
	    		$validator = Validator::make(
		            $data,
			            [
							'title' => 'required',
							'description' => 'nullable',
							'price' => ['required', 'numeric', 'min:0'],
							'max_price' => ['nullable', 'numeric', 'min:0'],
							'category' => ['required', Rule::exists(ProductCategories::class,'id')],
							'brand' => 'required',
							'tags' => ['nullable', 'array'],
							'tags.*' => ['string','max:20',],
							'color_id' => ['nullable', 'array'],
							'color_id.*' => ['required', Rule::exists(Colours::class,'id')],
							'gender' => ['required', Rule::in(['Male','Female','Unisex','Kids'])],
							'sku_number' => ['required', Rule::unique('products')->ignore($product->id)],
							'sizeData' => ['required', 'array']
		            	]
		        );
		        if(!$validator->fails())
		        {
		        	unset($data['_token']);
					unset($data['sizeData']);
					unset($data['size']);
					unset($data['sub_category']);
					unset($data['color_id']);
	        		/** ONLY IN CASE OF MULTIPLE IMAGE USE THIS **/
	        		if(isset($data['image']) && $data['image'])
	        		{
	        			$data['image'] = json_decode($data['image'], true);
	        			$product->image = $product->image ? json_decode($product->image) : [];
		        		$data['image'] = array_merge($product->image, $data['image']);
		        		$data['image'] = json_encode($data['image']);
		        	}
		        	else
		        	{
		        		unset($data['image']);
		        	}
		        	/** ONLY IN CASE OF MULTIPLE IMAGE USE THIS **/
					unset($data['brand']);
					unset($data['sub_category']);
					$data['category_id'] = $data['category'];
					unset($data['category']);
		        	if(Products::modify($id, $data))
		        	{
						if (!empty($sizeData)) {
							Products::handleSizes($product->id, $sizeData);
						}
						if(!empty($brands))
		        		{
		        			Products::handleBrands($product->id, $brands);
		        		}
						if(!empty($subCategory))
						{
							Products::handleSubCategory($product->id, $subCategory);
						}
						if(!empty($colors))
						{
							Products::handleColors($product->id, $colors);
						}
		        		$request->session()->flash('success', "Product updated successfully.");
						return Response()->json([
							'status' => true,
							'message' => "Product created successfully.",
							'id' => $product->id
						]);
		        	}
		        	else
		        	{
						return Response()->json([
							'status' => false,
							'message' => 'Product could not be saved. Please try again.'
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

			$categories = ProductCategories::getAll(
	    		[
	    			'product_categories.id',
	    			'product_categories.title'
	    		],
				[
					'status' => 1, 
				],
	    		'product_categories.title desc'
	    	);

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

			$brands = Brands::getAll(
	    		[
	    			'brands.id',
	    			'brands.title'
	    		],
	    	    [
					'status' => 1, 
				],
	    		'brands.title desc'
	    	);
			$colors = Colours::getAll(
	    		[
	    			'colours.id',
	    			'colours.color_code',
	    			'colours.title',
	    		],
	    	    [
				],
	    		'colours.color_code desc'
	    	);
			$sizes = Sizes::getAll(
	    		[
	    			'sizes.id',
	    			'sizes.type',
	    			'sizes.size_title',
	    			'sizes.from_cm',
	    			'sizes.to_cm',
	    		],
	    	    [
				],
	    		'sizes.size_title desc'
	    	);
			return view("admin/products/add", [
    			'product' => $product,
    			'categories' => $categories,
    			'users' => $users,
				'brands' => $brands,
				'colors' => $colors,
				'sizes' => $sizes
    		]);
		}
		else
		{
			abort(404);
		}
    }

    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('products', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
    	
    	if(Products::remove($id))
    	{
    		$request->session()->flash('success', 'Product deleted successfully.');
    		return redirect()->route('admin.products');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('products', 'update')) || ($action == 'delete' && !Permissions::hasPermission('products', 'delete')) ) 
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'active':
    				Products::modifyAll($ids, [
    					'status' => 1
    				]);
    				$message = count($ids) . ' records has been published.';
    			break;
    			case 'inactive':
    				Products::modifyAll($ids, [
    					'status' => 0
    				]);
    				$message = count($ids) . ' records has been unpublished.';
    			break;
    			case 'delete':
    				Products::removeAll($ids);
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

	public function getSize($gender)
	{
		$sizes = Sizes::select(['id','size_title','from_cm','to_cm'])->whereType($gender)->get();
		return response()->json([
			'status' => true,
			'sizes' => $sizes,
		]);
	}

	public function getSubCategory($categoryId)
	{
		$subCategory = ProductSubCategories::select(['id','title','status'])->whereStatus(1)->whereCategoryId($categoryId)->get();
		return response()->json([
			'status' => true,
			'subCategory' => $subCategory,
		]);
	}

	public function fetch($id)
	{
		$product = Products::get($id);
		return response()->json([
			'status' => true,
			'product' => $product,
		]);
	}

	public function search(Request $request)
	{
		$products = ProductSubCategoryRelation::leftJoin('products', 'product_sub_category_relation.product_id', '=', 'products.id')
			->select(['products.id', 'products.title', 'products.sku_number'])
			->where('sub_category_id', $request->get('subcategory'))
			->get();
		return response()->json([
			'status' => true,
			'products' => $products,
		]);
	}

	public function updateCustomization(Request $request, $id)
	{
		$product = Products::find($id);
		$product->logo_customization = $request->items;
		if($product->save())
		{
			return Response()->json([
				'status' => true,
				'message' => "Product created."
			]);
		}
		else
		{
			return Response()->json([
				'status' => false,
				'message' => 'Product could not be saved. Please try again.'
			], 400);
		}
	}
}
