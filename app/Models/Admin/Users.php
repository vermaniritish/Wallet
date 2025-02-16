<?php

namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use App\Models\API\UsersPermissions;
use App\Traits\OtpVerification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable implements MustVerifyEmail

{
    use OtpVerification, Notifiable,HasApiTokens;
    protected $table = 'users';
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
    * Name getter
    */
    function getNameAttribute()
    {
    	return $this->first_name . ' ' . $this->last_name;
    }

    
    /**
    * Password setter
    * @param $value
    */
    function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::make($value);
    }
    
    /**
    * To search and get pagination listing
    * @param Request $request
    * @param $limit
    */

    public static function getListing(Request $request, $where = [])
    {
    	$orderBy = $request->get('order') ? $request->get('order') : 'users.id';
    	$direction = $request->get('direction') ? $request->get('direction') : 'desc';
    	$page = $request->get('page') ? $request->get('page') : 1;
    	$limit = 20;
    	$offset = ($page - 1) * $limit;
    	
    	$listing = Users::select([
	    		'users.*'
	    	])
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
    public static function getAll($select = [], $where = [], $orderBy = 'users.id desc', $limit = null)
    {
    	$listing = Users::orderByRaw($orderBy);

    	if(!empty($select))
    	{
    		$listing->select($select);
    	}
    	else
    	{
    		$listing->select([
    			'users.*'
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
    	$record = Users::find($id);

	    return $record;
    }

    /**
    * To get single row by conditions
    * @param $where
    * @param $orderBy
    */
    public static function getRow($where = [], $orderBy = 'users.id desc')
    {
    	$record = Users::orderByRaw($orderBy);

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
    	$user = new Users();

    	foreach($data as $k => $v)
    	{
    		$user->{$k} = $v;
    	}

        $user->status = 1;
        $user->created_by = AdminAuth::getLoginId();
    	$user->created = date('Y-m-d H:i:s');
    	$user->modified = date('Y-m-d H:i:s');
	    if($user->save())
	    {
            Users::handlePermissions($user->id, [
                "email_buyer_message" => 1,
                "email_seller_message" => 1,
                "text_buyer_message" => 1,
                "text_seller_message" => 1
            ]);
	    	return $user;
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
    	$user = Users::find($id);
    	foreach($data as $k => $v)
    	{
    		$user->{$k} = $v;
    	}

    	$user->modified = date('Y-m-d H:i:s');
	    if($user->save())
	    {
	    	return $user;
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
    		return Users::whereIn('users.id', $ids)
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
    	$user = Users::find($id);
    	return $user->delete();
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
    		return Users::whereIn('users.id', $ids)
		    		->delete();
	    }
	    else
	    {
	    	return null;
	    }

    }


    public static function handlePermissions($userId, $permissions)
    {
        UsersPermissions::where('user_id', $userId)->delete();
        foreach($permissions as $k => $p)
        {
            $usersPermissions = new UsersPermissions();
            $usersPermissions->user_id = $userId;
            $usersPermissions->status = $p;
            $usersPermissions->permission = $k;
            $usersPermissions->created = date('Y-m-d H:i:s');
            $usersPermissions->save();
        }
    }
}