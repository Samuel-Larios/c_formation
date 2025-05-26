<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Utilisateur;

class UtilisateurCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur;
    public $password;

    /**
     * Crée une nouvelle instance du message.
     *
     * @param Utilisateur $utilisateur
     * @param string $password
     */
    public function __construct(Utilisateur $utilisateur, $password)
    {
        $this->utilisateur = $utilisateur;
        $this->password = $password;
    }

    /**
     * Construit le message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Votre compte a été créé')
                    ->view('emails.utilisateur_created');
    }
}
