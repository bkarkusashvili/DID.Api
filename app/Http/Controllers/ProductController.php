<?php

namespace App\Http\Controllers;

use App\Mail\BoughtMail;
use App\Mail\NotBoughtMail;
use App\Models\Subscription;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivatedSite;
use Illuminate\Support\Facades\Mail; 


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
        $frontendUrl = env('FRONTEND_URL');
        $backendUrl = env('APP_URL');
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
                    'orderId ' => $email,
                    'callback' => $frontendUrl . '/activate-site',
                    'callbackError' => $frontendUrl . '/failed-transaction',
                    'preauthorize' => false,
                    'lang' => 'KA',
                    'hookUrl' => $frontendUrl . '/dashboard',
                    'hookUrlV2' => $frontendUrl . '/dashboard',
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
    // public function createSubscriptionTransactionUrl(Request $request)
    // {
    //     $client = new \GuzzleHttp\Client();
    
    //     $authorizationKey = env('PAYZE_AUTHORIZATION_KEY');
    //     $secretKey = env('PAYZE_SECRET_KEY');
    
    //     $productId = $request->input('productId');
    //     $email = $request->input('email');
    
    //     $payload = [
    //         'productId' => $productId,
    //         'hookUrl' => 'https://payze.io',
    //         'email' => $email,
    //         'phone' => '123456723',
    //         'callback' => 'http://localhost:8000/api/subscription/callback',
    //         'callbackError' => 'http://localhost:8000/api/subscription/callback',
    //         'sendEmails' => true,
    //     ];
    
    //     $response = $client->request('POST', 'https://payze.io/v2/api/subscription', [
    //         'json' => $payload,
    //         'headers' => [
    //             'Authorization' => "$authorizationKey:$secretKey",
    //             'accept' => 'application/json',
    //             'content-type' => 'application/json',
    //         ],
    //     ]);
    
    //     $responseData = json_decode($response->getBody(), true);
    
    //     // Redirect the user to the transaction URL
    //     return $responseData;
    // }
    // public function subscriptionCallback(Request $request)
    // {
    //     // Redirect the user to your React application on localhost:3000/dashboard
        
    //     return Redirect::to('http://localhost:3000/dashboard');
    // }


    
    public function justpayCallbackSuccessful(Request $request)
    {
        $siteId = $request->input('site_id');
        $duration = $request->input('duration');
        $transactionId = $request->input('transaction_id');
        $user = Auth::user();
    
        try {
            // Get authenticated user
            
            if (!$user) {
                // If user is not authenticated, return unauthorized response
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            $userId = $user->id;
    
            // Check if the transaction ID already exists
            $existingTransaction = ActivatedSite::where('payment_transaction_id', $transactionId)->first();
            
            if ($existingTransaction) {
                // If the transaction ID already exists, return a response indicating the duplicate transaction
                return response()->json(['error' => 'Transaction ID already exists'], 400);
            }
    
            // Save data to database
            $activatedSite = new ActivatedSite();
            $activatedSite->user_id = $userId;
            $activatedSite->site_id = $siteId;
            $activatedSite->payment_transaction_id = $transactionId;
            $activatedSite->duration_month = $duration;
            $activatedSite->save();
    
            Mail::to($user->email)->send(new BoughtMail());
            
            // Respond to the client
            return response()->json(['activated_site' => $activatedSite], 200);
        } 
        catch (\Exception $e) {
            // Handle exceptions
            \Log::error('Error processing request: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    
    }
    
    
    public function justpayCallbackError(Request $request)
    {
        $user = Auth::user();
        Mail::to($user->email)->send(new NotBoughtMail());
        
    }
}