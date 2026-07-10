<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \App\User;
use \App\Hospede;
use Crypt;

class HospedagemAlteradoPeriodoAdm extends Mailable
{
    use Queueable, SerializesModels;
    private $hospede;
    /**
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Hospede $hospede)
    {
        return $this->hospede = $hospede; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Período de Hospedagem alterado pela Administração!');
        $this->to($this->hospede->user->email);
        $nome = $this->hospede->user->name;
        $posto = $this->hospede->user->posto->sigla;
        
        return $this->markdown('mail.hospedagemAlteradoPedidoAdm')->with([
              
                    'user' => $this->hospede->id,
                    'nome' => $nome,
                    'unidade' => $this->hospede->tipouh->descricao,
                    'data_inicio' => $this->hospede->data_inicio,
                    'data_termino' => $this->hospede->data_termino,
                    'tipo_unidade' => $this->hospede->und_habitacionais_id,
                    'adultos' => $this->hospede->adultos,
                    'criancas' => $this->hospede->criancas,
                    'pne' => $this->hospede->pne,
                    'pet' => $this->hospede->pet,
                    'valor' => $this->hospede->valor,
                    'valortarifa' => $this->hospede->valortarifa,
                    'posto' => $posto,
                    

                    ]);
    }
}
