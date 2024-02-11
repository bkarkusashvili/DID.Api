<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Support\Facades\Auth;

class FetchUserData extends Controller
{
    public function fetchUserData(Request $request)
    {
        $user = Auth::user();
        // $user_id = $request->input('user_id'); // Assuming user_id is sent in the request
        // Query the database
        // $user_data = DB::table('users')
            // ->where('id', $user_id)
            // ->first(); // Retrieve the first matching row

        if ($user) {
            return response()->json($user); // Return user data as JSON
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
