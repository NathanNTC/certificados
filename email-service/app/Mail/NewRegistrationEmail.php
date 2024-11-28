<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRegistrationEmail extends Mailable
{
    use SerializesModels;

    public $email;
    public $password;

    /**
     * Cria uma nova instância de mensagem.
     *
     * @param string $email
     * @param string $password
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Define o conteúdo da mensagem.
     *
     * @return \Illuminate\View\View
     */
    public function build()
    {
        return $this->subject('Bem-vindo! Suas credenciais de acesso')
                    ->view('mails.new_registration');
    }
}
