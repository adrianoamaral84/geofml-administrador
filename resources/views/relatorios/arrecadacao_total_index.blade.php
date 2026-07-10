@extends('layouts.app')

@section('content')
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Relatório de Arrecadação Total </h3> 
                    <small>Relatório de arrecadação total!</small>
                </div>
            </div>
        </div>
        <div class="items-search">
           
        </div>
    </div>
    <section class="section">
        <div class="row sameheight-container">
            <div class="col-12">
                <div class="card card-block sameheight-item">
                        <p class="title-description">  </p><br>
                 
                     <form id="profile-form" action="{{ route('arrecadacao.total.view')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                   

                    <div class="row has-error">
                         <div class="form-group col-sm-12 col-md-12 col-lg-12">
                        
                             <label class="control-label">{{ __('Selecione o Ano') }}</label>
                                <select name="ano" id="ano" required="" class="custom-select mr-sm-2 @error('ano') is-invalid @enderror" autocomplete="off">
                                   <option value="">Selecione o ano</option>
                                    
                                    
                                    <option value="2023" @if( $ano  == '2023') selected @endif>2023</option>                                    
                                    <option value="2024" @if( $ano  == '2024') selected @endif>2024</option>                                    
                                    <option value="2025" @if( $ano  == '2025') selected @endif>2025</option>                                    
                                    <option value="2026" @if( $ano  == '2026') selected @endif>2026</option>                                    
                                    <option value="2027" @if( $ano  == '2027') selected @endif>2027</option>                                    
                                    <option value="2028" @if( $ano  == '2028') selected @endif>2028</option>                                    

                                    </select>                             
                           
                                    @error('ano')
                                    <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror


                      </div>
                    </div>

                     




             


                     <hr>
                    <div class="form-group row">
                        <div class="col-sm-12 col-xl-12">
                            <p class="title-description"> 

                             </p><br>
                            
                             <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle fa-sm"></i>  
                                    Ver Relatório
                            </button>                    


                        </div>
                    </div>


                               
                </form>





               
           

                  
                               
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 col-sm-offset-2">
                                 
                                </div>
                           
                    
                </div>
            </div>
        </div>
    </section>
</article>

@endsection