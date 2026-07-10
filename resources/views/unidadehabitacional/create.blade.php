@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Cadastra Unidade Habitacional </h3>
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
                                <form id="password-form" action="{{ route('uh.store') }}" method="POST">
                                    @csrf
                                 
        <div class="row has-error">
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Tipo') }}</label>
                
                                <select name="tipo" id="tipo" required class="custom-select mr-sm-2 @error('tipo') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Tipo</option>
                                
                                    @foreach($tipos as $tipo)

                                         <option value="{{$tipo->id}}">{{$tipo->descricao}}</option>

                                    @endforeach
                            </select>
                            @error('tipo')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>



            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Classe') }}</label>
                
                                <select name="classe" id="classe" required class="custom-select mr-sm-2 @error('classe') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Tipo</option>
                                
                                    @foreach($classes as $classe)

                                         <option value="{{$classe->id}}">{{$classe->classe}} | {{$classe->descricao}}</option>

                                    @endforeach
                            </select>
                            @error('classe')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Destino') }}</label>
                
                                <select name="destino" id="destino" required class="custom-select mr-sm-2 @error('destino') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Tipo</option>
                                
                                    @foreach($destinos as $destino)

                            <option value="{{$destino->id}}">{{$destino->descricao}}</option>

                                    @endforeach
                            </select>
                            @error('destino')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
        </div>

        <div class="row has-error">

             <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Número') }}</label>
                            <input type="text" class="form-control boxed @error('numero') is-invalid @enderror" value="{{ old('numero') }}" name="numero" id="numero" autofocus required maxlength="10" onpaste="return false;">
                            @error('numero')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
            
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Sala') }}</label>
                
                                <select name="sala" id="sala" required class="custom-select mr-sm-2 @error('sala') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Sala</option>
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                            </select>
                            @error('sala')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Cozinha') }}</label>
                
                                <select name="cozinha" id="cozinha" required class="custom-select mr-sm-2 @error('cozinha') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Cozinha</option>                          
                                         <option value="1">Sim</option>
                                         <option value="0">Não</option>
                            </select>
                            @error('cozinha')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>

        </div>

        <div class="row has-error">

            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Quartos') }}</label>
                            <input type="number" class="form-control boxed @error('quartos') is-invalid @enderror" value="{{ old('quartos') }}" name="quartos" id="quartos" autofocus required maxlength="10" onpaste="return false;">
                            @error('quartos')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Capacidade') }}</label>
                            <input type="number" class="form-control boxed @error('capacidade') is-invalid @enderror" value="{{ old('capacidade') }}" name="capacidade" id="capacidade" autofocus required maxlength="10" onpaste="return false;">
                            @error('capacidade')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Pet') }}</label>
                
                                <select name="pet" id="pet" required class="custom-select mr-sm-2 @error('pet') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione</option>                          
                                         <option value="1">Sim</option>
                                         <option value="0">Não</option>
                            </select>
                            @error('pet')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>




        </div>
        <div class="row has-error">
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Disponível') }}</label>
                
                                <select name="disponivel" id="disponivel" required class="custom-select mr-sm-2 @error('disponivel') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione</option>                          
                                         <option value="1">Sim</option>
                                         <option value="0">Não</option>
                            </select>
                            @error('disponivel')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
           
            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                            <label class="control-label">{{ __('Observação') }}</label>
                            <input type="text" class="form-control boxed @error('observacao') is-invalid @enderror" value="{{ old('observacao') }}" name="observacao" id="observacao" autofocus required maxlength="50" onpaste="return false;">
                            @error('observacao')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>



        </div>

                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary btn-sm rounded-s"><i class="fas fa-check-circle btn-sm fa-sm"></i>  Cadastrar </button>
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