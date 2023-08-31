<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
class ProductController extends Controller

{
    public function create(Request $request)
    {
        $client = new Client();

        $requestData = $request->input('create_product_data');

        $jsonBody = [
            "name" => $requestData['name'],
            "description" => $requestData['description'],
            "imageUrl" => $requestData['imageUrl'],
            "price" => $requestData['price'],
            "currency" => $requestData['currency'],
            "occurrenceType" => $requestData['occurrenceType'],
            "occurrenceNumber" => $requestData['occurrenceNumber'],
            "occurrenceDuration" => $requestData['occurrenceDuration'],
            "freeTrial" => $requestData['freeTrial'],
            "numberOfFailedRetry" => $requestData['numberOfFailedRetry']
        ];

        $authorizationKey = env('PAYZE_AUTHORIZATION_KEY');
        $secretKey = env('PAYZE_SECRET_KEY');

        $response = $client->request('POST', 'https://payze.io/v2/api/product', [
            'json' => $jsonBody,
            'headers' => [
                "Authorization" => "$authorizationKey:$secretKey",
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        return response()->json($responseData);
    }
    public function justPay(Request $request)
    {
        $apiKey = env('PAYZE_AUTHORIZATION_KEY');
        $apiSecret = env('PAYZE_SECRET_KEY');

        $client = new Client();
    
        $name = $request->input('name');
        $amount = $request->input('amount');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $response = $client->request('POST', 'https://payze.io/api/v1', [
            'json' => [
                'method' => 'justPay',
                'apiKey' => $apiKey,
                'apiSecret' => $apiSecret,
                'data' => [
                    'amount' => $amount,
                    'currency' => 'GEL',
                    'orderId ' => 'giorgipapidze37@gmail.com',
                    'callback' => 'http://localhost:3000/dashboard',
                    'callbackError' => 'http://localhost:3000/dashboard',
                    'preauthorize' => false,
                    'lang' => 'KA',
                    'hookUrl' => 'http://localhost:3000/dashboard',
                    'hookUrlV2' => 'http://localhost:3000/dashboard',
                    'hookRefund' => false,
                ],
            ],
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Process $responseBody and handle payment result

        return response()->json(['response' => $responseData]);
    }
    public function createSubscriptionTransactionUrl(Request $request)
    {
        $client = new \GuzzleHttp\Client();
    
        $authorizationKey = env('PAYZE_AUTHORIZATION_KEY');
        $secretKey = env('PAYZE_SECRET_KEY');
    
        $productId = $request->input('productId');
        $email = $request->input('email');
    
        $payload = [
            'productId' => $productId,
            'hookUrl' => 'https://payze.io',
            'email' => $email,
            'phone' => '123456723',
            'callback' => 'http://localhost:8000/api/subscription/callback',
            'callbackError' => 'http://localhost:8000/api/subscription/callback',
            'sendEmails' => true,
        ];
    
        $response = $client->request('POST', 'https://payze.io/v2/api/subscription', [
            'json' => $payload,
            'headers' => [
                'Authorization' => "$authorizationKey:$secretKey",
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);
    
        $responseData = json_decode($response->getBody(), true);
    
        // Redirect the user to the transaction URL
        return $responseData;
    }
    public function subscriptionCallback(Request $request)
    {
        // Redirect the user to your React application on localhost:3000/dashboard
        
        return Redirect::to('http://localhost:3000/dashboard');
    }

    public function justpayCallback(Request $request)
    {
        // Redirect the user to your React application on localhost:3000/dashboard
        return Redirect::to('http://localhost:3000/dashboard');
    }
}
