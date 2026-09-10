@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-12">

            {{-- =========================================================
                CABEÇALHO
            ========================================================== --}}
            <div class="card">

                <div class="card-block">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <div>

                            <h4 class="card-title mb-1">
                                Usuários Mecenas DECEx/MHEx
                            </h4>

                            <p class="text-muted mb-0">
                                Usuários que fazem jus ao benefício de 30% de desconto.
                            </p>

                        </div>

                        <div class="mt-2 mt-md-0">

                            <span
                                class="badge badge-primary"
                                style="font-size: 14px;"
                            >
                                Total: {{ $totalMecenas }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                PESQUISA NO BANCO
            ========================================================== --}}
            <div class="card mt-3">

                <div class="card-block">

                    <form
                        method="GET"
                        action="{{ route('user.mecenas') }}"
                    >

                        <div class="row">

                            <div class="col-md-9">

                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    value="{{ $search }}"
                                    placeholder="Buscar por nome, CPF, identidade ou e-mail"
                                >

                            </div>

                            <div class="col-md-3">

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                >
                                    <i class="fa fa-search"></i>
                                    Buscar
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- =========================================================
                TABELA
            ========================================================== --}}
            <div class="card mt-3">

                <div class="card-block">

                    <div class="table-responsive">

                        <table
    id="tabelaMecenas"
    class="table table-striped table-bordered table-hover flip-content"
>

                            <thead class="flip-header">

                                <tr>

                                    <th>Posto/Graduação</th>

                                    <th>Nome</th>

                                    <th>OM</th>

                                    <th>Status</th>

                                    <th>Ações</th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($usuarios as $usuario)

                                    <tr>

                                        {{-- POSTO / GRADUAÇÃO --}}
                                        <td>
                                            {{
                                                optional($usuario->posto)->sigla
                                                ?? '-'
                                            }}
                                        </td>


                                        {{-- NOME --}}
                                        <td>
                                            {{ $usuario->name ?: '-' }}
                                        </td>


                                        {{-- OM --}}
                                        <td>

                                            @if($usuario->om)

                                                {{
                                                    optional($usuario->om)->sigla
                                                    ??
                                                    optional($usuario->om)->nome
                                                    ??
                                                    '-'
                                                }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- STATUS --}}
                                        <td>

                                            @if ((int) $usuario->status === 1)

                                                <span class="badge badge-success">
                                                    Ativo
                                                </span>

                                            @elseif ((int) $usuario->status === 2)

                                                <span class="badge badge-danger">
                                                    Inativo
                                                </span>

                                            @elseif ((int) $usuario->status === 3)

                                                <span class="badge badge-info">
                                                    Aguardando
                                                </span>

                                            @elseif ((int) $usuario->status === 4)

                                                <span class="badge badge-secondary">
                                                    Expirado
                                                </span>

                                            @elseif ((int) $usuario->status === 5)

                                                <span class="badge badge-warning">
                                                    Pré Cadastro
                                                </span>

                                            @elseif ((int) $usuario->status === 6)

                                                <span class="badge badge-danger">
                                                    Negado
                                                </span>

                                            @else

                                                <span class="badge badge-secondary">
                                                    Desconhecido
                                                </span>

                                            @endif

                                        </td>


                                        {{-- AÇÕES --}}
                                        <td>

                                            <a
                                                href="{{ route(
                                                    'usuario.verdados',
                                                    Crypt::encrypt($usuario->id)
                                                ) }}"
                                                class="btn btn-sm btn-info"
                                                title="Ver dados do usuário"
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

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css"
>

<link
    rel="stylesheet"
    href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"
>

<style>

/*
|--------------------------------------------------------------------------
| WRAPPER
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper {
    width: 100%;
}


/*
|--------------------------------------------------------------------------
| CABEÇALHO DA TABELA
|--------------------------------------------------------------------------
*/

#tabelaMecenas thead th {
    vertical-align: middle;
    white-space: nowrap;
    position: relative;
}


/*
|--------------------------------------------------------------------------
| SETAS DE ORDENAÇÃO
|--------------------------------------------------------------------------
*/

#tabelaMecenas thead th.sorting,
#tabelaMecenas thead th.sorting_asc,
#tabelaMecenas thead th.sorting_desc {
    cursor: pointer !important;
    padding-right: 30px !important;
}


/* Coluna disponível para ordenar */
#tabelaMecenas thead th.sorting::before {
    content: "▲" !important;
    position: absolute !important;
    right: 10px !important;
    top: 32% !important;
    font-size: 8px !important;
    opacity: 0.25 !important;
}

#tabelaMecenas thead th.sorting::after {
    content: "▼" !important;
    position: absolute !important;
    right: 10px !important;
    top: 52% !important;
    font-size: 8px !important;
    opacity: 0.25 !important;
}


/* ASC */
#tabelaMecenas thead th.sorting_asc::before {
    content: "" !important;
}

#tabelaMecenas thead th.sorting_asc::after {
    content: "▲" !important;
    position: absolute !important;
    right: 10px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    font-size: 10px !important;
    opacity: 0.85 !important;
}


/* DESC */
#tabelaMecenas thead th.sorting_desc::before {
    content: "" !important;
}

#tabelaMecenas thead th.sorting_desc::after {
    content: "▼" !important;
    position: absolute !important;
    right: 10px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    font-size: 10px !important;
    opacity: 0.85 !important;
}


/*
|--------------------------------------------------------------------------
| GRUPO DOS BOTÕES
|--------------------------------------------------------------------------
|
| Aparência igual à imagem:
|
| Excel | PDF | Print | Mostrar 10 linhas
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .dt-buttons {
    display: inline-flex !important;
    align-items: stretch !important;

    margin: 0 0 15px 0 !important;

    float: none !important;

    white-space: nowrap !important;
}


/*
|--------------------------------------------------------------------------
| BOTÕES
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .dt-buttons .dt-button {

    position: relative !important;

    display: inline-block !important;

    margin: 0 !important;

    padding: 6px 12px !important;

    min-height: 33px !important;

    line-height: 19px !important;

    font-family: inherit !important;

    font-size: 14px !important;

    font-weight: 400 !important;

    color: #45566c !important;

    background: #ffffff !important;

    background-color: #ffffff !important;

    background-image: none !important;

    border: 1px solid #d6dde5 !important;

    border-radius: 0 !important;

    box-shadow: none !important;

    text-shadow: none !important;

    outline: none !important;

    cursor: pointer !important;
}


/*
 * Remove borda duplicada entre os botões.
 */
#tabelaMecenas_wrapper .dt-buttons .dt-button + .dt-button {
    margin-left: -1px !important;
}


/*
 * Primeiro botão.
 */
#tabelaMecenas_wrapper .dt-buttons .dt-button:first-child {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}


/*
 * Último botão.
 */
#tabelaMecenas_wrapper .dt-buttons .dt-button:last-child {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}


/*
|--------------------------------------------------------------------------
| HOVER
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .dt-buttons .dt-button:hover {

    color: #33475b !important;

    background: #f7f9fb !important;

    background-color: #f7f9fb !important;

    background-image: none !important;

    border: 1px solid #cbd3dc !important;

    box-shadow: none !important;

    text-shadow: none !important;

}


/*
|--------------------------------------------------------------------------
| FOCUS
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .dt-buttons .dt-button:focus {

    color: #33475b !important;

    background: #f7f9fb !important;

    background-image: none !important;

    border-color: #cbd3dc !important;

    box-shadow: none !important;

    outline: none !important;

}


/*
|--------------------------------------------------------------------------
| BOTÃO ATIVO
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .dt-buttons .dt-button:active,
#tabelaMecenas_wrapper .dt-buttons .dt-button.active {

    color: #33475b !important;

    background: #eef2f5 !important;

    background-image: none !important;

    border-color: #cbd3dc !important;

    box-shadow: none !important;

}


/*
|--------------------------------------------------------------------------
| REMOVE CORES ESPECÍFICAS DO DATATABLES
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .buttons-excel,
#tabelaMecenas_wrapper .buttons-pdf,
#tabelaMecenas_wrapper .buttons-print,
#tabelaMecenas_wrapper .buttons-page-length {

    color: #45566c !important;

    background: #ffffff !important;

    background-color: #ffffff !important;

    background-image: none !important;

}


/*
|--------------------------------------------------------------------------
| DROPDOWN "MOSTRAR LINHAS"
|--------------------------------------------------------------------------
*/

div.dt-button-collection {

    background: #ffffff !important;

    border: 1px solid #d6dde5 !important;

    border-radius: 2px !important;

    box-shadow:
        0 2px 8px rgba(0, 0, 0, 0.12) !important;

    padding: 4px !important;

}


div.dt-button-collection .dt-button {

    display: block !important;

    width: 100% !important;

    margin: 0 !important;

    border: none !important;

    border-radius: 0 !important;

    color: #45566c !important;

    background: #ffffff !important;

    text-align: left !important;

}


div.dt-button-collection .dt-button:hover {

    background: #f3f5f7 !important;

    color: #33475b !important;

}


/*
|--------------------------------------------------------------------------
| PESQUISA DO DATATABLES
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .dataTables_filter {
    margin-bottom: 15px;
}


#tabelaMecenas_wrapper .dataTables_filter input {

    border: 1px solid #d6dde5 !important;

    border-radius: 2px !important;

    padding: 5px 8px !important;

    margin-left: 5px !important;

    box-shadow: none !important;

}


/*
|--------------------------------------------------------------------------
| PAGINAÇÃO
|--------------------------------------------------------------------------
*/

#tabelaMecenas_wrapper .dataTables_paginate {
    margin-top: 10px;
}


/*
|--------------------------------------------------------------------------
| COLUNA AÇÕES
|--------------------------------------------------------------------------
*/

#tabelaMecenas td:last-child {
    white-space: nowrap;
}

</style>

@endpush

@push('javascript')

<script type="text/javascript">

$(document).ready(function() {

    var table = $('#tabelaMecenas').DataTable({

        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÃO GERAL
        |--------------------------------------------------------------------------
        */

        lengthChange: false,

        searching: false,

        lengthMenu: [
            [10, 25, 50, -1],
            [
                '10 registros',
                '25 registros',
                '50 registros',
                'Mostrar todos'
            ]
        ],


        /*
        |--------------------------------------------------------------------------
        | BOTÕES
        |--------------------------------------------------------------------------
        |
        | Mesma configuração visual usada
        | na página Usuários do Sistema.
        |--------------------------------------------------------------------------
        */

        buttons: [

            {
                extend: 'excelHtml5',
                title: 'GEOFML - Usuários Mecenas DECEx/MHEx'
            },

            {
                extend: 'pdfHtml5',
                title: 'GEOFML - Usuários Mecenas DECEx/MHEx'
            },

            'print',

            'pageLength'

        ],


        /*
        |--------------------------------------------------------------------------
        | SELEÇÃO
        |--------------------------------------------------------------------------
        */

        select: true,


        /*
        |--------------------------------------------------------------------------
        | PROCESSAMENTO
        |--------------------------------------------------------------------------
        */

        processing: true,


        /*
        |--------------------------------------------------------------------------
        | ORDENAÇÃO INICIAL
        |--------------------------------------------------------------------------
        |
        | 0 = Posto/Graduação
        | 1 = Nome
        | 2 = OM
        | 3 = Status
        | 4 = Ações
        |--------------------------------------------------------------------------
        */

        order: [
            [1, 'asc']
        ],


        /*
        |--------------------------------------------------------------------------
        | NÃO ORDENAR AÇÕES
        |--------------------------------------------------------------------------
        */

        columnDefs: [

            {
                targets: 4,
                orderable: false,
                searchable: false
            }

        ],


        /*
        |--------------------------------------------------------------------------
        | SALVA ESTADO
        |--------------------------------------------------------------------------
        */

        stateSave: true,


        /*
        |--------------------------------------------------------------------------
        | IDIOMA
        |--------------------------------------------------------------------------
        */

        language: {

            "sEmptyTable":
                "Nenhum registro encontrado",

            "sInfo":
                "Mostrando de _START_ até _END_ de _TOTAL_ registros",

            "sInfoEmpty":
                "Mostrando 0 até 0 de 0 registros",

            "sInfoFiltered":
                "(Filtrados de _MAX_ registros)",

            "sInfoPostFix":
                "",

            "sInfoThousands":
                ".",

            "sLengthMenu":
                "_MENU_ resultados por página",

            "sLoadingRecords":
                "Carregando...",

            "sProcessing":
                "Processando...",

            "sZeroRecords":
                "Nenhum registro encontrado",

            "sSearch":
                "Pesquisar",

            "oPaginate": {

                "sNext":
                    "Próximo",

                "sPrevious":
                    "Anterior",

                "sFirst":
                    "Primeiro",

                "sLast":
                    "Último"

            },

            "oAria": {

                "sSortAscending":
                    ": Ordenar colunas de forma ascendente",

                "sSortDescending":
                    ": Ordenar colunas de forma descendente"

            },

            "select": {

                "rows": {

                    "_":
                        " Selecionado %d linhas",

                    "0":
                        " Nenhuma linha selecionada",

                    "1":
                        " Selecionado 1 linha"

                }

            },

            "buttons": {

                "copy":
                    "Copiar para a área de transferência",

                "copyTitle":
                    "Cópia bem sucedida",

                "copySuccess": {

                    "1":
                        "Uma linha copiada com sucesso",

                    "_":
                        "%d linhas copiadas com sucesso"

                }

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | POSICIONA OS BOTÕES
    |--------------------------------------------------------------------------
    |
    | Igual à tela de usuários.
    |--------------------------------------------------------------------------
    */

    table
        .buttons()
        .container()
        .appendTo(
            '#tabelaMecenas_wrapper .col-md-6:eq(0)'
        );

});

</script>


{{-- ============================================================
    MESMOS ARQUIVOS USADOS NA PÁGINA USUÁRIOS DO SISTEMA
============================================================ --}}

<script
    src="{{ asset('js/DataTables/datatables.min.js') }}"
></script>

<script
    src="{{ asset('js/DataTables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js') }}"
></script>

<script
    src="{{ asset('js/DataTables/Buttons-1.6.5/js/dataTables.select.min.js') }}"
></script>

<script
    src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.bootstrap4.min.js') }}"
></script>

<script
    src="{{ asset('js/DataTables/Buttons-1.6.5/js/dataTables.buttons.min.js') }}"
></script>

<script
    src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.colVis.min.js') }}"
></script>

<script
    src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.html5.min.js') }}"
></script>

<script
    src="{{ asset('js/DataTables/Select-1.3.1/js/select.bootstrap4.min.js') }}"
></script>

@endpush