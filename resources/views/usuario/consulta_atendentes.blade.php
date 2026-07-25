@extends('layouts.app')

@section('content')

<div class="title-block">
    <h3 class="title">
        Consulta de Atendentes
    </h3>

    <p class="title-description">
        Lista de usuários ativos com a função de atendente.
    </p>
</div>

<section class="section">

    <div class="card mb-4">

        <div
            class="card-header"
            style="padding: 15px 20px;"
        >
            <strong>Pesquisar atendentes</strong>
        </div>

        <div
            class="card-block"
            style="padding: 20px;"
        >

            <form
                method="GET"
                action="{{ route('consulta.atendentes.index') }}"
            >
                <div class="row">

                    <div class="col-md-9">

                        <div class="form-group">

                            <label for="pesquisa">
                                Nome, CPF ou e-mail
                            </label>

                            <input
                                type="text"
                                name="pesquisa"
                                id="pesquisa"
                                class="form-control"
                                value="{{ $pesquisa }}"
                                placeholder="Digite os dados do atendente"
                            >

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div
                            class="form-group"
                            style="padding-top: 29px;"
                        >

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="fa fa-search"></i>
                                Pesquisar
                            </button>

                            <a
                                href="{{ route(
                                    'consulta.atendentes.index'
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Limpar
                            </a>

                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>

    <div class="card">

        <div
            class="card-header"
            style="padding: 15px 20px;"
        >
            <strong>Atendentes ativos</strong>

            <span class="badge badge-primary">
                {{ $atendentes->total() }}
            </span>
        </div>

        <div
            class="card-block"
            style="padding: 20px;"
        >

            @if($atendentes->isEmpty())

                <div class="alert alert-info">
                    Nenhum atendente ativo foi encontrado.
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
                                <th>Nome</th>
                                
                                <th>CPF</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Posto/Graduação</th>
                                <th>Organização Militar</th>
                                <th>Status</th>
                                <th width="130">Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($atendentes as $atendente)

                                <tr>

                                    <td>
                                        {{ $atendente->id }}
                                    </td>

                                    <td>
                                        {{ $atendente->name ?: '-' }}
                                    </td>

                                   

                                    <td>
                                        @if($atendente->cpf)

                                            {{
                                                preg_replace(
                                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                                    '$1.$2.$3-$4',
                                                    $atendente->cpf
                                                )
                                            }}

                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        {{ $atendente->email ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $atendente->telefone ?: '-' }}
                                    </td>

                                    <td>
                                        {{
                                            optional(
                                                $atendente->posto
                                            )->sigla

                                            ?? optional(
                                                $atendente->posto
                                            )->nome

                                            ?? '-'
                                        }}
                                    </td>

                                    <td>
                                        {{
                                            optional(
                                                $atendente->om
                                            )->sigla

                                            ?? optional(
                                                $atendente->om
                                            )->nome

                                            ?? '-'
                                        }}
                                    </td>

                                    <td>
                                        <span
                                            class="badge badge-success"
                                        >
                                            Ativo
                                        </span>
                                    </td>

                                    <td>

                                        <a
                                            href="{{ route(
                                                'usuario.verdados',
                                                ['id' => Crypt::encrypt($atendente->id)]
                                            ) }}"
                                            class="
                                                btn
                                                btn-sm
                                                btn-info
                                            "
                                        >
                                            <i class="fa fa-eye"></i>
                                            Visualizar
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>

                </div>

                <div class="mt-3">
                    {{ $atendentes->links() }}
                </div>

            @endif

        </div>
    </div>

</section>

@endsection