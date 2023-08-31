<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailText;

class EmailTextController extends Controller
{
    public function getOfferSent()
    {
        $content = EmailText::getContentByName('offer_sent');
        if ($content) {
            return response()->json(['content' => $content]);
        } else {
            return response()->json(['message' => 'Email text not found'], 404);
        }
    }
}
