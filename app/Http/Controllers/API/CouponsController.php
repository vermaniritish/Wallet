<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\CouponsResource;
use App\Models\Admin\Coupons;
use App\Models\API\Coupons as APICoupons;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CouponsController extends BaseController
{
    public function index(Request $request)
    {
        $coupon = Coupons::where('coupon_code', $request->get('code'))
            ->where('status', 1)
            ->whereColumn('used', '<', 'max_use')
            ->first();
        if (!$coupon) {
            return Response()->json(['status' => false]);
        }
        
        $coupon->amount = $coupon->amount * 1;
        $coupon->is_percentage = $coupon->is_percentage * 1;

        return Response()
			->json([
				'status' => true,
				'coupon' => $coupon
			]);
    }
}