<?php
// app/Http/Controllers/MailController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoughtMail;
use App\Mail\FillFormMail;
use App\Mail\RegistrationMail;

class MailController extends Controller
{
    public function sendMail(Request $request) {
        $email = $request->input('email');
        $type = $request->input('type');

        if (!empty($email)) {
            switch ($type) {
                case 'bought':
                    Mail::to($email)->send(new BoughtMail());
                    break;
                case 'fillform':
                    Mail::to($email)->send(new FillFormMail());
                    break;
                case 'registration':
                    Mail::to($email)->send(new RegistrationMail());
                    break;
                default:
                    return response()->json(['message' => 'Invalid "type" parameter'], 400);
            }

            return response()->json(['message' => 'Email sent successfully'], 200);
        } else {
            return response()->json(['message' => 'Email not provided'], 400);
        }
    }
}
