@extends('layouts.app')

@section('content')

<div class="title-block">
    <h3 class="title">
        Consulta PTTC
    </h3>

    <p class="title-description">
        Lista de usuários ativos identificados como PTTC.
    </p>
</div>

<section class="section">

    {{-- Filtro de pesquisa --}}
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
                           <div class="d-flex align-items-end flex-wrap w-100" style="gap: 15px; margin-bottom: 15px;">
                
                <!-- Campo de Busca (Ocupa o máximo de espaço horizontal disponível) -->
                <div class="flex-grow-1" style="min-width: 250px;">
                    <div class="form-group" style="margin-bottom: 0;">
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

                <!-- Botões (Travados lado a lado na horizontal com espaçamento) -->
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button
                        type="submit"
                        class="btn btn-primary d-inline-flex align-items-center"
                    >
                        <i class="fa fa-search" style="margin-right: 5px;"></i>
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
                                 <th>Posto/Graduação</th>
                                <th>Nome</th>
                                
                                <th>CPF</th>
                               
                                <th>E-mail</th>
                                
                               
                                <th>Organização Militar</th>
                               
                                <th>Status</th>
                                <th width="130">Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($usuariosPttc as $usuario)

                                <tr>

                                    <td>
                                        {{ $usuario->id }}
                                    </td>
                                    <td>
                                        {{
                                            optional(
                                                $usuario->posto
                                            )->sigla

                                            ?? optional(
                                                $usuario->posto
                                            )->nome

                                            ?? '-'
                                        }}
                                    </td>
                                    <td>
                                        {{ $usuario->name ?: '-' }}
                                    </td>

                                   
                                    

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

                                   
                                    

                                    <td>
                                        {{ $usuario->email ?: '-' }}
                                    </td>

                                    
                                    

                                    

                                    <td>
                                        {{
                                            optional(
                                                $usuario->om
                                            )->sigla

                                            ?? optional(
                                                $usuario->om
                                            )->nome

                                            ?? '-'
                                        }}
                                    </td>

                                   
                                    

                                    <td>

                                        <span class="badge badge-success">
                                            Ativo
                                        </span>

                                        <span class="badge badge-info">
                                            PTTC
                                        </span>

                                    </td>

                                    <td>

                                        <a
                                            href="{{ route(
                                                'usuario.verdados',
                                                ['id' => Crypt::encrypt($usuario->id)]
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
                    {{ $usuariosPttc->links() }}
                </div>

            @endif

        </div>
    </div>

</section>

@endsection