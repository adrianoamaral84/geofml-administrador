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
    @media (max-width: 767.98px) {
        .availability-frame { min-height: 520px; }
        .modal-dialog.modal-xl { max-width: calc(100% - 20px); margin: 10px auto; }
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
                                <select name="uf" id="uf" required readonly="" class="custom-select mr-sm-2 @error('pne') is-invalid @enderror" autocomplete="off">
                                
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
                                <select name="pet" id="pet" required readonly="" class="custom-select mr-sm-2 @error('pet') is-invalid @enderror" autocomplete="off">
                                
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
    class="btn btn-outline-primary btn-sm mt-3"
    disabled
>
    <i class="fas fa-calendar-check"></i>
    Ver calendário da unidade
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

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check-circle fa-sm"></i>  
                                        Aprovar Solicitação!
                                </button>
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
           <div
    class="modal fade"
    id="modalCalendarioUH"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalCalendarioUHTitulo"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-xl"
        role="document"
        style="max-width: 95%;"
    >
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h4
                        class="modal-title"
                        id="modalCalendarioUHTitulo"
                    >
                        <i class="fas fa-calendar-alt"></i>
                        Disponibilidade da Unidade Habitacional
                    </h4>

                    <small class="text-muted">
                        Consulte as hospedagens existentes e os dias livres.
                    </small>
                </div>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Fechar"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Unidade:</strong>
                        <span id="modalUnidadeTexto">-</span>
                    </div>

                    <div class="col-md-3">
                        <strong>Entrada:</strong>
                        <span id="modalEntradaTexto">-</span>
                    </div>

                    <div class="col-md-3">
                        <strong>Saída:</strong>
                        <span id="modalSaidaTexto">-</span>
                    </div>
                </div>

                <div
                    id="calendarioLoading"
                    class="text-center p-5"
                    style="display:none;"
                >
                    <i class="fas fa-spinner fa-spin fa-3x"></i>

                    <p class="mt-3">
                        Carregando calendário...
                    </p>
                </div>

                <iframe
                    id="calendarioUnidadeFrame"
                    title="Calendário da unidade habitacional"
                    style="
                        width: 100%;
                        height: 650px;
                        border: 0;
                        display: none;
                        background: white;
                    "
                ></iframe>

            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    id="btnRecarregarCalendario"
                    class="btn btn-outline-primary"
                >
                    <i class="fas fa-sync-alt"></i>
                    Atualizar
                </button>

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
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
@include('calendario.scriptsCalendario')

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
    /*
     * Laravel gera a URL correta, inclusive quando o sistema
     * estiver instalado em uma subpasta ou dentro de /public.
     */
    var carregarUnidadesBaseUrl = '{!! url("/cascade/carregarUnidades") !!}';

    var calendarioUrlAtual = null;

    /*
     * Protege textos inseridos dinamicamente no HTML.
     */
    function escapeHtml(texto) {
        return $('<div>')
            .text(texto || '')
            .html();
    }

    /*
     * Mostra mensagens abaixo do select de UH.
     */
    function setAvailabilityStatus(mensagem, tipo) {
        var $status = $('#availabilityStatus');

        if (!$status.length) {
            return;
        }

        $status
            .removeClass(
                'alert-info ' +
                'alert-success ' +
                'alert-warning ' +
                'alert-danger'
            )
            .addClass(
                'is-visible alert-' + (tipo || 'info')
            )
            .html(mensagem);
    }

    /*
     * Carrega as unidades habitacionais pertencentes
     * ao grupo de destinação selecionado.
     */
    function validaGrupo(grupoId, campoDestino, tipo) {
        var $select = $(
            'select[name="' + campoDestino + '"]'
        );

        if (!$select.length) {
            console.error(
                'Select de unidades habitacionais não encontrado.'
            );

            return;
        }

        $select
            .empty()
            .prop('disabled', true)
            .append(
                '<option value="" disabled selected>' +
                    'Carregando unidades habitacionais...' +
                '</option>'
            );

        $('#btnAbrirCalendario').prop(
            'disabled',
            true
        );

        calendarioUrlAtual = null;

        /*
         * Confere se o grupo foi selecionado.
         */
        if (!grupoId) {
            $select
                .empty()
                .prop('disabled', false)
                .append(
                    '<option value="" disabled selected>' +
                        'Selecione uma unidade habitacional' +
                    '</option>'
                );

            setAvailabilityStatus(
                'Selecione primeiro um grupo de destinação.',
                'info'
            );

            return;
        }

        /*
         * Confere se o tipo da UH existe.
         */
        if (!tipo) {
            $select
                .empty()
                .prop('disabled', false)
                .append(
                    '<option value="" disabled selected>' +
                        'Tipo de unidade não informado' +
                    '</option>'
                );

            setAvailabilityStatus(
                'Não foi possível identificar o tipo de unidade habitacional.',
                'danger'
            );

            console.error(
                'O campo unidade_habitacional_id está vazio.'
            );

            return;
        }

        /*
         * Monta a URL final.
         */
        var urlCarregarUnidades =
            carregarUnidadesBaseUrl
            + '/'
            + encodeURIComponent(grupoId)
            + '/'
            + encodeURIComponent(tipo);

        console.log(
            'Carregando unidades pela URL:',
            urlCarregarUnidades
        );

        $.ajax({
            url: urlCarregarUnidades,
            type: 'GET',
            dataType: 'json',
            cache: false,

            success: function (data) {
                $select
                    .empty()
                    .prop('disabled', false);

                /*
                 * O Controller pode retornar:
                 *
                 * [
                 *   { ... },
                 *   { ... }
                 * ]
                 *
                 * ou:
                 *
                 * {
                 *   "0": { ... },
                 *   "1": { ... }
                 * }
                 *
                 * O $.each funciona nos dois formatos.
                 */
                var quantidade = 0;

                $select.append(
                    '<option value="" disabled selected>' +
                        'Selecione uma unidade habitacional' +
                    '</option>'
                );

                $.each(data || {}, function (
                    indice,
                    unidade
                ) {
                    if (!unidade || !unidade.id) {
                        return;
                    }

                    quantidade++;

                    var sigla =
                        unidade.sigla
                        ? unidade.sigla
                        : unidade.id;

                    var descricaoTipo =
                        'Tipo não informado';

                    if (
                        unidade.tipohabitacao
                        &&
                        unidade.tipohabitacao.descricao
                    ) {
                        descricaoTipo =
                            unidade
                                .tipohabitacao
                                .descricao;
                    }

                    var descricao =
                        'Nº '
                        + sigla
                        + ' - '
                        + descricaoTipo;

                    if (
                        parseInt(
                            unidade.pet,
                            10
                        ) === 1
                    ) {
                        descricao += ' - PET';
                    }

                    $select.append(
                        $('<option>', {
                            value: unidade.id,
                            text: descricao
                        })
                    );
                });

                if (quantidade > 0) {
                    setAvailabilityStatus(
                        quantidade
                        + ' unidade(s) encontrada(s). '
                        + 'Selecione uma para visualizar '
                        + 'o calendário.',
                        'success'
                    );
                } else {
                    $select
                        .empty()
                        .append(
                            '<option value="" disabled selected>' +
                                'Nenhuma unidade disponível para este grupo' +
                            '</option>'
                        );

                    setAvailabilityStatus(
                        'Nenhuma unidade habitacional foi encontrada '
                        + 'para o grupo selecionado.',
                        'warning'
                    );
                }
            },

            error: function (
                xhr,
                textStatus,
                errorThrown
            ) {
                $select
                    .empty()
                    .prop('disabled', false)
                    .append(
                        '<option value="" disabled selected>' +
                            'Erro ao carregar unidades' +
                        '</option>'
                    );

                console.error(
                    'Erro ao carregar unidades habitacionais.',
                    {
                        url: urlCarregarUnidades,
                        statusHttp: xhr.status,
                        statusTexto: xhr.statusText,
                        textStatus: textStatus,
                        errorThrown: errorThrown,
                        resposta: xhr.responseText
                    }
                );

                var mensagem =
                    'Não foi possível carregar as unidades habitacionais.';

                if (xhr.status === 404) {
                    mensagem +=
                        ' A rota de carregamento não foi encontrada.';
                } else if (xhr.status === 403) {
                    mensagem +=
                        ' O usuário não possui permissão para essa consulta.';
                } else if (xhr.status === 401) {
                    mensagem +=
                        ' A sessão pode ter expirado.';
                } else if (xhr.status === 500) {
                    mensagem +=
                        ' O servidor apresentou um erro interno.';
                } else if (xhr.status) {
                    mensagem +=
                        ' Código HTTP: ' + xhr.status + '.';
                }

                setAvailabilityStatus(
                    mensagem,
                    'danger'
                );
            }
        });
    }

    /*
     * Monta a URL do calendário da unidade.
     */
    function montarUrlCalendario() {
    var unidade =
        $('#unidadeshabitacionais').val();

    var dataInicio =
        $('input[name="peridoinicial1"]').val();

    var dataFinal =
        $('input[name="final1"]').val();

    if (
        !unidade
        ||
        !dataInicio
        ||
        !dataFinal
    ) {
        return null;
    }

    /*
     * O Laravel gera a rota usando marcadores.
     * Depois o JavaScript substitui pelos valores selecionados.
     */
    var url = '{!! route(
        "calendario.unidade",
        [
            "unidade" => "__UNIDADE__",
            "data_ini" => "__DATA_INICIO__",
            "data_final" => "__DATA_FINAL__"
        ]
    ) !!}';

    url = url.replace(
        '__UNIDADE__',
        encodeURIComponent(unidade)
    );

    url = url.replace(
        '__DATA_INICIO__',
        encodeURIComponent(dataInicio)
    );

    url = url.replace(
        '__DATA_FINAL__',
        encodeURIComponent(dataFinal)
    );

    return url;
}
    /*
     * Abre o calendário no modal.
     */
   function abrirCalendarioUnidade() {
        calendarioUrlAtual = montarUrlCalendario();

        if (!calendarioUrlAtual) {
            setAvailabilityStatus(
                'Confira as datas de entrada e saída e selecione uma unidade.',
                'warning'
            );
            return;
        }

        var $modal = $('#modalCalendarioUH').first();
        var $frame = $('#calendarioUnidadeFrame').first();
        var $loading = $('#calendarioLoading').first();

        if (!$modal.length || !$frame.length) {
            console.error('Modal ou iframe do calendário não encontrado.');
            window.open(
                calendarioUrlAtual,
                '_blank',
                'width=1200,height=800,scrollbars=1,resizable=1'
            );
            return;
        }

        $('#modalUnidadeTexto').first().text(
            $('#unidadeshabitacionais option:selected').text()
        );
        $('#modalEntradaTexto').first().text(
            $('input[name="peridoinicial1"]').val()
        );
        $('#modalSaidaTexto').first().text(
            $('input[name="final1"]').val()
        );

        $loading.show();
        $frame.hide().attr('src', 'about:blank');

        $modal.modal('show');

        setTimeout(function () {
            $frame.attr('src', calendarioUrlAtual);
        }, 150);
    }

    /*
     * Recarrega o calendário aberto.
     */
    function recarregarCalendario() {
        calendarioUrlAtual = montarUrlCalendario();

        if (!calendarioUrlAtual) {
            return;
        }

        var $frame = $('#calendarioUnidadeFrame').first();
        var $loading = $('#calendarioLoading').first();

        $loading.show();
        $frame.hide().attr('src', 'about:blank');

        setTimeout(function () {
            $frame.attr('src', calendarioUrlAtual);
        }, 150);
    }

    /*
     * Funções dos demais botões da página.
     */
    function CancelarReserva(id) {
        var url =
            '{{ route("cancelar.hospedagem", ":id") }}';

        url = url.replace(
            ':id',
            id
        );

        $('#cancelar')
            .attr(
                'action',
                url
            );
    }

    function formSubmitCancelar() {
        $('#cancelar').submit();
    }

    function VoltarReserva(id) {
        var url =
            '{{ route("hospedagem.retornarDistribuicao", ":id") }}';

        url = url.replace(
            ':id',
            id
        );

        $('#voltarcancelar')
            .attr(
                'action',
                url
            );
    }

    function formSubmitVoltarCancelar() {
        $('#voltarcancelar').submit();
    }

    /*
     * Eventos da página.
     */
    $(document).ready(function () {
        $('#unidadeshabitacionaisdiv').hide();

        $('#calendarioUnidadeFrame')
            .first()
            .off('load.calendarioUH')
            .on('load.calendarioUH', function () {
                var src = $(this).attr('src');

                if (src && src !== 'about:blank') {
                    $('#calendarioLoading').first().hide();
                    $(this).show();
                }
            });

        $('#btnAbrirCalendario')
            .off('click.calendarioUH')
            .on('click.calendarioUH', function (event) {
                event.preventDefault();
                event.stopPropagation();
                abrirCalendarioUnidade();
            });

        $('#btnRecarregarCalendario')
            .off('click.calendarioUH')
            .on('click.calendarioUH', function (event) {
                event.preventDefault();
                recarregarCalendario();
            });

        $('#grupodestinacao')
            .off('change.calendarioUH')
            .on('change.calendarioUH', function () {
                var grupoId = $(this).val();
                var tipo = $('input[name="unidade_habitacional_id"]').val();

                $('#unidadeshabitacionaisdiv').slideDown(150);
                validaGrupo(grupoId, 'unidadeshabitacionais', tipo);
            });

        $('#unidadeshabitacionais')
            .off('change.calendarioUH')
            .on('change.calendarioUH', function () {
                var unidadeSelecionada = $(this).val();
                var unidadeTexto = $(this).find('option:selected').text();

                calendarioUrlAtual = montarUrlCalendario();

                if (!unidadeSelecionada || !calendarioUrlAtual) {
                    $('#btnAbrirCalendario').prop('disabled', true);
                    setAvailabilityStatus(
                        'Confira as datas e selecione uma unidade habitacional.',
                        'warning'
                    );
                    return;
                }

                $('#btnAbrirCalendario').prop('disabled', false);
                setAvailabilityStatus(
                    '<strong>' + escapeHtml(unidadeTexto) + '</strong> selecionada. ' +
                    'Clique em “Ver calendário da unidade” para consultar a disponibilidade.',
                    'success'
                );
            });

        $('#modalCalendarioUH')
            .first()
            .on('hidden.bs.modal', function () {
                $('#calendarioUnidadeFrame').first().attr('src', 'about:blank').hide();
                $('#calendarioLoading').first().hide();
            });
    });

    /*
     * Configuração do seletor de datas.
     *
     * Só cria o Litepicker quando os campos existirem.
     * Isso evita erro para usuários ou situações em que
     * os inputs não forem exibidos pela Blade.
     */
    window.disableLitepickerStyles = true;

    var campoEntrada =
        document.getElementById('peridoinicial');

    var campoSaida =
        document.getElementById('final');

    if (
        campoEntrada
        &&
        campoSaida
        &&
        typeof Litepicker !== 'undefined'
    ) {
        var picker = new Litepicker({
            element: campoEntrada,
            elementEnd: campoSaida,

            plugins: [
                'mobilefriendly',
                'keyboardnav'
            ],

            keyboardnav: {
                firstTabIndex: 2
            },

            mobilefriendly: {
                breakpoint: 480
            },

            dropdowns: {
                minYear: 2020,
                maxYear:
                    new Date().getFullYear() + 5,
                months: false,
                years: false
            },

            singleMode: false,
            allowRepick: false,
            numberOfMonths: 2,
            autoRefresh: true,
            disallowLockDaysInRange: true,
            format: 'DD-MM-YYYY',
            lang: 'pt-BR',
            numberOfColumns: 2,

            lockDays: {!! $a !!},

            tooltipText: {
                one: 'diária',
                other: 'diárias'
            },

            tooltipNumber: function (
                totalDays
            ) {
                return totalDays - 1;
            }
        });
    }
</script>
    
@endpush
@endsection
