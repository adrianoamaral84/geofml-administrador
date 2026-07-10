@extends('layouts.app')

@section('content')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  

<link rel="stylesheet" href="{{ asset('css/litepicker.css') }}"/>
<article class="items-list-page">
    <div class="title-search-block">
        <div class="title-block">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="title"> Adicionar Produtos na Hospedagem #{{ $hospedagem->id }}</h3>
                    <small>Adicione aqui os Produtos na conta do Hospede</small><br>
                   
                </div>
            </div>
        </div>
        <div class="items-search">
            






        </div>
    </div>

      <div>
                    <div id="msgAviso" class="alert alert-danger" style="display:none;" role="alert">
                        <div align="center" class="card-content">
                            <span>Não é permitido voltar pelo botão do browser.</span>
                        </div>
                    </div>
                </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <section class="example">
                            <div class="table-flip-scroll">
                                






                                <form id="password-form" action="{{ route('cadastrarProdutoCarrinho') }}" method="POST">
                                    @csrf
                                
        <div class="row has-error">
            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" class="form-control boxed @error('id') is-invalid @enderror" value="{{ $hospedagem->id }}" name="id" id="id">
                            <input type="hidden" class="form-control boxed @error('user_id') is-invalid @enderror" value="{{ $hospedagem->user->id }}" name="user_id" id="user_id">

                            <label class="control-label">{{ __('Nome: ') }}</label>
                           {{ $hospedagem->user->name }}

            </div>   
        </div>

        <div class="row has-error">
              <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">{{ __('Produto') }}</label>
                
                                <select name="produto" id="produto" required class="custom-select mr-sm-2 @error('produto') is-invalid @enderror" autocomplete="off">
                                     <option value="">Selecione</option>
                                
                                    @foreach($produtos as $produto)

                                         <option value="{!! $produto->id !!}"> {{ $produto->descricao }} | R$ {{ $produto->valor }} </option>

                                    @endforeach
                            </select>
                            @error('tipo')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>

        </div>
       

     

       
           

            <div class="row has-error">
            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">{{ __('Quantidade') }}</label>
                           
                            <input type="number" placeholder="Digite a Quantidade" onkeypress="return event.charCode >= 48 && event.charCode <= 57" min="1" class="form-control boxed @error('quantidade') is-invalid @enderror" value="{{ old('quantidade') }}" name="quantidade" id="quantidade" autofocus required maxlength="10" onpaste="return false;">
                            @error('quantidade')
                                <span class="has-error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
            </div>

                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <button type="submit" class="btn btn-primary rounded-s"> Adicionar </button>
                                        </div>
                                    </div>
                                </form>

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
                                
                                 <h3 class="title"> Carrinho</h3>
                                 <hr>




                                
                <form id="finalizarCarrinho" action="{{ route('carrinho.finalizar') }}" method="POST">
                @csrf                    
        
        <div class="row has-error">
            <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <div class="table-flip-scroll">
                                <table class="table table-striped table-bordered table-hover flip-content" id="tabela">
                                    <thead class="flip-header">
                                        <tr>
                                          
                                            <th width="">Produto</th>
                                            <th width="15%" style="text-align: center;">Quantidade</th>
                                            <th width="15%" style="text-align: center;">Valor</th>       
                                            <th width="10%" style="text-align: center;">Ação</th>

                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                            @if ($carrinho->count() == 0)
                                            <tr>
                                                <td colspan="5" style="text-align: center;"><small>Nenhum registro encontrado</small></td>
                                            </tr>
                                            @else
                                             @foreach ($carrinho as $item) 
                                            <tr>
                                                <td>
                                                   
                                             
                                                                {{ $item->tarifa->descricao }} 
                                                
                                                   
                                                    
                                                    </td>
                                                <td style="text-align: center;">
                                                     {{ $item->quantidade }}
                                                </td>
                                                <td style="text-align: center;">
                                           
                                                    R$ {{ number_format( $item->valor, 2, ',', '.' )}}
                                                 

                                            

                                            
                                        </td>
                                            
                                            <td style="text-align: center;">                                               

                                            <a href="javascript:;" data-toggle="modal" onclick="deleteData('{{ Crypt::encrypt($item->id) }}')" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Deletar">
                                            <i class="fas fa-trash fa-sm" ></i></a>                  
                                          

                                            </td>
                                    </tr>
                                            @endforeach
                                            <tr>
                                                

                                                
                                                
                                                
                                                
                                                <td style="text-align: center;">
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                <b>Total:</b>
                                                </td>
                                                
                                                <td style="text-align: center;">

                                                    <input type="hidden" name="total" value="{{$total}}">
                                                    <input type="hidden" class="form-control boxed @error('id') is-invalid @enderror" value="{{ $hospedagem->id }}" name="id" id="id">
                                                    <input type="hidden" class="form-control boxed @error('user_id') is-invalid @enderror" value="{{ $hospedagem->user->id }}" name="user_id" id="user_id">
                                                    R$ {{  number_format($total, 2, ',', '.') }}
                                                    
                                                </td>
                                                
                                                <td style="text-align: center;">
                                                </td>
                                                

 

                                            </tr>
                                            <tr>

                                                <td>


                                                  


                                    <a class="btn btn-success" style="color: white;" target="_blank" title="Realizar Pagamento" onClick="myFunction()">
                                    <i class="fas fa-money-bill-alt" style="color: white;" ></i> Finalizar Carrinho </a>


                                             </td>
                                             <td style="text-align: center;">
                                                </td>
                                                 <td style="text-align: center;">
                                                </td>
                                                 <td style="text-align: center;">
                                                </td>
                                            </tr>
                                            @endif
                                    </tbody>
                                </table>
                                

                             

                                 
                              
                            </div>         

            </div>   
        </div>
</form>
        

    <div class="form-group row">
        <div class="col-sm-12 col-md-12 col-lg-12">


       <a href="{{ route('hospede.meupedido', ['id' => Crypt::encrypt($hospedagem->id)])}}" class="btn btn-danger" style="color: #ffffff" title="Voltar">
        <i class="fas fa-arrow-left" style="color: #ffffff"></i>  Voltar</a>
           
   
    </div>
        </div>
                                
        

                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                           
                                                            
                                          
                                           
                                        </div>
                                    </div>
                                

                            </div>
                            

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>




                                <div class="modal fade" id="DeleteModal">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="deletearea" method="get">
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
                                                    <p>Tem certeza que deseja deletar ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmit()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                                <div class="modal fade" id="Carrinho">
                                    <div class="modal-dialog" role="document">
                                        <form action="" id="finalizacarrinho" method="get">
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
                                                    <p>Deseja Finalizar o Carrinho ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="formSubmitCarrinho()">Sim</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>











</article>


@push('javascript')
  
<script type="text/javascript">
    
    function deleteData(id)
    {

         var id = id;
         var url = '{{ route("carrinho.deletar.item", ":id") }}';
         url = url.replace(':id', id);
         $("#deletearea").attr('action', url);
         
    }

    function formSubmit()
    {

         $("#deletearea").submit();
        
    }


    function finalizaCarrinho(id)
    {

         var id = id;
         var url = '{{ route("carrinho.finalizar") }}';    
         $("#finalizarCarrinho").attr('action', url);

    }

    function formSubmitCarrinho()
    {

        $("#finalizarCarrinho").submit();     
        window.open('{{ route("carrinho.finalizar") }}', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
        
    }

</script>

<script>
function myFunction() {
  window.open("{{ route('pagamento.pagamentoCarrinho', ['id' => Crypt::encrypt($hospedagem->id), 'total' => Crypt::encrypt($total)]) }}", "_blank", "status=no, location=no, menubar=no, fullscreen=no, toolbar=no,scrollbars=no,resizable=no,top=500,left=500,width=700,height=700");

}

(function(window) { 
  'use strict'; 
 
var noback = { 
     
    //globals 
    version: '0.0.1', 
    history_api : typeof history.pushState !== 'undefined', 
     
    init:function(){ 
        window.location.hash = '#no-back'; 
        noback.configure(); 
    }, 
     
    hasChanged:function(){ 
        if (window.location.hash == '#no-back' ){ 
            window.location.hash = '#BLOQUEIO';
            //mostra mensagem que não pode usar o btn volta do browser
            if($( "#msgAviso" ).css('display') =='none'){
                $( "#msgAviso" ).slideToggle("slow");
            }
        } 
    }, 
     
    checkCompat: function(){ 
        if(window.addEventListener) { 
            window.addEventListener("hashchange", noback.hasChanged, false); 
        }else if (window.attachEvent) { 
            window.attachEvent("onhashchange", noback.hasChanged); 
        }else{ 
            window.onhashchange = noback.hasChanged; 
        } 
    }, 
     
    configure: function(){ 
        if ( window.location.hash == '#no-back' ) { 
            if ( this.history_api ){ 
                history.pushState(null, '', '#BLOQUEIO'); 
            }else{  
                window.location.hash = '#BLOQUEIO';
                //mostra mensagem que não pode usar o btn volta do browser
                if($( "#msgAviso" ).css('display') =='none'){
                    $( "#msgAviso" ).slideToggle("slow");
                }
            } 
        } 
        noback.checkCompat(); 
        noback.hasChanged(); 
    } 
     
    }; 
     
    // AMD support 
    if (typeof define === 'function' && define.amd) { 
        define( function() { return noback; } ); 
    }  
    // For CommonJS and CommonJS-like 
    else if (typeof module === 'object' && module.exports) { 
        module.exports = noback; 
    }  
    else { 
        window.noback = noback; 
    } 
    noback.init();
}(window)); 
</script>
@endpush
@endsection