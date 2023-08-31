<?php

namespace App\Mail;

use App\Models\EmailText;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Success extends Mailable
{
    use Queueable, SerializesModels;

    protected $emailContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->emailContent = EmailText::getContentByName('offer_sent');
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('name')
            ->with(['content' => $this->emailContent]);
    }
}
