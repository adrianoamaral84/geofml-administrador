<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
</head>
<body>
    <p>Olá, {{ $hospedagemOriginal->user->name }}.</p>

    <p>
        Sua solicitação de hospedagem precisou de ajustes para atender à capacidade máxima
        das unidades habitacionais.
    </p>

    <p>
        A inscrição original foi dividida em duas acomodações vinculadas ao seu usuário.
        Confira os detalhes no sistema e confirme o remanejamento.
    </p>

    <p>
        <a href="{{ $urlAceite }}">Clique aqui para aprovar as novas acomodações</a>
    </p>

    <p>
        Inscrição original: #{{ $hospedagemOriginal->id }} -
        {{ $hospedagemOriginal->adulto }} adulto(s) e
        {{ $hospedagemOriginal->crianca }} criança(s).
    </p>

    <p>
        Nova inscrição: #{{ $hospedagemEspelho->id }} -
        {{ $hospedagemEspelho->adulto }} adulto(s) e
        {{ $hospedagemEspelho->crianca }} criança(s).
    </p>
</body>
</html>
