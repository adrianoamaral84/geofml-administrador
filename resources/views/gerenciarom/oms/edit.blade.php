@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Editar OM</h3>
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
                                <form id="password-form" action="{{ route('gerenciarom.update') }}" method="POST">
                                    @csrf

                                   
                                      <div class="row form-group has-error">
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <label class="control-label">Sigla</label>

                                            <input type="text" name="sigla" id="sigla" class="form-control boxed" maxlength="100" onpaste="return false;" value="{{$consulta->sigla}}" autofocus>

                                             <input type="hidden" name="id" value="{{$consulta->id}}">
                                            @error('sigla')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>
                                    </div>

                                   
                                        <div class="row form-group has-error">
                                        <div class="col-4">
                                            <label class="control-label">Força</label>
                                             <select name="forca" class="form-control boxed">
                                  
                                  @foreach ($forcas as $forca) 
                                <option value="{{$forca->id}}" @if ($consulta->forca->id == $forca->id) selected 
                                @endif >{{$forca->forca}}</option>
                                  
                                 
                                </option>


                                 @endforeach
                                </select> 
                                @error('forca')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                        </div>
                                    </div>




                                <div class="row form-group has-error">
                                        <div class="col-4">
                                            <label class="control-label">Cidade</label>
                                             <select name="cidade" class="form-control boxed">
                                  
                                  @foreach ($cidades as $cidade) 
                                            
                                  <option value="{{$cidade->id}}" @if ($consulta->cidade->id == $cidade->id) selected 
                                @endif >{{$cidade->descricao}}</option>


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
@endsection