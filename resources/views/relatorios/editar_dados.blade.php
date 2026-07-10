@extends('layouts.app')

@section('content')
<div class="title-block">
    <h3 class="title"> Dados do Pedido </h3>
    <p class="title-description">Usuário aguardando a confirmação da sua solicitação!</p>
</div>
<section class="section">
    <div class="row sameheight-container">
        <div class="col-12">
           
            <div class="card card-block sameheight-item">
                
                <p class="title-description">  </p><br>
                <form id="profile-form" action="{{ route('relatorio.atualizar')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row has-error">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                        <label class="control-label">{{ __('Nome:') }}</label>  {{ $hospedagem->user->name }}
                        </div>
                    </div>
                    <div class="row has-error">

                        <div class="form-group col-sm-3 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Check-in') }}</label> <div style="float: right;"> <small>
                                                <input type="checkbox" name="limpacheckin" id="limpacheckin" value="1" class="form-check-input"> <label for="indeterminado">
                            Limpa
                                                
                        </label> </small></div>
                            <input type="datetime-local" class="form-control boxed @error('checkin') is-invalid @enderror" value="{{ $hospedagem->checkin_at }}" name="checkin" id="checkin" autofocus onpaste="return false;">
                            
                            <input type="hidden" name="id" value="{{ $hospedagem->id }}" placeholder="">
                             
                            @error('checkin')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        

                        <div class="form-group col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">{{ __('Check-out') }}</label><div style="float: right;"> <small>
                                                <input type="checkbox" name="limpacheckout" id="limpacheckout" value="1" class="form-check-input"> <label for="indeterminado">
                            Limpa
                                                
                        </label> </small></div>
                            <input type="datetime-local" class="form-control boxed @error('checkout') is-invalid @enderror" value="{{ $hospedagem->checkout_at}}" name="checkout" id="checkout" autofocus onpaste="return false;">
                            
                           
                            
                            @error('checkout')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-2 col-md-2 col-lg-2">
                            <label class="control-label">{{ __('Diárias') }}</label>
                            <input type="number" class="form-control boxed @error('diarias') is-invalid @enderror" value="{{ $hospedagem->qntdiarias }}" name="diarias" id="diarias" autofocus onpaste="return false;">

                            @error('diarias')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-2 col-md-2 col-lg-2">
                            <label class="control-label">{{ __('Valor diária') }}</label>
                            <input type="text" class="form-control boxed @error('valordiaria') is-invalid @enderror" value="{{ number_format($hospedagem->valortarifa, 2, ',', '.')}}" disabled="" name="valordiaria" id="valordiaria" >

                            @error('valordiaria')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-2 col-md-2 col-lg-2">
                            <label class="control-label">{{ __('Valor') }}</label>
                            <input type="text" class="form-control boxed @error('valor') is-invalid @enderror" value="{{ number_format($hospedagem->valor, 2, ',', '.')}}" name="valor" id="valor" >

                            <input type="hidden" class="form-control boxed @error('valor2') is-invalid @enderror" value="{{$hospedagem->valor}}" name="valor2" id="valor2" >

                            @error('valor')
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
                                        Atualizar
                                </button>     
                        </div>
                    </div>
                </form>               
            </div>                            
        </div>
    </div>
</section>


@push('javascript')
<script src="{{asset('js/jquery.maskMoney.min.js')}}"></script>
<script src="{{asset('lib/jquery-mask-plugin/dist/jquery.mask.min.js')}}"></script>
<script>

    $(document).ready(function(){

        $("#valor").maskMoney({
        prefix: "R$ ",
        decimal: ",",
        thousands: "."
        });

        $('#valor').mask("#.##0,00", {reverse: true});
        $('#valordiaria').mask("#.##0,00", {reverse: true});

        
        
    });

            
$("#diarias").on("keyup input", function(){

    //var valordiaria = $("#valordiaria").val();
    //$("#vlr_total_diar").val($("#n_diarias").val());
    var price = $("#valordiaria").val();
    var price_return = price.replace("R$ ", "");
    var price_return_b = price_return.replace(/\./g, "").replace(",", ".") || 2;
    var calc = ((parseFloat(price_return_b))*$("#diarias").val()).toFixed(3);
    calc = calc.substr(0, calc.indexOf(".")) + calc.substr(calc.indexOf("."),3);
    console.log(calc.toLocaleString());
    calc = parseFloat(calc);
    $("#valor").val(calc.toLocaleString("pt-BR", { style: "currency" , currency:"BRL", currencyDisplay:"symbol"}));
    $("#valor2").val(calc);

    //$("#valor2").val(calc);
    //alert(calc);

  



});

            
      $('#limpacheckin').on('change', ()=>{
    
        if($('#limpacheckin').is(':checked')){
             $('#checkin').attr('readonly', true);
                //alert('Confirma a data de validade da sua identidade militar? Atualmente a validade é de 10 anos.');
                document.getElementById('checkin').value = '';
        }else{
            
                //document.getElementById('checkin').value = '';
                $('#checkin').attr('readonly', false);
                //alert('Confirma a data de validade da sua identidade militar? Atualmente a validade é de 10 anos.')
              
                
        }
            
    });  

       $('#limpacheckout').on('change', ()=>{
    
        if($('#limpacheckout').is(':checked')){
             $('#checkout').attr('readonly', true);
                //alert('Confirma a data de validade da sua identidade militar? Atualmente a validade é de 10 anos.');
                document.getElementById('checkout').value = '';
        }else{
            
                //document.getElementById('checkin').value = '';
                $('#checkout').attr('readonly', false);
                //alert('Confirma a data de validade da sua identidade militar? Atualmente a validade é de 10 anos.')
              
                
        }
            
    });  



       


 
    
 
 


</script>  
    
@endpush
@endsection