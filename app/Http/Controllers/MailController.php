<?php

namespace App\Http\Controllers;

use App\Mail\Success;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    //
    public function sendMail(Request $request){
        $email = $request->input('email');
        
        if (!empty($email)) {
            Mail::to($email)->send(new Success());
            return response()->json(['message' => 'Email sent successfully'], 200);
        } else {
            return response()->json(['message' => 'Email not provided'], 400);
        }
    }


}
