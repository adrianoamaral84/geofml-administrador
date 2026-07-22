@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-block">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-1">
                                Usuários Mecenas
                            </h4>

                            <p class="text-muted mb-0">
                                Usuários que fazem jus ao benefício de 30% de desconto.
                            </p>
                        </div>

                        <div class="mt-2 mt-md-0">
                            <span class="badge badge-primary" style="font-size: 14px;">
                                Total: {{ $totalMecenas }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-block">
                    <form method="GET" action="{{ route('user.mecenas') }}">
                        <div class="row">
                            <div class="col-md-9">
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    value="{{ $search }}"
                                    placeholder="Buscar por nome, nome de guerra, CPF, identidade ou e-mail"
                                >
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Posto/Graduação</th>
                                    <th>Nome</th>
                                   
                                    
                                    
                                    
                                    <th>OM</th>
                                    
                                    <th>Status</th>
                                    
                                    <th>Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($usuarios as $usuario)
                                    <tr>
                                        <td>
                                            {{ optional($usuario->posto)->sigla ?? '-' }}
                                        </td>
                                        <td>{{ $usuario->name ?: '-' }}</td>

                                       

                                        

                                        <td>
                                            {{ optional($usuario->om)->sigla ?? $usuario->om ?? '-' }}
                                        </td>

                                      
                                        <td>
                                           @if ((int) $usuario->status === 1)
    <span class="badge badge-success">Ativo</span>
@elseif ((int) $usuario->status === 2)
    <span class="badge badge-danger">Inativo</span>
    @elseif ((int) $usuario->status === 5)
    <span class="badge badge-danger">Pré Cadastro</span>
    @elseif ((int) $usuario->status === 3)
    <span class="badge badge-danger">Aguardando</span>
    @elseif ((int) $usuario->status === 6)
    <span class="badge badge-danger">Negado</span>
    @elseif ((int) $usuario->status === 4)
    <span class="badge badge-danger">Expirado</span>
@else
    <span class="badge badge-secondary">Desconhecido</span>
@endif
                                        </td>
                                            <td>
                                            <a
    href="{{ route('usuario.verdados', Crypt::encrypt($usuario->id)) }}"
    class="btn btn-sm btn-info"
    title="Ver dados do usuário"
>
    <i class="fa fa-eye"></i>
</a>
                                                
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            Nenhum usuário mecenas encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $usuarios->appends(['search' => $search])->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection