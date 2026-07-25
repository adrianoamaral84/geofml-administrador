@extends('layouts.app')

@section('content')

<div class="title-block">
    <h3 class="title">
        Consulta de Usuário
    </h3>

    <p class="title-description">
        Consulte o perfil e os pedidos realizados pelo usuário.
    </p>
</div>

<section class="section">

    {{-- Formulário de pesquisa --}}
    <div class="card mb-4">

                <div class="card-header" style="padding: 15px 20px;">

            <strong>Pesquisar por CPF</strong>
        </div>

        <div class="card-block">

            <form
                method="GET"
                action="{{ route('consulta.usuario.index') }}"
            >
                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">

                            <label for="cpf">
                                CPF do usuário
                            </label>

                            <input
                                type="text"
                                name="cpf"
                                id="cpf"
                                class="form-control"
                                value="{{ old('cpf', $cpfDigitado) }}"
                                placeholder="000.000.000-00"
                                maxlength="14"
                                required
                                autofocus
                            >

                        </div>
                    </div>

                    <div class="col-md-4">

                        <div
                            class="form-group"
                            style="padding-top: 29px;"
                        >
                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="fa fa-search"></i>
                                Consultar
                            </button>

                            <a
                                href="{{ route(
                                    'consulta.usuario.index'
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Limpar
                            </a>
                        </div>

                    </div>

                </div>
            </form>

            {{-- Erros de validação --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    @foreach($errors->all() as $erro)
                        <div>{{ $erro }}</div>
                    @endforeach

                </div>

            @endif

        </div>
    </div>

    {{-- CPF pesquisado, mas usuário não encontrado --}}
    @if($pesquisou && !$usuario && !$errors->any())

        <div class="alert alert-warning">

            <i class="fa fa-exclamation-triangle"></i>

            Nenhum usuário foi encontrado com o CPF informado.

        </div>

    @endif

    {{-- Perfil do usuário --}}
    @if($usuario)

        <div class="card mb-4">

        <div class="card-header" style="padding: 15px 20px;">
    <strong>Perfil do usuário</strong>
</div>

            <div class="card-block">

                <div class="row">

                    <div class="col-md-6">

                        <p>
                            <strong>Nome:</strong><br>
                            {{ $usuario->name ?: '-' }}
                        </p>


                        <p>
                            <strong>CPF:</strong><br>

                            {{
                                preg_replace(
                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                    '$1.$2.$3-$4',
                                    $usuario->cpf
                                )
                            }}
                        </p>

                        <p>
                            <strong>Identidade militar:</strong><br>
                            {{ $usuario->idtMil ?: '-' }}
                        </p>

                        <p>
                            <strong>SIAPE:</strong><br>
                            {{ $usuario->siape ?: '-' }}
                        </p>

                        <p>
                            <strong>E-mail:</strong><br>
                            {{ $usuario->email ?: '-' }}
                        </p>

                    </div>

                    <div class="col-md-6">

                        <p>
                            <strong>Telefone:</strong><br>
                            {{ $usuario->telefone ?: '-' }}
                        </p>

                        <p>
                            <strong>Posto/Graduação:</strong><br>

                            {{
                                optional($usuario->posto)->sigla
                                ?? optional($usuario->posto)->nome
                                ?? '-'
                            }}
                        </p>

                        <p>
                            <strong>Organização Militar:</strong><br>

                            {{
                                optional($usuario->om)->sigla
                                ?? optional($usuario->om)->nome
                                ?? '-'
                            }}
                        </p>

                        <p>
                            <strong>Perfil:</strong><br>

                            {{
                                optional($usuario->perfil)->nome
                                ?? optional($usuario->perfil)->descricao
                                ?? '-'
                            }}
                        </p>

                        

                        <p>
                            <strong>Situação do cadastro:</strong><br>

                            @if($usuario->status == 1)

                                <span class="badge badge-success">
                                    Ativo
                                </span>

                            @elseif($usuario->status == 2)

                                <span class="badge badge-danger">
                                    Inativo
                                </span>

                            @else

                                <span class="badge badge-secondary">
                                    Status {{ $usuario->status }}
                                </span>

                            @endif
                        </p>

                    </div>

                </div>

                <hr>

                <a href="{{ route('usuario.verdados',['id' => Crypt::encrypt($usuario->id)]) }}" class="btn btn-info">
                    <i class="fa fa-user"></i>
                    Abrir cadastro completo
                </a>

            </div>
        </div>

        {{-- Resumo dos pedidos --}}
        <div class="row">

            <div class="col-md-4">

                <div class="card mb-3">

                    <div class="card-block text-center">

                        <h3>
                            {{ $pedidos->count() }}
                        </h3>

                        <span>
                            Total de pedidos
                        </span>

                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card mb-3">

                    <div class="card-block text-center">

                        <h3>
                            {{
                                $pedidos
                                    ->where('status', 2)
                                    ->count()
                            }}
                        </h3>

                        <span>
                            Pedidos aprovados
                        </span>

                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card mb-3">

                    <div class="card-block text-center">

                        <h3>
                            {{
                                $pedidos
                                    ->where('status', 3)
                                    ->count()
                            }}
                        </h3>

                        <span>
                            Pedidos cancelados/negados
                        </span>

                    </div>
                </div>

            </div>

        </div>

        {{-- Histórico dos pedidos --}}
        <div class="card">

                    <div class="card-header" style="padding: 15px 20px;">


                <strong>Pedidos realizados</strong>

                <span class="badge badge-primary">
                    {{ $pedidos->count() }}
                </span>

            </div>

            <div class="card-block">

                @if($pedidos->isEmpty())

                    <div class="alert alert-info">
                        Este usuário ainda não possui pedidos cadastrados.
                    </div>

                @else

                    <div class="table-responsive">

                        <table
                            class="
                                table
                                table-striped
                                table-bordered
                            "
                        >

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data do pedido</th>
                                    <th>Período solicitado</th>
                                    <th>Tipo de UH</th>
                                    <th>Unidade</th>
                                    <th>Diárias</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($pedidos as $pedido)

                                    <tr>

                                        <td>
                                            {{ $pedido->id }}
                                        </td>

                                        <td>
                                            @if($pedido->created_at)

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $pedido->created_at
                                                    )->format(
                                                        'd/m/Y H:i'
                                                    )
                                                }}

                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>

                                            @if($pedido->data_inicio)

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $pedido->data_inicio
                                                    )->format('d/m/Y')
                                                }}

                                            @else
                                                -
                                            @endif

                                            até

                                            @if($pedido->data_termino)

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $pedido->data_termino
                                                    )->format('d/m/Y')
                                                }}

                                            @else
                                                -
                                            @endif

                                        </td>

                                        <td>

                                            {{
                                                optional(
                                                    $pedido->tipouh
                                                )->descricao

                                                ?? optional(
                                                    $pedido->tipouh
                                                )->tipo

                                                ?? optional(
                                                    $pedido->tipouh
                                                )->nome

                                                ?? '-'
                                            }}

                                        </td>

                                        <td>

                                            {{
                                                optional(
                                                    $pedido->undHB
                                                )->numero

                                                ?? optional(
                                                    $pedido->undHB
                                                )->nome

                                                ?? optional(
                                                    $pedido->undHB
                                                )->descricao

                                                ?? '-'
                                            }}

                                        </td>

                                        <td>
                                            {{
                                                $pedido->qntdiarias
                                                ?? '-'
                                            }}
                                        </td>

                                        <td>

                                            @if(
                                                $pedido->valor !== null
                                                && $pedido->valor !== ''
                                            )

                                                R$
                                                {{
                                                    number_format(
                                                        $pedido->valor,
                                                        2,
                                                        ',',
                                                        '.'
                                                    )
                                                }}

                                            @else
                                                -
                                            @endif

                                        </td>

                                        <td>

                                            <span
                                                class="badge badge-secondary"
                                            >
                                                {{
                                                    optional(
                                                        $pedido
                                                            ->status_hospedagem
                                                    )->status

                                                    ?? 'Status '
                                                        . $pedido->status
                                                }}
                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                @endif

            </div>
        </div>

    @endif

</section>

@endsection

@push('javascript')

<script>
document.addEventListener('DOMContentLoaded', function () {

    var campoCpf = document.getElementById('cpf');

    if (!campoCpf) {
        return;
    }

    /*
     * Aplica máscara visual no CPF.
     * O Controller também remove a formatação antes da consulta.
     */
    campoCpf.addEventListener('input', function () {

        var cpf = campoCpf.value.replace(/\D/g, '');

        cpf = cpf.substring(0, 11);

        if (cpf.length > 9) {
            cpf = cpf.replace(
                /(\d{3})(\d{3})(\d{3})(\d{1,2})/,
                '$1.$2.$3-$4'
            );
        } else if (cpf.length > 6) {
            cpf = cpf.replace(
                /(\d{3})(\d{3})(\d{1,3})/,
                '$1.$2.$3'
            );
        } else if (cpf.length > 3) {
            cpf = cpf.replace(
                /(\d{3})(\d{1,3})/,
                '$1.$2'
            );
        }

        campoCpf.value = cpf;
    });

});
</script>

@endpush