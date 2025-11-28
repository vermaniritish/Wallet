<?php
// app/Services/PayPalService.php
namespace App\Libraries;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

class PayPal
{
    private $client;

    public function __construct()
    {
        $environment = config('paypal.settings.mode') === 'sandbox'
            ? new SandboxEnvironment(config('paypal.client_id'), config('paypal.secret'))
            : new ProductionEnvironment(config('paypal.client_id'), config('paypal.secret'));

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($orderId, $amount)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [
              [
                "custom_id" => $orderId,
                "reference_id" =>  General::hash(),
                "amount" =>  [
                  "currency_code" =>  "GBP",
                  "value" =>  $amount
                ]
              ]
            ]
          ];

        try {
            $response = $this->client->execute($request);
            return $response;
        } catch (\Exception $ex) {
            // Handle the exception
           return ['status' => false, 'message' => $ex->getMessage()];
        }
    }

    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');

        try {
            $response = $this->client->execute($request);
            return $response;
        } catch (\Exception $ex) {
            // Handle the exception
           return ['status' => false, 'message' => $ex->getMessage()];
        }
    }
}
