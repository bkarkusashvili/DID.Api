<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SubscriptionTransaction; // Import your SubscriptionTransaction model

class SubscriptionController extends Controller
{
    public function storeSubscription(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'subscription_id' => 'required',
            'subscription_product_id' => 'required',
            'payment_id' => 'required',
            'duration_months' => 'required|integer',
            'product_id' => 'required',
            'chosen_payment_option' => 'required',
        ]);

        // Create a new SubscriptionTransaction record in the database
        SubscriptionTransaction::create($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Subscription transaction created successfully.']);
    }
}
