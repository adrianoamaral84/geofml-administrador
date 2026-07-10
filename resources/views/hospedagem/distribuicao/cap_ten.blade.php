@extends('layouts.app')

@section('content')
<style type="text/css">

a:link { text-decoration: none; }

a:visited { text-decoration: none; }

a:hover { text-decoration: none; }

a:active { text-decoration: none; }

</style>
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title"> Distribuição  </h3>
                    <small>Lista de Oficiais Intermediários, Oficiais Subalternos e SC NS</small> 
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
                                <h3 class="title"> Oficiais Intermediários e Oficiais Subalternos </h3>
                                 <hr>

                                
                                
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                            <th width="10%">Posto / Grad</th>
                                            <th width="35%">Nome</th>
                                            <th width="20%" style="text-align: center;">Tipo</th>
                                            <th width="10%" style="text-align: center;">Data Ini</th>
                                            <th width="10%" style="text-align: center;">Data Term</th>
                                            <th width="10%" style="text-align: center;">Status</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @foreach ($tenCapCMS as $item)     
                                            <tr style="background-color: #CCFF94; color: #000;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($tenCapNOCMS as $item)     
                                            <tr>
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{ $item->descricao }}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            

                                           





                                            @foreach ($tenCapCMSPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($tenCapNOCMSPET as $item)     
                                            <tr style="background-color: #e60000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            

                                            
                                           
                                    </tbody>
                                </table>
                                <small>Legenda:</small>
                                 <table>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #CCFF94; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OMs do CMS </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #dee2e6; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OM dos demais Cmdo Mil A </small></td> 
                                </tr>

                                
                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #9b0000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OMs do CMS</small></td> 
                                </tr>


                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #e60000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OM dos demais Cmdo Mil A</small></td> 
                                </tr>
                               
                                </table>
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

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <h3 class="title"> Oficiais Intermediários e Oficiais Subalternos R1 PTTC </h3>
                                 <hr>

                                
                                
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                            <th width="10%">Posto / Grad</th>
                                            <th width="35%">Nome</th>
                                            <th width="20%" style="text-align: center;">Tipo</th>
                                            <th width="10%" style="text-align: center;">Data Ini</th>
                                            <th width="10%" style="text-align: center;">Data Term</th>
                                            <th width="10%" style="text-align: center;">Status</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @foreach ($tenCapPttcCMS as $item)     
                                            <tr style="background-color: #CCFF94; color: #000;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach
                                            @foreach ($tenCapPttcNOCMS as $item)     
                                            <tr>
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            

                                           

                                            @foreach ($tenCapPttcCMSPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($tenCapPttcNOCMSPET as $item)     
                                            <tr style="background-color: #e60000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            

                                           

                                            
                                           
                                    </tbody>
                                </table>
                               <small>Legenda:</small>
                                 <table>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #CCFF94; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OMs do CMS </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #dee2e6; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OM dos demais Cmdo Mil A </small></td> 
                                </tr>

                                
                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #9b0000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OMs do CMS</small></td> 
                                </tr>


                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #e60000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OM dos demais Cmdo Mil A</small></td> 
                                </tr>
                               
                                </table>
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

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <h3 class="title"> Oficiais Intermediários e Oficiais Subalternos R1 </h3>
                                 <hr>

                                
                                
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                            <th width="10%">Posto / Grad</th>
                                            <th width="35%">Nome</th>
                                            <th width="20%" style="text-align: center;">Tipo</th>
                                            <th width="10%" style="text-align: center;">Data Ini</th>
                                            <th width="10%" style="text-align: center;">Data Term</th>
                                            <th width="10%" style="text-align: center;">Status</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @foreach ($tenCapReserva as $item)     
                                             <tr style="background-color: #CCFF94; color: #000;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($tenCapReservaNOCMSR1 as $item)     
                                            <tr>
                                                <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;"  href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            

                                            

                                            @foreach ($tenCapReservaPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status }}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status }}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($tenCapReservaPETNOCMS as $item)     
                                            <tr style="background-color: #e60000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach















                                           
                                    </tbody>
                                </table>
                               <small>Legenda:</small>
                                 <table>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #CCFF94; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OMs do CMS </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #dee2e6; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OM dos demais Cmdo Mil A </small></td> 
                                </tr>

                                
                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #9b0000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OMs do CMS</small></td> 
                                </tr>


                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #e60000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OM dos demais Cmdo Mil A</small></td> 
                                </tr>
                               
                                </table>
                              
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

     <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <h3 class="title"> Servidor Civil NS </h3>
                                 <hr>

                                
                                
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                            <th width="10%">Posto / Grad</th>
                                            <th width="35%">Nome</th>
                                            <th width="20%" style="text-align: center;">Tipo</th>
                                            <th width="10%" style="text-align: center;">Data Ini</th>
                                            <th width="10%" style="text-align: center;">Data Term</th>
                                            <th width="10%" style="text-align: center;">Status</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @foreach ($ServidorCivilNS as $item)     
                                            <tr>
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($ServidorCivilNSPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach
                                           
                                    </tbody>
                                </table>
                                <small>Legenda:</small>

                                <table>
                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #9b0000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET </small></td> 
                                </tr>
                                </table>
                                
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


      <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <h3 class="title"> Pensionistas de Oficiais Intermediários e Oficiais Subalternos </h3>
                                 <hr>

                                
                                
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                            <th width="10%">Posto / Grad</th>
                                            <th width="35%">Nome</th>
                                            <th width="20%" style="text-align: center;">Tipo</th>
                                            <th width="10%" style="text-align: center;">Data Ini</th>
                                            <th width="10%" style="text-align: center;">Data Term</th>
                                            <th width="10%" style="text-align: center;">Status</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>  


                                            @foreach ($tenCapPensionista as $item)     
                                            <tr style="background-color: #CCFF94; color: #000;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach



                                            @foreach ($tenCapPensionistaNOCMS as $item)     
                                            <tr style="background-color: #dee2e6;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach



                                            @foreach ($tenCapPensionistaPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}} </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                             @foreach ($tenCapPensionistaPETNOCMS as $item)     
                                            <tr style="background-color: #e60000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach
                                           
                                    </tbody>
                                </table>
                                  <small>Legenda:</small>
                                <table>
                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #CCFF94; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OMs do CMS </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #dee2e6; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares das OM dos demais Cmdo Mil A </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #9b0000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OMs do CMS</small></td> 
                                </tr>


                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #e60000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET das OM dos demais Cmdo Mil A</small></td> 
                                </tr>

                                </table>
                              
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


<section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <h3 class="title">  Militares da Força Aérea Brasileira  </h3>
                                 <hr>

                                
                                
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                            <th width="10%">Posto / Grad</th>
                                            <th width="35%">Nome</th>
                                            <th width="20%" style="text-align: center;">Tipo</th>
                                            <th width="10%" style="text-align: center;">Data Ini</th>
                                            <th width="10%" style="text-align: center;">Data Term</th>
                                            <th width="10%" style="text-align: center;">Status</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            
                                        @foreach ($tenCapForcaAerea as $item)     
                                           <tr style="background-color: #0B2161; color: #fff">
                                            
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach




                                             @foreach ($tenCapReservaForcaAereaPTTC as $item)     
                                                <tr style="background-color: #2E64FE; color: #fff">
                                                <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach



                                            


                                            @foreach ($tenCapReservaFORCAAEREAR1 as $item)     
                                            <tr style="background-color: #81BEF7; color: #fff">
                                                <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach



                                            @foreach ($tenCapReservaForcaAereaPTTCPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($tenCapReservaFORCAPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td > {{$item->sigla}} </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach
















                                            @foreach ($tenCapForcaAereaPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach
                                           
                                    </tbody>
                                </table>
                                <small>Legenda:</small>
                                 <table>

                               
                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #0B2161; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares da Ativa da Força Aérea Brasileira </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #2E64FE; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares PTTC da Força Aérea Brasileira </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #81BEF7; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares R1 da Força Aérea Brasileira </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #9b0000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET </small></td> 
                                </tr>
                               
                                </table>
                              
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





    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                <h3 class="title"> Militares da Marinha do Brasil </h3>
                                 <hr>

                                
                                
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                            <th width="10%">Posto / Grad</th>
                                            <th width="35%">Nome</th>
                                            <th width="20%" style="text-align: center;">Tipo</th>
                                            <th width="10%" style="text-align: center;">Data Ini</th>
                                            <th width="10%" style="text-align: center;">Data Term</th>
                                            <th width="10%" style="text-align: center;">Status</th>                                          
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            

                                         @foreach ($tenCapMarinha as $item)     
                                             <tr style="background-color: #424242; color: #fff">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach



                                            @foreach ($tenCapReservaMarinhaPTTC as $item)     
                                            <tr style="background-color: #A4A4A4; color: #fff">
                                              <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach






                                            @foreach ($tenCapReservaMARINHAR1 as $item)     
                                            <tr style="background-color: #FAFAFA; color: #000">
                                                <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #000;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach






                                             @foreach ($tenCapReservaMarinhaPTTCPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach



                                            @foreach ($tenCapMarinhaPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td> {{$item->sigla}}  </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status }}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            @foreach ($tenCapReservaMARINHAPET as $item)     
                                            <tr style="background-color: #9b0000; color: #fff;">
                                               <td > {{$item->sigla}} </td>
                                                <td> 

                                                <a style="color: #fff;" href="{{ route('hospedagem.verdados', ['id' => Crypt::encrypt($item->id)]) }}" title="Ver Dados">         
                                                {{$item->name}}
                                                </a>

                                                </td>
                                                <td style="text-align: center;">{{$item->descricao}}</td>
                                                <td style="text-align: center;">
                                                {{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}
                                                </td>
                                               
                                                <td style="text-align: center;">
                                                   
                                                @if($item->status == "Fila de Espera")
                                                    <h6><span class="badge badge-info">{{ $item->status}}</span></h6>
                                                @endif

                                                @if($item->status == "Aguardando Aprovação")
                                                    <h6><span class="badge badge-secondary">{{ $item->status}}</span></h6>
                                                @endif
                                                    </td>
                     
                                            </tr>
                                            @endforeach

                                            
                                           
                                    </tbody>
                                </table>
                                        <small>Legenda:</small>
                                 <table>

                               
                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #424242; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares da Ativa da Marinha do Brasil </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #A4A4A4; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares PTTC da Marinha do Brasil </small></td> 
                                </tr>

                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #FAFAFA; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> Militares R1 da Marinha do Brasil</small></td> 
                                </tr>


                                <tr>
                                <td style="width: 30px"><div style="width: 20px; height: 20px; background-color: #9b0000; border: #8f8f8f 1px solid;"></div></td>
                                <td> <small> UH PET </small></td> 
                                </tr>


                               
                                </table>
                              
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
<script type="text/javascript">
     function deleteData(id)
     {
         var id = id;
         var url = '{{ route("uh.delete", ":id") }}';
         url = url.replace(':id', id);
         $("#deletearea").attr('action', url);
     }
     function liberaHospedagem(id)
     {
         var id = id;
         var url = '{{ route("hospedagem.liberar", ":id") }}';
         url = url.replace(':id', id);
         $("#aprovapedido").attr('action', url);
     }

     function negaHospedagem(id)
     {
         var id = id;
         var url = '{{ route("hospedagem.negar", ":id") }}';
         url = url.replace(':id', id);
         $("#negarpedido").attr('action', url);
     }

     function formSubmit()
     {
         $("#deletearea").submit();
     }

    function formSubmitAprova()
     {
         $("#aprovapedido").submit();
     }

     function formSubmitNegar()
     {
         $("#negarpedido").submit();
     }

  


    $(document).ready(function() {
        var table = $('#tabela1').DataTable({
    
    lengthChange: false,
    lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 registros', '25 registros', '50 registros', 'Mostrar todos' ]
        ],
    buttons: [

            {
                extend: 'excelHtml5',              
                title: 'GEOFML - Lista Pedidos Hospedagem'
            },
            {
                extend: 'pdfHtml5',
                title: 'GEOFML - Lista Pedidos Hospedagem'
            },
            'print','pageLength',
    ],
    select: true,
    "processing": true,
    "order": [[ 1, "asc" ]],
    stateSave: true,
    language: {          
    "sEmptyTable": "Nenhum registro encontrado",
    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
    "sInfoPostFix": "",
    "sInfoThousands": ".",
    "sLengthMenu": "_MENU_ resultados por página",
    "sLoadingRecords": "Carregando...",
    "sProcessing": "Processando...",
    "sZeroRecords": "Nenhum registro encontrado",
    "sSearch": "Pesquisar",
    "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
    },
    "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
    },
    "select": {
        "rows": {
            "_": " Selecionado %d linhas",
            "0": " Nenhuma linha selecionada",
            "1": " Selecionado 1 linha"
        }
    },
    "buttons": {
        "copy": "Copiar para a área de transferência",
        "copyTitle": "Cópia bem sucedida",
        "copySuccess": {
            "1": "Uma linha copiada com sucesso",
            "_": "%d linhas copiadas com sucesso"
        }
    }


    }
    });
    table.buttons().container().appendTo( '#tabela_wrapper .col-md-6:eq(0)' );
    } ); 
</script>  
    <script src="{{ asset('js/DataTables/datatables.min.js') }}" ></script>  
    <script src="{{ asset('js/DataTables/DataTables-1.10.22/js/dataTables.bootstrap4.min.js') }}" ></script>  
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/dataTables.select.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.bootstrap4.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/dataTables.buttons.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.colVis.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Buttons-1.6.5/js/buttons.html5.min.js') }}" ></script>
    <script src="{{ asset('js/DataTables/Select-1.3.1/js/select.bootstrap4.min.js') }}" ></script>   

@endpush
@endsection