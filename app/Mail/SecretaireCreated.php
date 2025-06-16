<?php

namespace App\Mail;

use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecretaireCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $secretaire;
    public $password;

    public function __construct(Utilisateur $secretaire, $password)
    {
        $this->secretaire = $secretaire;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Votre compte secrétaire a été créé')
                    ->view('emails.secretaire.created');
    }
}
