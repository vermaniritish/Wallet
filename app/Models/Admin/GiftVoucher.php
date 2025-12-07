<?php
namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use Illuminate\Support\Str;
use App\Libraries\General;


class GiftVoucher extends AppModel
{
    protected $table = 'gift_vouchers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'sender_name', 'sender_email', 'sender_mobile', 'amount',
        'delivery_mode', 'receiver_name', 'receiver_email',
        'receiver_mobile', 'message', 'order_id', 'status',
        'expiry_date', 'applied'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public static function getListing(Request $request, $where = [])
    {
    	$orderBy = $request->get('sort') ? $request->get('sort') : 'gift_vouchers.id';
    	$direction = $request->get('direction') ? $request->get('direction') : 'desc';
    	$page = $request->get('page') ? $request->get('page') : 1;
    	$limit = self::$paginationLimit;
    	$offset = ($page - 1) * $limit;
    	
        $listing = GiftVoucher::select([
            'gift_vouchers.*',
            'users.first_name as owner_first_name',
            'users.last_name as owner_last_name'
        ])
        ->leftJoin('users', 'users.id', '=', 'gift_vouchers.user_id')
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

    public static function create($data)
    {
    	$product = new GiftVoucher();
    	foreach($data as $k => $v)
    	{
    		$product->{$k} = $v;
    	}
    	$product->created = date('Y-m-d H:i:s');
    	$product->modified = date('Y-m-d H:i:s');
	    return $product;
    }
}
