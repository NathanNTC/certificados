<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class CheckinEmail extends Mailable
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->view('mails.checkin') // A vista que será usada
                    ->with(['email' => $this->email]); // Passando os dados para a vista
    }
}
