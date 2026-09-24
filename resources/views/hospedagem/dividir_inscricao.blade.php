@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <h3 class="title">Dividir Inscrição #{{ $hospedagem->id }}</h3>
            <p class="title-description">
                Separe os hóspedes entre a inscrição original e uma nova inscrição espelho.
            </p>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-block">
                <div class="alert alert-info">
                    Total atual: <strong>{{ (int) $hospedagem->adulto + (int) $hospedagem->crianca }}</strong> hóspede(s).
                    Capacidade de referência da inscrição original:
                    <strong>{{ $capacidadeOriginal }}</strong>.
                </div>

                <form method="POST" action="{{ route('hospedagem.dividir.salvar') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $hospedagem->id }}">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Adultos que permanecem na inscrição original</label>
                            <input type="number" min="0" max="{{ $hospedagem->adulto }}"
                                   name="adulto_original" class="form-control"
                                   value="{{ old('adulto_original', $hospedagem->adulto) }}" required>
                            @error('adulto_original')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Crianças que permanecem na inscrição original</label>
                            <input type="number" min="0" max="{{ $hospedagem->crianca }}"
                                   name="crianca_original" class="form-control"
                                   value="{{ old('crianca_original', 0) }}" required>
                            @error('crianca_original')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>UH para a inscrição espelho</label>
                        <select name="unidade_destino" class="custom-select" required>
                            <option value="">Selecione</option>
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}" {{ old('unidade_destino') == $unidade->id ? 'selected' : '' }}>
                                    {{ $unidade->sigla }} -
                                    {{ optional($unidade->tipohabitacao)->descricao }} -
                                    capacidade {{ $unidade->capacidade_ocupacao }}
                                </option>
                            @endforeach
                        </select>
                        @error('unidade_destino')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="alert alert-warning">
                        Ao confirmar, o sistema criará uma inscrição espelho, registrará a auditoria
                        e enviará ao usuário um e-mail para aceitar o remanejamento.
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-code-branch"></i> Dividir Inscrição
                    </button>
                    <a href="{{ route('hospedagem.verdados_aguardando_liberacao', ['id' => Crypt::encrypt($hospedagem->id)]) }}"
                       class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </section>
</article>
@endsection
