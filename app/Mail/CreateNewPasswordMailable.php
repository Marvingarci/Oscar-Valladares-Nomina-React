<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateNewPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    public $subject = "Creación de Contraseña";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.createNewPassword')
            ->with([
                'id' => $this->user->id,
                'temporal_password' => $this->user->temporal_password,
            ]);
    }
}
