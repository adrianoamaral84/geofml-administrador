@extends('layouts.app')

@section('content')

<div class="title-block">
    <h3 class="title">
        Pedidos duplicados por usuário
    </h3>

    <p class="title-description">
        Pedidos do mesmo usuário dentro do mês selecionado.
    </p>
</div>

<section class="section">
    <div class="card">
        <div class="card-block">

            {{-- Formulário para selecionar o mês --}}
            <form
                method="GET"
                action="{{ route('pedidos.duplicados.index') }}"
                class="form-inline mb-4"
            >
                <div class="form-group mr-2">
                    <label
                        for="competencia"
                        class="mr-2"
                    >
                        <strong>Mês:</strong>
                    </label>

                    <input
                        type="month"
                        id="competencia"
                        name="competencia"
                        class="form-control"
                        value="{{ $competencia }}"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fa fa-search"></i>
                    Consultar
                </button>
            </form>

            {{-- Exibe os erros de validação --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $erro)
                        <div>{{ $erro }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Nenhum pedido duplicado localizado --}}
            @if($pedidos->isEmpty())

                <div class="alert alert-info">
                    Nenhum pedido duplicado encontrado para o
                    mês selecionado.
                </div>

            @else

                {{-- Formulário de exclusão em lote --}}
                <form
                    method="POST"
                    action="{{ route(
                        'pedidos.duplicados.destroySelecionados'
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Confirma a exclusão dos pedidos selecionados? '
                            + 'Esta ação não poderá ser desfeita.'
                        );
                    "
                >
                    @csrf

                    <div class="mb-3">
                        <button
                            type="submit"
                            class="btn btn-danger"
                        >
                            <i class="fa fa-trash"></i>
                            Excluir selecionados
                        </button>
                    </div>

                    {{-- Cada grupo representa um usuário --}}
                    @foreach($pedidos as $userId => $pedidosUsuario)

                        @php
                            $usuario = $pedidosUsuario
                                ->first()
                                ->user;
                        @endphp

                        <div class="card mb-4">

                            <div class="card-header">

                                <strong>
                                    {{
                                        optional($usuario)->name
                                        ?? 'Usuário não localizado'
                                    }}
                                </strong>

                                @if($usuario && $usuario->cpf)
                                    — CPF: {{ $usuario->cpf }}
                                @endif

                                <span
                                    class="badge badge-warning ml-2"
                                >
                                    {{ $pedidosUsuario->count() }}
                                    pedidos
                                </span>

                            </div>

                            <div class="table-responsive">

                                <table
                                    class="
                                        table
                                        table-striped
                                        table-bordered
                                        mb-0
                                    "
                                >
                                    <thead>
                                        <tr>
                                            <th width="45">
                                                <input
                                                    type="checkbox"
                                                    class="marcar-grupo"
                                                    title="
                                                        Selecionar todos
                                                        deste usuário
                                                    "
                                                >
                                            </th>

                                            <th>ID</th>
                                            <th>Período</th>
                                            <th>Tipo de UH</th>
                                            <th>Status</th>
                                            <th>Criado em</th>
                                            <th width="110">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach(
                                            $pedidosUsuario as $pedido
                                        )

                                            <tr>
                                                <td>
                                                    <input
                                                        type="checkbox"
                                                        name="pedidos[]"
                                                        value="{{ $pedido->id }}"
                                                        class="
                                                            pedido-checkbox
                                                        "
                                                    >
                                                </td>

                                                <td>
                                                    {{ $pedido->id }}
                                                </td>

                                                <td>
                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $pedido->data_inicio
                                                        )->format('d/m/Y')
                                                    }}

                                                    a

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $pedido->data_termino
                                                        )->format('d/m/Y')
                                                    }}
                                                </td>

                                                <td>
                                                    {{
                                                        optional(
                                                            $pedido->tipouh
                                                        )->nome

                                                        ?? optional(
                                                            $pedido->tipouh
                                                        )->descricao

                                                        ?? '-'
                                                    }}
                                                </td>

                                                <td>
                                                    {{
                                                        optional(
                                                            $pedido
                                                                ->status_hospedagem
                                                        )->status

                                                        ?? optional(
                                                            $pedido
                                                                ->status_hospedagem
                                                        )->descricao

                                                        ?? $pedido->status
                                                    }}
                                                </td>

                                                <td>
                                                    {{
                                                        optional(
                                                            $pedido->created_at
                                                        )->format(
                                                            'd/m/Y H:i'
                                                        )
                                                    }}
                                                </td>

                                                <td>
                                                    <button
                                                        type="button"
                                                        class="
                                                            btn
                                                            btn-sm
                                                            btn-danger
                                                            excluir-individual
                                                        "
                                                        data-url="{{
                                                            route(
                                                                'pedidos.duplicados.destroy',
                                                                $pedido->id
                                                            )
                                                        }}"
                                                    >
                                                        <i
                                                            class="
                                                                fa
                                                                fa-trash
                                                            "
                                                        ></i>

                                                        Excluir
                                                    </button>
                                                </td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @endforeach

                </form>

                {{--
                    Formulário escondido utilizado para a
                    exclusão individual.
                --}}
                <form
                    id="form-excluir-individual"
                    method="POST"
                    style="display: none;"
                >
                    @csrf
                    @method('DELETE')
                </form>

            @endif

        </div>
    </div>
</section>

@endsection

@push('javascript')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
     * Marca ou desmarca todos os pedidos
     * pertencentes ao mesmo usuário.
     */
    document
        .querySelectorAll('.marcar-grupo')
        .forEach(function (marcador) {

            marcador.addEventListener('change', function () {

                var tabela = marcador.closest('table');

                tabela
                    .querySelectorAll('.pedido-checkbox')
                    .forEach(function (checkbox) {
                        checkbox.checked = marcador.checked;
                    });
            });
        });

    /*
     * Exclusão individual.
     *
     * Reutiliza o formulário escondido e altera
     * dinamicamente sua URL.
     */
    document
        .querySelectorAll('.excluir-individual')
        .forEach(function (botao) {

            botao.addEventListener('click', function () {

                var confirmar = confirm(
                    'Confirma a exclusão deste pedido? '
                    + 'Esta ação não poderá ser desfeita.'
                );

                if (!confirmar) {
                    return;
                }

                var formulario = document.getElementById(
                    'form-excluir-individual'
                );

                formulario.action = botao.getAttribute(
                    'data-url'
                );

                formulario.submit();
            });
        });
});
</script>

@endpush