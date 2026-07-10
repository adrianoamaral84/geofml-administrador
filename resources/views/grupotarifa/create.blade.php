@extends('layouts.app')

@section('content')

<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Cadastra Grupo de Tarifa </h3>
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
                                <form id="password-form" action="{{ route('grupo_tarifa.store') }}" method="POST">
                                    @csrf
                                 



                <div class="row has-error">
                         <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Tipo Unidade Habitacional') }}</label>
                
                                <select name="habitacao" id="habitacao" required class="custom-select mr-sm-2 @error('habitacao') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Tipo UH </option>
                                
                                    @foreach($habitacoes as $habitacao)

                                    <option value="{{$habitacao->id}}">{{$habitacao->descricao}}</option>

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
                                            <input type="text" name="nome" id="nome" required="" class="form-control boxed" value="{{ old('nome') }}" maxlength="200" minlength="2" onpaste="return false;" autofocus>


                                            @error('nome')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                    </div>

                         


                                    <div class="row form-group has-error">

                                        
                                            @foreach($postos as $posto)

                        <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-check">
                                         <input class="form-check-input" type="checkbox" value="{{ $posto->id }}" name="posto[]" id="posto{{ $posto->id }}">
                                        <label class="form-check-label" for="posto{{ $posto->id }}">
                                            {{ $posto->sigla }}
                                        </label>
                                    </div>   
                                                </div>                                                               

                                             @endforeach


                                     </div>
                                 
                                   
                                   
                                    <hr>


                                    <div class="form-group row">
                                        <div class="col-sm-8 col-md-8 col-lg-8">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle btn-sm fa-sm"></i>  Cadastrar </button>
                                        </div>
                                    </div>
                                </form>
                               
                            </div>
                           
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>


@endsection