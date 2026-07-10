@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="title"> Cadastro de Tarifas </h3>
                <small>Cadastra a tarifa da Alta e Baixa temporada conforme os grupos de destino e sua habitação</small>
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
                                <form id="password-form" action="{{ route('tarifas.store') }}" method="POST">
                                    @csrf
                                 
        <div class="row has-error">
            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Tipo Unidade Habitacional') }}</label>
                
                                <select name="habitacao" id="habitacao" required class="custom-select mr-sm-2 @error('habitacao') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione Tipo Unidade Habitacional</option>
                                
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



            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Grupo de Tarifas') }}</label>
                
                                <select name="destinacao" id="destinacao" required class="custom-select mr-sm-2 @error('destinacao') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione um Grupo Tarifas</option>
                                
                                    @foreach($destinacoes as $destinacao)

                                         <option value="{{$destinacao->id}}">{{$destinacao->descricao}}</option>

                                    @endforeach
                            </select>
                            @error('destinacao')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
           
        </div>

        <div class="row has-error">

             <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Baixa Temporada') }}</label>
                            <input type="text" class="form-control boxed @error('valor_baixa') is-invalid @enderror" data-thousands="." data-decimal="," data-prefix="R$ " value="{{ old('valor_baixa') }}" name="valor_baixa" id="valor_baixa" autofocus required maxlength="50" onpaste="return false;">
                            @error('valor_baixa')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>


            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Alta Temporada') }}</label>
                            <input type="text" class="form-control boxed @error('valor') is-invalid @enderror" data-thousands="." data-decimal="," data-prefix="R$ " value="{{ old('valor') }}" name="valor" id="valor" autofocus required maxlength="50" onpaste="return false;">
                            @error('valor')
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



@push('javascript')

    <script src="{{asset('lib/jquery-mask-plugin/dist/jquery.mask.min.js')}}"></script>
    <script>
    $(document).ready(function(){
    
        $('#valor_baixa').mask("#.##0,00", {reverse: true});
        $('#valor').mask("#.##0,00", {reverse: true});
    });
    </script> 
@endpush
@endsection