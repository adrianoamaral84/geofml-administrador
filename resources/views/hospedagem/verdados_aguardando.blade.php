@extends('layouts.calendario')

@section('content')
<style>
    .distribution-helper { border: 1px solid #d9e2ec; border-radius: 8px; background: #f8fafc; padding: 16px; margin-top: 12px; }
    .distribution-helper__title { font-weight: 700; margin-bottom: 6px; }
    .distribution-helper__text { margin-bottom: 0; color: #52616b; }
    .availability-status { display: none; margin-top: 12px; }
    .availability-status.is-visible { display: block; }
    .availability-summary { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; }
    .availability-summary__item { background: #f8fafc; border: 1px solid #d9e2ec; border-radius: 6px; padding: 8px 12px; }
    .availability-summary__label { display: block; font-size: 12px; color: #6c757d; }
    .availability-summary__value { font-weight: 700; }
    .availability-frame { width: 100%; min-height: 620px; border: 0; background: #fff; }
    .availability-loading { padding: 50px 20px; text-align: center; }
    .availability-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .mapa-legenda { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 14px; }
    .mapa-legenda__item { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; }
    .mapa-legenda__cor { width: 18px; height: 18px; border: 1px solid #cbd5e0; border-radius: 3px; }
    .mapa-scroll { overflow-x: auto; border: 1px solid #d9e2ec; border-radius: 6px; }
    .mapa-table { border-collapse: separate; border-spacing: 0; min-width: 900px; margin-bottom: 0; }
    .mapa-table th, .mapa-table td { text-align: center; vertical-align: middle; white-space: nowrap; }
    .mapa-table thead th { position: sticky; top: 0; z-index: 3; background: #f8fafc; }
    .mapa-table .mapa-uh { position: sticky; left: 0; z-index: 2; min-width: 210px; text-align: left; background: #fff; }
    .mapa-table thead .mapa-uh { z-index: 4; background: #f8fafc; }
    .mapa-dia { width: 42px; min-width: 42px; height: 42px; padding: 0 !important; cursor: default; }
    .mapa-dia--livre { background: #edfdf3; }
    .mapa-dia--ocupada { background: #dc3545; color: #fff; }
    .mapa-dia--checkout { background: #ffc107; color: #212529; }
    .mapa-dia--solicitado { box-shadow: inset 0 0 0 3px #007bff; }
    .mapa-dia--conflito { box-shadow: inset 0 0 0 3px #111827; }
    .mapa-dia--fim-semana { background-image: linear-gradient(rgba(0,0,0,.035), rgba(0,0,0,.035)); }
    .mapa-uh__status { display: block; font-size: 12px; margin-top: 3px; }
    .mapa-uh__status--livre { color: #198754; }
    .mapa-uh__status--ocupada { color: #dc3545; }
    .mapa-selecionar { margin-left: 8px; }

    @media (max-width: 767.98px) {
        .availability-frame { min-height: 520px; }
        .modal-dialog.modal-xl { max-width: calc(100% - 20px); margin: 10px auto; }
    }
    .grupodestinacao {
    width: 100%;
    min-width: 100%;
    height: 40px;
    font-size: 16px;
}

.grupodestinacao option {
    font-size: 16px;
}
</style>

<div class="title-block">
    <h3 class="title"> Dados do Pedido </h3>
    <p class="title-description">Usuário aguardando a confirmação da sua solicitação!</p>
</div>


@if ($hospedagensAnoPassado->count() > 0)

<div>
    <div class="alert alert-danger" role="alert">
            <div align="center" class="card-content">
                            Usuário já foi contemplado na temporada passada!
                        </div>
                    </div>
                </div>
<section class="section">
    <div class="row sameheight-container">
        <div class="col-12">
             <div class="card card-block sameheight-item">
                <p class="title-description">Lista de datas contempladas</p>
                <hr>
              

<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Tipo Unidade Habitacional</th>
      <th scope="col">Data Início</th>
      <th scope="col">Data Final</th>
     
    </tr>
  </thead>

  <tbody>
    @foreach ($hospedagensAnoPassado as $item) 

    <tr>
      <td>{{ $item->tipouh->descricao }}</td>
      <td>{{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}</td>
      <td>{{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}</td>
     
    </tr>
    @endforeach

  </tbody>
</table>



</div>
</div>
</div>
</section>
@endif




@if ($ContempladoNessaTemporada->count() > 0)
<div>
    <div class="alert alert-danger" role="alert">
            <div align="center" class="card-content">
                            Usuário já foi contemplado nessa Alta Temporada!
                        </div>
                    </div>
                </div>
<section class="section">
    <div class="row sameheight-container">
        <div class="col-12">
             <div class="card card-block sameheight-item">
                <p class="title-description">Lista de datas contempladas</p>
                <hr>
              

<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Tipo Unidade Habitacional</th>
      <th scope="col">Data Início</th>
      <th scope="col">Data Final</th>
     
    </tr>
  </thead>

  <tbody>
    @foreach ($ContempladoNessaTemporada as $item) 

    <tr>
      <td>{{ $item->tipouh->descricao }}</td>
      <td>{{ \Carbon\Carbon::parse($item->data_inicio)->format('d/m/Y') }}</td>
      <td>{{ \Carbon\Carbon::parse($item->data_termino)->format('d/m/Y') }}</td>
     
    </tr>
    @endforeach

  </tbody>
</table>



</div>
</div>
</div>
</section>
@endif
@if($hospedagem->user->mecenas)
<div class="alert alert-success shadow-sm border-left-success mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-award fa-2x mr-3"></i>

        <div>
            <strong>Usuário Mecenas</strong><br>
            Este militar participa do Programa Mecenas e possui
            <strong>{{ $hospedagem->user->percentual_desconto }}% de desconto</strong>
            nas diárias.
            <br>
            <small>
                Todos os pagamentos gerados pelo PagTesouro já utilizam o valor com desconto.
            </small>
        </div>
    </div>
</div>
@endif


<section class="section">
    <div class="row sameheight-container">
        <div class="col-12">
           
            <div class="card card-block sameheight-item">
                
                <p class="title-description">  </p><br>
                <form id="profile-form" action="{{ route('hospedagem.liberar')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row has-error">

                        <div class="form-group col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Posto / Graduação') }}</label>
                            <input type="text" class="form-control boxed @error('posto') is-invalid @enderror" value="{{ $hospedagem->user->posto->sigla}}" name="posto" id="posto" autofocus required readonly="" maxlength="100" onpaste="return false;" style="text-transform: uppercase;">
                            <input type="hidden" name="id1" value="{{ $hospedagem->id }}" placeholder="">
                            <input type="hidden" name="posto_id" value="{{ $hospedagem->user->posto->id}}">
                            
                            @error('posto')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Nome') }}</label>
                            <input type="text" class="form-control boxed @error('nome') is-invalid @enderror" value="{{ $hospedagem->user->name}}" name="nome" id="nome" autofocus required readonly="" maxlength="100" onpaste="return false;" style="text-transform: uppercase;">
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($hospedagem) }}" placeholder="">
                            
                            @error('nome')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-3 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('CPF') }}</label>
                            <input type="text" class="form-control boxed @error('cpf') is-invalid @enderror" readonly="" value="{{ $hospedagem->user_cpf }}" name="cpf" id="cpf" required readonly="" autofocus maxlength="11" data-mask="000.000.000-00" onpaste="return false;">
                            @error('cpf')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
              
                    </div>

                    <div class="row has-error">

                        <div class="form-group col-sm-3 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('UF') }}</label>
                            <input type="text" class="form-control boxed @error('uf') is-invalid @enderror" readonly="" value="{{ $hospedagem->user->uf->descricao }}" name="uf" id="uf" required readonly="" autofocus onpaste="return false;">
                            @error('uf')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                         <div class="form-group col-sm-3 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Cidade') }}</label>
                            <input type="text" class="form-control boxed @error('cidade') is-invalid @enderror" readonly="" value="{{ $hospedagem->user->cidade->descricao }}" name="cidade" id="cidade" required readonly="" autofocus onpaste="return false;">
                            @error('cidade')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group col-sm-3 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('OM') }}</label>
                            <input type="text" class="form-control boxed @error('om') is-invalid @enderror" readonly="" value="{{ $hospedagem->user->om->sigla }}" name="om" id="om" required readonly="" autofocus onpaste="return false;">
                            @error('om')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-3 col-md-3 col-lg-3">
    <label class="control-label">Mecenas</label>

    <input type="text"
        class="form-control"
        value="{{ $hospedagem->user->mecenas ? 'Sim ('.$hospedagem->user->percentual_desconto.'% de desconto)' : 'Não' }}"
        readonly>

    <input type="hidden" name="mecenas" value="{{ $hospedagem->user->mecenas }}">
</div>

</div>




                       <div class="row has-error">

                        <div class="form-group col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Adulto') }}</label>
                            <input type="text" class="form-control boxed @error('adulto') is-invalid @enderror" readonly="" value="{{ $hospedagem->adulto }}" name="adulto" id="adulto" required autofocus onpaste="return false;">
                            @error('adulto')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                         <div class="form-group col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Criança') }}</label>
                            <input type="text" class="form-control boxed @error('crianca') is-invalid @enderror" readonly="" value="{{ $hospedagem->crianca }}" name="crianca" id="crianca" required autofocus onpaste="return false;">
                            @error('crianca')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group col-sm-12 col-md-3 col-lg-3">
                        
                            <label class="control-label">{{ __('PNE') }}</label>
                                <select id="pne_visual" class="custom-select mr-sm-2 @error('pne') is-invalid @enderror" disabled>
                                
                                 @if($hospedagem->pne == 1)
                                 <option value="1" selected >Sim</option>
                                @else
                                <option value="0">Não</option>
                                @endif   
                                
                                </select>
                                
                                
                                    @error('pne')
                                    <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                        </div>


                    <div class="form-group col-sm-12 col-md-3 col-lg-3">

                        <label class="control-label">{{ __('PET') }}</label>
                                <select id="pet_visual" class="custom-select mr-sm-2 @error('pet') is-invalid @enderror" disabled>
                                
                                 @if($hospedagem->pet == 1)
                                 <option value="1" selected >Sim</option>
                                @else
                                <option value="0">Não</option>
                                @endif   

                                
                                </select>
                               
                           
                                    @error('pet')
                                    <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>

                                    @enderror
                        
                            
                           
                            
                        </div>


                    </div>

                    <div class="row has-error">


                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">{{ __('Observação') }}</label>
                            <input type="text" class="form-control boxed @error('observacao1') is-invalid @enderror" readonly="" value="{{$hospedagem->observacao}}" name="observacao1" id="observacao1" required readonly="" autofocus onpaste="return false;">
                             @error('observacao1')
                            <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>





                    </div>
                    
                    <div class="row has-error">

                        <div class="form-group col-sm-12 col-md-6 col-lg-6">
                            <label class="control-label">{{ __('Tipo Unidade Habitacional') }}</label>
                            <input type="text" class="form-control boxed @error('unidade_habitacional') is-invalid @enderror" readonly="" value="{{ $hospedagem->tipouh->descricao }}" name="unidade_habitacional" id="unidade_habitacional" required readonly="" autofocus onpaste="return false;">
                            <input type="hidden" name="unidade_habitacional_id" value="{{ $hospedagem->tipouh->id }}">
                            @error('unidade_habitacional')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                         
                        
                    


                        @role('administrador_geral|auxiliar_administrador_geral')
                        
                         @if($hospedagem->checkin == null)
                         @if($hospedagem->status != 6  or $liberaDistribuir == 1)
                         <div class="form-group col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Período Entrada') }}</label>
                            <input type="text" class="form-control boxed @error('peridoinicial') is-invalid @enderror" value="{{ \Carbon\Carbon::parse($hospedagem->data_inicio)->format('d-m-Y') }}" name="peridoinicial1" id="peridoinicial" required autofocus onpaste="return false;">
                            @error('peridoinicial')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Período Saída') }}</label>
                            <input type="text" class="form-control boxed @error('final') is-invalid @enderror" value="{{ \Carbon\Carbon::parse($hospedagem->data_termino)->format('d-m-Y') }}" name="final1" id="final" required autofocus onpaste="return false;">
                            @error('final')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @endif
                        @endif
                        @endrole
                        
                         
                        

                    </div>






                    @role('administrador_geral|auxiliar_administrador_geral')                 
                    
                    <div class="row has-error">

                        @if($hospedagem->checkin == null)
                        @if($hospedagem->status != 6  or $liberaDistribuir == 1)
                        <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                
                             <label class="control-label">{{ __('Grupo Destinação') }}</label>
                                <select name="grupodestinacao" id="grupodestinacao" required class="custom-select mr-sm-2 @error('grupodestinacao') is-invalid @enderror" autocomplete="off">
                                   <option value="">Selecione Grupo Destinação</option>
                                    
                                    @foreach($grupoDestino as $grupo)
                                          <option value="{{$grupo->id}}">{{$grupo->descricao}}</option>
                                    @endforeach

                                                                                   
                                </select>
                               
                           
                                    @error('grupodestinacao')
                                    <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror


                        </div>

                       
                        <div class="form-group col-sm-12 col-md-6 col-lg-6">
                               
                            <div id="unidadeshabitacionaisdiv">
                                <label class="control-label">{{ __('Unidades Habitacionais') }}</label>

                                <select name="unidadeshabitacionais" id="unidadeshabitacionais" required
                                    class="custom-select mr-sm-2 @error('unidadeshabitacionais') is-invalid @enderror"
                                    autocomplete="off">
                                    <option value="" disabled selected>Selecione uma unidade habitacional</option>
                                </select>

                                @error('unidadeshabitacionais')
                                    <span class="has-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="distribution-helper">
                                    <div class="distribution-helper__title">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Consulta de disponibilidade
                                    </div>
                                    <p class="distribution-helper__text">
                                        Selecione uma unidade para visualizar, em uma janela ampla,
                                        as hospedagens já agendadas e os dias livres no período informado.
                                    </p>
                                    <div id="availabilityStatus" class="availability-status alert alert-info mb-0 mt-3">
                                        Selecione uma unidade habitacional para consultar o calendário.
                                    </div>
                                    <button
    type="button"
    id="btnAbrirCalendario"
    class="btn btn-primary btn-sm mt-3"
    disabled
>
    <i class="fas fa-calendar-check"></i>
    Ver calendário da unidade
</button>

<button
    type="button"
    id="btnAbrirMapaOcupacao"
    class="btn btn-secondary btn-sm mt-3 ml-2"
    disabled
>
    <i class="fas fa-th"></i>
    Ver mapa de ocupação
</button>
                                </div>
                            </div> 
                        </div>
                        @endif
                        @endif


                        @if($hospedagem->status == 4)
                        <div class="form-group col-sm-12 col-md-6 col-lg-6">

                                

                                    <label class="control-label">{{ __('Valor') }}</label>
                                    <input type="text" class="form-control boxed @error('valor') is-invalid @enderror" value="{{ number_format( $hospedagem->valor, 2, ',', '.' )}}" name="valor" id="valor" required autofocus readonly onpaste="return false;">
                                    @error('valor')
                                    <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                

                        </div> 
                        @endif   

                    </div>
                   
                    @endrole
                    

                        @if($hospedagem->status == 4)
                        @if($comprovante)
                            

                            
                             <a href="data:image/png;base64, {{ $comprovante->arquivo }}" target="_blank" title="Ver Comprovante de Pagamento" class="btn btn-secondary btn-xl" style="margin-top: 20px;"><i class="fas fa-address-card"></i>
                                Ver Comprovante de Pagamento
                            </a> 
                        @else
                            <a href="#" target="_blank" title="Ver Comprovante de Pagamento" class="btn btn-secondary btn-xl" style="margin-top: 20px; color: red;"><i class="fas fa-address-card"></i>
                                SEM Comprovante de Pagamento
                            </a> 


                        @endif
                        @endif


                        @role('administrador_geral|auxiliar_administrador_geral|administrador')
                        @if(isset($hospedagem->user->motivoinativos->motivo))
                        <div class="row has-error">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">{{ __('Observação') }}</label>
                            <input type="text" class="form-control boxed @error('motivo') is-invalid @enderror" readonly="" value="{{ $hospedagem->user->motivoinativos->motivo }}" name="motivo" id="motivo" required readonly="" autofocus onpaste="return false;">
                             @error('motivo')
                            <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>
                        @endif
                        @endrole
                    
                    <hr>
                
                
                                    
               
             
                
              
            </div>

            @role('administrador_geral|auxiliar_administrador_geral')
                    <div class="row mt-4">
    <div class="col-12">
                            <p class="title-description"> 

                             </p><br>

                             <!-- SE USUARIO NAO FEZ CHECK IN APARECE O BOTAO APROVAR E NEGAR -->
                            @if($hospedagem->checkin == null)
                            @if($hospedagem->status != 6 or $liberaDistribuir == 1)

                                <button
                                    type="submit"
                                    id="btnAprovarSolicitacao"
                                    class="btn btn-primary"
                                    disabled
                                >
                                    <i class="fas fa-check-circle fa-sm"></i>
                                    <span id="textoBtnAprovar">Aprovar Solicitação!</span>
                                </button>

                                <div
                                    id="mensagemAprovacao"
                                    class="alert alert-warning mt-3 mb-0"
                                    style="display:none;"
                                ></div>
                            <!--
                             <a href="javascript:;" data-toggle="modal" onclick="aprovapedido('{{Crypt::encrypt($hospedagem->id)}}')" class="btn btn-primary" data-target="#AprovaModal" title="Aprovar Pedido">
                                    <i class="fas fa-check-square" style="color: #ffffff"></i> Liberar Acesso!</a>
                            -->

                                    <a href="javascript:;" data-toggle="modal" onclick="negarpedido('{{Crypt::encrypt($hospedagem->id)}}')" data-target="#NegarModal" class="btn btn-danger" title="Negar Pedido">
                                    <i class="fas fa-ban fa-sm" ></i> Negar Solicitação! </a>
                            @endif 
                            @endif        
                                   
                                    @if($hospedagem->status == 0 or $hospedagem->status == 7)
                                    
                                        <a href="{{ route('envia.mail.espera', ['id' => Crypt::encrypt($hospedagem->id)])  }}" class="btn btn-success" style="color: white;" id="enviar_mensagem" title="Enviar Mensagem">
                                        <i class="fas fa-envelope" style="color: white;" ></i> Fila de Espera </a>
                                    
                                    @endif

                                    @if($hospedagem->status == 7 or $hospedagem->status == 3)
                                        

                                        <a href="javascript:;" data-toggle="modal" onclick="VoltarReserva('{{ Crypt::encrypt($hospedagem->id) }}')" data-target="#ModalVoltarCancelar" style="color: white;"  class="btn btn-info" title="Cancelar Reserva">
                                            <i class="fas fa-rotate-left"></i> Retornar Para Distribuição </a> 


                                    @endif
                                    
                                    @if($hospedagem->status == 2 or $hospedagem->status == 3 or $hospedagem->status == 5)
                                        @if($hospedagem->checkin == null)

                                    <a href="javascript:;" data-toggle="modal" onclick="CancelarReserva('{{ Crypt::encrypt($hospedagem->id) }}')" data-target="#ModalCancelar" style="background-color: red" class="btn btn-danger" title="Cancelar Reserva">
                                            <i class="fas fa-bed" ></i> Cancelar Reserva </a> 

                                        @endif
                                    @endif
                           



                        </div>
                    </div>

                @endrole
</form>
           <div class="modal fade" id="modalCalendarioUH" tabindex="-1" role="dialog"
    aria-labelledby="modalCalendarioUHTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title" id="modalCalendarioUHTitulo">
                        <i class="fas fa-calendar-alt"></i>
                        Disponibilidade da Unidade Habitacional
                    </h4>
                    <small class="text-muted">
                        Reservas em vermelho e período solicitado em azul.
                    </small>
                </div>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="availability-summary">
                    <div class="availability-summary__item">
                        <span class="availability-summary__label">Unidade</span>
                        <span id="modalUnidadeTexto" class="availability-summary__value">-</span>
                    </div>
                    <div class="availability-summary__item">
                        <span class="availability-summary__label">Entrada</span>
                        <span id="modalEntradaTexto" class="availability-summary__value">-</span>
                    </div>
                    <div class="availability-summary__item">
                        <span class="availability-summary__label">Saída</span>
                        <span id="modalSaidaTexto" class="availability-summary__value">-</span>
                    </div>
                </div>

                <div id="resultadoDisponibilidadeModal" class="alert mb-3"></div>

                <div id="calendarioLoading" class="availability-loading" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                    <div>Carregando calendário...</div>
                </div>

                <div id="calendarioUnidade" style="min-height:580px;"></div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btnRecarregarCalendario" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                    Atualizar calendário
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
            
                                    <div class="modal fade" id="modalMapaOcupacao" tabindex="-1" role="dialog"
    aria-labelledby="modalMapaOcupacaoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:98%;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title" id="modalMapaOcupacaoTitulo">
                        <i class="fas fa-th"></i>
                        Mapa de ocupação das unidades
                    </h4>
                    <small class="text-muted">
                        Clique em “Selecionar” para usar uma unidade no pedido.
                    </small>
                </div>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="mapa-legenda">
                    <span class="mapa-legenda__item">
                        <span class="mapa-legenda__cor" style="background:#edfdf3;"></span>
                        Livre
                    </span>
                    <span class="mapa-legenda__item">
                        <span class="mapa-legenda__cor" style="background:#dc3545;"></span>
                        Ocupada
                    </span>
                    <span class="mapa-legenda__item">
                        <span class="mapa-legenda__cor" style="background:#ffc107;"></span>
                        Check-out
                    </span>
                    <span class="mapa-legenda__item">
                        <span class="mapa-legenda__cor" style="box-shadow:inset 0 0 0 3px #007bff;"></span>
                        Período solicitado
                    </span>
                </div>

                <div id="mapaOcupacaoLoading" class="availability-loading" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                    <div>Montando mapa de ocupação...</div>
                </div>

                <div id="mapaOcupacaoErro" class="alert alert-danger" style="display:none;"></div>

                <div id="mapaOcupacaoResumo" class="alert alert-info"></div>

                <div id="mapaOcupacaoConteudo" class="mapa-scroll"></div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btnAtualizarMapaOcupacao" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                    Atualizar mapa
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AprovaModal">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="aprovapedido" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('PUT') }}
                                                    <p>Deseja realmente Aprovar esse Pedido?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmitAprova()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>


    

 


                                <div class="modal fade" id="NegarModal">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="negarpedido" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header-dangeri">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('PUT') }}
                                                    <p>Deseja realmente Negar esse Pedido?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="formSubmitNegar()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                                 <div class="modal fade" id="ModalCancelar">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="cancelar" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('DELETE') }}
                                                    <p >Antes de cancelar sua reserva por motivo de <b style="color: red;">mudança de período ou Unidade Habitacional,</b> consulte a Seção FML para os ajustes necessários.</p><br><br>
                                                      <p style="color: red;"> Caso efetue o cancelamento da reserva aprovada e fizer nova soicitação, terá de pagar nova diária para aprovação.</p>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmitCancelar()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>




                                <div class="modal fade" id="ModalVoltarCancelar">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="voltarcancelar" method="get">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-warning"></i> Atenção</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ csrf_field() }}  
                                                    {{ method_field('DELETE') }}
                                                    <p > Deseja retornar para Distribuição essa Reserva?</p>
                                                   
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmitVoltarCancelar()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>


        </div>
    </div>
</section>


@push('javascript')

<script src="{{ asset('lib/jquery-mask-plugin/dist/jquery.mask.min.js')}}"></script>
<script src="{{ asset('js/litepickerBudler.js')}}"></script>
<script src="{{ asset('js/mobilefriendly.js')}}"></script>

<script type="text/javascript">
     function aprovapedido(id)
     {
         var id = id;
         var url = '{{ route("hospedagem.liberar", ":id") }}';
         url = url.replace(':id', id);
         $("#aprovapedido").attr('action', url);
     }
     function negarpedido(id)
     {
         var id = id;
         var url = '{{ route("hospedagem.negar", ":id") }}';
         url = url.replace(':id', id);
         $("#negarpedido").attr('action', url);
     }

     function formSubmitAprova()
     {
         $("#aprovapedido").submit();
     }

     function formSubmitNegar()
     {
         $("#negarpedido").submit();
     }
</script>
    



<script>
(function ($) {
    'use strict';

    var unidadesUrl = '{!! url("/cascade/carregarUnidades") !!}';
    var eventosUrl = '{!! url("/admin/calendario/unidade") !!}';

    var mapaOcupacaoUrl =
        '{!! route("hospedagem.disponibilidade.mapa") !!}';
    var cache = {};
    var unidadeAtual = null;

    function status(mensagem, tipo) {
        $('#availabilityStatus')
            .removeClass('alert-info alert-success alert-warning alert-danger')
            .addClass('is-visible alert-' + (tipo || 'info'))
            .html(mensagem);
    }

    function periodo() {
        return {
            inicio: $('input[name="peridoinicial1"]').val(),
            fim: $('input[name="final1"]').val()
        };
    }

    function preencherUnidades(unidades) {
        var $select = $('#unidadeshabitacionais');

        $select.empty().prop('disabled', false).append(
            $('<option>', {
                value: '',
                text: 'Selecione uma unidade habitacional',
                disabled: true,
                selected: true
            })
        );

        $.each(unidades || [], function (_, unidade) {
            var tipo = unidade.tipohabitacao &&
                unidade.tipohabitacao.descricao
                ? unidade.tipohabitacao.descricao
                : 'Tipo não informado';

            var texto = 'Nº ' + (unidade.sigla || unidade.id) +
                ' - ' + tipo;

            if (parseInt(unidade.pet, 10) === 1) {
                texto += ' - PET';
            }

            if (unidade.disponivel_periodo === true) {
                texto += ' — DISPONÍVEL';
            } else if (unidade.disponivel_periodo === false) {
                var conflito = unidade.conflitos &&
                    unidade.conflitos.length
                    ? unidade.conflitos[0]
                    : null;

                texto += conflito
                    ? ' — OCUPADA ' + conflito.inicio +
                      ' a ' + conflito.termino
                    : ' — OCUPADA';
            }

            var $option = $('<option>', {
                value: unidade.id,
                text: texto
            });

            $option.data('unidade', unidade);
            $select.append($option);
        });

        if (!unidades || !unidades.length) {
            status(
                'Nenhuma unidade encontrada para o grupo selecionado.',
                'warning'
            );
            return;
        }

        var livres = $.grep(unidades, function (item) {
            return item.disponivel_periodo === true;
        }).length;

        status(
            unidades.length + ' unidade(s) encontrada(s); ' +
            livres + ' disponível(is) no período.',
            livres ? 'success' : 'warning'
        );
    }

    function carregarUnidades() {
        var grupo = $('#grupodestinacao').val();
        var tipo = $('input[name="unidade_habitacional_id"]').val();
        var datas = periodo();
        var hospedagem = $('input[name="id1"]').val();

        if (!grupo || !tipo || !datas.inicio || !datas.fim) {
            return;
        }

        var chave = [grupo, tipo, datas.inicio, datas.fim, hospedagem].join('|');

        $('#unidadeshabitacionaisdiv').slideDown(150);
        $('#unidadeshabitacionais')
            .empty()
            .prop('disabled', true)
            .append('<option>Carregando unidades...</option>');

        $('#btnAbrirCalendario').prop('disabled', true);
        $('#btnAbrirMapaOcupacao').prop('disabled', false);
        unidadeAtual = null;

        if (cache[chave]) {
            preencherUnidades(cache[chave]);
            return;
        }

        $.ajax({
            url: unidadesUrl + '/' +
                encodeURIComponent(grupo) + '/' +
                encodeURIComponent(tipo),
            type: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                data_inicio: datas.inicio,
                data_final: datas.fim,
                ignorar_hospedagem: hospedagem
            },
            success: function (data) {
                cache[chave] = data;
                preencherUnidades(data);
            },
            error: function (xhr) {
                console.error(xhr.responseText);

                $('#unidadeshabitacionais')
                    .empty()
                    .prop('disabled', false)
                    .append('<option>Erro ao carregar unidades</option>');

                status(
                    xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Não foi possível carregar as unidades.',
                    'danger'
                );
            }
        });
    }

    function iniciarCalendario(unidadeId) {
        var $calendar = $('#calendarioUnidade');
        var datas = periodo();
        var hospedagem = $('input[name="id1"]').val();

        if (typeof $.fn.fullCalendar !== 'function') {
            status('FullCalendar não carregado.', 'danger');
            return;
        }

        if ($calendar.hasClass('fc')) {
            $calendar.fullCalendar('destroy');
        }

        $('#calendarioLoading').show();

        $calendar.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,listYear'
            },
            defaultDate: moment(
                datas.inicio,
                'DD-MM-YYYY'
            ).format('YYYY-MM-DD'),
            editable: false,
            navLinks: true,
            eventLimit: true,
            selectable: false,
            locale: 'pt-br',
            height: 580,
            eventSources: [
                {
                    url: eventosUrl + '/' +
                        encodeURIComponent(unidadeId) +
                        '/eventos',
                    cache: false,
                    data: {
                        ignorar_hospedagem: hospedagem
                    }
                },
                {
                    events: [{
                        title: 'PERÍODO SOLICITADO',
                        start: moment(
                            datas.inicio,
                            'DD-MM-YYYY'
                        ).format('YYYY-MM-DD'),
                        end: moment(
                            datas.fim,
                            'DD-MM-YYYY'
                        ).format('YYYY-MM-DD'),
                        allDay: true,
                        color: '#007bff',
                        textColor: '#ffffff'
                    }]
                }
            ],
            eventAfterAllRender: function () {
                $('#calendarioLoading').hide();
            }
        });
    }

    function abrirCalendario() {
        var unidadeId = $('#unidadeshabitacionais').val();
        var datas = periodo();

        if (!unidadeId) {
            status('Selecione uma unidade.', 'warning');
            return;
        }

        $('#modalUnidadeTexto').text(
            $('#unidadeshabitacionais option:selected').text()
        );
        $('#modalEntradaTexto').text(datas.inicio);
        $('#modalSaidaTexto').text(datas.fim);

        var disponivel = unidadeAtual &&
            unidadeAtual.disponivel_periodo === true;

        $('#resultadoDisponibilidadeModal')
            .removeClass('alert-success alert-danger')
            .addClass(disponivel ? 'alert-success' : 'alert-danger')
            .html(
                disponivel
                    ? '<strong>Unidade disponível</strong> no período.'
                    : '<strong>Unidade com conflito</strong> no período.'
            );

        $('#modalCalendarioUH').modal('show');

        setTimeout(function () {
            iniciarCalendario(unidadeId);
        }, 200);
    }


    function dataEntre(data, inicio, fim) {
        return data >= inicio && data < fim;
    }

    function encontrarReservaDoDia(reservas, data) {
        var encontrada = null;

        $.each(reservas || [], function (_, reserva) {
            if (dataEntre(data, reserva.inicio, reserva.fim)) {
                encontrada = {
                    tipo: 'ocupada',
                    reserva: reserva
                };
                return false;
            }

            if (data === reserva.fim) {
                encontrada = {
                    tipo: 'checkout',
                    reserva: reserva
                };
                return false;
            }
        });

        return encontrada;
    }

    function montarMapaOcupacao(resposta) {
        var $conteudo = $('#mapaOcupacaoConteudo');
        var dias = resposta.dias || [];
        var unidades = resposta.unidades || [];

        $('#mapaOcupacaoResumo').html(
            '<strong>Período solicitado:</strong> ' +
            resposta.periodo.inicio + ' até ' +
            resposta.periodo.fim + '. ' +
            '<strong>' + unidades.length + '</strong> unidade(s) no mapa.'
        );

        if (!unidades.length) {
            $conteudo.html(
                '<div class="p-4 text-center text-muted">' +
                'Nenhuma unidade encontrada para esse grupo.' +
                '</div>'
            );
            return;
        }

        var html = '<table class="table table-bordered table-sm mapa-table">';
        html += '<thead><tr>';
        html += '<th class="mapa-uh">Unidade</th>';

        $.each(dias, function (_, dia) {
            html += '<th title="' + dia.data + '">' +
                '<div>' + dia.dia + '/' + dia.mes + '</div>' +
                '<small>' + dia.semana + '</small>' +
                '</th>';
        });

        html += '</tr></thead><tbody>';

        $.each(unidades, function (_, unidade) {
            var statusClasse = unidade.disponivel_periodo
                ? 'mapa-uh__status--livre'
                : 'mapa-uh__status--ocupada';

            var statusTexto = unidade.disponivel_periodo
                ? 'Disponível no período'
                : 'Conflito no período';

            html += '<tr>';
            html += '<td class="mapa-uh">';
            html += '<strong>UH ' + unidade.sigla + '</strong>';
            html += unidade.tipo ? ' — ' + $('<div>').text(unidade.tipo).html() : '';
            html += unidade.pet ? ' — PET' : '';
            html += '<span class="mapa-uh__status ' + statusClasse + '">' +
                statusTexto +
                '</span>';
            html += '<button type="button" ' +
                'class="btn btn-sm btn-outline-primary mapa-selecionar" ' +
                'data-unidade-id="' + unidade.id + '">' +
                'Selecionar</button>';
            html += '</td>';

            $.each(dias, function (_, dia) {
                var ocorrencia = encontrarReservaDoDia(
                    unidade.reservas,
                    dia.data
                );

                var classes = ['mapa-dia'];
                var titulo = 'Livre';

                if (ocorrencia && ocorrencia.tipo === 'ocupada') {
                    classes.push('mapa-dia--ocupada');
                    titulo =
                        'Ocupada: ' +
                        ocorrencia.reserva.inicio_formatado +
                        ' até ' +
                        ocorrencia.reserva.fim_formatado;
                } else if (ocorrencia && ocorrencia.tipo === 'checkout') {
                    classes.push('mapa-dia--checkout');
                    titulo =
                        'Check-out em ' +
                        ocorrencia.reserva.fim_formatado;
                } else {
                    classes.push('mapa-dia--livre');
                }

                if (dia.solicitado) {
                    classes.push('mapa-dia--solicitado');

                    if (ocorrencia && ocorrencia.tipo === 'ocupada') {
                        classes.push('mapa-dia--conflito');
                    }
                }

                if (dia.fim_semana) {
                    classes.push('mapa-dia--fim-semana');
                }

                html += '<td class="' + classes.join(' ') + '" ' +
                    'title="' + $('<div>').text(titulo).html() + '">' +
                    (ocorrencia && ocorrencia.tipo === 'checkout' ? 'S' : '') +
                    '</td>';
            });

            html += '</tr>';
        });

        html += '</tbody></table>';
        $conteudo.html(html);
    }

    function carregarMapaOcupacao() {
        var grupo = $('#grupodestinacao').val();
        var tipo = $('input[name="unidade_habitacional_id"]').val();
        var datas = periodo();
        var hospedagem = $('input[name="id1"]').val();

        if (!grupo || !tipo || !datas.inicio || !datas.fim) {
            status(
                'Selecione o grupo e confira as datas antes de abrir o mapa.',
                'warning'
            );
            return;
        }

        $('#mapaOcupacaoErro').hide().empty();
        $('#mapaOcupacaoConteudo').empty();
        $('#mapaOcupacaoLoading').show();

        $.ajax({
            url: mapaOcupacaoUrl,
            type: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                grupo_id: grupo,
                tipo_id: tipo,
                data_inicio: datas.inicio,
                data_final: datas.fim,
                ignorar_hospedagem: hospedagem
            },
            success: function (resposta) {
                montarMapaOcupacao(resposta);
            },
            error: function (xhr) {
                console.error(
                    'Erro ao carregar mapa de ocupação:',
                    xhr.responseText
                );

                $('#mapaOcupacaoErro')
                    .html(
                        xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Não foi possível carregar o mapa de ocupação.'
                    )
                    .show();
            },
            complete: function () {
                $('#mapaOcupacaoLoading').hide();
            }
        });
    }

    window.CancelarReserva = function (id) {
        var url = '{{ route("cancelar.hospedagem", ":id") }}';
        $('#cancelar').attr('action', url.replace(':id', id));
    };

    window.formSubmitCancelar = function () {
        $('#cancelar').submit();
    };

    window.VoltarReserva = function (id) {
        var url = '{{ route("hospedagem.retornarDistribuicao", ":id") }}';
        $('#voltarcancelar').attr('action', url.replace(':id', id));
    };

    window.formSubmitVoltarCancelar = function () {
        $('#voltarcancelar').submit();
    };

    $(function () {
        $('#unidadeshabitacionaisdiv').hide();

        $('#grupodestinacao')
            .off('change.distribuicao')
            .on('change.distribuicao', carregarUnidades);

        $('#unidadeshabitacionais')
            .off('change.distribuicao')
            .on('change.distribuicao', function () {
                unidadeAtual = $(this)
                    .find('option:selected')
                    .data('unidade') || null;

                $('#btnAbrirCalendario').prop(
                    'disabled',
                    !$(this).val()
                );

                if (!unidadeAtual) {
                    $('#btnAprovarSolicitacao').prop('disabled', true);
                    $('#mensagemAprovacao').hide().empty();
                    return;
                }

                var podeAprovar =
                    unidadeAtual.disponivel_periodo === true;

                $('#btnAprovarSolicitacao')
                    .prop('disabled', !podeAprovar);

                if (podeAprovar) {
                    $('#mensagemAprovacao').hide().empty();

                    status(
                        '<strong>Unidade disponível.</strong> ' +
                        'Abra o calendário para conferir.',
                        'success'
                    );
                } else {
                    $('#mensagemAprovacao')
                        .removeClass(
                            'alert-success alert-info alert-warning alert-danger'
                        )
                        .addClass('alert-danger')
                        .html(
                            '<strong>Aprovação bloqueada.</strong> ' +
                            'Selecione uma unidade disponível.'
                        )
                        .show();

                    status(
                        '<strong>Unidade ocupada.</strong> ' +
                        'Abra o calendário para ver o conflito.',
                        'danger'
                    );
                }
            });

        $('#btnAbrirMapaOcupacao')
            .off('click.mapaOcupacao')
            .on('click.mapaOcupacao', function (event) {
                event.preventDefault();

                $('#modalMapaOcupacao').modal('show');

                setTimeout(function () {
                    carregarMapaOcupacao();
                }, 150);
            });

        $('#btnAtualizarMapaOcupacao')
            .off('click.mapaOcupacao')
            .on('click.mapaOcupacao', function (event) {
                event.preventDefault();
                carregarMapaOcupacao();
            });

        $(document)
            .off('click.mapaOcupacao', '.mapa-selecionar')
            .on(
                'click.mapaOcupacao',
                '.mapa-selecionar',
                function () {
                    var unidadeId = String(
                        $(this).data('unidade-id')
                    );

                    $('#unidadeshabitacionais')
                        .val(unidadeId)
                        .trigger('change');

                    $('#modalMapaOcupacao').modal('hide');

                    $('html, body').animate({
                        scrollTop:
                            $('#unidadeshabitacionais').offset().top - 120
                    }, 250);
                }
            );

        $('#btnAbrirCalendario')
            .off('click.distribuicao')
            .on('click.distribuicao', function (event) {
                event.preventDefault();
                abrirCalendario();
            });

        $('#btnRecarregarCalendario')
            .off('click.distribuicao')
            .on('click.distribuicao', function (event) {
                event.preventDefault();

                var unidadeId = $('#unidadeshabitacionais').val();

                if (unidadeId) {
                    iniciarCalendario(unidadeId);
                }
            });

        $('#profile-form')
            .off('submit.disponibilidade')
            .on('submit.disponibilidade', function (event) {
                var form = this;

                if ($(form).data('disponibilidade-confirmada')) {
                    return;
                }

                event.preventDefault();

                var unidadeId = $('#unidadeshabitacionais').val();
                var datas = periodo();

                if (!unidadeId) {
                    status('Selecione uma unidade habitacional.', 'warning');
                    return;
                }

                $('#btnAprovarSolicitacao').prop('disabled', true);
                $('#textoBtnAprovar').html(
                    '<i class="fas fa-spinner fa-spin"></i> Verificando...'
                );

                $('#mensagemAprovacao')
                    .removeClass(
                        'alert-success alert-danger alert-warning alert-info'
                    )
                    .addClass('alert-info')
                    .html(
                        '<i class="fas fa-spinner fa-spin"></i> ' +
                        'Confirmando se a unidade continua disponível...'
                    )
                    .show();

                $.ajax({
                    url: '{!! route("hospedagem.disponibilidade.verificar") !!}',
                    type: 'GET',
                    dataType: 'json',
                    cache: false,
                    data: {
                        unidade_id: unidadeId,
                        data_inicio: datas.inicio,
                        data_final: datas.fim,
                        ignorar_hospedagem: $('input[name="id1"]').val()
                    },
                    success: function (resposta) {
                        if (!resposta.disponivel) {
                            cache = {};
                            carregarUnidades();

                            unidadeAtual.disponivel_periodo = false;

                            $('#mensagemAprovacao')
                                .removeClass(
                                    'alert-success alert-info alert-warning'
                                )
                                .addClass('alert-danger')
                                .html(
                                    '<strong>Aprovação interrompida.</strong> ' +
                                    'A unidade acabou de receber outra reserva. ' +
                                    'Selecione uma unidade disponível.'
                                )
                                .show();

                            status(
                                '<strong>Aprovação interrompida.</strong> ' +
                                'A unidade acabou de receber outra reserva.',
                                'danger'
                            );

                            return;
                        }

                        $('#mensagemAprovacao')
                            .removeClass(
                                'alert-info alert-danger alert-warning'
                            )
                            .addClass('alert-success')
                            .html(
                                '<strong>Disponibilidade confirmada.</strong> ' +
                                'Enviando aprovação...'
                            )
                            .show();

                        $(form).data('disponibilidade-confirmada', true);
                        form.submit();
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);

                        $('#mensagemAprovacao')
                            .removeClass(
                                'alert-success alert-info alert-warning'
                            )
                            .addClass('alert-danger')
                            .html(
                                '<strong>A aprovação não foi enviada.</strong> ' +
                                'Não foi possível confirmar a disponibilidade.'
                            )
                            .show();

                        status(
                            'Não foi possível confirmar a disponibilidade. ' +
                            'A solicitação não foi enviada.',
                            'danger'
                        );
                    },
                    complete: function () {
                        if (!$(form).data('disponibilidade-confirmada')) {
                            var podeAprovar =
                                unidadeAtual &&
                                unidadeAtual.disponivel_periodo === true;

                            $('#btnAprovarSolicitacao')
                                .prop('disabled', !podeAprovar);

                            $('#textoBtnAprovar')
                                .text('Aprovar Solicitação!');
                        }
                    }
                });
            });

        $('#modalCalendarioUH')
            .on('shown.bs.modal', function () {
                var $calendar = $('#calendarioUnidade');

                if ($calendar.hasClass('fc')) {
                    $calendar.fullCalendar('render');
                }
            })
            .on('hidden.bs.modal', function () {
                var $calendar = $('#calendarioUnidade');

                if ($calendar.hasClass('fc')) {
                    $calendar.fullCalendar('destroy');
                }
            });
    });

    window.disableLitepickerStyles = true;

    var entrada = document.getElementById('peridoinicial');
    var saida = document.getElementById('final');

    if (
        entrada &&
        saida &&
        typeof Litepicker !== 'undefined'
    ) {
        new Litepicker({
            element: entrada,
            elementEnd: saida,
            plugins: ['mobilefriendly', 'keyboardnav'],
            keyboardnav: { firstTabIndex: 2 },
            mobilefriendly: { breakpoint: 480 },
            dropdowns: {
                minYear: 2020,
                maxYear: new Date().getFullYear() + 5,
                months: false,
                years: false
            },
            singleMode: false,
            allowRepick: false,
            numberOfMonths: 2,
            numberOfColumns: 2,
            autoRefresh: true,
            disallowLockDaysInRange: true,
            format: 'DD-MM-YYYY',
            lang: 'pt-BR',
            lockDays: {!! $a !!},
            tooltipText: {
                one: 'diária',
                other: 'diárias'
            },
            tooltipNumber: function (totalDays) {
                return totalDays - 1;
            },
            setup: function (picker) {
                picker.on('selected', function () {
                    cache = {};

                    if ($('#grupodestinacao').val()) {
                        carregarUnidades();
                    }
                });
            }
        });
    }
})(jQuery);
</script>
    
@endpush
@endsection
