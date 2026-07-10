@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Editar Assinatura </h3>
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
                                <form id="password-form" action="{{ route('assinatura.update') }}" method="POST">
                                    @csrf

                                   <div class="row form-group has-error">
                                        <div class="col-4">
                                             <label class="control-label">{{ __('Função') }}</label>
                            <input type="hidden" name="id" value="{{ $assinatura->id }}">
                            <input type="text" name="funcao" id="funcao" class="form-control boxed @error('funcao') is-invalid @enderror" value="{{ $assinatura->funcao }}" maxlength="255" minlength="2" onpaste="return false;" autofocus>
                            
                                
                            @error('funcao')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                
                                    

                                  
                                        <div class="col-4">
                                            <label class="control-label">Nome de Guera</label>
                                             <input type="text" name="nome" id="nome" class="form-control boxed @error('nome') is-invalid @enderror" value="{{ $assinatura->nome }}" maxlength="255" minlength="2" onpaste="return false;" autofocus>
                                            @error('nome')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror 
                                        </div>
                                    


                                           <div class="col-4">
                                            <label class="control-label" id="nivel">{{ __('Posto / Graduação') }}</label>
                                <select name="posto" id="posto" class="custom-select mr-sm-2 @error('posto') is-invalid @enderror" autocomplete="off">
                                <option value="" >Selecione Posto / Grad</option>
                                @foreach($postos as $posto)                               
                                  @if($assinatura->posto_id == $posto->id)
                                <option value="{{$posto->id}}" selected="selected">{{$posto->sigla}}</option>
                                @else
                                 <option value="{{$posto->id}}">{{$posto->sigla}}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('posto')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>


                                    </div>
                                    
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary  rounded-s"><i class="fas fa-check-circle fa-sm"></i>  Atualizar </button>
                                             <a href="{{ route('email.index') }}" class="btn btn-danger  rounded-s"><i class="fas fa-window-close fa-sm"></i>  Cancelar </a>
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