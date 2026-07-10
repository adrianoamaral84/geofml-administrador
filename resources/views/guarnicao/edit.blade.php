@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Edita Guarnição </h3>
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
                                <form id="password-form" action="{{ route('guarnicao.update') }}" method="POST">
                                    @csrf
                                    <div class="row form-group has-error">
                                        <div class="col-4">
                                            <label class="control-label">Guarnição</label>
                                            <input type="text" name="nome" id="nome" class="form-control boxed" value="{{$consulta->descricao}}" maxlength="100" onpaste="return false;" autofocus>
                                            <input type="hidden" name="id" value="{{$consulta->id}}">
                                           


                                            
                                            @error('nome')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group has-error">
                                        <div class="col-4">
                                            <label class="control-label">Estado</label>
                                             <select name="estado" class="form-control boxed">

                                 
                                  @foreach ($estados as $estado) 
                                            
                                 
                                <option value="{{$estado->id}}" 
                                @if($consulta->uf->sigla == $estado->sigla) selected 
                                @endif >{{$estado->descricao}}</option>
                            

                                @endforeach
                                </select> 
                                @error('estado')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary btn-sm rounded-s"><i class="fas fa-check-circle btn-sm fa-sm"></i>  Atualizar </button>
                                               <a href="{{ route('guarnicao.index') }}" class="btn btn-danger btn-sm rounded-s"><i class="fas fa-window-close fa-sm"></i>  Cancelar </a>
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