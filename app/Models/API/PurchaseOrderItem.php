<?php

namespace App\Models\API;

use App\Models\Admin\PurchaseOrderItem as AdminPurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use App\Models\Scopes\Active;

class PurchaseOrderItem extends AdminPurchaseOrderItem
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public static function getApiListing(Request $request, $where = [])
    {
        $orderBy = $request->get('sort') ? $request->get('sort') : 'purchase_order_items.id';
        $direction = $request->get('direction') ? $request->get('direction') : 'desc';
        $page = $request->get('page') ? $request->get('page') : 1;
        $limit = self::$paginationLimit;
        $offset = ($page - 1) * $limit;
        
        $listing = PurchaseOrderItem::select([
            'purchase_order_items.*'
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

    public static function getPurchaseOrder($id)
    {
       $record = PurchaseOrderItem::select([
            'purchase_order_items.*'
        ])
        ->where('purchase_order_items.id',$id)
        ->first();

        return $record;
    }

    public static function createApi($data)
    {
        $purchaseOrder = new PurchaseOrderItem();

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