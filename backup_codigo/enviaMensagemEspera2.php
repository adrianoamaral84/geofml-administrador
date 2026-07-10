<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \App\User;
use Crypt;


class enviaMensagemEspera extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
       return $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($this->user);
        $this->subject('Solicitação de Acesso!');
        $this->to($this->user->email, $this->user->name);
        $name = $this->user->name;
        $id = Crypt::encrypt($this->user->id);
        $corpo = \App\GerenciarEmails::where('id', 14)->first();
        
        $senha = mt_rand(100000,99999999);
        //dd($number);

        return $this->markdown('mail.enviaMailEspera')->with([
                    'user' => $this->user,
                    'id' => $id,
                    'motivo' => $this->user,
                    'corpo' => $corpo->corpo,  
                    
                ]);

    }
}
