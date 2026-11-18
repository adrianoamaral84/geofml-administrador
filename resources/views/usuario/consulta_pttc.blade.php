@extends('layouts.app')

@section('content')

<style>
    /*
     * Setas de ordenação sem depender do DataTables.
     */
    #tabelaConsultaPttc th.sorting,
    #tabelaConsultaPttc th.sorting_asc,
    #tabelaConsultaPttc th.sorting_desc {
        position: relative;
        cursor: pointer;
        user-select: none;
        padding-right: 30px !important;
    }

    /*
     * Coluna ainda não selecionada:
     * mostra as duas setinhas em cinza.
     */
    #tabelaConsultaPttc th.sorting::before {
        content: "▲";
        position: absolute;
        right: 10px;
        top: 30%;
        font-size: 8px;
        color: #aaa;
    }

    #tabelaConsultaPttc th.sorting::after {
        content: "▼";
        position: absolute;
        right: 10px;
        top: 52%;
        font-size: 8px;
        color: #aaa;
    }

    /*
     * Ordenação crescente.
     */
    #tabelaConsultaPttc th.sorting_asc::after {
        content: "▲";
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
        color: #333;
    }

    /*
     * Ordenação decrescente.
     */
    #tabelaConsultaPttc th.sorting_desc::after {
        content: "▼";
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
        color: #333;
    }

    /*
     * Colunas sem ordenação.
     */
    #tabelaConsultaPttc th.no-sort {
        cursor: default;
    }
</style>


<div class="title-block">
    <h3 class="title">
        Consulta PTTC
    </h3>

    <p class="title-description">
        Lista de usuários ativos identificados como PTTC.
    </p>
</div>


<section class="section">

    {{-- Filtro --}}
    <div class="card mb-4">

        <div
            class="card-header"
            style="padding: 15px 20px;"
        >
            <strong>Pesquisar usuários PTTC</strong>
        </div>

        <div
            class="card-block"
            style="padding: 20px;"
        >

            <form
                method="GET"
                action="{{ route('consulta.pttc.index') }}"
            >

                <div
                    class="d-flex align-items-end flex-wrap w-100"
                    style="gap: 15px; margin-bottom: 15px;"
                >

                    <div
                        class="flex-grow-1"
                        style="min-width: 250px;"
                    >

                        <div
                            class="form-group"
                            style="margin-bottom: 0;"
                        >

                            <input
                                type="text"
                                name="pesquisa"
                                id="pesquisa"
                                class="form-control"
                                value="{{ $pesquisa }}"
                                placeholder="Buscar por nome, CPF, identidade ou e-mail"
                            >

                        </div>

                    </div>


                    <div
                        class="d-flex align-items-center"
                        style="gap: 8px;"
                    >

                        <button
                            type="submit"
                            class="btn btn-primary d-inline-flex align-items-center"
                        >
                            <i
                                class="fa fa-search"
                                style="margin-right: 5px;"
                            ></i>

                            Pesquisar
                        </button>


                        <a
                            href="{{ route('consulta.pttc.index') }}"
                            class="btn btn-secondary"
                        >
                            Limpar
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Listagem --}}
    <div class="card">

        <div
            class="card-header"
            style="padding: 15px 20px;"
        >

            <strong>Usuários PTTC ativos</strong>

            <span class="badge badge-primary">
                {{ $usuariosPttc->total() }}
            </span>

        </div>


        <div
            class="card-block"
            style="padding: 20px;"
        >

            @if($usuariosPttc->isEmpty())

                <div class="alert alert-info">
                    Nenhum usuário PTTC ativo foi encontrado.
                </div>

            @else


                @php

                    /*
                     * Ordenação atual.
                     */
                    $ordemAtual = request('ordem', 'name');

                    $direcaoAtual = request('direcao', 'asc');


                    /*
                     * Gera URL para ordenação.
                     */
                    $linkOrdem = function ($coluna) use (
                        $ordemAtual,
                        $direcaoAtual
                    ) {

                        /*
                         * Ao clicar em uma coluna diferente,
                         * começa em ASC.
                         */
                        $novaDirecao = 'asc';


                        /*
                         * Se clicar novamente na mesma coluna,
                         * alterna ASC / DESC.
                         */
                        if ($ordemAtual === $coluna) {

                            $novaDirecao =
                                $direcaoAtual === 'asc'
                                    ? 'desc'
                                    : 'asc';

                        }


                        /*
                         * Mantém pesquisa e demais parâmetros,
                         * mas remove a página para voltar à página 1.
                         */
                        $parametros = request()->except('page');


                        $parametros['ordem'] = $coluna;

                        $parametros['direcao'] = $novaDirecao;


                        return
                            url()->current()
                            . '?'
                            . http_build_query($parametros);
                    };


                    /*
                     * Define classe visual da ordenação.
                     */
                    $classeOrdem = function ($coluna) use (
                        $ordemAtual,
                        $direcaoAtual
                    ) {

                        if ($ordemAtual === $coluna) {

                            return
                                $direcaoAtual === 'desc'
                                    ? 'sorting_desc'
                                    : 'sorting_asc';

                        }


                        return 'sorting';

                    };

                @endphp


                <div class="table-responsive">

                    <table
                        id="tabelaConsultaPttc"
                        class="table table-striped table-bordered table-hover"
                        style="
                            width: 100%;
                            border-left: 1px solid #ddd !important;
                        "
                    >

                        <thead>

                            <tr>


                                {{-- ID --}}
                                <th
                                    class="{{ $classeOrdem('id') }}"
                                    onclick="window.location.href='{{ $linkOrdem('id') }}'"
                                >
                                    ID
                                </th>


                                {{-- Posto --}}
                                <th
                                    class="{{ $classeOrdem('id_posto') }}"
                                    onclick="window.location.href='{{ $linkOrdem('id_posto') }}'"
                                >
                                    Posto/Graduação
                                </th>


                                {{-- Nome --}}
                                <th
                                    class="{{ $classeOrdem('name') }}"
                                    onclick="window.location.href='{{ $linkOrdem('name') }}'"
                                >
                                    Nome
                                </th>


                                {{-- CPF --}}
                                <th
                                    class="{{ $classeOrdem('cpf') }}"
                                    onclick="window.location.href='{{ $linkOrdem('cpf') }}'"
                                >
                                    CPF
                                </th>


                                {{-- E-mail --}}
                                <th
                                    class="{{ $classeOrdem('email') }}"
                                    onclick="window.location.href='{{ $linkOrdem('email') }}'"
                                >
                                    E-mail
                                </th>


                                {{-- OM --}}
                                <th
                                    class="{{ $classeOrdem('id_om') }}"
                                    onclick="window.location.href='{{ $linkOrdem('id_om') }}'"
                                >
                                    Organização Militar
                                </th>

                                <th class="no-sort">
                                    Mês/Ano
                                </th>

                                {{-- Status --}}
                                <th class="no-sort">
                                    Status
                                </th>


                                {{-- Ações --}}
                                <th
                                    class="no-sort"
                                    width="130"
                                >
                                    Ações
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($usuariosPttc as $usuario)

                                <tr>


                                    {{-- ID --}}
                                    <td>
                                        {{ $usuario->id }}
                                    </td>


                                    {{-- Posto --}}
                                    <td>

                                        {{
                                            optional($usuario->posto)->sigla
                                            ??
                                            optional($usuario->posto)->nome
                                            ??
                                            '-'
                                        }}

                                    </td>


                                    {{-- Nome --}}
                                    <td>
                                        {{ $usuario->name ?: '-' }}
                                    </td>


                                    {{-- CPF --}}
                                    <td>

                                        @if($usuario->cpf)

                                            {{
                                                preg_replace(
                                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                                    '$1.$2.$3-$4',
                                                    $usuario->cpf
                                                )
                                            }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- E-mail --}}
                                    <td>
                                        {{ $usuario->email ?: '-' }}
                                    </td>


                                    {{-- OM --}}
                                    <td>

                                        {{
                                            optional($usuario->om)->sigla
                                            ??
                                            optional($usuario->om)->nome
                                            ??
                                            '-'
                                        }}

                                    </td>

                                    <td>

                                        {{ $usuario->mesAnoFinal ?: '-' }}

                                    </td>
                                    {{-- Status --}}
                                    <td>

                                        <span class="badge badge-success">
                                            Ativo
                                        </span>

                                        <span class="badge badge-info">
                                            PTTC
                                        </span>

                                    </td>


                                    {{-- Ações --}}
                                    <td>

                                        <a
                                            href="{{
                                                route(
                                                    'usuario.verdados',
                                                    [
                                                        'id' =>
                                                            Crypt::encrypt(
                                                                $usuario->id
                                                            )
                                                    ]
                                                )
                                            }}"
                                            class="btn btn-sm btn-info"
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


                {{-- Paginação --}}
                <div class="mt-3">

                    {{ $usuariosPttc->links() }}

                </div>


            @endif

        </div>

    </div>

</section>

@endsection