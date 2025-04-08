<?php

namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use Illuminate\Support\Str;
use App\Libraries\General;

class ProductCategories extends AppModel
{
    protected $table = 'product_categories';
    protected $primaryKey = 'id';
    public $timestamps = false;


    /**
    * ProductCategories -> Product belongsToMany relation
    *
    * @return ProductCategories
    */
    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_category_relation', 'category_id', 'product_id');
    }

    /**
    * Products -> Admins belongsTO relation
    * 
    * @return Admins
    */
    public function owner()
    {
        return $this->belongsTo(Admins::class, 'created_by', 'id');
    }

    /**
    * Get resize images
    *
    * @return array
    */
    public function getResizeImagesAttribute()
    {
        return $this->image ? FileSystem::getAllSizeImages($this->image) : null;
    }
    
    /**
    * To search and get pagination listing
    * @param Request $request
    * @param $limit
    */

    public static function getListing(Request $request, $where = [])
    {
    	$orderBy = $request->get('sort') ? $request->get('sort') : 'product_categories.id';
    	$direction = $request->get('direction') ? $request->get('direction') : 'desc';
    	$page = $request->get('page') ? $request->get('page') : 1;
    	$limit = self::$paginationLimit;
    	$offset = ($page - 1) * $limit;
    	
    	$listing = ProductCategories::select([
	    		'product_categories.*',
                'owner.first_name as owner_first_name',
                'owner.last_name as owner_last_name'
	    	])
            ->leftJoin('admins as owner', 'owner.id', '=', 'product_categories.created_by')
	    	->orderBy($orderBy, $direction);

        if(!empty($where))
	    {
	    	foreach($where as $query => $values)
	    	{
	    		if(is_array($values))
	    			$listing->whereRaw($query, $values);
	    		elseif(!is_numeric($query))
	    			$listing->where($query, $values);
                else
                    $listing->whereRaw($values);
	    	}
	    }
        
	    // Put offset and limit in case of pagination
	    if($page !== null && $page !== "" && $limit !== null && $limit !== "")
	    {
	    	$listing->offset($offset);
	    	$listing->limit($limit);
	    }

	    $listing = $listing->paginate($limit);

	    return $listing;
    }

    /**
    * To get all records
    * @param $where
    * @param $orderBy
    * @param $limit
    */
    public static function getAll($select = [], $where = [], $orderBy = 'product_categories.id desc', $limit = null)
    {
    	$listing = ProductCategories::orderByRaw($orderBy);
        
    	if(!empty($select))
    	{
    		$listing->select($select);
    	}
    	else
    	{
    		$listing->select([
    			'product_categories.*'
    		]);	
    	}

	    if(!empty($where))
	    {
	    	foreach($where as $query => $values)
	    	{
	    		if(is_array($values))
	    			$listing->whereRaw($query, $values);
	    		elseif(!is_numeric($query))
                    $listing->where($query, $values);
                else
                    $listing->whereRaw($values);
	    	}
	    }
	    
	    if($limit !== null && $limit !== "")
	    {
	    	$listing->limit($limit);
	    }

        $listing->orderByRaw($orderBy);

	    $listing = $listing->get();

	    return $listing;
    }

    /**
    * To get all records
    * @param $where
    * @param $orderBy
    * @param $limit
    */
    public static function getAllCategorySubCategory($ids = [])
    {
        $listing = ProductSubCategories::select([
                'id',
                'category_id',
                'title',
                'slug'
            ]);
        if($ids)
        {
            $listing->whereIn('id', $ids);
        }

        $subCategories = $listing->get();
        $finalSubCategories = [];
        foreach ($subCategories as $key => $value) {
            $finalSubCategories[$value->category_id][] = $value;
        }

        $listing = ProductCategories::select([
                'id',
                'parent_id',
                'title'
            ])
            ->whereNull('parent_id');
        if($ids)
        {
            $listing->whereIn('id', $ids);
            if(!empty(array_keys($finalSubCategories)))
            {
                $listing->orWhereIn('id', array_keys($finalSubCategories));
            }
        }

        $categories = $listing->get();
        foreach($categories as $key => $value)
        {
            if(isset($finalSubCategories[$value->id]) && $finalSubCategories[$value->id])
            {
                $categories[$key]->sub_categories = $finalSubCategories[$value->id];
            }
        }
        return $categories;
    }

    /**
    * To get single record by id
    * @param $id
    */
    public static function get($id)
    {
    	$record = ProductCategories::where('id', $id)
            ->first();

	    return $record;
    }

    /**
    * To get single row by conditions
    * @param $where
    * @param $orderBy
    */
    public static function getRow($where = [], $orderBy = 'product_categories.id desc')
    {
    	$record = ProductCategories::orderByRaw($orderBy);

	    foreach($where as $query => $values)
	    {
	    	if(is_array($values))
                $listing->whereRaw($query, $values);
            elseif(!is_numeric($query))
                $listing->where($query, $values);
            else
                $listing->whereRaw($values);
	    }
	    
	    $record = $record->limit(1)->first();

	    return $record;
    }

    /**
    * To insert
    * @param $where
    * @param $orderBy
    */
    public static function create($data)
    {
    	$category = new ProductCategories();

    	foreach($data as $k => $v)
    	{
    		$category->{$k} = $v;
    	}
        $category->created_by = AdminAuth::getLoginId();
    	$category->created = date('Y-m-d H:i:s');
    	$category->modified = date('Y-m-d H:i:s');
	    if($category->save())
	    {
            if(isset($data['title']) && $data['title'])
            {
                $category->slug = Str::slug($category->title);
                $category->save();
            }
	    	return $category;
	    }
	    else
	    {
	    	return null;
	    }
    }

    /**
    * To update
    * @param $id
    * @param $where
    */
    public static function modify($id, $data)
    {
    	$category = ProductCategories::find($id);
    	foreach($data as $k => $v)
    	{
    		$category->{$k} = $v;
    	}

    	$category->modified = date('Y-m-d H:i:s');
	    if($category->save())
	    {
            if(isset($data['title']) && $data['title'])
            {
                $category->slug = Str::slug($category->title) . '-' . General::encode($category->id);
                $category->save();
            }
	    	return $category;
	    }
	    else
	    {
	    	return null;
	    }
    }

    
    /**
    * To update all
    * @param $id
    * @param $where
    */
    public static function modifyAll($ids, $data)
    {
    	if(!empty($ids))
    	{
    		return ProductCategories::whereIn('product_categories.id', $ids)
		    		->update($data);
	    }
	    else
	    {
	    	return null;
	    }

    }

    /**
    * To delete
    * @param $id
    */
    public static function remove($id)
    {
    	$category = ProductCategories::find($id);
    	return $category->delete();
    }

    /**
    * To delete all
    * @param $id
    * @param $where
    */
    public static function removeAll($ids)
    {
    	if(!empty($ids))
    	{
    		return ProductCategories::whereIn('product_categories.id', $ids)
		    		->delete();
	    }
	    else
	    {
	    	return null;
	    }

    }
}