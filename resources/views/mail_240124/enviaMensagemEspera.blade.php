@component('mail::message')

<h1>Sr(a), {{ $posto }} {{ $nome }}</h1>
<br>
<center>
<div style="color: green; font-size: 16px;">
<b> Seu pedido de Hospedagem está na Fila de Espera!</b>
</div>
</center>
<br>
<br><center>
<b>Dados do Pedido</b>
</center>
<br>

<center>

@component('mail::table')

| Data Entrada	| Data Saída	| Tipo Unidade  | Diárias | Valor Diária |
|:-----------:|:-----------:|:------:|:------:|:------:|
|{{ \Carbon\Carbon::parse($data_inicio)->format('d/m/Y') }}	|{{ \Carbon\Carbon::parse($data_termino)->format('d/m/Y') }}	|{{ $unidade }}	 | {{ $diarias }}	 |R$ {{ number_format($valortarifa, 2, ',', '.') }} |
@endcomponent


	
@component('mail::button', ['url' => 'https://geofml.5rm.eb.mil.br', 'color' => 'green'])
Acessar o Sistema
@endcomponent

</center>


@component('mail::panel')
Informo que não há vaga para o período solicitado na Área de Lazer do Forte Marechal Luz.
<br>
Seu nome continuará na lista de espera para o mês solicitado. Caso haja desistência de contemplados no período solicitado, a Seção FML o consultará para ocupação.
<br>
<br>
Att,
<br>
<br>
Cel AGNELO - Cmt B Adm Ap / 5ª RM - PD Cap R1 JUAREZ - Gestor de Reservas do FML.
@endcomponent

<center>
{{ config('app.name') }}
</center>
<br>
<div style="color: red; text-align: center;">
<small>Atenção! Não responder este e-mail!</small>
</div>
@endcomponent