@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Editar Configuração PagTesouro </h3>
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
                                <form id="password-form" action="{{ route('pagamento.update') }}" method="POST">
                                    @csrf
                                 
                                    
        


                   <div class="row has-error">

             <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">{{ __('Url') }}</label> <small>( com o HTTPS )</small>
                            <input type="text" class="form-control boxed @error('url') is-invalid @enderror" value="{{ $consulta->url }}" name="url" id="url" autofocus required maxlength="200">

                            <input type="hidden" class="form-control boxed @error('id') is-invalid @enderror" value="{{ $consulta->id }}" name="id" id="id" autofocus required>
                            @error('url')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>

        </div>


                <div class="row has-error">

             <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">{{ __('Código Serviço') }}</label>
                            <input type="number" class="form-control boxed @error('codservico') is-invalid @enderror" value="{{ $consulta->codservico }}" name="codservico" id="codservico" autofocus required maxlength="200">

                            @error('codservico')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>

        </div>

        <div class="row has-error">

            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">{{ __('Token') }}</label>
                            <input type="text" class="form-control boxed @error('token') is-invalid @enderror" value="{{ $consulta->token }}" name="token" id="token" autofocus required>
                            @error('token')
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