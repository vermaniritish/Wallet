<?php

namespace App\Models\API;

use App\Models\Admin\WareHouse as AdminWareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use App\Models\Scopes\Active;

class WareHouse extends AdminWareHouse
{
    protected $table = 'ware_houses';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public static function getApiListing(Request $request, $where = [])
    {
        $orderBy = $request->get('sort') ? $request->get('sort') : 'ware_houses.id';
        $direction = $request->get('direction') ? $request->get('direction') : 'desc';
        $page = $request->get('page') ? $request->get('page') : 1;
        $limit = self::$paginationLimit;
        $offset = ($page - 1) * $limit;
        
        $listing = WareHouse::select([
            'ware_houses.*'
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

    public static function getWareHouse($id)
    {
       $record = WareHouse::select([
            'ware_houses.*'
        ])
        ->where('ware_houses.id',$id)
        ->first();

        return $record;
    }

    public static function createApi($data)
    {
        $wareHouse = new WareHouse();

        foreach($data as $k => $v)
        {
            $wareHouse->{$k} = $v;
        }

        $wareHouse->created_by = AdminAuth::getLoginId();
        $wareHouse->created = date('Y-m-d H:i:s');
        $wareHouse->modified = date('Y-m-d H:i:s');
        if($wareHouse->save())
        {
            if(isset($data['title']) && $data['title'])
            {
                $wareHouse->slug = Str::slug($wareHouse->title);
                $wareHouse->save();
            }

            return $wareHouse;
        }
        else
        {
            return null;
        }
    }
}