@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Editar Grupo de Tarifa</h3>
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
                                <form id="password-form" action="{{ route('grupo_tarifa.update') }}" method="POST">
                                    @csrf



                 <div class="row form-group has-error">
                                          <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Tipo Unidade Habitacional') }}</label>
                
                                <select name="habitacao" id="habitacao" required class="custom-select mr-sm-2 @error('habitacao') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Tipo UH</option>
                                
                    @foreach($habitacoes as $habitacao)

                    <option value="{{$habitacao->id}}" @if($consulta->unidade_habitacional_id == $habitacao->id) selected @endif>
                       {{$habitacao->descricao}}</option>

                                    @endforeach
                            </select>
                            @error('habitacao')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>

                    </div>

                    <div class="row form-group has-error">
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                            <label class="control-label">Descrição</label>
                                <input type="text" name="nome" id="nome" class="form-control boxed @error('nome') is-invalid @enderror" value="{{$consulta->descricao}}" maxlength="200" minlength="2" onpaste="return false;" autofocus>
                                            <input type="hidden" name="id" id="id" class="form-control boxed" value="{{$consulta->id}}">
                                            @error('nome')
                                            <span class="has-error" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                    </div>

                                      <div class="row form-group has-error">

                                           
                                            @foreach($postos as $postou)

                                                <div class="col-sm-6 col-md-4 col-lg-3">
                                                     <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $postou->id }}" name="posto[]" id="posto{{ $postou->id }}"

                                                    @foreach($consulta->postos as $posto)

                                                        @if($posto->id == $postou->id) checked @endif
                                                    
                                                           
                                                    @endforeach 
                                                    >
                                                   
                                                    



                                                        <label class="form-check-label" for="posto{{ $postou->id }}" >
                                                             {{ $postou->sigla }}
                                                        </label>
                                                    </div>   
                                                </div>                                                               
                                               
                                             @endforeach


                                     </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary btn-sm rounded-s"><i class="fas fa-check-circle fa-sm"></i>  Atualizar </button>
                                             <a href="{{ route('grupo_tarifa.index') }}" class="btn btn-danger btn-sm rounded-s"><i class="fas fa-window-close fa-sm"></i>  Cancelar </a>
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