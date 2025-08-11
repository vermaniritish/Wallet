<?php

namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;
use App\Libraries\General;
use App\Models\User;
use App\Traits\LogsCauserInfo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;


class Orders extends AppModel
{
    use LogsActivity, LogsCauserInfo;
    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    /**** ONLY USE FOR MAIN TALBLES NO NEED TO USE FOR RELATION TABLES OR DROPDOWNS OR SMALL SECTIONS ***/
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_by_admin' => 'boolean',
    ];

    /**
    * Order -> Admins belongsTO relation
    * 
    * @return Admins
    */
    public function owner()
    {
        return $this->belongsTo(Admins::class, 'created_by', 'id');
    }

    /**
    * Order -> Admins belongsTO relation
    * 
    * @return Admins
    */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    /**
    * Order -> Admins belongsTO relation
    * 
    * @return Admins
    */
    public function statusBy()
    {
        return $this->belongsTo(Admins::class, 'status_by', 'id');
    }
    
    /**
    * Order -> Products belongsToMany relation
    *
    * @return Products
    */
    public function products()
    {
        return $this->belongsToMany(Products::class, 'order_products', 'order_id', 'product_id')
            ->withPivot('product_title', 'sku_number', 'quantity','amount','product_description');
    }

    /**
    * Order -> Staff hasOne relation
    * 
    * @return Staff
    */
    public function staff()
    {
        return $this->hasOne(Staff::class, 'id','staff_id');
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

    public static function getStaticData()
    {
        return [

            'paymentType' => [
                'COD',
                'UPI',
                'Credit/Debit Cards'
            ],
            'status' => self::getStatuses(),
            
        ];
    }

    public static function getStatuses($status = null)
    {
        $statuses = [
            'draft' => ['label' => 'Draft', 'styles' => 'background-color: #ffa07a; color: #cc0000;', 'message' => 'Your order is in draft.', 
            'sms_message' => ''
        ],
            'pending' => ['label' => 'Pending', 'styles' => 'background-color: #ffa07a; color: #cc0000;', 'message' => 'Your order is pending to accept.', 
            'sms_message' => ''
        ],
            'accepted' => ['label' => 'Accepted', 'styles' => 'background-color: #ccffcc; color: #006600;', 'message' => 'Your order is accepted.', 
            'sms_message' => 'Your order {order_id} is accepted.'
        ],
            'in_progress' => ['label' => 'In Progress', 'styles' => 'background-color: #ffffcc; color: #996600;', 'message' => 'Your order is in progress.', 
            'sms_message' => 'Your order {order_id} is in progress.'
        ],
            'partial_shipped' => ['label' => 'Partial Shipped', 'styles' => 'background-color: #ccffcc; color: #006600;', 'message' => 'Your order is shipped partially.', 
            'sms_message' => ''
        ],
            'shipped' => ['label' => 'Shipped', 'styles' => 'background-color: #ccffcc; color: #006600;', 'message' => 'Your order is shipped.', 
            'sms_message' => ''
        ],
            'on_the_way' => ['label' => 'Dispached', 'styles' => 'background-color: #cce5ff; color: #004080;', 'message' => 'Your order is dispached.', 
            'sms_message' => 'Your order {order_id} is dispached.'
        ],
            'reached_at_location' => ['label' => 'Reached at Location', 'styles' => 'background-color: #cce5ff; color: #004080;', 'message' => 'Professionls is reached at location.', 
            'sms_message' => ''
        ],
            'completed' => ['label' => 'Completed', 'styles' => 'background-color: #d9ead3; color: #006600;', 'message' => 'Your order is in completed.', 
            'sms_message' => 'Your order {order_id} is completed.'
        ],
            'cancel' => ['label' => 'Cancel', 'styles' => 'background-color: #dc3545; color: #FFF;', 'message' => 'This order is cancelled.', 
            'sms_message' => 'This order {order_id} is cancelled.'
        ],
            'cancel_by_client' => ['label' => 'Cancel by client', 'styles' => 'background-color: #dc3545; color: #FFF;', 'message' => 'This order is cancelled.', 
            'sms_message' => 'This order {order_id} is cancelled.'
        ],
        ];
        return $status && $statuses[$status] ? $statuses[$status] : $statuses;
    }

    public static function getShippingOptions()
    {
        return ["SCD" => "express24 Courier Pack", "SND" => "express24", "S09" => "express9", "S10" => "express10", "S12" => "expressAM", "SUP" => "express48"];
    }

    public static function handleProducts($id, $productsData)
    {
        OrderProductRelation::where('order_id', $id)->delete();
        foreach ($productsData as $productData) {
            $relation = new OrderProductRelation();
            $product = Products::find($productData['id']);
            if ($product) {
                $relation->product_title = $product->title;
                $relation->product_description = $product->description;
                $relation->amount = $product->price;
                $relation->order_id = $id;
                $relation->product_id = $product->id;
                $relation->quantity = $productData['quantity'];
                $relation->created_at = now();
                $relation->updated_at = now();
                $relation->save();
            }
        }
    }

    public function updateStatusAndLogHistory($field, $newStatus, $orderCreate = null)
    {
        $id = $orderCreate ? $orderCreate : $this->id;
        $updated = Orders::where('id', $id)->update([
                $field => $newStatus,
                'status_by' => AdminAuth::getLoginId(),
                'status_at' => now()->format('Y-m-d H:i:s')
            ]);
        if ($updated) {
            $this->logStatusHistory($newStatus, $id);
        }

        return $updated;
    }

    public function updateFieldAndLogHistory($field, $newValue)
    {
        $id = $this->id;
        $old = Orders::where('id', $id)->limit(1)->value($field);
        if ($old === $newValue) {
            return true;
        }
        $updated = Orders::where('id', $id)->update([
            $field => $newValue
        ]);
        if ($updated) {
            $this->logFieldHistory($field,$newValue, $id,$old);
        }
        return $updated;
    }

    public function logFieldHistory($field,$newValue, $id,$old)
    {
        $create = OrderStatusHistory::create([
            'order_id' => $id,
            'field' => $field,
            'old_value' => $old,
            'new_value' => $newValue,
            'created_by' => AdminAuth::getLoginId(),
        ]);
        return $create;
    }

    public function logStatusHistory($newStatus, $id)
    {
        $create = OrderStatusHistory::create([
            'order_id' => $id,
            'status' => $newStatus,
            'created_by' => AdminAuth::getLoginId(),
        ]);
        return $create;
    }

    public function logStaffHistory($staffId, $id)
    {
        $create = OrderStatusHistory::create([
            'order_id' => $id,
            'staff_id' => $staffId,
            'created_by' => AdminAuth::getLoginId(),
        ]);
        return $create;
    }
    /**
    * To search and get pagination listing
    * @param Request $request
    * @param $limit
    */

    public static function getListing(Request $request, $where = [])
    {
        $orderBy = $request->get('sort') ? $request->get('sort') : 'orders.id';
        $direction = $request->get('direction') ? $request->get('direction') : 'desc';
        $page = $request->get('page') ? $request->get('page') : 1;
        $limit = 100;
        $offset = ($page - 1) * $limit;
        
        $listing = Orders::select([
                'orders.*',
                'owner.first_name as owner_first_name',
                'owner.last_name as owner_last_name',
            ])
            ->leftJoin('admins as owner', 'owner.id', '=', 'orders.created_by')
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
    public static function getAll($select = [], $where = [], $orderBy = 'orders.id desc', $limit = null)
    {
        $listing = Orders::orderByRaw($orderBy);

        if(!empty($select))
        {
            $listing->select($select);
        }
        else
        {
            $listing->select([
                'orders.*'
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
        $record = Orders::where('id', $id)
            ->with([
                'owner' => function($query) {
                    $query->select([
                            'id',
                            'first_name',
                            'last_name'
                        ]);
                },
                'customer' => function($query) {
                    $query->select([
                            'id',
                            'phonenumber',
                        ]);
                },
                'statusBy' => function($query) {
                    $query->select([
                            'id',
                            'first_name',
                            'last_name'
                        ]);
                },
                'products' => function($query) {
                    $query->select([
                            'products.id',
                            'title',
                            'amount',
                            'quantity'
                        ]);
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
    public static function getRow($where = [], $orderBy = 'orders.id desc')
    {
        $record = Orders::orderByRaw($orderBy);

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
        $page = new Orders();

        foreach($data as $k => $v)
        {
            $page->{$k} = $v;
        }

        $page->created_by = AdminAuth::getLoginId();
        $page->created = date('Y-m-d H:i:s');
        $page->modified = date('Y-m-d H:i:s');
        if($page->save())
        {
            $page->updateStatusAndLogHistory('status', 'pending', $page->id);
            if(isset($data['title']) && $data['title'])
            {
                $page->slug = Str::slug($page->title) . '-' . General::encode($page->id);
                $page->save();
            }

            return $page;
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
        $page = Orders::find($id);
        foreach($data as $k => $v)
        {
            $page->{$k} = $v;
        }

        $page->modified = date('Y-m-d H:i:s');
        if($page->save())
        {
            if(isset($data['title']) && $data['title'])
            {
                $page->slug = Str::slug($page->title) . '-' . General::encode($page->id);
                $page->save();
            }

            return $page;
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
            return Orders::whereIn('orders.id', $ids)
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
        $page = Orders::find($id);
        return $page->delete();
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
            return Orders::whereIn('orders.id', $ids)
                    ->delete();
        }
        else
        {
            return null;
        }

    }

    /**
	 * Define attributes that need to be logged.
	 */
	public function getActivitylogOptions(): LogOptions {
		return LogOptions::defaults()
			->logAll()
			->useLogName('orders');
	}
}