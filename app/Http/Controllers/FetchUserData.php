<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade

class FetchUserData extends Controller
{
    public function fetchUserData(Request $request)
    {
        $user_id = $request->input('user_id'); // Assuming user_id is sent in the request

        // Query the database
        $user_data = DB::table('users')
            ->where('id', $user_id)
            ->first(); // Retrieve the first matching row

        if ($user_data) {
            return response()->json($user_data); // Return user data as JSON
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}
