<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\OrderProductRelation as OrderProducts;
use App\Models\Admin\OrderStatusHistory;


class DPDService
{
    protected $username;
    protected $password;
    protected $geoClient;
    protected $baseUrl;

    public function __construct()
    {
        $this->username  = "pindersschoolwear";
        $this->password  = "PINder062025";
        $this->geoClient = "account/3026630";
        $this->baseUrl   = "https://api.dpdlocal.co.uk";
    }

    /**
     * LOGIN
     */
    public function login(): ?string
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->acceptJson()
                ->post($this->baseUrl . '/user/?action=login');

            if ($response->failed()) {
                Log::error('DPD Login Failed', $response->json());
                return null;
            }
            return $response->json('data.geoSession');

        } catch (\Throwable $e) {
            Log::error('DPD Login Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * CREATE SHIPMENT
     */
    public function createShipment($order, $data)
    {
        $geoSession = $this->login();

        if (!$geoSession) {
            return $this->error('DPD login failed');
        }

        $payload = $this->buildPayload($order, $data);
        try {
            $response = Http::withHeaders([
                'GeoClient'  => $this->geoClient,
                'GeoSession' => $geoSession,
                'Accept'     => 'application/json'
            ])->post($this->baseUrl . '/shipping/shipment', $payload);

            if ($response->failed()) {
                return $this->error('Shipment API failed', $response->json());
            }

            $data = $response->json();
            $tracking = $this->extractTracking($data);

            if (!$tracking) {
                return $this->error('Tracking not found', $data);
            }

            return [
                'success' => true,
                'tracking_number' => $tracking,
                'raw' => $data
            ];

        } catch (\Throwable $e) {
            Log::error('DPD Shipment Exception', ['error' => $e->getMessage()]);

            return $this->error('Exception occurred', [
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * PAYLOAD BUILDER (uses your schema)
     */
    private function buildPayload($order, $data)
    {
        $postInfo = OrderStatusHistory::find($data['logs']);
        $postInfo = $postInfo->shipping_info ? json_decode($postInfo->shipping_info, true) : null;
        $products = OrderProducts::where('order_id', $order->id)->whereIn('id', explode(",",$data['ids']))->get();

        $parcelDescription = $products->map(function ($item) {
            $qty = $item->quantity ?? 1;
            return "{$item->product_title} (x{$qty})";
        })->implode(', ');

        $totalWeight = $products->sum(function ($item) {
            return ($item->quantity ?? 1) * 0.5; // assume 0.5kg each
        });

        return [
            "collectionDate" => now()->setTime(9, 0)->toIso8601String(),

            "consignment" => [[
                "parcel" => [[
                    "weight" => $totalWeight ?: 5,
                    "content" => $parcelDescription
                ]],

                "collectionDetails" => [
                    "contactDetails" => [
                        "contactName" => "Pinder's Schoolwear",
                        "telephone" => "01142513275"
                    ],
                    "address" => [
                        "countryCode" => "GB",
                        "postcode" => "S26 2BS",
                        "street" => "Mansfield Road, Aston",
                        "town" => "Sheffield"
                    ]
                ],

                "deliveryDetails" => [
                    "contactDetails" => [
                        "contactName" => $postInfo ? $postInfo['firstName'] : null,
                        "telephone" => $postInfo ? $postInfo['phone'] : null
                    ],
                    "address" => [
                        "countryCode" => "GB",
                        "postcode" => $postInfo ? $postInfo['postalCode'] : '',
                        "street" => $postInfo ? $postInfo['addressLine1'] : '',
                        "locality" => '',
                        "town" => $postInfo ? $postInfo['city'] : null,
                        "county" => $postInfo ? $postInfo['city'] : null,
                    ],
                    "notificationDetails" => [
                        "email" => $postInfo ? $postInfo['email'] : $order->customer_email,
                        "mobile" => $postInfo ? $postInfo['phone'] : $order->customer_phone
                    ]
                ],

                "networkCode" => "2^32",
                "numberOfParcels" => 1,
                "totalWeight" => $totalWeight ?: 5,

                "shippingRef1" => "Order#" . $order->id,

                "deliveryInstructions" => $order->note ?? "Handle with care",

                "parcelDescription" => $parcelDescription,

                "liability" => false
            ]]
        ];
    }

    private function extractTracking($data): ?string
    {
        return $data['data']['shipmentId']
            ?? $data['data']['consignmentDetail'][0]['consignmentNumber']
            ?? $data['data']['consignmentDetail'][0]['parcelNumbers'][0]
            ?? null;
    }

    private function error($message, $data = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'error' => $data
        ];
    }
}