<?php

namespace App\Models\API;

use App\Models\Admin\PurchaseOrder as AdminPurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use App\Models\Scopes\Active;

class PurchaseOrder extends AdminPurchaseOrder
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public static function getApiListing(Request $request, $where = [])
    {
        $orderBy = $request->get('sort') ? $request->get('sort') : 'purchase_orders.id';
        $direction = $request->get('direction') ? $request->get('direction') : 'desc';
        $page = $request->get('page') ? $request->get('page') : 1;
        $limit = self::$paginationLimit;
        $offset = ($page - 1) * $limit;
        
        $listing = PurchaseOrder::select([
            'purchase_orders.*'
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

    public static function getpurchaseOrder($id)
    {
       $record = PurchaseOrder::select([
            'purchase_orders.*'
        ])
        ->where('purchase_orders.id',$id)
        ->first();

        return $record;
    }

    public static function createApi($data)
    {
        $purchaseOrder = new PurchaseOrder();

        foreach($data as $k => $v)
        {
            $purchaseOrder->{$k} = $v;
        }

        $purchaseOrder->created_by = AdminAuth::getLoginId();
        $purchaseOrder->created = date('Y-m-d H:i:s');
        $purchaseOrder->modified = date('Y-m-d H:i:s');
        if($purchaseOrder->save())
        {
            if(isset($data['title']) && $data['title'])
            {
                $purchaseOrder->slug = Str::slug($purchaseOrder->title);
                $purchaseOrder->save();
            }

            return $purchaseOrder;
        }
        else
        {
            return null;
        }
    }
}