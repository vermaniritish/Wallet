<?php

namespace App\Http\Controllers;

use App\Models\Admin\Orders;
use App\Libraries\DPDService;
use Illuminate\Http\Request;

class OrderShipmentController extends Controller
{
    protected $dpd;

    public function __construct(DPDService $dpd)
    {
        $this->dpd = $dpd;
    }

    /**
     * SINGLE ORDER
     */
    public function shipOrder(Request $request, $id)
    {
        $order = Orders::with('products')->findOrFail($id);

        $response = $this->dpd->createShipment($order, $request->toArray());
        
        if ($response['success']) {

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'tracking' => $response['tracking_number']
            ]);
        }

        return response()->json([
            'success' => false,
            'order_id' => $order->id,
            'message' => $response['message']
        ], 500);
    }

    /**
     * BULK ORDERS 🔥
     */
    public function shipBulk(Request $request)
    {
        $orderIds = $request->order_ids;

        $orders = Order::with('products')
            ->whereIn('id', $orderIds)
            ->get();

        $results = [
            'success' => [],
            'failed' => []
        ];

        foreach ($orders as $order) {

            $response = $this->dpd->createShipment($order);

            if ($response['success']) {

                $order->update([
                    'shipping_gateway' => 'DPD',
                    'shipment_tracking' => $response['tracking_number'],
                    'status' => 'shipped'
                ]);

                $results['success'][] = [
                    'order_id' => $order->id,
                    'tracking' => $response['tracking_number']
                ];

            } else {

                $results['failed'][] = [
                    'order_id' => $order->id,
                    'message' => $response['message']
                ];
            }
        }

        return response()->json($results);
    }

    public function downloadLabel(Request $request)
    {
        $shipmentId = $request->sn;

        if (!$shipmentId) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment number is required'
            ], 400);
        }

        $format = $request->format ?? 'text/html'; 
        // Options: text/html, application/pdf, etc.

        $result = $this->dpd->getLabel($shipmentId, $format);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 500);
        }

        // ✅ Return as downloadable file
        return response($result['content'])
            ->header('Content-Type', $result['content_type'])
            ->header('Content-Disposition', 'inline; filename="dpd-label-' . $shipmentId . '.html"');
    }
}