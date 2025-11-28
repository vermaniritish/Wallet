<?php
namespace App\Http\Controllers;

use App\Libraries\General;
use App\Libraries\PayPal;
use App\Models\Admin\OrderProductRelation;
use App\Models\Admin\Orders;
use App\Models\Admin\Settings;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    protected $payPalService;

    public function __construct(PayPal $payPalService)
    {
        $this->payPalService = $payPalService;
    }

    public function createOrder(Request $request)
    {
        $booking = Orders::select(['id', 'paypal_payment_data'])->where('prefix_id', $request->get('id'))->limit(1)->first();
        $amount = $request->input('amount');
        $amount = round($amount, 2);
        $order = $this->payPalService->createOrder($request->get('id'), $amount);
        if($order && isset($order['status']) && !$order['status'])
        {
            return response()->json(['status'=> false, 'message' => $order['message']]);
        }
        elseif($order && $order->result && $order->result->id && $booking)
        {
            $booking->paypal_payment_data = $order->result->id;
            $booking->save();
            return response()->json($order);
        }
        else
        {
            return response()->json(['status'=> false]);
        }
    }

    public function captureOrder(Request $request)
    {
        $orderId = $request->input('orderId');
        $capture = $this->payPalService->captureOrder($orderId);
        $order = Orders::where('paypal_payment_data', $orderId)->limit(1)->first();
        
        if($capture && isset($capture['status']) && !$capture['status'])
        {
            return response()->json(['status'=> false, 'message' => $capture['message']]);
        }
        elseif($capture && $capture->result && in_array($capture->result->status, ['APPROVED', 'COMPLETED']))
        {
            if($order)
            {
                $order->paypal_payment_data = json_encode($capture->result);
                $order->paid = 1;
                $order->save();

                // $pros = [];
                $listing = OrderProductRelation::getListing($request, ['order_products.order_id' => $order->id]);
                if($listing->count() > 0)
                foreach($listing->items() as $k => $row):
                	$pros[] = "o {$row->product_title} | *Qty: {$row->quantity}*";
                endforeach;
                $textMessage = "\nId: *{$order->prefix_id}*\nCustomer Name: *{$order->customer_name} - " . ($order->customer ? $order->customer->phonenumber : '') . "*\nAddress: *".implode(', ', array_filter([$order->address]))."*\nBooking Time: *"._d($order->booking_date)." | " . _time($order->booking_time) ."*\n".($order->latitude && $order->longitude ? "Locations:\nhttps://maps.google.com/maps?q={$order->latitude},{$order->longitude}&z=17&hl=en" : "")."\n----------------------------\n".implode("\n", $pros);

                $codes = [
                	'{order_id}' => $order->prefix_id,
                	'{order_information}' => nl2br($textMessage),
                	'{order_button}' => '<br /><br /><a href="'.url('/my-orders/'.$order->prefix_id).'" target="_blank" style="padding:30px;background:pink;">View Order</a>'
                ];

                try
                {
                    $this->sendEmail($request, $order->id);

                    $phone = preg_replace('/\D/', '', $order->customer_phone);
                    if(!$phone && $order->customer && $order->customer->phonenumber)
                    {
                        $phone = preg_replace('/\D/', '', $order->customer->phonenumber);
                    }
                    if($phone){
                        $sent = \App\Libraries\SMSGateway::send(
                            $phone,  
                            "Thank you for ordering with Pinder's Schoolwear. Your order no. is {$order->prefix_id}. Please check you Invoice at \n " . route('admin.orders.download', ['id' => $order->id])
                        );
                    }
                }
                catch(\Exceeption $e)
                {
                    
                }
            }
            return response()->json(['status' => true, 'id' => $order->prefix_id]);
        }
        else
        {
            return response()->json(['status' => false]);
        }
    }

    function sendEmail($request, $id)
	{
		$page = Orders::get($id);
		$where = ['order_products.order_id' => $page->id];
		$listing = OrderProductRelation::getListing($request, $where);
		$html = view(
            "admin/orders/pdf", 
            [
                'page' => $page,
                'listing' => $listing,
                'logo' => Settings::get('logo')
            ]
        )->render();
		$mpdf = new \Mpdf\Mpdf([
			'tempDir' => public_path('/uploads'),
			'mode' => 'utf-8', 
			'orientation' => 'P',
			'format' => [210, 297],
			'setAutoTopMargin' => true,
			'margin_left' => 0,'margin_right' => 0,'margin_top' => 0,'margin_bottom' => 0,'margin_header' => 0,'margin_footer' => 0
		]);
		$mpdf->showImageErrors = true;
		$mpdf->WriteHTML($html);
		$path = '/uploads/orders/Order-'.$page->prefix_id.'.pdf';
		$mpdf->Output(public_path($path), 'F');

		$codes = [
			'{order_id}' => $page->prefix_id,
		];
		if($page->customer_email)
		{
			General::sendTemplateEmail(
				$page->customer_email,
				'order-placed',
				$codes,
				file_exists(public_path($path)) ? [$path] : 
                null
			);	
		}

		$notify = Settings::get('admin_notification_email');
		if($notify)
		{
			General::sendTemplateEmail(
				$notify,
				'order-placed',
				$codes,
				file_exists(public_path($path)) ? [$path] : null
			);
		}
	}

    function successMsg(Request $request)
    {
        return view('message', [
            'orderId' => $request->get('id') 
        ]);
    }

    function errorMsg() 
    {
        return view('message', [
            'error' => true
        ]);
    }
}