<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \App\Hospede;
use Crypt;


class MailReenviarComprovante extends Mailable
{
    use Queueable, SerializesModels;
    private $hospede;
    private $motivo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Hospede $hospede, $motivo)
    {

        $this->motivo = $motivo;

       return $this->hospede = $hospede;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        $this->subject('Reenviar Comprovante!');
        $this->to($this->hospede->user->email, $this->hospede->user->name);
        $name = $this->hospede->user->name;
        $posto = $this->hospede->user->posto->sigla;

        $data_ini = $this->hospede->data_inicio;
        $data_final = $this->hospede->data_termino;
        
        $id = Crypt::encrypt($this->hospede->id);
        
        $senha = mt_rand(100000,99999999);
        //dd($number);

        return $this->markdown('mail.reenviarEmail')->with([
                    'user' => $this->hospede,
                    'id' => $id,
                    'motivo' => $this->hospede,
                    'posto' => $posto,
                    'name' => $name,
                    'data_ini' => $data_ini,
                    'data_final' => $data_final,
                    'motivo' => $this->motivo,


                ]);

    }
}
