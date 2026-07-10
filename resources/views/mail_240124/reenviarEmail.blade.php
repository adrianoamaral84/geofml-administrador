@component('mail::message')

<h1>Sr(a), {{ $posto}} {{ $name }}, </h1>
<br>

<center>
<div style="color: red; font-size: 16px;">
<b>Favor reenviar o Comprovante!</b>

</div>

</center>
<br>
<div style="font-size: 17px;">

@component('mail::panel')
Sua Solicitação de hospedagem da data {{ $data_ini }} - {{ $data_final }} retornou para o reenvio do Comprovante.

<p>Motivo: <b>{{ $motivo }}</b></p>
@component('mail::button', ['url' => 'https://geofml.5rm.eb.mil.br', 'color' => 'green'])
Acessar o Sistema
@endcomponent
<p>Favor entrar no sistema e reenviar o comprovante de pagamento para liberação de sua unidade.</p>
@endcomponent

</div>


<div style="color: red; text-align: center;">
<small>Atenção! Não responder este e-mail!</small>
</div>
@endcomponent