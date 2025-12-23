<?php

namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\FileSystem;
use Illuminate\Support\Str;
use App\Libraries\General;

class Wallet extends AppModel
{
    protected $table = 'wallet';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getListing(Request $request, $where = [])
    {
    	$orderBy = $request->get('sort') ? $request->get('sort') : 'wallet.id';
    	$direction = $request->get('direction') ? $request->get('direction') : 'desc';
    	$page = $request->get('page') ? $request->get('page') : 1;
    	$limit = self::$paginationLimit;
    	$offset = ($page - 1) * $limit;
    	
    	$listing = Wallet::select([
	    		'wallet.*',
                'owner.first_name as owner_first_name',
                'owner.last_name as owner_last_name',
                'parent.title as parent_title'
	    	])
            ->leftJoin('admins as owner', 'owner.id', '=', 'wallet.created_by')
            ->leftJoin('wallet as parent', 'parent.id', '=', 'wallet.parent_id')
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
    public static function getAll($select = [], $where = [], $orderBy = 'wallet.id desc', $limit = null)
    {
    	$listing = Wallet::orderByRaw($orderBy);

    	if(!empty($select))
    	{
    		$listing->select($select);
    	}
    	else
    	{
    		$listing->select([
    			'wallet.*'
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
    * To get single record by id
    * @param $id
    */
    public static function get($id)
    {
    	$record = Wallet::where('id', $id)
            ->with([
                'parent' => function($query) {
                    $query->select(['id', 'title']);
                }
            ])
            ->first();

	    return $record;
    }

    /**
    * To get single row by conditions
    * @param $where
    * @param $orderBy
    */
    public static function getRow($where = [], $orderBy = 'wallet.id desc')
    {
    	$record = Wallet::orderByRaw($orderBy);
        $record->with([
                'parent' => function($query) {
                    $query->select(['id', 'title']);
                }
            ]);
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
    	$category = new Wallet();

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
    * To update
    * @param $id
    * @param $where
    */
    public static function modify($id, $data)
    {
    	$category = Wallet::find($id);
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
    		return Wallet::whereIn('wallet.id', $ids)
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
    	$category = Wallet::find($id);
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
    		return Wallet::whereIn('wallet.id', $ids)
		    		->delete();
	    }
	    else
	    {
	    	return null;
	    }

    }
}