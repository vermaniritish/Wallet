<?php
namespace App\Libraries;

use App\Models\Admin\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SMSGateway
{
	public static function send($phoneNumber, $message)
	{
		$phoneNumber = preg_replace('/\D/', '', $phoneNumber);
		if(!$phoneNumber || !trim($message)) return null;

		$Authorization = 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJrZXkiOiJhZTE0MDdhMy1lMDg3LTQ3OTYtODhmYy0yZmQzMDY1NDkzY2UiLCJzZWNyZXQiOiJiODI5NDEyMmFhNWJkYjNhNjNhMWY4MjQ5NDE4YTQ0ZWUwYjY3YzQ5MGU2ZTFiMmJjMDExZWUwMzAwOTFhMDFmIiwiaWF0IjoxNjIwNzExMTg3LCJleHAiOjI0MDkxMTExODd9.u2ZnoWnL3SFEhHphX2oq9I7VVC_qp7qKPFgioQ0gXWw';


		$jsonData = array(
			'sender' => 'TheSMSWorks',
			'destination' => $phoneNumber,
			'content' => $message . "\n - Pinder's WorkWear"
		);

		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization:'.$Authorization
			));
			curl_setopt($ch, CURLOPT_URL, "https://api.thesmsworks.co.uk/v1/message/send" );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($ch, CURLOPT_POST, 1 );
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonData));

			$result=curl_exec($ch);
			curl_close($ch);
		}
		catch(\Exception $e)
		{
			$result = $e->getMessage();
		}
		DB::table('sms_logs')->insert([
			'log' => json_encode([
				'request' => $jsonData,
				'response' => $result
			]),
			'created' => date('Y-m-d H:i:s'),	
			'modified' => date('Y-m-d H:i:s')	
		]);
		return $result;
	}
}