<?php

namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use Illuminate\Support\Str;
use App\Libraries\General;

class Schools extends AppModel
{
    protected $table = 'schools';
    protected $primaryKey = 'id';
    public $timestamps = false;

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
    * Schools -> Admins belongsTO relation
    * 
    * @return Admins
    */
    public function owner()
    {
        return $this->belongsTo(Admins::class, 'created_by', 'id');
    }
    
    /**
    * To search and get pagination listing
    * @param Request $request
    * @param $limit
    */

    public static function getListing(Request $request, $where = [])
    {
    	$orderBy = $request->get('order') ? $request->get('order') : 'schools.id';
    	$direction = $request->get('direction') ? $request->get('direction') : 'desc';
    	$page = $request->get('page') ? $request->get('page') : 1;
    	$limit = self::$paginationLimit;
    	$offset = ($page - 1) * $limit;
    	
    	$listing = Schools::select([
	    		'schools.*',
                'user.first_name as user_fname',
                'user.last_name as user_lname'
	    	])
            ->leftJoin('users as user', 'user.id', '=', 'schools.user_id')
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
    public static function getAll($select = [], $where = [], $orderBy = 'schools.id desc', $limit = null)
    {
    	$listing = Schools::orderByRaw($orderBy)
            ->leftJoin('users', 'users.id', '=', 'schools.user_id');
    	if(!empty($select))
    	{
    		$listing->select($select);
    	}
    	else
    	{
    		$listing->select([
    			'schools.*'
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

	    $listing = $listing->get();

	    return $listing;
    }

    /**
    * To get single record by id
    * @param $id
    */
    public static function get($id)
    {
    	$record = Schools::where('id', $id)
            ->with([
                'shop_owner' => function($query) {
                    $query->select(['id', 'first_name', 'last_name']);
                },
                'owner' => function($query) {
                    $query->select(['id', 'first_name', 'last_name']);
                },
            ])
            ->first();

	    return $record;
    }

    /**
    * To get single row by conditions
    * @param $where
    * @param $orderBy
    */
    public static function getRow($where = [], $orderBy = 'schools.id desc')
    {
    	$record = Schools::orderByRaw($orderBy);

	    foreach($where as $query => $values)
	    {
	    	if(is_array($values))
                $record->whereRaw($query, $values);
            elseif(!is_numeric($query))
                $record->where($query, $values);
            else
                $record->whereRaw($values);
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
    	$shop = new Schools();

    	foreach($data as $k => $v)
    	{
    		$shop->{$k} = $v;
    	}

        $shop->status = 1;
        $shop->created_by = AdminAuth::getLoginId();
    	$shop->created = date('Y-m-d H:i:s');
    	$shop->modified = date('Y-m-d H:i:s');

	    if($shop->save())
	    {
            if(isset($data['title']) && $data['title'])
            {
                $shop->slug = Str::slug($shop->title) . '-' . General::encode($shop->id);
                $shop->save();
            }

	    	return $shop;
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
    	$shop = Schools::find($id);
    	foreach($data as $k => $v)
    	{
    		$shop->{$k} = $v;
    	}

    	$shop->modified = date('Y-m-d H:i:s');
	    if($shop->save())
	    {
            if(isset($data['title']) && $data['title'])
            {
                $shop->slug = Str::slug($shop->title) . '-' . General::encode($shop->id);
                $shop->save();
            }

	    	return $shop;
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
    		return Schools::whereIn('schools.id', $ids)
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
    	$shop = Schools::find($id);
    	return $shop->delete();
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
    		return Schools::whereIn('schools.id', $ids)
		    		->delete();
	    }
	    else
	    {
	    	return null;
	    }

    }
}