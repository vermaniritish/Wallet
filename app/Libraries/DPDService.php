<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\OrderProducts;

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
        pr($payload); die;
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
        $products = OrderProducts::where('order_id', $order->id)->whereIn('id', $data['shipped'])->get();

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
                        "contactName" => trim($order->first_name . ' ' . $order->last_name),
                        "telephone" => $order->customer_phone
                    ],
                    "address" => [
                        "countryCode" => "GB",
                        "postcode" => $order->ship_zip ?? $order->postcode,
                        "street" => $order->ship_address1 ?? $order->address,
                        "locality" => $order->ship_address2 ?? '',
                        "town" => $order->ship_city ?? $order->city,
                        "county" => $order->ship_state ?? $order->state,
                    ],
                    "notificationDetails" => [
                        "email" => $order->customer_email,
                        "mobile" => $order->customer_phone
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