@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Editar Posto / Graduação </h3>
                </div>
            </div>
        </div>
        <div class="items-search">
            
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <form id="password-form" action="{{Route('postograduacao.update')}}" method="POST">
                                    @csrf
                                     <div class="row form-group has-error">
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <label class="control-label">Sigla</label>
                                            <input type="text" name="sigla" id="sigla" class="form-control boxed" value="{{$consulta->sigla}}" maxlength="20" onpaste="return false;" autofocus>
                                             <input type="hidden" name="id" id="id" class="form-control boxed" value="{{$consulta->id}}">
                                            @error('sigla')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                    </div>
                                    <div class="row form-group has-error">
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <label class="control-label">Descrição</label>
                                                <input type="text" name="nome" id="nome" class="form-control boxed @error('nome') is-invalid @enderror" value="{{ $consulta->descricao }}" maxlength="50">
 
                                           

                                            @error('nome')
                                            <span class="has-error" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                    </div>


                         <div class="row has-error">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Situação') }}</label>

                                <select name="situacao" id="situacao" required class="custom-select mr-sm-2 @error('situacao') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Situação</option>
                                
                                    @foreach($situacoes as $situacao)

                                    <option value="{{$situacao->id}}" @if($consulta->situacao_id == $situacao->id) selected @endif>
                                    {{$situacao->situacao}}</option>

                                    @endforeach
                            </select>
                            @error('situacao')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                        </div>

                                    
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary btn-sm rounded-s"><i class="fas fa-check-circle fa-sm"></i>  Atualizar </button>
                                             <a href="{{ route('postograduacao.list') }}" class="btn btn-danger btn-sm rounded-s"><i class="fas fa-window-close fa-sm"></i>  Cancelar </a>
                                        </div>
                                    </div>
                                </form>
                               
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 col-sm-offset-2">
                                    
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
@endsection