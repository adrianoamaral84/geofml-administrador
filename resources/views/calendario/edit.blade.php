@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Editar Horário </h3>
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
                                <form id="password-form" action="{{ route('horario.update') }}" method="POST">
                                    @csrf

                                   <div class="row form-group has-error">
                                        
                                
                                    

                                  
                                        <div class="col-4">
                                            <label class="control-label">Horário Entrada ( check-in )</label>
                                            <input type="hidden" name="id" value="{{ $consulta->id}}">
                                            <input type="time" name="horaentrada" id="horaentrada" class="form-control boxed" value="{{ $consulta->entrada }}" maxlength="50" minlength="2" onpaste="return false;" autofocus>
                                            @error('horaentrada')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror 
                                        </div>
                                    


                                           <div class="col-4">
                                            <label class="control-label">Horário Saída ( check-out )</label>
                                            <input type="time" name="horasaida" id="horasaida" class="form-control boxed" value="{{ $consulta->saida }}" maxlength="50" minlength="2" onpaste="return false;" autofocus>
                                            @error('horasaida')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                        </div>


                                    </div>
                                    
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <button type="submit" class="btn btn-primary btn-sm rounded-s"><i class="fas fa-check-circle fa-sm"></i>  Atualizar </button>
                                             <a href="{{ route('horario.index') }}" class="btn btn-danger btn-sm rounded-s"><i class="fas fa-window-close fa-sm"></i>  Cancelar </a>
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