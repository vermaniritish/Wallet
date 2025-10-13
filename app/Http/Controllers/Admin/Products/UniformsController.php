<?php

namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Settings;
use App\Models\Admin\Permissions;
use App\Models\Admin\AdminAuth;
use App\Libraries\General;
use App\Models\Admin\Products;
use App\Models\Admin\Schools;
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

class UniformsController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('uniforms', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$where = [];
    	if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . $search . '%';
    		$where['(products.title LIKE ?)'] = [$search];
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
    	
    	if($request->get('schools') && !empty(array_filter($request->get('schools'))) )
    	{
    		$shops = $request->get('schools');
    		$shops = $shops ? implode(',', $shops) : 0;
    		$where[] = 'products.school_id IN ('.$shops.')';
    	}

    	if($request->get('category'))
    	{
    		$ids = ProductSubCategoryRelation::distinct()->whereIn('sub_category_id', $request->get('category'))->pluck('product_id')->toArray();
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
		$where['products.is_uniform'] = 1;
    	$listing = Products::getListing($request, $where);

    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/uniforms/listingLoop", 
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
	    		"admin/uniforms/index", 
	    		[
	    			'listing' => $listing,
	    			'categories' => $filters['categories'],
					'brands' => $filters['brands'],
					'schools' => $filters['schools']
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

		$schools = Schools::select(['id', 'schooltype', 'name'])->orderBy('name', 'asc')->get();

    	return [
    		'categories' => $categories,
			'brands' => [],
			'schools' => $schools
    	];
    }

    function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('uniforms', 'listing'))
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
				return view("admin/uniforms/view", [
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

    function add(Request $request, $unifromId = null)
    {
    	if(!Permissions::hasPermission('uniforms', 'create'))
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
					'schools' => $unifromId ? ['nullable'] : ['required'],
					'product' => $unifromId ? ['nullable'] : ['required', Rule::exists(Products::class,'id')],
	                'description' => 'nullable',
					'price' => ['required', 'numeric', 'min:0'],
					'sale_price' => ['nullable', 'numeric', 'min:0'],
	                'category' => $unifromId ? ['nullable'] : ['required', Rule::exists(ProductCategories::class,'id')],
					'tags' => ['nullable', 'array'],
					'tags.*' => ['string','max:20',],
					'color_id' => ['nullable', 'array'],
					'color_id.*' => ['distinct','required', Rule::exists(Colours::class,'id')],
					'gender' => ['nullable', Rule::in(['Male','Female','Unisex'])],
					'sizeData' => ['required', 'array']
	            ]
	        );

	        if(!$validator->fails())
	        {
				if(!$unifromId){
					$schools = $data['schools'];
					$data['parent_id'] = $data['product'];
					$data['category_id'] = $data['category'];
				}

				$data['is_uniform'] = 1;
				/** ONLY IN CASE OF MULTIPLE IMAGE USE THIS **/
				if(isset($data['image']) && $data['image'])
				{
					if($unifromId)
					{
						$lastImages = Products::select(['image'])->where('id', $unifromId)->limit(1)->first();
						$data['image'] = json_decode($data['image'], true);
						$lastImages = $lastImages ? json_decode($lastImages, true) : [];
						$data['image'] = array_merge($lastImages, $data['image']);
					}
					$data['image'] = json_encode($data['image']);
				}
				else
				{
					unset($data['image']);
				}
				if(isset($data['size_file']) && $data['size_file'])
				{
					$data['size_file'] = $data['size_file'];
				}
				else
				{
					unset($data['size_file']);
				}
				/** ONLY IN CASE OF MULTIPLE IMAGE USE THIS **/
				unset($data['schools']);
				unset($data['product']);
				unset($data['size']);
				unset($data['sizeData']);
				unset($data['color_id']);
				unset($data['brand']);
				unset($data['sub_category']);
				unset($data['category']);

				if($unifromId)
				{
					$product = Products::modify($unifromId, $data);
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
					if(!empty($subCategory) || !empty($data['category_id']))
	        		{
	        			Products::handleSubCategory($product->id, $data['category_id'], $subCategory);
	        		}
				}
				else
				{
					foreach($schools as $s)
					{
						$data['school_id'] = $s;
						$product = Products::create($data);
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
						if(!empty($subCategory) || !empty($data['category_id']))
						{
							Products::handleSubCategory($product->id, $data['category_id'], $subCategory);
						}
					}
				}
				$request->session()->flash('success', "Uniform created successfully.");
				return Response()->json([
					'status' => true,
					'message' => "Uniform created successfully.",
					'id' => $product->id
				]);
		    }
		    else
		    {
				return Response()->json([
					'status' => false,
					'message' => current(current($validator->errors()->getMessages()))
				], 400);
		    }
		}
		
		$categories = $brands = $schools = [];
		if(!$unifromId)
		{
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

			$schools = Schools::orderBy('name', 'asc')->get();
		}
		
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
			
		return view("admin/uniforms/add", [
			'categories' => $categories,
			'brands' => $brands,
			'colors' => $colors,
			'sizes' => $sizes,
			'schools' => $schools,
			'product' => $unifromId ? Products::find($unifromId)  : null
		]);
    }


    function delete(Request $request, $id)
    {
    	if(!Permissions::hasPermission('uniforms', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
    	
    	if(Products::remove($id))
    	{
    		$request->session()->flash('success', 'Product deleted successfully.');
    		return redirect()->route('admin.uniforms');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('uniforms', 'update')) || ($action == 'delete' && !Permissions::hasPermission('uniforms', 'delete')) ) 
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
}
