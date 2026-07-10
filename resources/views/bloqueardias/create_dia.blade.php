@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/litepicker.css') }}"/>

<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Cadastra o dia a ser bloqueado </h3>
                    <small>Obs: se deixar 0 zero ele libera todos dias</small>
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
                                <form id="password-form" action="{{ route('configurarhospedagem.create.dia.post') }}" method="POST">
                                    @csrf
                                 

                                    <div class="row form-group has-error">
                                        <div class="form-group col-sm-12 col-md-12 col-lg-6">
                                             <label class="control-label">{{ __('Dia') }}</label>
                
                               
                               <input type="number" name="dia" id="dia" class="form-control boxed" value="{{ $bloqueioDia->dia }}" maxlength="100" minlength="2" onpaste="return false;" required="" autofocus>
                               <input type="hidden" name="id" value="{{ $bloqueioDia->id }}">
                            @error('dia')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>                      
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary rounded-s"><i class="fas fa-check-circle btn-sm fa-sm"></i>  Cadastrar </button>
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
<script src="{{ asset('js/litepickerBudler.js')}}"></script>
<script src="{{ asset('js/mobilefriendly.js')}}"></script>





@endpush
@endsection