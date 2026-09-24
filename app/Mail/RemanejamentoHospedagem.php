<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemanejamentoHospedagem extends Mailable
{
    use Queueable, SerializesModels;

    private $hospedagemOriginal;
    private $hospedagemEspelho;
    private $token;

    public function __construct($hospedagemOriginal, $hospedagemEspelho, $token)
    {
        $this->hospedagemOriginal = $hospedagemOriginal;
        $this->hospedagemEspelho = $hospedagemEspelho;
        $this->token = $token;
    }

    public function build()
    {
        $url = rtrim(config('geofml.internet_url'), '/')
            . '/hospede/remanejamento/'
            . $this->token;

        return $this
            ->subject('Ajuste necessário na sua solicitação de hospedagem')
            ->to($this->hospedagemOriginal->user->email)
            ->view('mail.remanejamento_hospedagem')
            ->with([
                'hospedagemOriginal' => $this->hospedagemOriginal,
                'hospedagemEspelho' => $this->hospedagemEspelho,
                'urlAceite' => $url,
            ]);
    }
}
