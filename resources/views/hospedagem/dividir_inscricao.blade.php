@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="title">Dividir Inscrição #{{ $hospedagem->id }}</h3>
                    <p class="mb-1">
                        Usuário: <strong>{{ $hospedagem->user->name }}</strong>
                    </p>
                    <p class="mb-0">
                        UH atual: <strong>{{ $hospedagem->tipouh->descricao }}</strong>
                        — capacidade máxima: <strong>{{ $capacidadeOriginal }}</strong> pessoas.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            A inscrição original será reduzida para as quantidades informadas abaixo.
                            O restante será colocado em uma nova inscrição espelho vinculada ao mesmo usuário.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Inscrição atual</h5>
                                <p>
                                    Adultos: <strong>{{ $hospedagem->adulto }}</strong><br>
                                    Crianças: <strong>{{ $hospedagem->crianca }}</strong><br>
                                    Total: <strong>{{ (int) $hospedagem->adulto + (int) $hospedagem->crianca }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5>Período</h5>
                                <p>
                                    {{ \Carbon\Carbon::parse($hospedagem->data_inicio)->format('d/m/Y') }}
                                    até
                                    {{ \Carbon\Carbon::parse($hospedagem->data_termino)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('hospedagem.dividir.store', Crypt::encrypt($hospedagem->id)) }}"
                              id="form-dividir-inscricao">
                            @csrf

                            <hr>
                            <h5>Pessoas que permanecerão na inscrição original</h5>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="adultos_original">Adultos</label>
                                    <input
                                        type="number"
                                        min="0"
                                        max="{{ $hospedagem->adulto }}"
                                        class="form-control"
                                        id="adultos_original"
                                        name="adultos_original"
                                        value="{{ old('adultos_original', min((int) $hospedagem->adulto, $capacidadeOriginal)) }}"
                                        required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="criancas_original">Crianças</label>
                                    <input
                                        type="number"
                                        min="0"
                                        max="{{ $hospedagem->crianca }}"
                                        class="form-control"
                                        id="criancas_original"
                                        name="criancas_original"
                                        value="{{ old('criancas_original', 0) }}"
                                        required>
                                </div>
                            </div>

                            <div id="alerta-original" class="alert alert-danger" style="display:none;"></div>

                            <hr>
                            <h5>Nova inscrição espelho</h5>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Adultos transferidos</label>
                                    <input type="text" id="adultos_espelho" class="form-control" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Crianças transferidas</label>
                                    <input type="text" id="criancas_espelho" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tipo_espelho_id">Tipo de UH para a inscrição espelho</label>
                                <select
                                    class="custom-select"
                                    id="tipo_espelho_id"
                                    name="tipo_espelho_id"
                                    required>
                                    <option value="">Selecione</option>
                                    @foreach ($tipos as $tipo)
                                        <option
                                            value="{{ $tipo->id }}"
                                            data-capacidade="{{ $tipo->capacidade_maxima }}"
                                            {{ (string) old('tipo_espelho_id') === (string) $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->descricao }} — capacidade {{ $tipo->capacidade_maxima }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="alerta-espelho" class="alert alert-danger" style="display:none;"></div>
                            <div id="resumo-divisao" class="alert alert-secondary"></div>

                            <div class="form-group">
                                <label for="observacao_auditoria">Observação para auditoria</label>
                                <textarea
                                    class="form-control"
                                    id="observacao_auditoria"
                                    name="observacao_auditoria"
                                    maxlength="1000"
                                    rows="3"
                                    placeholder="Ex.: ajuste confirmado por telefone com o usuário.">{{ old('observacao_auditoria') }}</textarea>
                            </div>

                            <div class="form-check mb-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    id="confirmacao_usuario"
                                    name="confirmacao_usuario"
                                    {{ old('confirmacao_usuario') ? 'checked' : '' }}
                                    required>
                                <label class="form-check-label" for="confirmacao_usuario">
                                    Confirmo que o usuário foi contatado e concordou com o desmembramento.
                                </label>
                            </div>

                            <a
                                href="{{ route('hospedagem.verdados', Crypt::encrypt($hospedagem->id)) }}"
                                class="btn btn-secondary">
                                Voltar
                            </a>

                            <button
                                type="submit"
                                id="btn-dividir"
                                class="btn btn-warning">
                                Dividir Inscrição
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

@push('javascript')
<script>
$(function () {
    var adultosAtuais = {{ (int) $hospedagem->adulto }};
    var criancasAtuais = {{ (int) $hospedagem->crianca }};
    var capacidadeOriginal = {{ (int) $capacidadeOriginal }};

    function atualizarResumo() {
        var adultosOriginal = parseInt($('#adultos_original').val(), 10) || 0;
        var criancasOriginal = parseInt($('#criancas_original').val(), 10) || 0;

        var adultosEspelho = adultosAtuais - adultosOriginal;
        var criancasEspelho = criancasAtuais - criancasOriginal;

        var totalOriginal = adultosOriginal + criancasOriginal;
        var totalEspelho = adultosEspelho + criancasEspelho;

        var capacidadeEspelho =
            parseInt($('#tipo_espelho_id option:selected').data('capacidade'), 10) || 0;

        $('#adultos_espelho').val(adultosEspelho);
        $('#criancas_espelho').val(criancasEspelho);

        var erroOriginal =
            adultosOriginal < 0 ||
            criancasOriginal < 0 ||
            adultosOriginal > adultosAtuais ||
            criancasOriginal > criancasAtuais ||
            totalOriginal < 1 ||
            totalOriginal > capacidadeOriginal;

        var erroEspelho =
            totalEspelho < 1 ||
            adultosEspelho < 0 ||
            criancasEspelho < 0 ||
            (
                $('#tipo_espelho_id').val() &&
                capacidadeEspelho > 0 &&
                totalEspelho > capacidadeEspelho
            );

        if (erroOriginal) {
            $('#alerta-original')
                .text(
                    'A inscrição original deve permanecer com pelo menos uma pessoa e respeitar a capacidade máxima de ' +
                    capacidadeOriginal + ' pessoas.'
                )
                .show();
        } else {
            $('#alerta-original').hide();
        }

        if (erroEspelho) {
            $('#alerta-espelho')
                .text(
                    capacidadeEspelho > 0
                        ? 'A inscrição espelho terá ' + totalEspelho +
                          ' pessoa(s), acima da capacidade selecionada de ' +
                          capacidadeEspelho + '.'
                        : 'A divisão deve transferir pelo menos uma pessoa para a inscrição espelho.'
                )
                .show();
        } else {
            $('#alerta-espelho').hide();
        }

        $('#resumo-divisao').html(
            '<strong>Original:</strong> ' + totalOriginal + ' pessoa(s). ' +
            '<strong>Espelho:</strong> ' + totalEspelho + ' pessoa(s).' +
            (
                capacidadeEspelho > 0
                    ? ' Capacidade da nova UH: ' + capacidadeEspelho + '.'
                    : ''
            )
        );

        var tipoSelecionado = !!$('#tipo_espelho_id').val();

        $('#btn-dividir').prop(
            'disabled',
            erroOriginal || erroEspelho || !tipoSelecionado
        );
    }

    $('#adultos_original, #criancas_original, #tipo_espelho_id')
        .on('input change', atualizarResumo);

    atualizarResumo();
});
</script>
@endpush
@endsection
