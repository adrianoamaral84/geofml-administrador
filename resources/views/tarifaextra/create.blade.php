@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="title"> Cadastra Tarifa Extra</h3>
                <small>Cadastra a tarifa extra</small>
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
                                <form id="password-form" action="{{ route('tarifaextra.store') }}" method="POST">
                                    @csrf
                                 
       

        <div class="row has-error">

             <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Tipo Tarifa Extra') }}</label>
                            <input type="text" class="form-control boxed @error('tarifaextra') is-invalid @enderror" value="{{ old('tarifaextra') }}" name="tarifaextra" id="tarifaextra" autofocus required maxlength="100" onpaste="return false;">
                            @error('tarifaextra')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>


            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Valor') }}</label>
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
    
       
        $('#valor').mask("#.##0,00", {reverse: true});
    });
    </script> 
@endpush
@endsection