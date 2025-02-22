<?php

/**
 * ProductCategories Class
 *
 * @package    ProductCategoriesController
 
 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Permissions;
use App\Models\Admin\ProductCategories;
use App\Models\Admin\Admins;
use Illuminate\Validation\Rule;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use App\Models\Admin\ProductSubCategories;

class ProductSubCategoriesController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('product_categories', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$where = [];
    	if($request->get('search'))
    	{
    		$search = $request->get('search');
    		$search = '%' . strtolower($search) . '%';
			$where['(lower(sub_categories.title) LIKE ? or lower(category.title) LIKE ? or lower(owner.first_name) LIKE ? or lower(owner.last_name) LIKE ?)'] = [$search, $search, $search, $search];
    	}

    	if($request->get('created_on'))
    	{
    		$createdOn = $request->get('created_on');
    		if(isset($createdOn[0]) && !empty($createdOn[0]))
    			$where['sub_categories.created >= ?'] = [
    				date('Y-m-d', strtotime($createdOn[0]))
    			];
    		if(isset($createdOn[1]) && !empty($createdOn[1]))
    			$where['sub_categories.created <= ?'] = [
    				date('Y-m-d', strtotime($createdOn[1]))
    			];
    	}

    	if($request->get('category'))
    	{
    		$ids = $request->get('category');
    		$ids = implode(',', $ids);
    		$where[] = 'sub_categories.parent_id IN ('.$ids.')';
    	}

    	if($request->get('admins'))
    	{
    		$admins = $request->get('admins');
    		$admins = $admins ? implode(',', $admins) : 0;
    		$where[] = 'sub_categories.created_by IN ('.$admins.')';
    	}

    	$listing = ProductSubCategories::getListing($request, $where);

    	if($request->ajax())
    	{
		    $html = view(
	    		"admin/products/subCategories/listingLoop", 
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
			/** Filter Data **/
			$categories = ProductSubCategories::getAll(
	    		[
	    			'sub_categories.id',
	    			'sub_categories.title'
	    		],
	    		[
	    		],
	    		'sub_categories.title desc'
	    	);

	    	$admins = Admins::getAll(
	    		[
	    			'admins.id',
	    			'admins.first_name',
	    			'admins.last_name'
	    		],
	    		[
	    			'admins.status' => 1
	    		],
	    		'concat(admins.first_name, admins.last_name) desc'
	    	);
	    	/** Filter Data **/

	    	return view(
	    		"admin/products/subCategories/index", 
	    		[
	    			'listing' => $listing,
	    			'categories' => $categories,
	    			'admins' => $admins
	    		]
	    	);
	    }
    }

    function add(Request $request)
    {
    	if(!Permissions::hasPermission('product_categories', 'create'))
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
	                'title' => [
	                	'required',
	                	Rule::unique('product_categories')->whereNull('deleted_at')
					],
					'description' => [
						'nullable'
					],

	            ]
	        );

	        if(!$validator->fails())
	        {
	        	$category = ProductSubCategories::create($data);
	        	if($category)
	        	{
	        		$request->session()->flash('success', 'Sub category created successfully.');
	        		return redirect()->route('admin.products.subCategories');
	        	}
	        	else
	        	{
	        		$request->session()->flash('error', 'Sub Category could not be save. Please try again.');
		    		return redirect()->back()->withErrors($validator)->withInput();
	        	}
		    }
		    else
		    {
		    	$request->session()->flash('error', 'Please provide valid inputs.');
		    	return redirect()->back()->withErrors($validator)->withInput();
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

	    return view("admin/products/subCategories/add",
		[
			'categories' => $categories
		]);
    }

    function edit(Request $request, $id)
    {
    	if(!Permissions::hasPermission('product_categories', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$category = ProductSubCategories::get($id);
    	if($category)
    	{
	    	if($request->isMethod('post'))
	    	{
	    		$data = $request->toArray();
	    		$validator = Validator::make(
		            $request->toArray(),
		            [
		                'title' => [
		                	'required',
		                	Rule::unique('product_categories')->ignore($category->id)->whereNull('deleted_at'),
						],
						'description' => [
							'nullable'
						]
		            ]
		        );

		        if(!$validator->fails())
		        {
		        	unset($data['_token']);
	        		
	        		/** IN CASE OF SINGLE UPLOAD **/
		        	if(isset($data['image']) && $data['image'])
		        	{
		        		$oldImage = $category->image;
		        	}
		        	else
		        	{
		        		unset($data['image']);
		        		
		        	}
		        	/** IN CASE OF SINGLE UPLOAD **/
		        	if(ProductSubCategories::modify($id, $data))
		        	{
		        		/** IN CASE OF SINGLE UPLOAD **/
		        		if(isset($oldImage) && $oldImage)
		        		{
		        			FileSystem::deleteFile($oldImage);
		        		}
		        		/** IN CASE OF SINGLE UPLOAD **/

		        		$request->session()->flash('success', 'Sub category updated successfully.');
		        		return redirect()->route('admin.products.subCategories');
		        	}
		        	else
		        	{
		        		$request->session()->flash('error', 'Sub Category could not be save. Please try again.');
			    		return redirect()->back()->withErrors($validator)->withInput();
		        	}
			    }
			    else
			    {
			    	$request->session()->flash('error', 'Please provide valid inputs.');
			    	return redirect()->back()->withErrors($validator)->withInput();
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
		    return view("admin/products/subCategories/edit", [
		    		'category' => $category,
					'categories' => $categories
	    		]);
		}
		else
		{
			abort(404);
		}
    }

	function view(Request $request, $id)
    {
    	if(!Permissions::hasPermission('product_categories', 'listing'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$page = ProductSubCategories::get($id);
    	if($page)
    	{
	    	return view("admin/products/subCategories/view", [
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
    	if(!Permissions::hasPermission('product_categories', 'delete'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}

    	$product = ProductSubCategories::find($id);
    	if($product->delete())
    	{
    		$request->session()->flash('success', 'Category deleted successfully.');
    		return redirect()->route('admin.products.subCategories');
    	}
    }

    function bulkActions(Request $request, $action)
    {
    	if( ($action != 'delete' && !Permissions::hasPermission('product_categories', 'update')) || ($action == 'delete' && !Permissions::hasPermission('product_categories', 'delete')) ) 
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
    	
    	$ids = $request->get('ids');
    	if(is_array($ids) && !empty($ids))
    	{
    		switch ($action) {
    			case 'delete':
    				ProductSubCategories::removeAll($ids);
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
}
