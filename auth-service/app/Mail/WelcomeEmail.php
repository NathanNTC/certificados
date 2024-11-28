<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;



class WelcomeEmail extends Mailable
{
    public $user;

    // Recebe o usuário para ser enviado na view
    public function __construct($user)
    {
        $this->user = $user;
    }

    // Envio do e-mail
    public function build()
    {
        return $this->subject('Bem-vindo ao nosso serviço!')
                    ->view('emails.welcome')  // View do e-mail
                    ->with([
                        'name' => $this->user->name,  // Passa o nome do usuário para a view
                    ]);
    }
}
