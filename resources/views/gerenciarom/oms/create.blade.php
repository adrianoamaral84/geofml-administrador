@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Cadastra OM </h3>
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
                                <form id="password-form" action="{{ route('gerenciarom.create') }}" method="POST">
                                    @csrf

                                   
                                      <div class="row form-group has-error">
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <label class="control-label">Sigla</label>
                                            <input type="text" name="sigla" id="sigla" class="form-control boxed" value="" maxlength="50" onpaste="return false;" autofocus>

                                            <input type="hidden" name="id" id="id" class="form-control boxed" value="{{$id}}" maxlength="20">
                                            
                                        
                                            @error('sigla')
                                        <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                    </div>


                                     <div class="row form-group has-error">
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <label class="control-label">Cidade</label>
                                             <select name="cidade" class="custom-select mr-sm-2 @error('cidade') is-invalid @enderror">
                                  <option value="">Selecione Cidade</option>
                                  @foreach ($cidades as $cidade) 
                                            
                                  <option value="{{$cidade->id}}">{{$cidade->descricao}}</option>
                                 @endforeach
                                </select> 
                                @error('cidade')
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